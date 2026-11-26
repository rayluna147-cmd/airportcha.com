<?php
get_header();

while ( have_posts() ) :
	the_post();
	$brand_id   = get_the_ID();
	$meta       = airportcha_get_brand_meta( $brand_id );
	$go_url     = airportcha_get_brand_go_url( $brand_id, 'brand_detail' );
	$plans      = airportcha_get_brand_plans( $brand_id );
	$faqs       = airportcha_get_brand_faqs( $brand_id );
	$regions    = get_the_terms( $brand_id, 'brand_region' );
	$highlights = array_filter( array_map( 'trim', explode( "\n", $meta['highlights'] ) ) );

	$related_reviews = get_posts(
		array(
			'post_type'      => 'review',
			'posts_per_page' => 3,
			'meta_key'       => '_ac_review_brand_id',
			'meta_value'     => $brand_id,
		)
	);
	?>

	<section class="ac-page-header">
		<div class="ac-container">
			<?php airportcha_breadcrumbs(); ?>
			<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;margin-top:12px;">
				<div class="ac-brand-id" style="gap:16px;">
					<?php echo airportcha_render_brand_logo( $brand_id, 'ac-brand-logo' ); ?>
					<div>
						<?php echo airportcha_render_tier_badge( $meta['tier'] ); ?>
						<h1 style="margin:6px 0 6px;"><?php the_title(); ?><?php echo $meta['en_name'] ? ' <small style="font-size:16px;opacity:0.75;font-weight:500;">(' . esc_html( $meta['en_name'] ) . ')</small>' : ''; ?></h1>
						<?php if ( $meta['tagline'] ) : ?>
							<p><?php echo esc_html( $meta['tagline'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
					<div class="ac-rating-pill" style="padding:10px 18px;font-size:15px;">
						独立评分 <strong><?php echo esc_html( $meta['rating'] ); ?></strong> / 5.0
					</div>
					<?php if ( $go_url ) : ?>
						<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( $go_url ); ?>" target="_blank" rel="nofollow sponsored noopener">访问 <?php the_title(); ?> 官网 →</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container ac-container-narrow">
			<div class="ac-article-wrap">
				<div class="ac-score-box">
					<h2 style="margin:0 0 16px;font-size:18px;color:var(--ac-navy);">核心参数总览</h2>
					<ul class="ac-spec-list" style="border:none;padding:0;margin:0;">
						<?php if ( $meta['price'] ) : ?><li><span>起步价格</span><strong><?php echo esc_html( $meta['price'] ); ?></strong></li><?php endif; ?>
						<?php if ( $meta['protocols'] ) : ?><li><span>支持协议</span><strong><?php echo esc_html( $meta['protocols'] ); ?></strong></li><?php endif; ?>
						<?php if ( $meta['nodes'] ) : ?><li><span>节点规模</span><strong><?php echo esc_html( $meta['nodes'] ); ?></strong></li><?php endif; ?>
						<?php if ( $meta['streaming'] ) : ?><li><span>流媒体与 AI 解锁</span><strong><?php echo esc_html( $meta['streaming'] ); ?></strong></li><?php endif; ?>
						<?php if ( $meta['best_for'] ) : ?><li><span>适用人群</span><strong><?php echo esc_html( $meta['best_for'] ); ?></strong></li><?php endif; ?>
						<li><span>最近更新</span><strong><?php echo esc_html( get_the_modified_date() ); ?></strong></li>
					</ul>
					<?php if ( $meta['coupon'] ) : ?>
						<div class="ac-coupon-bar" style="margin-top:16px;margin-bottom:0;">
							<span>专属优惠码：<code><?php echo esc_html( $meta['coupon'] ); ?></code><?php echo $meta['coupon_desc'] ? ' · ' . esc_html( $meta['coupon_desc'] ) : ''; ?></span>
							<button type="button" class="ac-copy-btn" data-copy="<?php echo esc_attr( $meta['coupon'] ); ?>">一键复制</button>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $plans ) ) : ?>
					<h2 style="margin:0 0 12px;font-size:20px;color:var(--ac-navy);">常见套餐与价格参考</h2>
					<div class="ac-plans-grid">
						<?php foreach ( $plans as $plan ) : ?>
							<div class="ac-plan-card">
								<strong><?php echo esc_html( $plan['name'] ?? '标准套餐' ); ?></strong>
								<div class="ac-plan-price"><?php echo esc_html( $plan['price'] ?? '' ); ?> <small style="font-size:13px;font-weight:500;color:var(--ac-muted);">/ <?php echo esc_html( $plan['cycle'] ?? '月付' ); ?></small></div>
								<?php if ( ! empty( $plan['traffic'] ) ) : ?><div style="font-size:13px;color:var(--ac-text);">流量：<?php echo esc_html( $plan['traffic'] ); ?></div><?php endif; ?>
								<?php if ( ! empty( $plan['best_for'] ) ) : ?><div style="font-size:12px;color:var(--ac-muted);margin-top:4px;"><?php echo esc_html( $plan['best_for'] ); ?></div><?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $highlights ) ) : ?>
					<div class="ac-box-pros" style="margin-bottom:28px;">
						<h3>核心优势亮点</h3>
						<ul style="margin:0;padding-left:20px;">
							<?php foreach ( $highlights as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<div class="ac-prose">
					<?php the_content(); ?>
				</div>

				<?php if ( ! empty( $faqs ) ) : ?>
					<div style="margin-top:32px;">
						<h2 style="margin:0 0 12px;font-size:20px;color:var(--ac-navy);">常见问题（FAQ）</h2>
						<div class="ac-faq-list">
							<?php foreach ( $faqs as $f ) : ?>
								<?php if ( ! empty( $f['q'] ) && ! empty( $f['a'] ) ) : ?>
									<div class="ac-faq-item">
										<h4><?php echo esc_html( $f['q'] ); ?></h4>
										<p><?php echo esc_html( $f['a'] ); ?></p>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $regions ) && ! is_wp_error( $regions ) ) : ?>
					<div style="margin-top:28px;">
						<strong>覆盖节点地区：</strong>
						<div class="ac-tag-row" style="margin-top:8px;">
							<?php foreach ( $regions as $r ) : ?>
								<span class="ac-chip"><?php echo esc_html( $r->name ); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $meta['source'] ) : ?>
					<p style="margin-top:24px;font-size:12px;color:var(--ac-muted);">资料来源：<?php echo esc_html( $meta['source'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $related_reviews ) ) : ?>
					<div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--ac-border);">
						<h3 style="margin:0 0 14px;">相关深度评测</h3>
						<?php foreach ( $related_reviews as $rev ) : ?>
							<div style="margin-bottom:10px;">
								<a href="<?php echo esc_url( get_permalink( $rev->ID ) ); ?>"><strong>→ <?php echo esc_html( $rev->post_title ); ?></strong></a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main>

<?php
endwhile;
get_footer();
