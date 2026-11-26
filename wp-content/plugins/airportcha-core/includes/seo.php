<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 检测是否已启用 Rank Math 或 Yoast SEO，避免重复输出 Meta 标签
 */
function airportcha_has_external_seo_plugin() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' );
}

/**
 * 支持品牌自定义 SEO Title
 */
function airportcha_filter_document_title( $title ) {
	if ( ! airportcha_has_external_seo_plugin() ) {
		if ( is_page( 'recommend' ) ) {
			return '机场推荐与排行榜：稳定性、价格、套餐及品牌对比 | AIRPORTCHA';
		}
		if ( is_singular( 'brand' ) ) {
			$custom = get_post_meta( get_the_ID(), '_ac_seo_title', true );
			if ( ! empty( $custom ) ) {
				return $custom;
			}
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'airportcha_filter_document_title' );

/**
 * 输出基础 SEO Meta 标签与结构化数据（Bing SEO + GEO 优化）
 */
function airportcha_output_seo_head() {
	if ( ! airportcha_has_external_seo_plugin() ) {
		$description = get_bloginfo( 'description' );
		$title       = wp_get_document_title();
		$url         = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
		$og_image    = '';

		if ( is_page( 'recommend' ) ) {
			$description = 'AIRPORTCHA 机场推荐与品牌排行榜：基于晚高峰稳定性、价格套餐、协议支持与流媒体/AI 解锁维度客观对比多品牌机场服务，提供按需求选购指南与透明评测方法。';
			$url         = get_permalink();
		} elseif ( is_singular() ) {
			$post_id = get_the_ID();
			if ( is_singular( 'brand' ) ) {
				$custom_desc = get_post_meta( $post_id, '_ac_seo_desc', true );
				if ( $custom_desc ) {
					$description = $custom_desc;
				}
			}
			if ( $description === get_bloginfo( 'description' ) ) {
				$excerpt = get_the_excerpt();
				if ( $excerpt ) {
					$description = wp_strip_all_tags( $excerpt );
				}
			}
			if ( has_post_thumbnail( $post_id ) ) {
				$og_image = get_the_post_thumbnail_url( $post_id, 'large' );
			}
			$url = get_permalink();
		} elseif ( is_post_type_archive( 'brand' ) ) {
			$description = 'AIRPORTCHA 机场服务品牌目录：客观收录全球主流高速网络节点与专线机场，提供协议、价格、节点与解锁实测参数。';
		} elseif ( is_post_type_archive( 'review' ) ) {
			$description = 'AIRPORTCHA 深度品牌评测：独立实测各大机场服务的晚高峰速度、稳定性、流媒体与 AI 解锁表现。';
		} elseif ( is_post_type_archive( 'comparison' ) ) {
			$description = 'AIRPORTCHA 机场品牌横向对比：多维度对比主流服务商的线路质量、价格与适用场景。';
		}

		$description = mb_substr( trim( preg_replace( '/\s+/', ' ', $description ) ), 0, 155 );

		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
		echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '" />' . "\n";
		echo '<meta property="og:locale" content="zh_CN" />' . "\n";
		if ( $og_image ) {
			echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
		}
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	}

	// JSON-LD 结构化数据
	$schemas = array();

	// BreadcrumbList Schema
	$crumbs = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => '首页',
			'item'     => home_url( '/' ),
		),
	);
	if ( is_singular( 'brand' ) ) {
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => '品牌目录', 'item' => get_post_type_archive_link( 'brand' ) );
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() );
	} elseif ( is_singular( 'review' ) ) {
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => '品牌评测', 'item' => get_post_type_archive_link( 'review' ) );
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() );
	} elseif ( is_singular( 'comparison' ) ) {
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => '品牌比较', 'item' => get_post_type_archive_link( 'comparison' ) );
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() );
	} elseif ( is_page() && ! is_front_page() ) {
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() );
	} elseif ( is_singular( 'post' ) ) {
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => '博客', 'item' => home_url( '/blog/' ) );
		$crumbs[] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => get_permalink() );
	}
	if ( count( $crumbs ) > 1 ) {
		$schemas[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $crumbs,
		);
	}

	if ( is_front_page() ) {
		$schemas[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'WebSite',
			'@id'             => home_url( '/#website' ),
			'name'            => get_bloginfo( 'name' ),
			'url'             => home_url( '/' ),
			'inLanguage'      => 'zh-CN',
			'publisher'       => array( '@id' => home_url( '/#organization' ) ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
		$schemas[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'@id'      => home_url( '/#organization' ),
			'name'     => 'AIRPORTCHA',
			'url'      => home_url( '/' ),
		);
	} elseif ( is_page( 'recommend' ) ) {
		$rank_brands = get_posts(
			array(
				'post_type'      => 'brand',
				'posts_per_page' => 10,
				'meta_key'       => '_ac_sort_order',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
			)
		);
		$list_items = array();
		$pos        = 1;
		foreach ( $rank_brands as $rb ) {
			$rm = airportcha_get_brand_meta( $rb->ID );
			if ( 'RISK' === $rm['tier'] || in_array( $rm['status'], array( 'suspended', 'closed' ), true ) ) {
				continue;
			}
			$list_items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $rb->post_title,
				'url'      => get_permalink( $rb->ID ),
			);
		}
		if ( ! empty( $list_items ) ) {
			$schemas[] = array(
				'@context'        => 'https://schema.org',
				'@type'           => 'ItemList',
				'name'            => 'AIRPORTCHA 机场推荐与品牌排行榜',
				'itemListElement' => $list_items,
			);
		}
		$schemas[] = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(
				array(
					'@type'          => 'Question',
					'name'           => '新手第一次选择机场服务，应该先买月付还是年付？',
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => '建议优先选择月付套餐，在自己的宽带与移动网络下实测至少一周晚高峰体验后，再考虑是否升级季付或年付以降低风险。',
					),
				),
				array(
					'@type'          => 'Question',
					'name'           => '商业推荐位与独立评测评分有什么区别？',
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => '商业合作等级仅决定编辑精选等展示位置并明确标注合作关系；独立评测评分基于线路稳定性、协议兼容、解锁表现与性价比独立评定，不受商业权重影响。',
					),
				),
				array(
					'@type'          => 'Question',
					'name'           => 'IPLC 专线机场和普通中转机场该怎么选？',
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => '如果经常在晚高峰进行远程办公、代码开发或使用 ChatGPT/Claude，优先选择 IPLC/IEPL 专线；如果以日常浏览和预算优先为主，高性价比中转机场即可满足需求。',
					),
				),
			),
		);
	} elseif ( is_singular( 'brand' ) ) {
		$faqs = airportcha_get_brand_faqs( get_the_ID() );
		if ( ! empty( $faqs ) ) {
			$faq_items = array();
			foreach ( $faqs as $f ) {
				if ( ! empty( $f['q'] ) && ! empty( $f['a'] ) ) {
					$faq_items[] = array(
						'@type'          => 'Question',
						'name'           => $f['q'],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => $f['a'],
						),
					);
				}
			}
			if ( ! empty( $faq_items ) ) {
				$schemas[] = array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $faq_items,
				);
			}
		}
	} elseif ( is_singular( 'review' ) ) {
		$meta      = airportcha_get_review_meta( get_the_ID() );
		$brand_obj = $meta['brand_id'] ? get_post( $meta['brand_id'] ) : null;
		$schemas[] = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Review',
			'name'          => get_the_title(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'author'        => array(
				'@type' => 'Organization',
				'name'  => 'AIRPORTCHA 编辑部',
			),
			'itemReviewed'  => array(
				'@type' => 'Service',
				'name'  => $brand_obj ? $brand_obj->post_title : get_the_title(),
			),
			'reviewRating'  => array(
				'@type'       => 'Rating',
				'ratingValue' => $meta['score_overall'],
				'bestRating'  => '5',
				'worstRating' => '1',
			),
		);
	} elseif ( is_singular( 'post' ) || is_singular( 'comparison' ) ) {
		$schemas[] = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Article',
			'@id'           => get_permalink() . '#article',
			'headline'      => get_the_title(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'isPartOf'      => array( '@id' => home_url( '/#website' ) ),
			'author'        => array(
				'@type' => 'Organization',
				'@id'   => home_url( '/#organization' ),
				'name'  => 'AIRPORTCHA',
			),
			'publisher'     => array(
				'@type' => 'Organization',
				'@id'   => home_url( '/#organization' ),
				'name'  => 'AIRPORTCHA',
			),
		);
	}

	foreach ( $schemas as $schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'airportcha_output_seo_head', 5 );

/**
 * 优化 WordPress robots.txt，引导 Bingbot 抓取 Sitemap 并屏蔽 /go/
 */
function airportcha_custom_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}
	$sitemap = home_url( '/wp-sitemap.xml' );
	$output  = "User-agent: *\n";
	$output .= "Disallow: /wp-admin/\n";
	$output .= "Allow: /wp-admin/admin-ajax.php\n";
	$output .= "Disallow: /go/\n";
	$output .= "Disallow: /?s=\n";
	$output .= "Sitemap: {$sitemap}\n";
	return $output;
}
add_filter( 'robots_txt', 'airportcha_custom_robots_txt', 10, 2 );
