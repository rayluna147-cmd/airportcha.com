<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 渲染品牌 Logo（有特色图片则输出原生缩略图，否则生成品牌首字科技徽标）
 */
function airportcha_render_brand_logo( $brand_id, $class = 'ac-brand-logo' ) {
	$title = get_the_title( $brand_id );
	if ( has_post_thumbnail( $brand_id ) ) {
		return get_the_post_thumbnail(
			$brand_id,
			'thumbnail',
			array(
				'class'   => $class . ' ac-brand-logo-img',
				'alt'     => esc_attr( $title . ' Logo' ),
				'loading' => 'lazy',
				'width'   => 48,
				'height'  => 48,
			)
		);
	}
	$initial = mb_substr( trim( $title ), 0, 1 );
	return '<span class="' . esc_attr( $class ) . ' ac-brand-logo-fallback" aria-hidden="true">' . esc_html( $initial ?: 'A' ) . '</span>';
}

/**
 * 渲染商业等级徽章
 */
function airportcha_render_tier_badge( $tier ) {
	switch ( $tier ) {
		case 'PRIMARY':
			return '<span class="ac-badge ac-badge-primary">核心主推</span>';
		case 'FEATURED':
			return '<span class="ac-badge ac-badge-accent">编辑重点推荐</span>';
		case 'PARTNER':
			return '<span class="ac-badge">合作品牌</span>';
		case 'RISK':
			return '<span class="ac-badge ac-badge-risk">已停止推荐</span>';
		default:
			return '';
	}
}

/**
 * 渲染单个品牌卡片（支持 standard / horizontal / compact / detailed）
 */
function airportcha_render_brand_card_html( $brand_id, $style = 'standard', $slot = '' ) {
	$post = get_post( $brand_id );
	if ( ! $post || 'brand' !== $post->post_type || 'publish' !== $post->post_status ) {
		return '';
	}
	$meta     = airportcha_get_brand_meta( $brand_id );
	$features = get_the_terms( $brand_id, 'brand_feature' );
	$go_url   = airportcha_get_brand_go_url( $brand_id, $slot );
	$is_hi    = in_array( $meta['tier'], array( 'PRIMARY', 'FEATURED' ), true );

	ob_start();
	?>
	<article class="ac-card ac-brand-card ac-card-<?php echo esc_attr( $style ); ?> <?php echo $is_hi ? 'is-featured' : ''; ?>"
		data-brand-slug="<?php echo esc_attr( $post->post_name ); ?>"
		data-tier="<?php echo esc_attr( $meta['tier'] ); ?>"
		data-rating="<?php echo esc_attr( $meta['rating'] ); ?>"
		data-order="<?php echo esc_attr( $meta['sort_order'] ); ?>">
		<div class="ac-card-head">
			<div class="ac-brand-id">
				<?php echo airportcha_render_brand_logo( $brand_id ); ?>
				<div>
					<?php echo airportcha_render_tier_badge( $meta['tier'] ); ?>
					<h3 class="ac-card-title">
						<a href="<?php echo esc_url( get_permalink( $brand_id ) ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
					</h3>
				</div>
			</div>
			<div class="ac-rating-pill" title="独立评测综合评分">
				<strong><?php echo esc_html( $meta['rating'] ); ?></strong><span>/5</span>
			</div>
		</div>

		<?php if ( $meta['tagline'] ) : ?>
			<p class="ac-card-desc"><?php echo esc_html( $meta['tagline'] ); ?></p>
		<?php endif; ?>

		<?php if ( 'compact' !== $style ) : ?>
			<ul class="ac-spec-list">
				<?php if ( $meta['price'] ) : ?>
					<li><span>起步价格</span><strong><?php echo esc_html( $meta['price'] ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $meta['protocols'] ) : ?>
					<li><span>协议支持</span><strong><?php echo esc_html( $meta['protocols'] ); ?></strong></li>
				<?php endif; ?>
				<?php if ( $meta['nodes'] ) : ?>
					<li><span>节点规模</span><strong><?php echo esc_html( $meta['nodes'] ); ?></strong></li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $meta['coupon'] && 'compact' !== $style ) : ?>
			<div class="ac-coupon-bar">
				<span>优惠码：<code><?php echo esc_html( $meta['coupon'] ); ?></code></span>
				<button type="button" class="ac-copy-btn" data-copy="<?php echo esc_attr( $meta['coupon'] ); ?>">复制</button>
			</div>
		<?php endif; ?>

		<?php if ( 'compact' !== $style && ! empty( $features ) && ! is_wp_error( $features ) ) : ?>
			<div class="ac-tag-row">
				<?php foreach ( array_slice( $features, 0, 3 ) as $term ) : ?>
					<span class="ac-chip"><?php echo esc_html( $term->name ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="ac-card-actions">
			<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( get_permalink( $brand_id ) ); ?>">查看档案</a>
			<?php if ( $go_url ) : ?>
				<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( $go_url ); ?>" target="_blank" rel="nofollow sponsored noopener">访问官网</a>
			<?php endif; ?>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * 短代码：[airportcha_brand_card slug="guangnianti" style="horizontal"]
 */
function airportcha_shortcode_brand_card( $atts ) {
	$atts = shortcode_atts(
		array(
			'slug'  => '',
			'id'    => 0,
			'style' => 'horizontal',
			'slot'  => 'article_inline',
		),
		$atts,
		'airportcha_brand_card'
	);

	$brand_id = intval( $atts['id'] );
	if ( ! $brand_id && ! empty( $atts['slug'] ) ) {
		$obj = get_page_by_path( sanitize_title( $atts['slug'] ), OBJECT, 'brand' );
		if ( $obj ) {
			$brand_id = $obj->ID;
		}
	}
	if ( ! $brand_id ) {
		return '';
	}
	return airportcha_render_brand_card_html( $brand_id, sanitize_key( $atts['style'] ), sanitize_key( $atts['slot'] ) );
}
add_shortcode( 'airportcha_brand_card', 'airportcha_shortcode_brand_card' );

/**
 * 短代码：[airportcha_top_brands limit="6" featured_only="0"]
 */
function airportcha_shortcode_top_brands( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'         => 6,
			'featured_only' => '0',
			'slot'          => 'recommend_rank',
		),
		$atts,
		'airportcha_top_brands'
	);

	$meta_query = array(
		'relation' => 'AND',
		array(
			'relation' => 'OR',
			array(
				'key'     => '_ac_tier',
				'value'   => 'RISK',
				'compare' => '!=',
			),
			array(
				'key'     => '_ac_tier',
				'compare' => 'NOT EXISTS',
			),
		),
	);

	if ( '1' === $atts['featured_only'] ) {
		$meta_query[] = array(
			'key'     => '_ac_tier',
			'value'   => array( 'PRIMARY', 'FEATURED' ),
			'compare' => 'IN',
		);
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => intval( $atts['limit'] ),
			'meta_key'       => '_ac_sort_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'meta_query'     => $meta_query,
		)
	);

	if ( ! $query->have_posts() ) {
		return '<p>暂无已发布的品牌数据，请在 WordPress 后台「品牌目录」中添加。</p>';
	}

	ob_start();
	echo '<div class="ac-brand-grid">';
	while ( $query->have_posts() ) {
		$query->the_post();
		echo airportcha_render_brand_card_html( get_the_ID(), 'standard', sanitize_key( $atts['slot'] ) );
	}
	echo '</div>';
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'airportcha_top_brands', 'airportcha_shortcode_top_brands' );

/**
 * 短代码：[airportcha_placement slot="home_primary" limit="3"]
 */
function airportcha_shortcode_placement( $atts ) {
	$atts = shortcode_atts(
		array(
			'slot'  => 'home_primary',
			'limit' => 3,
			'style' => 'standard',
		),
		$atts,
		'airportcha_placement'
	);

	$slot   = sanitize_key( $atts['slot'] );
	$brands = get_posts(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => -1,
			'meta_key'       => '_ac_sort_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		)
	);

	$matched = array();
	foreach ( $brands as $b ) {
		$m = airportcha_get_brand_meta( $b->ID );
		if ( 'RISK' === $m['tier'] || in_array( $m['status'], array( 'suspended', 'closed' ), true ) ) {
			continue;
		}
		if ( in_array( $slot, $m['placements'], true ) ) {
			$matched[] = $b->ID;
		}
		if ( count( $matched ) >= intval( $atts['limit'] ) ) {
			break;
		}
	}

	if ( empty( $matched ) ) {
		return do_shortcode( '[airportcha_top_brands limit="' . intval( $atts['limit'] ) . '" slot="' . esc_attr( $slot ) . '"]' );
	}

	ob_start();
	echo '<div class="ac-brand-grid">';
	foreach ( $matched as $bid ) {
		echo airportcha_render_brand_card_html( $bid, sanitize_key( $atts['style'] ), $slot );
	}
	echo '</div>';
	return ob_get_clean();
}
add_shortcode( 'airportcha_placement', 'airportcha_shortcode_placement' );

/**
 * 短代码：[airportcha_comparison_table slugs="guangnianti,xingyun-cloud,aurora-net"]
 */
function airportcha_shortcode_comparison_table( $atts ) {
	$atts = shortcode_atts(
		array(
			'slugs' => '',
			'limit' => 5,
		),
		$atts,
		'airportcha_comparison_table'
	);

	$args = array(
		'post_type'      => 'brand',
		'posts_per_page' => intval( $atts['limit'] ),
		'meta_key'       => '_ac_sort_order',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
	);

	if ( ! empty( $atts['slugs'] ) ) {
		$args['post_name__in'] = array_map( 'sanitize_title', explode( ',', $atts['slugs'] ) );
	}

	$brands = get_posts( $args );
	if ( empty( $brands ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="ac-table-wrap">
		<table class="ac-table">
			<thead>
				<tr>
					<th>品牌名称</th>
					<th>独立评分</th>
					<th>起步价格</th>
					<th>协议支持</th>
					<th>流媒体与 AI 解锁</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $brands as $b ) : ?>
					<?php
					$m      = airportcha_get_brand_meta( $b->ID );
					$go_url = airportcha_get_brand_go_url( $b->ID, 'comparison_table' );
					?>
					<tr>
						<td>
							<a href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>"><strong><?php echo esc_html( $b->post_title ); ?></strong></a>
							<?php echo airportcha_render_tier_badge( $m['tier'] ); ?>
						</td>
						<td><strong><?php echo esc_html( $m['rating'] ); ?></strong> / 5.0</td>
						<td><?php echo esc_html( $m['price'] ); ?></td>
						<td><?php echo esc_html( $m['protocols'] ); ?></td>
						<td><?php echo esc_html( $m['streaming'] ); ?></td>
						<td>
							<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>">档案</a>
							<?php if ( $go_url ) : ?>
								<a class="ac-btn ac-btn-sm ac-btn-primary" href="<?php echo esc_url( $go_url ); ?>" target="_blank" rel="nofollow sponsored noopener">官网</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'airportcha_comparison_table', 'airportcha_shortcode_comparison_table' );

/**
 * 辅助函数：从价格字符串提取数值用于排序（如 "¥28 / 月起" -> 28，无数字返回 9999）
 */
function airportcha_extract_price_num( $price_str ) {
	if ( preg_match( '/(\d+(?:\.\d+)?)/', (string) $price_str, $m ) ) {
		return floatval( $m[1] );
	}
	return 9999;
}

/**
 * 短代码：[airportcha_ranking_board limit="10"]
 * 专业动态多品牌排行榜（初始 HTML 完整输出 + 前端轻量筛选排序，商业推荐与独立评分清晰分离）
 */
function airportcha_shortcode_ranking_board( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit' => 10,
			'slot'  => 'recommend_rank',
		),
		$atts,
		'airportcha_ranking_board'
	);

	$brands = get_posts(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => intval( $atts['limit'] ),
			'meta_key'       => '_ac_sort_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		)
	);

	$valid_brands = array();
	foreach ( $brands as $b ) {
		$m = airportcha_get_brand_meta( $b->ID );
		if ( 'RISK' === $m['tier'] || in_array( $m['status'], array( 'suspended', 'closed' ), true ) ) {
			continue;
		}
		$valid_brands[] = array( 'post' => $b, 'meta' => $m );
	}

	if ( empty( $valid_brands ) ) {
		return '<p>暂无可展示的品牌排行数据。</p>';
	}

	$features = get_terms( array( 'taxonomy' => 'brand_feature', 'hide_empty' => true ) );

	ob_start();
	?>
	<div class="ac-rank-board" data-ac-rank-board>
		<div class="ac-toolbar-row">
			<div class="ac-filter-bar" style="margin-bottom:0;">
				<button type="button" class="ac-filter-pill is-active" data-rank-filter="all">全部品牌</button>
				<?php if ( ! empty( $features ) && ! is_wp_error( $features ) ) : ?>
					<?php foreach ( $features as $f ) : ?>
						<button type="button" class="ac-filter-pill" data-rank-filter="<?php echo esc_attr( $f->slug ); ?>"><?php echo esc_html( $f->name ); ?></button>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<select class="ac-sort-select" data-rank-sort aria-label="排行榜排序方式">
				<option value="order">按综合推荐权重</option>
				<option value="rating">按独立评测评分（高到低）</option>
				<option value="price">按起步价格（低到高）</option>
			</select>
		</div>

		<div class="ac-table-wrap">
			<table class="ac-table ac-rank-table">
				<thead>
					<tr>
						<th>排名</th>
						<th>品牌与定位</th>
						<th>独立评分</th>
						<th>起步价格</th>
						<th>协议与节点</th>
						<th>适用人群</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$idx = 1;
					foreach ( $valid_brands as $item ) :
						$b        = $item['post'];
						$m        = $item['meta'];
						$go_url   = airportcha_get_brand_go_url( $b->ID, sanitize_key( $atts['slot'] ) );
						$b_terms  = get_the_terms( $b->ID, 'brand_feature' );
						$slugs    = ( ! empty( $b_terms ) && ! is_wp_error( $b_terms ) ) ? implode( ',', wp_list_pluck( $b_terms, 'slug' ) ) : '';
						$p_num    = airportcha_extract_price_num( $m['price'] );
						$rev_list = get_posts(
							array(
								'post_type'      => 'review',
								'posts_per_page' => 1,
								'meta_key'       => '_ac_review_brand_id',
								'meta_value'     => $b->ID,
							)
						);
						$rev_url  = ! empty( $rev_list ) ? get_permalink( $rev_list[0]->ID ) : '';
						?>
						<tr data-rank-row data-features="<?php echo esc_attr( $slugs ); ?>" data-order="<?php echo esc_attr( $m['sort_order'] ); ?>" data-rating="<?php echo esc_attr( $m['rating'] ); ?>" data-price="<?php echo esc_attr( $p_num ); ?>">
							<td><span class="ac-rank-num"><?php echo esc_html( $idx++ ); ?></span></td>
							<td>
								<div class="ac-brand-id">
									<?php echo airportcha_render_brand_logo( $b->ID, 'ac-brand-logo' ); ?>
									<div>
										<a href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>"><strong><?php echo esc_html( $b->post_title ); ?></strong></a>
										<?php echo airportcha_render_tier_badge( $m['tier'] ); ?>
										<?php if ( $m['tagline'] ) : ?>
											<div style="font-size:12px;color:var(--ac-muted);margin-top:2px;"><?php echo esc_html( $m['tagline'] ); ?></div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td><strong><?php echo esc_html( $m['rating'] ); ?></strong> / 5.0</td>
							<td><?php echo esc_html( $m['price'] ?: '待补充' ); ?></td>
							<td style="font-size:13px;"><?php echo esc_html( trim( $m['protocols'] . ( $m['nodes'] ? ' · ' . $m['nodes'] : '' ) ) ?: '待补充' ); ?></td>
							<td style="font-size:13px;"><?php echo esc_html( $m['best_for'] ?: '通用网络用户' ); ?></td>
							<td>
								<div style="display:flex;flex-wrap:wrap;gap:6px;">
									<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>">档案</a>
									<?php if ( $rev_url ) : ?>
										<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( $rev_url ); ?>">评测</a>
									<?php endif; ?>
									<?php if ( $go_url ) : ?>
										<a class="ac-btn ac-btn-sm ac-btn-primary" href="<?php echo esc_url( $go_url ); ?>" target="_blank" rel="nofollow sponsored noopener">官网</a>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'airportcha_ranking_board', 'airportcha_shortcode_ranking_board' );

/**
 * 短代码：[airportcha_plans_matrix limit="6"]
 * 真实套餐价格横向对比表（严格读取 _ac_plans_json，无套餐数据明确标示待补充，不虚构）
 */
function airportcha_shortcode_plans_matrix( $atts ) {
	$atts = shortcode_atts( array( 'limit' => 6 ), $atts, 'airportcha_plans_matrix' );
	$brands = get_posts(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => intval( $atts['limit'] ),
			'meta_key'       => '_ac_sort_order',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
		)
	);
	if ( empty( $brands ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="ac-table-wrap">
		<table class="ac-table">
			<thead>
				<tr>
					<th>品牌名称</th>
					<th>套餐名称</th>
					<th>价格</th>
					<th>计费周期</th>
					<th>流量配额</th>
					<th>适用人群</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $brands as $b ) : ?>
					<?php
					$m     = airportcha_get_brand_meta( $b->ID );
					$plans = airportcha_get_brand_plans( $b->ID );
					if ( empty( $plans ) ) :
						?>
						<tr>
							<td><a href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>"><strong><?php echo esc_html( $b->post_title ); ?></strong></a></td>
							<td>基础入门参考</td>
							<td><?php echo esc_html( $m['price'] ?: '待补充' ); ?></td>
							<td>以官网计费周期为准</td>
							<td>待补充</td>
							<td><?php echo esc_html( $m['best_for'] ?: '通用用户' ); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ( $plans as $p ) : ?>
							<tr>
								<td><a href="<?php echo esc_url( get_permalink( $b->ID ) ); ?>"><strong><?php echo esc_html( $b->post_title ); ?></strong></a></td>
								<td><?php echo esc_html( $p['name'] ?? '标准套餐' ); ?></td>
								<td><strong><?php echo esc_html( $p['price'] ?? '待补充' ); ?></strong></td>
								<td><?php echo esc_html( $p['cycle'] ?? '月付' ); ?></td>
								<td><?php echo esc_html( $p['traffic'] ?? '待补充' ); ?></td>
								<td><?php echo esc_html( $p['best_for'] ?? '—' ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'airportcha_plans_matrix', 'airportcha_shortcode_plans_matrix' );
