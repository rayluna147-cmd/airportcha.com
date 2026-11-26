<?php
/**
 * Template Name: 栏目文章聚合页（博客/使用教程/网络知识）
 */
get_header();

while ( have_posts() ) :
	the_post();
	$cat_slug = get_post_meta( get_the_ID(), '_ac_hub_category', true );
	if ( ! $cat_slug ) {
		$cat_slug = get_post_field( 'post_name', get_the_ID() );
	}
	$paged = max( 1, get_query_var( 'paged' ) );
	$query = new WP_Query(
		array(
			'post_type'      => 'post',
			'category_name'  => $cat_slug,
			'posts_per_page' => 9,
			'paged'          => $paged,
		)
	);
	?>

	<section class="ac-page-header">
		<div class="ac-container">
			<?php airportcha_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<div style="color:#CBD7EC;max-width:720px;"><?php the_content(); ?></div>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container">
			<?php if ( $query->have_posts() ) : ?>
				<div class="ac-post-grid">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$cats = get_the_category();
						?>
						<article class="ac-card">
							<div class="ac-post-meta">
								<?php if ( ! empty( $cats ) ) : ?>
									<span class="ac-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
								<?php endif; ?>
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

				<div class="ac-pagination">
					<?php
					echo paginate_links(
						array(
							'total' => $query->max_num_pages,
						)
					);
					?>
				</div>
				<?php
				wp_reset_postdata();
			else :
				echo '<p>该栏目暂无文章，请在 WordPress 后台「文章 -> 写文章」并勾选对应分类发布。</p>';
			endif;
			?>
		</div>
	</main>

<?php
endwhile;
get_footer();
