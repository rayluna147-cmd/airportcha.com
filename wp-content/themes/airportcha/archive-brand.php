<?php
get_header();

$current_feature = isset( $_GET['feature'] ) ? sanitize_title( wp_unslash( $_GET['feature'] ) ) : '';
$features        = get_terms(
	array(
		'taxonomy'   => 'brand_feature',
		'hide_empty' => true,
	)
);

$args = array(
	'post_type'      => 'brand',
	'posts_per_page' => 24,
	'paged'          => max( 1, get_query_var( 'paged' ) ),
	'meta_key'       => '_ac_sort_order',
	'orderby'        => 'meta_value_num',
	'order'          => 'ASC',
);

if ( $current_feature ) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'brand_feature',
			'field'    => 'slug',
			'terms'    => $current_feature,
		),
	);
}

$brand_query = new WP_Query( $args );
?>

<section class="ac-page-header">
	<div class="ac-container">
		<?php airportcha_breadcrumbs(); ?>
		<h1>机场服务品牌目录</h1>
		<p>收录并持续追踪全球主流网络加速与专线机场品牌，支持按核心特性筛选与评分排序。</p>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container">
		<div class="ac-toolbar-row">
			<?php if ( ! empty( $features ) && ! is_wp_error( $features ) ) : ?>
				<div class="ac-filter-bar" style="margin-bottom:0;">
					<a class="ac-filter-pill <?php echo '' === $current_feature ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'brand' ) ); ?>">全部品牌</a>
					<?php foreach ( $features as $f ) : ?>
						<a class="ac-filter-pill <?php echo $current_feature === $f->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'feature', $f->slug, get_post_type_archive_link( 'brand' ) ) ); ?>">
							<?php echo esc_html( $f->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<select id="ac-brand-sort" class="ac-sort-select" aria-label="排序方式">
				<option value="order">按编辑推荐排序</option>
				<option value="rating">按独立评分从高到低</option>
			</select>
		</div>

		<?php if ( $brand_query->have_posts() ) : ?>
			<div id="ac-brand-directory-grid" class="ac-brand-grid">
				<?php
				while ( $brand_query->have_posts() ) :
					$brand_query->the_post();
					echo airportcha_render_brand_card_html( get_the_ID(), 'standard', 'brand_directory' );
				endwhile;
				?>
			</div>

			<div class="ac-pagination">
				<?php
				echo paginate_links(
					array(
						'total' => $brand_query->max_num_pages,
					)
				);
				?>
			</div>
			<?php
			wp_reset_postdata();
		else :
			echo '<p>暂无符合条件的品牌。</p>';
		endif;
		?>
	</div>
</main>

<?php
get_footer();
