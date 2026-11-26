<?php
/**
 * Plugin Name: AIRPORTCHA Core
 * Plugin URI:  https://airportcha.com/
 * Description: AIRPORTCHA 核心内容与商业管理插件：多品牌数据库、五级商业等级、统一 /go/ 推广跳转与点击统计、动态推荐组件、Bing SEO/GEO 结构化数据与静态导出支持。
 * Version:     2.0.0
 * Author:      AIRPORTCHA
 * Text Domain: airportcha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIRPORTCHA_CORE_VERSION', '2.0.0' );
define( 'AIRPORTCHA_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'AIRPORTCHA_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once AIRPORTCHA_CORE_DIR . 'includes/post-types.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/meta-boxes.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/redirects.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/shortcodes.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/seo.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/brand-reviews-data.php';
require_once AIRPORTCHA_CORE_DIR . 'includes/seeder.php';

/**
 * 插件激活时注册 CPT、跳转路由并刷新固定链接
 */
function airportcha_core_activate() {
	airportcha_register_post_types();
	airportcha_register_taxonomies();
	airportcha_register_go_rewrite();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'airportcha_core_activate' );

/**
 * 插件停用时刷新固定链接
 */
function airportcha_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'airportcha_core_deactivate' );
