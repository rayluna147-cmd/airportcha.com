<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container">
		<?php airportcha_breadcrumbs(); ?>
		<h1>品牌深度评测</h1>
		<p>基于真实网络环境的多时段测速、丢包监测与流媒体 / AI 工具解锁实测报告。</p>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container">
		<?php if ( have_posts() ) : ?>
			<div class="ac-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$rmeta = airportcha_get_review_meta( get_the_ID() );
					?>
					<article class="ac-card">
						<div class="ac-post-meta">
							<span class="ac-badge">深度评测</span>
							<strong>评分 <?php echo esc_html( $rmeta['score_overall'] ); ?> / 5.0</strong>
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36 ) ); ?></p>
						<div class="ac-card-actions">
							<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">阅读评测报告</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ac-pagination"><?php echo paginate_links(); ?></div>
		<?php else : ?>
			<p>暂无已发布的品牌评测。</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
