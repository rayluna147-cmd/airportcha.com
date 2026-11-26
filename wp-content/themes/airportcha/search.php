<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container">
		<?php airportcha_breadcrumbs(); ?>
		<h1>搜索结果：<?php echo esc_html( get_search_query() ); ?></h1>
		<p>共为您检索机场品牌、深度评测、品牌比较与博客知识库内容。</p>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container">
		<form role="search" method="get" class="ac-search-form" style="margin:0 0 32px;" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="输入关键词重新搜索..." />
			<button type="submit" class="ac-btn ac-btn-primary">搜索</button>
		</form>

		<?php if ( have_posts() ) : ?>
			<div class="ac-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$pto = get_post_type_object( get_post_type() );
					?>
					<article class="ac-card">
						<div class="ac-post-meta">
							<span class="ac-badge"><?php echo esc_html( $pto ? $pto->labels->singular_name : '文章' ); ?></span>
							<span><?php echo esc_html( get_the_date() ); ?></span>
						</div>
						<h2 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36 ) ); ?></p>
						<div class="ac-card-actions">
							<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">查看内容</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="ac-pagination"><?php echo paginate_links(); ?></div>
		<?php else : ?>
			<div class="ac-article-wrap">
				<p>未找到与「<strong><?php echo esc_html( get_search_query() ); ?></strong>」相关的内容，请尝试更换关键词（如：光年梯、IPLC、Clash）。</p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
