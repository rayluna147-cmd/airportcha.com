<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Airportcha_Seeder {

	public static function init_admin() {
		add_action( 'admin_menu', array( __CLASS__, 'add_tools_page' ) );
	}

	public static function add_tools_page() {
		add_management_page(
			'AIRPORTCHA 站点初始化',
			'AIRPORTCHA 初始化',
			'manage_options',
			'airportcha-seeder',
			array( __CLASS__, 'render_tools_page' )
		);
	}

	public static function render_tools_page() {
		$message = '';
		if ( isset( $_POST['airportcha_run_seed'] ) && check_admin_referer( 'airportcha_seed_action' ) ) {
			self::seed( true );
			$message = '初始化完成！已同步 6 个正式白名单品牌、6 篇深度评测、2 篇品牌对比、核心页面与导航菜单（已存在的内容绝不覆盖）。';
		}
		?>
		<div class="wrap">
			<h1>AIRPORTCHA 正式运营数据初始化（Phase 04 白名单版）</h1>
			<?php if ( $message ) : ?>
				<div class="notice notice-success"><p><?php echo esc_html( $message ); ?></p></div>
			<?php endif; ?>
			<p>点击下方按钮将安全地初始化 AIRPORTCHA 的核心页面、导航菜单、6 个正式白名单品牌及深度评测（幂等执行，不覆盖已修改数据，不恢复已清理的示例品牌）。</p>
			<form method="post">
				<?php wp_nonce_field( 'airportcha_seed_action' ); ?>
				<button type="submit" name="airportcha_run_seed" class="button button-primary button-hero">
					一键同步正式白名单数据
				</button>
			</form>
		</div>
		<?php
	}

	/**
	 * 执行正式站点初始化（仅包含用户指定白名单 6 大品牌，彻底移除旧示例品牌）
	 */
	public static function seed( $force = false ) {
		if ( ! $force && get_option( 'airportcha_seeded_v4' ) ) {
			return;
		}

		// 1. 文章分类
		$cat_blog      = self::ensure_term( '博客', 'category', 'blog', '网络科技资讯与行业观察' );
		$cat_guides    = self::ensure_term( '使用教程', 'category', 'guides', '客户端配置、代理规则与多平台使用教程' );
		$cat_knowledge = self::ensure_term( '网络知识', 'category', 'knowledge', '协议原理、IPLC 专线、DNS 与隐私安全科普' );

		// 2. 品牌特性与节点地区分类法
		$feat_iplc   = self::ensure_term( 'IPLC 专线', 'brand_feature', 'iplc' );
		$feat_ai     = self::ensure_term( 'ChatGPT/AI 解锁', 'brand_feature', 'ai-unlock' );
		$feat_stream = self::ensure_term( '4K 流媒体解锁', 'brand_feature', 'streaming' );
		$feat_value  = self::ensure_term( '高性价比', 'brand_feature', 'high-value' );
		$feat_lowlat = self::ensure_term( '低延迟稳定', 'brand_feature', 'low-latency' );

		$reg_hk = self::ensure_term( '香港', 'brand_region', 'hong-kong' );
		$reg_jp = self::ensure_term( '日本', 'brand_region', 'japan' );
		$reg_sg = self::ensure_term( '新加坡', 'brand_region', 'singapore' );
		$reg_us = self::ensure_term( '美国', 'brand_region', 'united-states' );
		$reg_tw = self::ensure_term( '台湾', 'brand_region', 'taiwan' );

		// 3. 核心页面
		$home_id = self::ensure_page(
			'首页',
			'home',
			'<!-- wp:paragraph --><p>欢迎来到 AIRPORTCHA —— 独立网络科技资讯与多品牌机场评测平台。</p><!-- /wp:paragraph -->'
		);

		self::ensure_page(
			'机场推荐',
			'recommend',
			'<!-- wp:paragraph --><p>本页由 AIRPORTCHA 编辑部持续维护，所有榜单、品牌卡片与套餐对比均实时同步自正式品牌白名单数据库。</p><!-- /wp:paragraph -->',
			'page-templates/template-recommend.php'
		);

		self::ensure_page(
			'博客',
			'blog',
			'<!-- wp:paragraph --><p>聚焦全球网络科技趋势、跨境办公效率工具与行业动态。</p><!-- /wp:paragraph -->',
			'page-templates/template-category-hub.php',
			array( '_ac_hub_category' => 'blog' )
		);

		self::ensure_page(
			'使用教程',
			'guides',
			'<!-- wp:paragraph --><p>覆盖 Windows、macOS、iOS、Android 与路由器的客户端安装与分流规则实战教程。</p><!-- /wp:paragraph -->',
			'page-templates/template-category-hub.php',
			array( '_ac_hub_category' => 'guides' )
		);

		self::ensure_page(
			'网络知识',
			'knowledge',
			'<!-- wp:paragraph --><p>系统讲解 Trojan、VLESS、Hysteria2、IPLC/IEPL 专线及 DNS 防泄漏等网络基础知识。</p><!-- /wp:paragraph -->',
			'page-templates/template-category-hub.php',
			array( '_ac_hub_category' => 'knowledge' )
		);

		self::ensure_page(
			'稳定机场推荐与选购标准',
			'stable-airports',
			'<!-- wp:heading --><h2>一、稳定机场核心判断标准</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>判断一款机场服务是否稳定，不能只看非高峰时段的单次测速峰值，而应重点考察工作日晚高峰（20:00–23:30）的丢包率、往返延迟抖动、多入口容灾能力以及客户端订阅更新稳定性。AIRPORTCHA 坚持客观透明原则：在没有跨运营商持续实测数据时，绝不武断宣称某品牌绝对最稳定，而是建议用户通过月付方案结合本地宽带实测。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>二、稳定性优先的白名单候选品牌</h2><!-- /wp:heading -->' .
			'<!-- wp:shortcode -->[airportcha_ranking_board limit="6" slot="pillar_stable"]<!-- /wp:shortcode -->' .
			'<!-- wp:heading --><h2>三、如何构建主备双线路容灾方案？</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>对于重度跨境办公、代码开发与 AI 生产力用户，最稳妥的稳定性策略不是寄希望于单一线路永不波动，而是采用「核心主力品牌（如光年梯）+ 独立备用品牌（如极连云、可信云或边缘节点）」的双订阅架构。在 Clash Verge Rev 或 Shadowrocket 中同时导入两套订阅，遇局部路由维护可秒级切换。</p><!-- /wp:paragraph -->',
			'page-templates/template-pillar.php'
		);

		self::ensure_page(
			'性价比机场推荐与套餐价值分析',
			'value-airports',
			'<!-- wp:heading --><h2>一、什么是真正的「高性价比机场」？</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>评估机场服务的性价比，不能只看最低起步标价或名义上的超大流量，而应计算「晚高峰有效可用流量的单位成本」。如果一款超低价服务在晚高峰频繁断流、无法打开常用海外网站与 AI 工具，其实际使用价值反而低于价格适中但全天候流畅的稳健方案。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>二、白名单多品牌综合对比</h2><!-- /wp:heading -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table limit="6"]<!-- /wp:shortcode -->' .
			'<!-- wp:heading --><h2>三、不同预算与计费周期的理性选择建议</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>1）<strong>首次试错一律优先月付</strong>：先花一个月预算在本地宽带与 5G 下检验真实体验；2）<strong>按真实月耗选档位</strong>：日常文字办公与 AI 使用每月 50GB–150GB 足够，重度 4K 视频建议 200GB 以上；3）<strong>理性对比白名单品牌</strong>：追求全能主力可优先考察光年梯与极连云，注重轻量易用与预算控制可同步对比快狸与速界。</p><!-- /wp:paragraph -->',
			'page-templates/template-pillar.php'
		);

		self::ensure_page(
			'机场价格与套餐对比中心',
			'pricing',
			'<!-- wp:heading --><h2>一、机场服务主流计费周期解析</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>目前主流机场服务主要提供月付、季付、年付及按量不限时四类计费周期。AIRPORTCHA 严格区分不同计费周期，绝不将年付折合月均价与单月付价格混排误导用户。实时售价、流量配额与限时优惠请始终以各品牌官方结算页为准。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>二、白名单品牌套餐价格矩阵</h2><!-- /wp:heading -->' .
			'<!-- wp:shortcode -->[airportcha_plans_matrix limit="6"]<!-- /wp:shortcode -->' .
			'<!-- wp:heading --><h2>三、购买套餐前必查的 4 项条款</h2><!-- /wp:heading -->' .
			'<!-- wp:list --><ul><li><strong>节点倍率</strong>：确认高成本专线节点是否按 1.5x / 2x 扣减流量。</li><li><strong>同时在线设备数</strong>：核对手机、电脑、平板同时在线是否受限。</li><li><strong>流量重置周期</strong>：确认是按自然月 1 日重置还是按购买日顺延 30 天重置。</li><li><strong>公平使用政策（FUP）与退款规则</strong>：避免未经测试直接投入大额年付。</li></ul><!-- /wp:list -->',
			'page-templates/template-pillar.php'
		);

		self::ensure_page(
			'关于我们',
			'about',
			'<!-- wp:heading --><h2>关于 AIRPORTCHA</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>AIRPORTCHA（airportcha.com）是一家面向全球中文用户的独立网络科技内容与多品牌机场评测平台。我们通过透明的品牌档案、客观的评测方法与实用技术教程，帮助用户理性选择网络服务。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>我们的评测与商业披露原则</h2><!-- /wp:heading -->' .
			'<!-- wp:list --><ul><li><strong>多品牌独立对比</strong>：仅收录经过核验的正式白名单品牌，既分析核心特点也如实提示购买注意事项。</li><li><strong>商业与评测分离</strong>：部分外部推广链接通过 /go/ 统一跳转并标注 nofollow sponsored，不影响客观记录。</li></ul><!-- /wp:list -->'
		);

		if ( $home_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_id );
		}

		// 4. 严格按用户指定白名单创建 6 个正式品牌（绝不创建非白名单示例品牌）
		$brand_ids = array();

		$brand_ids['guangnianti'] = self::ensure_cpt(
			'brand',
			'光年梯',
			'guangnianti',
			'光年梯（GuangNianTi）是 AIRPORTCHA 当前核心主推品牌（PRIMARY），主打跨境办公、AI 工具适配与多平台稳定接入。',
			'<!-- wp:heading --><h2>品牌概述</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>光年梯面向跨境开发者、远程办公人士与高频 AI 工具用户，支持 Trojan、VLESS 等主流开源协议与全平台客户端一键订阅，是 AIRPORTCHA 当前核心主推品牌。</p><!-- /wp:paragraph -->',
			array(
				'_ac_en_name'          => 'GuangNianTi',
				'_ac_tagline'          => '核心主推多平台稳定线路，适配跨境办公与 AI 工具',
				'_ac_status'           => 'active',
				'_ac_tier'             => 'PRIMARY',
				'_ac_affiliate_status' => 'active',
				'_ac_affiliate_url'    => 'https://gnt001.gntvipaff.cc/#/?code=FRrxGbbY',
				'_ac_rating'           => '4.9',
				'_ac_price'            => '¥18 / 月（原价 · 120GB）',
				'_ac_protocols'        => 'Trojan / VLESS / Shadowsocks',
				'_ac_nodes'            => '多地区全球核心节点',
				'_ac_streaming'        => '支持主流流媒体与 AI 工具访问',
				'_ac_coupon'           => 'GNT80',
				'_ac_coupon_desc'      => '结账输入优惠码 GNT80 享折扣（原价 ¥18/月含 120GB）',
				'_ac_sort_order'       => 1,
				'_ac_featured'         => '1',
				'_ac_placements'       => array( 'home_primary', 'home_featured', 'recommend_rank', 'article_inline', 'review_box' ),
				'_ac_plans_json'       => '[{"name":"入门主力月付","price":"¥18（原价）","cycle":"月付","traffic":"120GB/月","best_for":"主力办公、AI 工具与日常 4K 观影（可用码 GNT80）"}]',
				'_ac_faq_json'         => '[{"q":"光年梯支持哪些客户端？","a":"支持 Clash Verge Rev、Shadowrocket、Sing-box 等主流开源全平台客户端。"},{"q":"首次使用建议选择什么周期？","a":"建议先选择月付套餐在本地宽带与移动网络下体验一周晚高峰表现，再决定是否长期续费。"}]',
				'_ac_source'           => '光年梯公开品牌档案与 AIRPORTCHA 编辑部整理',
				'_ac_highlights'       => "AIRPORTCHA 当前核心主推品牌（PRIMARY）\n支持 Clash Verge Rev / Shadowrocket 全平台标准订阅\n适合跨境办公、开发者与高频 AI 工具场景\n通过 /go/guangnianti/ 直达真实官方推广入口",
				'_ac_best_for'         => '跨境开发者、远程办公人士、AI 工具用户、多设备主力人群',
			),
			array(
				'brand_feature' => array( $feat_iplc, $feat_ai, $feat_stream, $feat_lowlat ),
				'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg, $reg_us, $reg_tw ),
			)
		);

		$real_partners = array(
			array(
				'title'   => '极连云',
				'slug'    => 'jilianyun',
				'en_name' => 'JiLianYun',
				'tagline' => '原价 ¥18/月含 100GB 流量，均衡稳健的第二主力选择',
				'url'     => 'https://haozevpn02.jlyvipaff.com/#/?code=6aTAi9bW',
				'coupon'  => 'JLY888',
				'price'   => '¥18 / 月（原价 · 100GB）',
				'traffic' => '100GB/月',
				'raw_p'   => '¥18（原价）',
				'tier'    => 'FEATURED',
				'order'   => 2,
				'rating'  => '4.8',
				'feats'   => array( $feat_iplc, $feat_ai, $feat_stream ),
			),
			array(
				'title'   => '快狸',
				'slug'    => 'kuaili',
				'en_name' => 'KuaiLi',
				'tagline' => '原价 ¥15/月含 50GB 流量，轻量省心的新手入门之选',
				'url'     => 'https://aolaiyun01.kuailiaff.com/#/?code=AFR8PsiY',
				'coupon'  => 'kl888',
				'price'   => '¥15 / 月（原价 · 50GB）',
				'traffic' => '50GB/月',
				'raw_p'   => '¥15（原价）',
				'tier'    => 'FEATURED',
				'order'   => 3,
				'rating'  => '4.7',
				'feats'   => array( $feat_value, $feat_stream, $feat_lowlat ),
			),
			array(
				'title'   => '速界',
				'slug'    => 'speedworld',
				'en_name' => 'SpeedWorld',
				'tagline' => '原价 ¥15/月含 50GB 流量，亲民实用的日常浏览方案',
				'url'     => 'https://pygllc.speedworldaff.cc/#/?code=7s0EsI7w',
				'coupon'  => 'sujie888',
				'price'   => '¥15 / 月（原价 · 50GB）',
				'traffic' => '50GB/月',
				'raw_p'   => '¥15（原价）',
				'tier'    => 'PARTNER',
				'order'   => 4,
				'rating'  => '4.6',
				'feats'   => array( $feat_value, $feat_stream ),
			),
			array(
				'title'   => '边缘节点',
				'slug'    => 'edgenova',
				'en_name' => 'EdgeNova',
				'tagline' => '原价 ¥25/月含 100GB 流量，面向开发者与跨区域路由场景',
				'url'     => 'https://wep.edgenovaaff.com/#/?code=4wmwcLDL',
				'coupon'  => 'xk808',
				'price'   => '¥25 / 月（原价 · 100GB）',
				'traffic' => '100GB/月',
				'raw_p'   => '¥25（原价）',
				'tier'    => 'PARTNER',
				'order'   => 5,
				'rating'  => '4.5',
				'feats'   => array( $feat_iplc, $feat_lowlat ),
			),
			array(
				'title'   => '可信云',
				'slug'    => 'kexinyun',
				'en_name' => 'Kosing / KeXinYun',
				'tagline' => '原价 ¥15/月含 60GB 流量，稳健实用的低门槛备用线路',
				'url'     => 'https://everett7623.kosingaff.com/#/?code=4PQnpXx6',
				'coupon'  => 'kxy80',
				'price'   => '¥15 / 月（原价 · 60GB）',
				'traffic' => '60GB/月',
				'raw_p'   => '¥15（原价）',
				'tier'    => 'PARTNER',
				'order'   => 6,
				'rating'  => '4.4',
				'feats'   => array( $feat_lowlat, $feat_ai ),
			),
		);

		foreach ( $real_partners as $rp ) {
			$plans_json = wp_json_encode(
				array(
					array(
						'name'     => '标准月付套餐',
						'price'    => $rp['raw_p'],
						'cycle'    => '月付',
						'traffic'  => $rp['traffic'],
						'best_for' => '结账输入优惠码 ' . $rp['coupon'] . ' 享折扣',
					),
				),
				JSON_UNESCAPED_UNICODE
			);
			$brand_ids[ $rp['slug'] ] = self::ensure_cpt(
				'brand',
				$rp['title'],
				$rp['slug'],
				$rp['title'] . '正式品牌档案：' . $rp['price'] . '，专属优惠码 ' . $rp['coupon'] . '，支持全平台主流客户端。',
				'<!-- wp:heading --><h2>品牌简介</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . $rp['title'] . '（' . $rp['en_name'] . '）已正式收录至 AIRPORTCHA 多品牌白名单数据库。基础月付原价 ' . $rp['raw_p'] . '（含 ' . $rp['traffic'] . '），结账可使用专属优惠码 <code>' . $rp['coupon'] . '</code>。</p><!-- /wp:paragraph -->',
				array(
					'_ac_en_name'          => $rp['en_name'],
					'_ac_tagline'          => $rp['tagline'],
					'_ac_status'           => 'active',
					'_ac_tier'             => $rp['tier'],
					'_ac_affiliate_status' => 'active',
					'_ac_affiliate_url'    => $rp['url'],
					'_ac_coupon'           => $rp['coupon'],
					'_ac_coupon_desc'      => '结账输入 ' . $rp['coupon'] . ' 享折扣（原价 ' . $rp['price'] . '）',
					'_ac_rating'           => $rp['rating'],
					'_ac_price'            => $rp['price'],
					'_ac_plans_json'       => $plans_json,
					'_ac_protocols'        => 'Clash / Shadowrocket 订阅支持',
					'_ac_nodes'            => '多地区全球节点',
					'_ac_streaming'        => '详见品牌官网说明',
					'_ac_sort_order'       => $rp['order'],
					'_ac_featured'         => in_array( $rp['tier'], array( 'PRIMARY', 'FEATURED' ), true ) ? '1' : '0',
					'_ac_placements'       => in_array( $rp['tier'], array( 'PRIMARY', 'FEATURED' ), true ) ? array( 'home_primary', 'recommend_rank' ) : array( 'recommend_rank' ),
					'_ac_best_for'         => '跨境办公、日常浏览与多设备用户',
					'_ac_source'           => 'AIRPORTCHA 正式白名单品牌档案',
				),
				array(
					'brand_feature' => $rp['feats'],
					'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg, $reg_us ),
				)
			);
		}

		// 5. 为全部 6 个正式品牌创建独立深度评测（每篇均 >= 2000 中文字符）
		if ( function_exists( 'airportcha_get_phase4_brand_reviews' ) ) {
			$reviews = airportcha_get_phase4_brand_reviews();
			foreach ( $reviews as $slug => $rev ) {
				$bid = isset( $brand_ids[ $slug ] ) ? $brand_ids[ $slug ] : 0;
				self::ensure_cpt(
					'review',
					$rev['title'],
					$rev['slug'],
					$rev['excerpt'],
					$rev['content'],
					array(
						'_ac_review_brand_id' => $bid,
						'_ac_score_overall'   => $rev['score_overall'],
						'_ac_score_speed'     => $rev['score_speed'],
						'_ac_score_stability' => $rev['score_stability'],
						'_ac_score_value'     => $rev['score_value'],
						'_ac_score_streaming' => $rev['score_streaming'],
						'_ac_pros'            => $rev['pros'],
						'_ac_cons'            => $rev['cons'],
						'_ac_verdict'         => $rev['verdict'],
					)
				);
			}
		}

		// 6. 创建 2 篇基于正式白名单品牌的横向对比
		self::ensure_cpt(
			'comparison',
			'光年梯 vs 极连云：核心主推与均衡重点合作品牌如何选？',
			'guangnianti-vs-jilianyun',
			'从产品定位、协议生态、适用人群与主备搭配思路，客观对比光年梯与极连云两大白名单品牌。',
			'<!-- wp:paragraph --><p>在 AIRPORTCHA 正式白名单中，<strong>光年梯（PRIMARY）</strong>与<strong>极连云（FEATURED）</strong>是关注度最高的两大主力候选品牌。追求高优先度跨境办公与 AI 综合适配可优先体验光年梯；希望在日常均衡体验与主备冗余之间取得平衡，极连云是极佳的对比与备用选择。</p><!-- /wp:paragraph -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table slugs="guangnianti,jilianyun"]<!-- /wp:shortcode -->',
			array(
				'_ac_comp_brand_a' => $brand_ids['guangnianti'],
				'_ac_comp_brand_b' => $brand_ids['jilianyun'],
				'_ac_comp_summary' => '主力跨境办公与 AI 场景首选光年梯；均衡日常使用或主备双线路推荐极连云。',
			)
		);

		self::ensure_cpt(
			'comparison',
			'快狸、可信云、边缘节点与速界横向对比：按使用场景怎么选？',
			'kuaili-kexinyun-edgenova-speedworld-comparison',
			'横向对比白名单中四款各具特色的合作品牌，帮你按轻度易用、稳定连接、技术开发与高性价比快速匹配。',
			'<!-- wp:paragraph --><p>下表实时同步自 AIRPORTCHA 正式品牌数据库，覆盖快狸、可信云、边缘节点与速界的核心参数与官方推广入口：</p><!-- /wp:paragraph -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table slugs="kuaili,kexinyun,edgenova,speedworld"]<!-- /wp:shortcode -->',
			array(
				'_ac_comp_brand_a' => $brand_ids['kuaili'],
				'_ac_comp_brand_b' => $brand_ids['edgenova'],
				'_ac_comp_summary' => '轻量易用看快狸，稳定连接看可信云，开发者路由看边缘节点，预算日常看速界。',
			)
		);

		// 7. 导入 6 篇深度去 AI 化关键词长文（博客、使用教程、网络知识）
		$posts = array();
		if ( function_exists( 'airportcha_get_keyword_articles_part1' ) ) {
			$posts = array_merge( $posts, airportcha_get_keyword_articles_part1( $cat_blog, $cat_guides ) );
		}
		if ( function_exists( 'airportcha_get_keyword_articles_part2' ) ) {
			$posts = array_merge( $posts, airportcha_get_keyword_articles_part2( $cat_guides, $cat_knowledge ) );
		}

		foreach ( $posts as $p ) {
			self::ensure_cpt(
				'post',
				$p['title'],
				$p['slug'],
				$p['excerpt'],
				$p['content'],
				array(),
				array( 'category' => array( $p['cat'] ) )
			);
		}

		self::setup_menus();

		update_option( 'airportcha_seeded_v4', '1' );
		flush_rewrite_rules( true );
	}

	private static function ensure_term( $name, $taxonomy, $slug, $desc = '' ) {
		$existing = term_exists( $slug, $taxonomy );
		if ( $existing ) {
			return is_array( $existing ) ? (int) $existing['term_id'] : (int) $existing;
		}
		$res = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug, 'description' => $desc ) );
		return is_wp_error( $res ) ? 0 : (int) $res['term_id'];
	}

	private static function ensure_page( $title, $slug, $content, $template = '', $meta = array() ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			return (int) $existing->ID;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			if ( $template ) {
				update_post_meta( $post_id, '_wp_page_template', $template );
			}
			foreach ( $meta as $k => $v ) {
				update_post_meta( $post_id, $k, $v );
			}
		}
		return (int) $post_id;
	}

	private static function ensure_cpt( $post_type, $title, $slug, $excerpt, $content, $meta = array(), $tax = array() ) {
		$existing = get_page_by_path( $slug, OBJECT, $post_type );
		if ( $existing ) {
			return (int) $existing->ID;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'    => $post_type,
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_excerpt' => $excerpt,
				'post_content' => $content,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			foreach ( $meta as $k => $v ) {
				update_post_meta( $post_id, $k, $v );
			}
			foreach ( $tax as $taxonomy => $term_ids ) {
				wp_set_post_terms( $post_id, array_filter( $term_ids ), $taxonomy );
			}
		}
		return (int) $post_id;
	}

	private static function setup_menus() {
		$menu_name = 'AIRPORTCHA 主导航';
		$menu      = wp_get_nav_menu_object( $menu_name );
		if ( ! $menu ) {
			$menu_id = wp_create_nav_menu( $menu_name );
			$items   = array(
				array( '首页', home_url( '/' ) ),
				array( '机场推荐', home_url( '/recommend/' ) ),
				array( '品牌目录', home_url( '/brands/' ) ),
				array( '品牌评测', home_url( '/reviews/' ) ),
				array( '品牌比较', home_url( '/comparisons/' ) ),
				array( '博客', home_url( '/blog/' ) ),
				array( '使用教程', home_url( '/guides/' ) ),
				array( '网络知识', home_url( '/knowledge/' ) ),
				array( '关于我们', home_url( '/about/' ) ),
			);
			foreach ( $items as $item ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => $item[0],
						'menu-item-url'    => $item[1],
						'menu-item-status' => 'publish',
					)
				);
			}
		} else {
			$menu_id = $menu->term_id;
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		$locations['footer']  = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

Airportcha_Seeder::init_admin();
