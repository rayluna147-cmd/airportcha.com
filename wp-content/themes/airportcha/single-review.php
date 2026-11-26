<?php
get_header();

while ( have_posts() ) :
	the_post();
	$rmeta  = airportcha_get_review_meta( get_the_ID() );
	$brand  = $rmeta['brand_id'] ? get_post( $rmeta['brand_id'] ) : null;
	$bmeta  = $brand ? airportcha_get_brand_meta( $brand->ID ) : null;
	$go_url = $brand ? airportcha_get_brand_go_url( $brand->ID, 'review_header' ) : '';
	$pros   = array_filter( array_map( 'trim', explode( "\n", $rmeta['pros'] ) ) );
	$cons   = array_filter( array_map( 'trim', explode( "\n", $rmeta['cons'] ) ) );
	$scores = array(
		'综合评分'   => $rmeta['score_overall'],
		'速度表现'   => $rmeta['score_speed'],
		'线路稳定性' => $rmeta['score_stability'],
		'价格性价比' => $rmeta['score_value'],
		'流媒体与 AI' => $rmeta['score_streaming'],
	);
	?>

	<section class="ac-page-header">
		<div class="ac-container ac-container-narrow">
			<?php airportcha_breadcrumbs(); ?>
			<div style="display:flex;align-items:center;gap:14px;margin-top:12px;">
				<?php if ( $brand ) : ?>
					<?php echo airportcha_render_brand_logo( $brand->ID, 'ac-brand-logo' ); ?>
				<?php endif; ?>
				<h1 style="margin:0;"><?php the_title(); ?></h1>
			</div>
			<p style="margin-top:10px;">
				作者：AIRPORTCHA 编辑部 · 发布于 <?php echo esc_html( get_the_date() ); ?> · 最近更新 <?php echo esc_html( get_the_modified_date() ); ?> · 独立评分 <?php echo esc_html( $rmeta['score_overall'] ); ?> / 5.0
			</p>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container ac-container-narrow">
			<article class="ac-article-wrap">
				<div class="ac-score-box">
					<h2 style="margin:0 0 18px;font-size:18px;color:var(--ac-navy);">AIRPORTCHA 综合评分维度</h2>
					<?php foreach ( $scores as $label => $val ) : ?>
						<?php $pct = min( 100, max( 0, ( floatval( $val ) / 5 ) * 100 ) ); ?>
						<div class="ac-score-row">
							<span><?php echo esc_html( $label ); ?></span>
							<div class="ac-score-bar"><div class="ac-score-fill" style="width:<?php echo esc_attr( $pct ); ?>%;"></div></div>
							<strong><?php echo esc_html( $val ); ?></strong>
						</div>
					<?php endforeach; ?>
					<?php if ( $rmeta['verdict'] ) : ?>
						<p style="margin:16px 0 0;padding-top:14px;border-top:1px solid var(--ac-border);font-size:14px;">
							<strong>编辑总结：</strong><?php echo esc_html( $rmeta['verdict'] ); ?>
						</p>
					<?php endif; ?>
				</div>

				<!-- 自动文章目录（由 main.js 自动提取 H2 生成） -->
				<nav id="ac-auto-toc" class="ac-tldr-box" style="display:none;" aria-label="文章目录">
					<strong style="display:block;margin-bottom:8px;color:var(--ac-navy);">本文目录</strong>
					<ul id="ac-toc-list" style="margin:0;padding-left:18px;font-size:14px;display:flex;flex-direction:column;gap:4px;"></ul>
				</nav>

				<?php if ( ! empty( $pros ) || ! empty( $cons ) ) : ?>
					<div class="ac-pros-cons">
						<div class="ac-box-pros">
							<h3>主要优点</h3>
							<ul style="margin:0;padding-left:18px;">
								<?php foreach ( $pros as $p ) : ?><li><?php echo esc_html( $p ); ?></li><?php endforeach; ?>
							</ul>
						</div>
						<div class="ac-box-cons">
							<h3>不足与注意事项</h3>
							<ul style="margin:0;padding-left:18px;">
								<?php foreach ( $cons as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<div class="ac-prose" id="ac-review-body">
					<?php the_content(); ?>
				</div>

				<?php if ( $brand && $bmeta ) : ?>
					<div class="ac-score-box" style="margin-top:32px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
						<div class="ac-brand-id">
							<?php echo airportcha_render_brand_logo( $brand->ID, 'ac-brand-logo' ); ?>
							<div>
								<?php echo airportcha_render_tier_badge( $bmeta['tier'] ); ?>
								<h3 style="margin:4px 0 2px;"><?php echo esc_html( $brand->post_title ); ?></h3>
								<p style="margin:0;font-size:13px;color:var(--ac-muted);"><?php echo esc_html( $bmeta['price'] . ' · ' . $bmeta['protocols'] ); ?></p>
							</div>
						</div>
						<div style="display:flex;gap:10px;flex-wrap:wrap;">
							<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( get_permalink( $brand->ID ) ); ?>">查看品牌档案</a>
							<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">返回机场推荐榜</a>
							<?php if ( $go_url ) : ?>
								<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( $go_url ); ?>" target="_blank" rel="nofollow sponsored noopener">访问官网</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</article>
		</div>
	</main>

<?php
endwhile;
get_footer();
