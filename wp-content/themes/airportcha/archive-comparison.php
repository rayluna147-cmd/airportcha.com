<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container">
		<?php airportcha_breadcrumbs(); ?>
		<h1>品牌横向比较</h1>
		<p>多维度对比热门机场服务商的线路架构、晚高峰速度、价格与适用场景，助你快速决策。</p>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container">
		<?php if ( have_posts() ) : ?>
			<div class="ac-two-col">
				<?php
				while ( have_posts() ) :
					the_post();
					$cmeta = airportcha_get_comparison_meta( get_the_ID() );
					?>
					<article class="ac-card">
						<div class="ac-post-meta">
							<span class="ac-badge ac-badge-accent">品牌对比</span>
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="ac-card-desc"><?php echo esc_html( $cmeta['summary'] ?: get_the_excerpt() ); ?></p>
						<div class="ac-card-actions">
							<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">查看完整对比</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ac-pagination"><?php echo paginate_links(); ?></div>
		<?php else : ?>
			<p>暂无已发布的品牌对比内容。</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
