<?php
get_header();

while ( have_posts() ) :
	the_post();
	$cmeta   = airportcha_get_comparison_meta( get_the_ID() );
	$brand_a = $cmeta['brand_a'] ? get_post( $cmeta['brand_a'] ) : null;
	$brand_b = $cmeta['brand_b'] ? get_post( $cmeta['brand_b'] ) : null;
	?>

	<section class="ac-page-header">
		<div class="ac-container ac-container-narrow">
			<?php airportcha_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( $cmeta['summary'] ) : ?>
				<p><?php echo esc_html( $cmeta['summary'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container ac-container-narrow">
			<article class="ac-article-wrap">
				<?php if ( $brand_a && $brand_b ) : ?>
					<?php
					$ma = airportcha_get_brand_meta( $brand_a->ID );
					$mb = airportcha_get_brand_meta( $brand_b->ID );
					?>
					<div class="ac-two-col" style="margin-bottom:28px;">
						<div class="ac-score-box" style="margin-bottom:0;">
							<div class="ac-badge ac-badge-accent">品牌 A</div>
							<h3 style="margin:8px 0 6px;"><a href="<?php echo esc_url( get_permalink( $brand_a->ID ) ); ?>"><?php echo esc_html( $brand_a->post_title ); ?></a></h3>
							<p style="margin:0 0 8px;font-size:14px;color:var(--ac-muted);">评分 <?php echo esc_html( $ma['rating'] ); ?>/5 · <?php echo esc_html( $ma['price'] ); ?></p>
							<p style="margin:0;font-size:13px;"><?php echo esc_html( $ma['tagline'] ); ?></p>
						</div>
						<div class="ac-score-box" style="margin-bottom:0;">
							<div class="ac-badge">品牌 B</div>
							<h3 style="margin:8px 0 6px;"><a href="<?php echo esc_url( get_permalink( $brand_b->ID ) ); ?>"><?php echo esc_html( $brand_b->post_title ); ?></a></h3>
							<p style="margin:0 0 8px;font-size:14px;color:var(--ac-muted);">评分 <?php echo esc_html( $mb['rating'] ); ?>/5 · <?php echo esc_html( $mb['price'] ); ?></p>
							<p style="margin:0;font-size:13px;"><?php echo esc_html( $mb['tagline'] ); ?></p>
						</div>
					</div>
				<?php endif; ?>

				<div class="ac-prose">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	</main>

<?php
endwhile;
get_footer();
