<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 注册自定义文章类型：品牌目录(brand)、品牌评测(review)、品牌比较(comparison)
 */
function airportcha_register_post_types() {
	// 1. 机场品牌目录 /brands/
	register_post_type(
		'brand',
		array(
			'labels'              => array(
				'name'               => '品牌目录',
				'singular_name'      => '机场品牌',
				'menu_name'          => '品牌目录',
				'add_new'            => '添加新品牌',
				'add_new_item'       => '添加新机场品牌',
				'edit_item'          => '编辑机场品牌',
				'new_item'           => '新机场品牌',
				'view_item'          => '查看品牌详情',
				'search_items'       => '搜索品牌',
				'not_found'          => '未找到品牌',
				'not_found_in_trash' => '回收站中无品牌',
				'all_items'          => '所有品牌',
			),
			'public'              => true,
			'has_archive'         => 'brands',
			'rewrite'             => array(
				'slug'       => 'brands',
				'with_front' => false,
			),
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-networking',
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
			'show_in_nav_menus'   => true,
		)
	);

	// 2. 品牌评测 /reviews/
	register_post_type(
		'review',
		array(
			'labels'              => array(
				'name'               => '品牌评测',
				'singular_name'      => '品牌评测',
				'menu_name'          => '品牌评测',
				'add_new'            => '撰写新评测',
				'add_new_item'       => '撰写新品牌评测',
				'edit_item'          => '编辑品牌评测',
				'new_item'           => '新品牌评测',
				'view_item'          => '查看评测',
				'search_items'       => '搜索评测',
				'not_found'          => '未找到评测',
				'not_found_in_trash' => '回收站中无评测',
				'all_items'          => '所有评测',
			),
			'public'              => true,
			'has_archive'         => 'reviews',
			'rewrite'             => array(
				'slug'       => 'reviews',
				'with_front' => false,
			),
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-chart-bar',
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
			'show_in_nav_menus'   => true,
		)
	);

	// 3. 品牌比较 /comparisons/
	register_post_type(
		'comparison',
		array(
			'labels'              => array(
				'name'               => '品牌比较',
				'singular_name'      => '品牌比较',
				'menu_name'          => '品牌比较',
				'add_new'            => '新建对比',
				'add_new_item'       => '新建品牌比较',
				'edit_item'          => '编辑品牌比较',
				'new_item'           => '新品牌比较',
				'view_item'          => '查看对比',
				'search_items'       => '搜索品牌比较',
				'not_found'          => '未找到品牌比较',
				'not_found_in_trash' => '回收站中无品牌比较',
				'all_items'          => '所有比较',
			),
			'public'              => true,
			'has_archive'         => 'comparisons',
			'rewrite'             => array(
				'slug'       => 'comparisons',
				'with_front' => false,
			),
			'menu_position'       => 7,
			'menu_icon'           => 'dashicons-columns',
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'show_in_nav_menus'   => true,
		)
	);
}
add_action( 'init', 'airportcha_register_post_types' );

/**
 * 注册品牌自定义分类法：特性标签 & 节点地区
 */
function airportcha_register_taxonomies() {
	register_taxonomy(
		'brand_feature',
		array( 'brand' ),
		array(
			'labels'            => array(
				'name'          => '品牌特性',
				'singular_name' => '品牌特性',
				'search_items'  => '搜索特性',
				'all_items'     => '所有特性',
				'edit_item'     => '编辑特性',
				'add_new_item'  => '添加新特性',
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'brand-feature' ),
		)
	);

	register_taxonomy(
		'brand_region',
		array( 'brand' ),
		array(
			'labels'            => array(
				'name'          => '节点地区',
				'singular_name' => '节点地区',
				'search_items'  => '搜索地区',
				'all_items'     => '所有地区',
				'edit_item'     => '编辑地区',
				'add_new_item'  => '添加新地区',
			),
			'hierarchical'      => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'brand-region' ),
		)
	);
}
add_action( 'init', 'airportcha_register_taxonomies' );

/**
 * 让全站搜索同时包含 post、brand、review、comparison
 */
function airportcha_extend_search_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'brand', 'review', 'comparison' ) );
	}
}
add_action( 'pre_get_posts', 'airportcha_extend_search_query' );
