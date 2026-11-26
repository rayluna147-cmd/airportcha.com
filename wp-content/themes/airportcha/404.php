<?php
get_header();
?>

<section class="ac-page-header">
	<div class="ac-container ac-container-narrow">
		<h1>404 · 页面未找到</h1>
		<p>抱歉，你访问的页面可能已移动或不存在。</p>
	</div>
</section>

<main class="ac-section">
	<div class="ac-container ac-container-narrow">
		<div class="ac-article-wrap" style="text-align:center;">
			<p>你可以返回首页，或直接搜索你想找的机场品牌与评测。</p>
			<div style="margin-top:20px;">
				<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">返回首页</a>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">浏览品牌目录</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
