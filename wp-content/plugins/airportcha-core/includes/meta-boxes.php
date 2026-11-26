<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 定义五级品牌商业等级与推荐位置选项
 */
function airportcha_get_brand_tiers() {
	return array(
		'PRIMARY'  => 'PRIMARY（核心主推）',
		'FEATURED' => 'FEATURED（重点合作）',
		'PARTNER'  => 'PARTNER（普通合作）',
		'ARCHIVE'  => 'ARCHIVE（非合作档案）',
		'RISK'     => 'RISK（风险/停止推荐）',
	);
}

function airportcha_get_brand_statuses() {
	return array(
		'active'    => '正常运营',
		'pending'   => '待完善',
		'suspended' => '暂停推荐',
		'closed'    => '已停止运营',
	);
}

function airportcha_get_placement_options() {
	return array(
		'home_primary'   => '首页主推品牌',
		'home_featured'  => '首页核心品牌推荐',
		'recommend_rank' => '机场推荐排行榜',
		'article_inline' => '文章正文推荐',
		'review_box'     => '深度评测推荐',
		'sidebar'        => '侧边栏推荐',
	);
}

/**
 * 注册后台 Meta Boxes
 */
function airportcha_add_meta_boxes() {
	add_meta_box(
		'airportcha_brand_details',
		'品牌数据库、商业等级与推广配置（Phase 2）',
		'airportcha_render_brand_meta_box',
		'brand',
		'normal',
		'high'
	);

	add_meta_box(
		'airportcha_review_details',
		'评测评分与实测结论',
		'airportcha_render_review_meta_box',
		'review',
		'normal',
		'high'
	);

	add_meta_box(
		'airportcha_comparison_details',
		'对比品牌配置',
		'airportcha_render_comparison_meta_box',
		'comparison',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'airportcha_add_meta_boxes' );

/**
 * 渲染：品牌详情 Meta Box（分组化后台表单）
 */
function airportcha_render_brand_meta_box( $post ) {
	wp_nonce_field( 'airportcha_save_brand_meta', 'airportcha_brand_nonce' );
	$meta       = airportcha_get_brand_meta( $post->ID );
	$tiers      = airportcha_get_brand_tiers();
	$statuses   = airportcha_get_brand_statuses();
	$placements = airportcha_get_placement_options();
	$selected_p = is_array( $meta['placements'] ) ? $meta['placements'] : array();
	?>
	<style>
		.ac-meta-section { margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #dcdcde; }
		.ac-meta-section h4 { margin: 0 0 12px; color: #101B32; font-size: 14px; }
		.ac-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
		.ac-meta-field { display: flex; flex-direction: column; gap: 6px; }
		.ac-meta-field.full { grid-column: 1 / -1; }
		.ac-meta-field label { font-weight: 600; color: #1d2327; }
		.ac-meta-field input[type="text"],
		.ac-meta-field input[type="url"],
		.ac-meta-field input[type="number"],
		.ac-meta-field select,
		.ac-meta-field textarea { width: 100%; }
		.ac-check-group { display: flex; flex-wrap: wrap; gap: 12px; }
	</style>

	<div class="ac-meta-section">
		<h4>1. 品牌基础信息与运营状态</h4>
		<div class="ac-meta-grid">
			<div class="ac-meta-field">
				<label for="ac_en_name">英文名称 / 品牌别名</label>
				<input type="text" id="ac_en_name" name="ac_en_name" value="<?php echo esc_attr( $meta['en_name'] ); ?>" placeholder="如：GuangNianTi / LightYear" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_tagline">一句话定位</label>
				<input type="text" id="ac_tagline" name="ac_tagline" value="<?php echo esc_attr( $meta['tagline'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_status">运营状态</label>
				<select id="ac_status" name="ac_status">
					<?php foreach ( $statuses as $k => $label ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $meta['status'], $k ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="ac-meta-field">
				<label for="ac_rating">独立评测综合评分（1.0 - 5.0，不受商业等级影响）</label>
				<input type="number" step="0.1" min="1" max="5" id="ac_rating" name="ac_rating" value="<?php echo esc_attr( $meta['rating'] ); ?>" />
			</div>
		</div>
	</div>

	<div class="ac-meta-section">
		<h4>2. 商业等级、推广链接与推荐位置</h4>
		<div class="ac-meta-grid">
			<div class="ac-meta-field">
				<label for="ac_tier">五级商业合作等级</label>
				<select id="ac_tier" name="ac_tier">
					<?php foreach ( $tiers as $k => $label ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $meta['tier'], $k ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="ac-meta-field">
				<label for="ac_affiliate_status">推广状态</label>
				<select id="ac_affiliate_status" name="ac_affiliate_status">
					<option value="pending" <?php selected( $meta['affiliate_status'], 'pending' ); ?>>待配置（隐藏购买按钮）</option>
					<option value="active" <?php selected( $meta['affiliate_status'], 'active' ); ?>>已生效（启用 /go/ 跳转）</option>
					<option value="disabled" <?php selected( $meta['affiliate_status'], 'disabled' ); ?>>已停用</option>
				</select>
			</div>
			<div class="ac-meta-field">
				<label for="ac_affiliate_url">真实推广目标 URL（严禁填写本站 /go/ 路径）</label>
				<input type="url" id="ac_affiliate_url" name="ac_affiliate_url" value="<?php echo esc_attr( $meta['affiliate_url'] ); ?>" placeholder="https://example.com/?aff=xxx" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_sort_order">推荐排序权重（数字越小越靠前）</label>
				<input type="number" id="ac_sort_order" name="ac_sort_order" value="<?php echo esc_attr( $meta['sort_order'] ); ?>" />
			</div>
			<div class="ac-meta-field full">
				<label>允许展示的商业推荐位置</label>
				<div class="ac-check-group">
					<?php foreach ( $placements as $pk => $plabel ) : ?>
						<label style="font-weight:400;">
							<input type="checkbox" name="ac_placements[]" value="<?php echo esc_attr( $pk ); ?>" <?php checked( in_array( $pk, $selected_p, true ) ); ?> />
							<?php echo esc_html( $plabel ); ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="ac-meta-section">
		<h4>3. 产品参数、套餐价格与优惠活动</h4>
		<div class="ac-meta-grid">
			<div class="ac-meta-field">
				<label for="ac_price">起步价格（如：¥28 / 月起）</label>
				<input type="text" id="ac_price" name="ac_price" value="<?php echo esc_attr( $meta['price'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_protocols">支持协议</label>
				<input type="text" id="ac_protocols" name="ac_protocols" value="<?php echo esc_attr( $meta['protocols'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_nodes">节点规模</label>
				<input type="text" id="ac_nodes" name="ac_nodes" value="<?php echo esc_attr( $meta['nodes'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_streaming">流媒体与 AI 解锁</label>
				<input type="text" id="ac_streaming" name="ac_streaming" value="<?php echo esc_attr( $meta['streaming'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_coupon">专属优惠码（选填）</label>
				<input type="text" id="ac_coupon" name="ac_coupon" value="<?php echo esc_attr( $meta['coupon'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_coupon_desc">优惠说明与有效期（如：年付 9 折 · 2026-12-31 止）</label>
				<input type="text" id="ac_coupon_desc" name="ac_coupon_desc" value="<?php echo esc_attr( $meta['coupon_desc'] ); ?>" />
			</div>
			<div class="ac-meta-field full">
				<label for="ac_plans_json">套餐列表 JSON（每项含 name, price, cycle, traffic, best_for）</label>
				<textarea id="ac_plans_json" name="ac_plans_json" rows="4" placeholder='[{"name":"标准月付","price":"¥28","cycle":"月付","traffic":"200GB/月","best_for":"日常办公与 AI"}]'><?php echo esc_textarea( $meta['plans_json'] ); ?></textarea>
			</div>
			<div class="ac-meta-field full">
				<label for="ac_highlights">核心优势（每行一条）</label>
				<textarea id="ac_highlights" name="ac_highlights" rows="3"><?php echo esc_textarea( $meta['highlights'] ); ?></textarea>
			</div>
			<div class="ac-meta-field full">
				<label for="ac_best_for">适用人群</label>
				<input type="text" id="ac_best_for" name="ac_best_for" value="<?php echo esc_attr( $meta['best_for'] ); ?>" />
			</div>
		</div>
	</div>

	<div class="ac-meta-section" style="border-bottom:none;margin-bottom:0;padding-bottom:0;">
		<h4>4. SEO / GEO 实体与数据来源</h4>
		<div class="ac-meta-grid">
			<div class="ac-meta-field">
				<label for="ac_seo_title">自定义 SEO Title（留空自动生成）</label>
				<input type="text" id="ac_seo_title" name="ac_seo_title" value="<?php echo esc_attr( $meta['seo_title'] ); ?>" />
			</div>
			<div class="ac-meta-field">
				<label for="ac_source">资料来源说明</label>
				<input type="text" id="ac_source" name="ac_source" value="<?php echo esc_attr( $meta['source'] ); ?>" placeholder="如：官方公开套餐资料与 AIRPORTCHA 实测" />
			</div>
			<div class="ac-meta-field full">
				<label for="ac_seo_desc">自定义 Meta Description（留空自动摘要）</label>
				<textarea id="ac_seo_desc" name="ac_seo_desc" rows="2"><?php echo esc_textarea( $meta['seo_desc'] ); ?></textarea>
			</div>
			<div class="ac-meta-field full">
				<label for="ac_faq_json">品牌 FAQ JSON（用于页面展示与 FAQPage Schema，每项含 q, a）</label>
				<textarea id="ac_faq_json" name="ac_faq_json" rows="3" placeholder='[{"q":"支持哪些客户端？","a":"支持 Clash Verge Rev、Shadowrocket 与 Sing-box。"}]'><?php echo esc_textarea( $meta['faq_json'] ); ?></textarea>
			</div>
		</div>
		<p style="margin:12px 0 0;color:#5C6B82;font-size:12px;">
			累计本地推广点击：<strong><?php echo esc_html( (int) $meta['outbound_clicks'] ); ?></strong> · 统一跳转路由：<code>/go/<?php echo esc_html( $post->post_name ?: '{slug}' ); ?>/</code>
		</p>
	</div>
	<?php
}

/**
 * 渲染：评测详情 Meta Box
 */
function airportcha_render_review_meta_box( $post ) {
	wp_nonce_field( 'airportcha_save_review_meta', 'airportcha_review_nonce' );
	$meta   = airportcha_get_review_meta( $post->ID );
	$brands = get_posts(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_review_brand_id">关联机场品牌</label>
			<select id="ac_review_brand_id" name="ac_review_brand_id">
				<option value="">-- 请选择品牌 --</option>
				<?php foreach ( $brands as $b ) : ?>
					<option value="<?php echo esc_attr( $b->ID ); ?>" <?php selected( (int) $meta['brand_id'], $b->ID ); ?>>
						<?php echo esc_html( $b->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_score_overall">综合评分（1.0 - 5.0）</label>
			<input type="number" step="0.1" min="1" max="5" id="ac_score_overall" name="ac_score_overall" value="<?php echo esc_attr( $meta['score_overall'] ); ?>" />
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_score_speed">速度表现（1.0 - 5.0）</label>
			<input type="number" step="0.1" min="1" max="5" id="ac_score_speed" name="ac_score_speed" value="<?php echo esc_attr( $meta['score_speed'] ); ?>" />
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_score_stability">稳定性（1.0 - 5.0）</label>
			<input type="number" step="0.1" min="1" max="5" id="ac_score_stability" name="ac_score_stability" value="<?php echo esc_attr( $meta['score_stability'] ); ?>" />
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_score_value">性价比（1.0 - 5.0）</label>
			<input type="number" step="0.1" min="1" max="5" id="ac_score_value" name="ac_score_value" value="<?php echo esc_attr( $meta['score_value'] ); ?>" />
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_score_streaming">流媒体与 AI 解锁（1.0 - 5.0）</label>
			<input type="number" step="0.1" min="1" max="5" id="ac_score_streaming" name="ac_score_streaming" value="<?php echo esc_attr( $meta['score_streaming'] ); ?>" />
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_pros">优点（每行一条）</label>
			<textarea id="ac_pros" name="ac_pros" rows="4"><?php echo esc_textarea( $meta['pros'] ); ?></textarea>
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_cons">不足之处（每行一条）</label>
			<textarea id="ac_cons" name="ac_cons" rows="4"><?php echo esc_textarea( $meta['cons'] ); ?></textarea>
		</div>
		<div style="grid-column:1 / -1;display:flex;flex-direction:column;gap:6px;">
			<label for="ac_verdict">编辑实测总结</label>
			<textarea id="ac_verdict" name="ac_verdict" rows="3"><?php echo esc_textarea( $meta['verdict'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/**
 * 渲染：品牌比较 Meta Box
 */
function airportcha_render_comparison_meta_box( $post ) {
	wp_nonce_field( 'airportcha_save_comparison_meta', 'airportcha_comparison_nonce' );
	$meta   = airportcha_get_comparison_meta( $post->ID );
	$brands = get_posts(
		array(
			'post_type'      => 'brand',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_comp_brand_a">对比品牌 A</label>
			<select id="ac_comp_brand_a" name="ac_comp_brand_a">
				<option value="">-- 请选择品牌 --</option>
				<?php foreach ( $brands as $b ) : ?>
					<option value="<?php echo esc_attr( $b->ID ); ?>" <?php selected( (int) $meta['brand_a'], $b->ID ); ?>>
						<?php echo esc_html( $b->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div style="display:flex;flex-direction:column;gap:6px;">
			<label for="ac_comp_brand_b">对比品牌 B</label>
			<select id="ac_comp_brand_b" name="ac_comp_brand_b">
				<option value="">-- 请选择品牌 --</option>
				<?php foreach ( $brands as $b ) : ?>
					<option value="<?php echo esc_attr( $b->ID ); ?>" <?php selected( (int) $meta['brand_b'], $b->ID ); ?>>
						<?php echo esc_html( $b->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div style="grid-column:1 / -1;display:flex;flex-direction:column;gap:6px;">
			<label for="ac_comp_summary">一句话对比结论</label>
			<textarea id="ac_comp_summary" name="ac_comp_summary" rows="3"><?php echo esc_textarea( $meta['summary'] ); ?></textarea>
		</div>
	</div>
	<?php
}

/**
 * 保存 Meta 数据
 */
function airportcha_save_all_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['airportcha_brand_nonce'] ) && wp_verify_nonce( $_POST['airportcha_brand_nonce'], 'airportcha_save_brand_meta' ) ) {
		$fields = array(
			'ac_en_name'          => 'sanitize_text_field',
			'ac_tagline'          => 'sanitize_text_field',
			'ac_status'           => 'sanitize_key',
			'ac_tier'             => 'sanitize_text_field',
			'ac_affiliate_status' => 'sanitize_key',
			'ac_rating'           => 'sanitize_text_field',
			'ac_price'            => 'sanitize_text_field',
			'ac_protocols'        => 'sanitize_text_field',
			'ac_nodes'            => 'sanitize_text_field',
			'ac_streaming'        => 'sanitize_text_field',
			'ac_affiliate_url'    => 'esc_url_raw',
			'ac_coupon'           => 'sanitize_text_field',
			'ac_coupon_desc'      => 'sanitize_text_field',
			'ac_sort_order'       => 'intval',
			'ac_highlights'       => 'sanitize_textarea_field',
			'ac_best_for'         => 'sanitize_text_field',
			'ac_plans_json'       => 'wp_kses_post',
			'ac_seo_title'        => 'sanitize_text_field',
			'ac_seo_desc'         => 'sanitize_textarea_field',
			'ac_faq_json'         => 'wp_kses_post',
			'ac_source'           => 'sanitize_text_field',
		);
		foreach ( $fields as $key => $cb ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, '_' . $key, call_user_func( $cb, wp_unslash( $_POST[ $key ] ) ) );
			}
		}

		$tier = isset( $_POST['ac_tier'] ) ? sanitize_text_field( wp_unslash( $_POST['ac_tier'] ) ) : 'ARCHIVE';
		update_post_meta( $post_id, '_ac_featured', in_array( $tier, array( 'PRIMARY', 'FEATURED' ), true ) ? '1' : '0' );

		$raw_p = isset( $_POST['ac_placements'] ) && is_array( $_POST['ac_placements'] ) ? array_map( 'sanitize_key', wp_unslash( $_POST['ac_placements'] ) ) : array();
		update_post_meta( $post_id, '_ac_placements', $raw_p );
	}

	if ( isset( $_POST['airportcha_review_nonce'] ) && wp_verify_nonce( $_POST['airportcha_review_nonce'], 'airportcha_save_review_meta' ) ) {
		$fields = array(
			'ac_review_brand_id' => 'intval',
			'ac_score_overall'   => 'sanitize_text_field',
			'ac_score_speed'     => 'sanitize_text_field',
			'ac_score_stability' => 'sanitize_text_field',
			'ac_score_value'     => 'sanitize_text_field',
			'ac_score_streaming' => 'sanitize_text_field',
			'ac_pros'            => 'sanitize_textarea_field',
			'ac_cons'            => 'sanitize_textarea_field',
			'ac_verdict'         => 'sanitize_textarea_field',
		);
		foreach ( $fields as $key => $cb ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, '_' . $key, call_user_func( $cb, wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}

	if ( isset( $_POST['airportcha_comparison_nonce'] ) && wp_verify_nonce( $_POST['airportcha_comparison_nonce'], 'airportcha_save_comparison_meta' ) ) {
		update_post_meta( $post_id, '_ac_comp_brand_a', isset( $_POST['ac_comp_brand_a'] ) ? intval( $_POST['ac_comp_brand_a'] ) : 0 );
		update_post_meta( $post_id, '_ac_comp_brand_b', isset( $_POST['ac_comp_brand_b'] ) ? intval( $_POST['ac_comp_brand_b'] ) : 0 );
		update_post_meta( $post_id, '_ac_comp_summary', isset( $_POST['ac_comp_summary'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ac_comp_summary'] ) ) : '' );
	}
}
add_action( 'save_post', 'airportcha_save_all_meta' );

/**
 * 后台品牌列表自定义管理列
 */
function airportcha_brand_admin_columns( $columns ) {
	return array(
		'cb'        => $columns['cb'],
		'title'     => '品牌名称',
		'ac_tier'   => '商业等级',
		'ac_status' => '运营/推广状态',
		'ac_price'  => '起步价格',
		'ac_rating' => '独立评分',
		'ac_order'  => '权重',
		'ac_clicks' => '点击量',
		'date'      => '更新时间',
	);
}
add_filter( 'manage_brand_posts_columns', 'airportcha_brand_admin_columns' );

function airportcha_brand_admin_column_values( $column, $post_id ) {
	$meta = airportcha_get_brand_meta( $post_id );
	switch ( $column ) {
		case 'ac_tier':
			echo '<strong>' . esc_html( $meta['tier'] ) . '</strong>';
			break;
		case 'ac_status':
			echo esc_html( $meta['status'] . ' / ' . $meta['affiliate_status'] );
			break;
		case 'ac_price':
			echo esc_html( $meta['price'] ?: '—' );
			break;
		case 'ac_rating':
			echo esc_html( $meta['rating'] . ' / 5' );
			break;
		case 'ac_order':
			echo esc_html( (string) $meta['sort_order'] );
			break;
		case 'ac_clicks':
			echo esc_html( (string) $meta['outbound_clicks'] );
			break;
	}
}
add_action( 'manage_brand_posts_custom_column', 'airportcha_brand_admin_column_values', 10, 2 );

/**
 * 辅助函数：读取品牌完整字段（向下兼容第一阶段数据）
 */
function airportcha_get_brand_meta( $post_id ) {
	$featured = (string) ( get_post_meta( $post_id, '_ac_featured', true ) ?: '0' );
	$tier     = (string) get_post_meta( $post_id, '_ac_tier', true );
	if ( '' === $tier ) {
		$tier = '1' === $featured ? 'FEATURED' : 'ARCHIVE';
	}
	$aff_url    = (string) get_post_meta( $post_id, '_ac_affiliate_url', true );
	$aff_status = (string) get_post_meta( $post_id, '_ac_affiliate_status', true );
	if ( '' === $aff_status ) {
		$aff_status = ( '' !== $aff_url && false === strpos( $aff_url, '/go/' ) ) ? 'active' : 'pending';
	}
	$placements = get_post_meta( $post_id, '_ac_placements', true );
	if ( ! is_array( $placements ) ) {
		$placements = array();
	}

	return array(
		'en_name'          => (string) get_post_meta( $post_id, '_ac_en_name', true ),
		'tagline'          => (string) get_post_meta( $post_id, '_ac_tagline', true ),
		'status'           => (string) ( get_post_meta( $post_id, '_ac_status', true ) ?: 'active' ),
		'tier'             => $tier,
		'affiliate_status' => $aff_status,
		'rating'           => (string) ( get_post_meta( $post_id, '_ac_rating', true ) ?: '4.5' ),
		'price'            => (string) get_post_meta( $post_id, '_ac_price', true ),
		'protocols'        => (string) get_post_meta( $post_id, '_ac_protocols', true ),
		'nodes'            => (string) get_post_meta( $post_id, '_ac_nodes', true ),
		'streaming'        => (string) get_post_meta( $post_id, '_ac_streaming', true ),
		'affiliate_url'    => $aff_url,
		'coupon'           => (string) get_post_meta( $post_id, '_ac_coupon', true ),
		'coupon_desc'      => (string) get_post_meta( $post_id, '_ac_coupon_desc', true ),
		'sort_order'       => (int) ( get_post_meta( $post_id, '_ac_sort_order', true ) ?: 10 ),
		'featured'         => in_array( $tier, array( 'PRIMARY', 'FEATURED' ), true ) ? '1' : $featured,
		'placements'       => $placements,
		'plans_json'       => (string) get_post_meta( $post_id, '_ac_plans_json', true ),
		'highlights'       => (string) get_post_meta( $post_id, '_ac_highlights', true ),
		'best_for'         => (string) get_post_meta( $post_id, '_ac_best_for', true ),
		'seo_title'        => (string) get_post_meta( $post_id, '_ac_seo_title', true ),
		'seo_desc'         => (string) get_post_meta( $post_id, '_ac_seo_desc', true ),
		'faq_json'         => (string) get_post_meta( $post_id, '_ac_faq_json', true ),
		'source'           => (string) get_post_meta( $post_id, '_ac_source', true ),
		'outbound_clicks'  => (int) get_post_meta( $post_id, '_ac_outbound_clicks', true ),
	);
}

/**
 * 辅助函数：解析品牌套餐 JSON
 */
function airportcha_get_brand_plans( $post_id ) {
	$raw = get_post_meta( $post_id, '_ac_plans_json', true );
	if ( empty( $raw ) ) {
		return array();
	}
	$decoded = json_decode( $raw, true );
	return is_array( $decoded ) ? $decoded : array();
}

/**
 * 辅助函数：解析品牌 FAQ JSON
 */
function airportcha_get_brand_faqs( $post_id ) {
	$raw = get_post_meta( $post_id, '_ac_faq_json', true );
	if ( empty( $raw ) ) {
		return array();
	}
	$decoded = json_decode( $raw, true );
	return is_array( $decoded ) ? $decoded : array();
}

/**
 * 辅助函数：读取评测字段
 */
function airportcha_get_review_meta( $post_id ) {
	return array(
		'brand_id'        => (int) get_post_meta( $post_id, '_ac_review_brand_id', true ),
		'score_overall'   => (string) ( get_post_meta( $post_id, '_ac_score_overall', true ) ?: '4.5' ),
		'score_speed'     => (string) ( get_post_meta( $post_id, '_ac_score_speed', true ) ?: '4.5' ),
		'score_stability' => (string) ( get_post_meta( $post_id, '_ac_score_stability', true ) ?: '4.5' ),
		'score_value'     => (string) ( get_post_meta( $post_id, '_ac_score_value', true ) ?: '4.5' ),
		'score_streaming' => (string) ( get_post_meta( $post_id, '_ac_score_streaming', true ) ?: '4.5' ),
		'pros'            => (string) get_post_meta( $post_id, '_ac_pros', true ),
		'cons'            => (string) get_post_meta( $post_id, '_ac_cons', true ),
		'verdict'         => (string) get_post_meta( $post_id, '_ac_verdict', true ),
	);
}

/**
 * 辅助函数：读取比较字段
 */
function airportcha_get_comparison_meta( $post_id ) {
	return array(
		'brand_a' => (int) get_post_meta( $post_id, '_ac_comp_brand_a', true ),
		'brand_b' => (int) get_post_meta( $post_id, '_ac_comp_brand_b', true ),
		'summary' => (string) get_post_meta( $post_id, '_ac_comp_summary', true ),
	);
}
