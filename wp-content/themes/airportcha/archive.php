<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container">
		<?php airportcha_breadcrumbs(); ?>
		<h1><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<p>', '</p>' ); ?>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container">
		<?php if ( have_posts() ) : ?>
			<div class="ac-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="ac-card">
						<div class="ac-post-meta">
							<span class="ac-badge"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36 ) ); ?></p>
						<div class="ac-card-actions">
							<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">查看详情</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ac-pagination"><?php echo paginate_links(); ?></div>
		<?php else : ?>
			<p>该归档下暂无内容。</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
