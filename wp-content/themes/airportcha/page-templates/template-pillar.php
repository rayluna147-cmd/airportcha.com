<?php
/**
 * Template Name: 一级核心流量专题页（Pillar Page）
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="ac-page-header">
		<div class="ac-container">
			<?php airportcha_breadcrumbs(); ?>
			<h1><?php the_title(); ?></h1>
			<p>AIRPORTCHA 一级核心专题 · 基于正式白名单多品牌数据库持续更新 · 最近更新：<?php echo esc_html( get_the_modified_date() ); ?></p>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container">
			<article class="ac-article-wrap">
				<nav id="ac-auto-toc" class="ac-tldr-box" style="display:none;" aria-label="专题目录">
					<strong style="display:block;margin-bottom:8px;color:var(--ac-navy);">本专题目录</strong>
					<ul id="ac-toc-list" style="margin:0;padding-left:18px;font-size:14px;display:flex;flex-direction:column;gap:4px;"></ul>
				</nav>

				<div class="ac-prose" id="ac-review-body">
					<?php the_content(); ?>
				</div>

				<section class="ac-score-box" style="margin-top:36px;margin-bottom:0;">
					<h3 style="margin:0 0 10px;font-size:17px;color:var(--ac-navy);">相关核心专题与品牌入口</h3>
					<div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
						<a class="ac-btn ac-btn-sm ac-btn-primary" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">机场推荐总榜</a>
						<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/stable-airports/' ) ); ?>">稳定机场专题</a>
						<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/value-airports/' ) ); ?>">性价比机场专题</a>
						<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">机场价格与套餐</a>
						<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">全部品牌目录</a>
						<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>">深度评测中心</a>
					</div>
					<p style="margin:0;font-size:12px;color:var(--ac-muted);">
						资料来源：AIRPORTCHA 正式白名单品牌数据库与编辑部整理 · 最近更新：<?php echo esc_html( get_the_modified_date() ); ?>
					</p>
				</section>
			</article>
		</div>
	</main>
<?php
endwhile;
get_footer();
