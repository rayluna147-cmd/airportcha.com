<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="ac-header">
	<div class="ac-container ac-header-inner">
		<a class="ac-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="ac-logo-mark">A</span>
			<span>AIRPORTCHA</span>
		</a>

		<nav id="ac-primary-nav" class="ac-nav" aria-label="主导航">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'airportcha_fallback_menu',
				)
			);
			?>
		</nav>

		<div class="ac-header-tools">
			<button id="ac-search-toggle" class="ac-search-toggle" type="button" aria-label="打开搜索">
				搜索
			</button>
			<button id="ac-menu-toggle" class="ac-menu-toggle" type="button" aria-expanded="false" aria-label="展开菜单">
				菜单
			</button>
		</div>
	</div>

	<div id="ac-search-drawer" class="ac-search-drawer">
		<div class="ac-container">
			<form role="search" method="get" class="ac-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="搜索机场品牌、深度评测、Clash 教程或网络知识..." />
				<button type="submit" class="ac-btn ac-btn-primary">搜索</button>
			</form>
		</div>
	</div>
</header>
