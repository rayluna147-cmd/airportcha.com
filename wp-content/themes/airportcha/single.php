<?php
get_header();

while ( have_posts() ) :
	the_post();
	$cats = get_the_category();
	$tags = get_the_tags();
	?>
	<section class="ac-page-header">
		<div class="ac-container ac-container-narrow">
			<?php airportcha_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<p>
				<?php if ( ! empty( $cats ) ) : ?>
					<span>分类：<?php echo esc_html( $cats[0]->name ); ?></span> ·
				<?php endif; ?>
				<span>发布于 <?php echo esc_html( get_the_date() ); ?></span> ·
				<span>更新于 <?php echo esc_html( get_the_modified_date() ); ?></span>
			</p>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container ac-container-narrow">
			<article class="ac-article-wrap">
				<div class="ac-prose">
					<?php the_content(); ?>
				</div>

				<?php if ( $tags ) : ?>
					<div class="ac-tag-row" style="margin-top:28px;padding-top:20px;border-top:1px solid var(--ac-border);">
						<?php foreach ( $tags as $tag ) : ?>
							<a class="ac-chip" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</article>
		</div>
	</main>
<?php
endwhile;
get_footer();
