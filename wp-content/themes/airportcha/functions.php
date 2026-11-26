<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function airportcha_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 40, 'width' => 180, 'flex-width' => true ) );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => '主导航菜单',
			'footer'  => '页脚导航菜单',
		)
	);
}
add_action( 'after_setup_theme', 'airportcha_theme_setup' );

function airportcha_enqueue_assets() {
	wp_enqueue_style( 'airportcha-style', get_stylesheet_uri(), array(), '1.0.0' );
	wp_enqueue_script( 'airportcha-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'airportcha_enqueue_assets' );

/**
 * 面包屑导航（兼容 Rank Math，否则使用内置轻量面包屑）
 */
function airportcha_breadcrumbs() {
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		echo '<div class="ac-breadcrumbs">';
		rank_math_the_breadcrumbs();
		echo '</div>';
		return;
	}

	echo '<nav class="ac-breadcrumbs" aria-label="面包屑导航">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">首页</a>';

	if ( is_singular( 'brand' ) ) {
		echo ' <span>/</span> <a href="' . esc_url( get_post_type_archive_link( 'brand' ) ) . '">品牌目录</a>';
		echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_singular( 'review' ) ) {
		echo ' <span>/</span> <a href="' . esc_url( get_post_type_archive_link( 'review' ) ) . '">品牌评测</a>';
		echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_singular( 'comparison' ) ) {
		echo ' <span>/</span> <a href="' . esc_url( get_post_type_archive_link( 'comparison' ) ) . '">品牌比较</a>';
		echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			echo ' <span>/</span> <a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
		}
		echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_page() ) {
		echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_post_type_archive() ) {
		echo ' <span>/</span> <span>' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
	} elseif ( is_category() || is_tag() || is_tax() ) {
		echo ' <span>/</span> <span>' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		echo ' <span>/</span> <span>搜索：' . esc_html( get_search_query() ) . '</span>';
	}
	echo '</nav>';
}

/**
 * 默认导航回退（尚未分配菜单时）
 */
function airportcha_fallback_menu() {
	$items = array(
		array( '首页', home_url( '/' ) ),
		array( '机场推荐', home_url( '/recommend/' ) ),
		array( '品牌目录', home_url( '/brands/' ) ),
		array( '品牌评测', home_url( '/reviews/' ) ),
		array( '品牌比较', home_url( '/comparisons/' ) ),
		array( '博客', home_url( '/blog/' ) ),
		array( '使用教程', home_url( '/guides/' ) ),
		array( '网络知识', home_url( '/knowledge/' ) ),
		array( '关于我们', home_url( '/about/' ) ),
	);
	echo '<ul>';
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Phase 05 性能优化：移除 WordPress 默认 Emoji 外部脚本与样式，消除静态站无效外链请求
 */
function airportcha_disable_wp_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'airportcha_disable_wp_emojis' );
