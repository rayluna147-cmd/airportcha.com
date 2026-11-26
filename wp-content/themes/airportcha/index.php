<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container">
		<h1><?php bloginfo( 'name' ); ?></h1>
		<p><?php bloginfo( 'description' ); ?></p>
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
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36 ) ); ?></p>
						<div class="ac-card-actions">
							<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">阅读全文</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ac-pagination"><?php echo paginate_links(); ?></div>
		<?php else : ?>
			<p>暂无内容。</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
