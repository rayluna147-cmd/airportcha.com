<?php
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="ac-page-header">
		<div class="ac-container ac-container-narrow">
			<?php airportcha_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container ac-container-narrow">
			<article class="ac-article-wrap">
				<div class="ac-prose">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</main>
<?php
endwhile;
get_footer();
