<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 注册统一推广跳转路由：/go/{brand-slug}/
 */
function airportcha_register_go_rewrite() {
	add_rewrite_rule( '^go/([^/]+)/?$', 'index.php?ac_go_slug=$matches[1]', 'top' );
}
add_action( 'init', 'airportcha_register_go_rewrite' );

function airportcha_register_go_query_vars( $vars ) {
	$vars[] = 'ac_go_slug';
	return $vars;
}
add_filter( 'query_vars', 'airportcha_register_go_query_vars' );

/**
 * 校验品牌推广目标 URL 是否有效且安全（阻止循环跳转与非法协议）
 */
function airportcha_validate_affiliate_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	$parsed = wp_parse_url( $url );
	if ( empty( $parsed['scheme'] ) || ! in_array( strtolower( $parsed['scheme'] ), array( 'http', 'https' ), true ) ) {
		return '';
	}
	if ( empty( $parsed['host'] ) ) {
		return '';
	}
	// 阻止指向本站 /go/ 的循环跳转
	$path = isset( $parsed['path'] ) ? $parsed['path'] : '';
	if ( preg_match( '#^/go/#i', $path ) ) {
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( in_array( strtolower( $parsed['host'] ), array( strtolower( (string) $site_host ), 'airportcha.com', 'www.airportcha.com', '127.0.0.1', 'localhost' ), true ) ) {
			return '';
		}
	}
	return esc_url_raw( $url );
}

/**
 * 判断品牌是否允许对外商业推广，有效时返回 /go/{slug}/，否则返回空字符串
 */
function airportcha_get_brand_go_url( $brand_id, $slot = '' ) {
	$post = get_post( $brand_id );
	if ( ! $post || 'brand' !== $post->post_type || 'publish' !== $post->post_status ) {
		return '';
	}
	$meta = airportcha_get_brand_meta( $brand_id );
	if ( in_array( $meta['tier'], array( 'RISK' ), true ) || in_array( $meta['status'], array( 'suspended', 'closed' ), true ) ) {
		return '';
	}
	if ( 'active' !== $meta['affiliate_status'] ) {
		return '';
	}
	if ( '' === airportcha_validate_affiliate_url( $meta['affiliate_url'] ) ) {
		return '';
	}
	$go_url = home_url( '/go/' . $post->post_name . '/' );
	if ( $slot ) {
		$go_url = add_query_arg( 'slot', sanitize_key( $slot ), $go_url );
	}
	return $go_url;
}

/**
 * 处理 /go/{slug}/ 302 跳转与本地点击统计
 */
function airportcha_handle_go_redirect() {
	$slug = get_query_var( 'ac_go_slug' );
	if ( empty( $slug ) ) {
		return;
	}

	$slug  = sanitize_title( $slug );
	$brand = get_page_by_path( $slug, OBJECT, 'brand' );

	header( 'X-Robots-Tag: noindex, nofollow', true );
	header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true );

	if ( ! $brand || 'publish' !== $brand->post_status ) {
		wp_safe_redirect( home_url( '/brands/' ), 302 );
		exit;
	}

	$meta       = airportcha_get_brand_meta( $brand->ID );
	$target_url = airportcha_validate_affiliate_url( $meta['affiliate_url'] );

	if ( '' === $target_url || 'active' !== $meta['affiliate_status'] || 'RISK' === $meta['tier'] || in_array( $meta['status'], array( 'suspended', 'closed' ), true ) ) {
		wp_safe_redirect( get_permalink( $brand->ID ), 302 );
		exit;
	}

	// 记录累计点击与按日点击（本地 WordPress 统计）
	$total = (int) get_post_meta( $brand->ID, '_ac_outbound_clicks', true );
	update_post_meta( $brand->ID, '_ac_outbound_clicks', $total + 1 );

	$today = gmdate( 'Y-m-d' );
	$daily = get_post_meta( $brand->ID, '_ac_clicks_daily', true );
	if ( ! is_array( $daily ) ) {
		$daily = array();
	}
	$daily[ $today ] = isset( $daily[ $today ] ) ? ( (int) $daily[ $today ] + 1 ) : 1;
	update_post_meta( $brand->ID, '_ac_clicks_daily', $daily );

	wp_redirect( $target_url, 302 );
	exit;
}
add_action( 'template_redirect', 'airportcha_handle_go_redirect', 1 );

/**
 * 为 brand CPT 注册 REST 字段，供 scripts/export-static.mjs 动态导出 dist/_data/affiliates.json
 */
function airportcha_register_brand_affiliate_rest() {
	register_rest_field(
		'brand',
		'ac_affiliate',
		array(
			'get_callback' => function ( $object ) {
				$id     = (int) $object['id'];
				$meta   = airportcha_get_brand_meta( $id );
				$valid  = airportcha_validate_affiliate_url( $meta['affiliate_url'] );
				$active = ( 'active' === $meta['affiliate_status'] && '' !== $valid && 'RISK' !== $meta['tier'] && ! in_array( $meta['status'], array( 'suspended', 'closed' ), true ) );
				return array(
					'targetUrl' => $active ? $valid : '',
					'status'    => $active ? 'active' : 'pending',
					'tier'      => $meta['tier'],
				);
			},
			'schema'       => null,
		)
	);
}
add_action( 'rest_api_init', 'airportcha_register_brand_affiliate_rest' );
