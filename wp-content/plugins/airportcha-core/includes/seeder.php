<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Airportcha_Seeder {

	/**
	 * 注册后台「工具 -> AIRPORTCHA 初始化」菜单
	 */
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
			$message = '初始化完成！已创建/更新核心页面、导航菜单、品牌目录、评测、对比及博客示例内容。';
		}
		?>
		<div class="wrap">
			<h1>AIRPORTCHA 站点内容与架构初始化</h1>
			<?php if ( $message ) : ?>
				<div class="notice notice-success"><p><?php echo esc_html( $message ); ?></p></div>
			<?php endif; ?>
			<p>点击下方按钮将安全地创建（不会重复覆盖已有同名内容）AIRPORTCHA 的核心页面、导航菜单、分类及测试数据。</p>
			<form method="post">
				<?php wp_nonce_field( 'airportcha_seed_action' ); ?>
				<button type="submit" name="airportcha_run_seed" class="button button-primary button-hero">
					一键初始化页面、菜单与示例数据
				</button>
			</form>
		</div>
		<?php
	}

	/**
	 * 执行完整站点初始化
	 */
	public static function seed( $force = false ) {
		if ( ! $force && get_option( 'airportcha_seeded_v1' ) ) {
			return;
		}

		// 1. 创建文章分类
		$cat_blog      = self::ensure_term( '博客', 'category', 'blog', '网络科技资讯与行业观察' );
		$cat_guides    = self::ensure_term( '使用教程', 'category', 'guides', '客户端配置、代理规则与多平台使用教程' );
		$cat_knowledge = self::ensure_term( '网络知识', 'category', 'knowledge', '协议原理、IPLC 专线、DNS 与隐私安全科普' );

		// 2. 创建品牌特性与节点地区分类法
		$feat_iplc      = self::ensure_term( 'IPLC 专线', 'brand_feature', 'iplc' );
		$feat_ai        = self::ensure_term( 'ChatGPT/AI 解锁', 'brand_feature', 'ai-unlock' );
		$feat_stream    = self::ensure_term( '4K 流媒体解锁', 'brand_feature', 'streaming' );
		$feat_value     = self::ensure_term( '高性价比', 'brand_feature', 'high-value' );
		$feat_lowlat    = self::ensure_term( '低延迟稳定', 'brand_feature', 'low-latency' );

		$reg_hk = self::ensure_term( '香港', 'brand_region', 'hong-kong' );
		$reg_jp = self::ensure_term( '日本', 'brand_region', 'japan' );
		$reg_sg = self::ensure_term( '新加坡', 'brand_region', 'singapore' );
		$reg_us = self::ensure_term( '美国', 'brand_region', 'united-states' );
		$reg_tw = self::ensure_term( '台湾', 'brand_region', 'taiwan' );

		// 3. 创建核心页面
		$home_id = self::ensure_page(
			'首页',
			'home',
			'<!-- wp:paragraph --><p>欢迎来到 AIRPORTCHA —— 独立网络科技资讯与机场服务评测平台。</p><!-- /wp:paragraph -->'
		);

		self::ensure_page(
			'机场推荐',
			'recommend',
			'<!-- wp:paragraph --><p>以下榜单由 AIRPORTCHA 编辑部结合近 30 天晚高峰测速、线路稳定性、AI 与流媒体解锁表现及性价比综合评选，所有数据均与后台品牌目录实时同步。</p><!-- /wp:paragraph -->' .
			'<!-- wp:shortcode -->[airportcha_top_brands limit="6"]<!-- /wp:shortcode -->' .
			'<!-- wp:heading --><h2>主流机场核心参数快速对比</h2><!-- /wp:heading -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table limit="5"]<!-- /wp:shortcode -->',
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
			'关于我们',
			'about',
			'<!-- wp:heading --><h2>关于 AIRPORTCHA</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>AIRPORTCHA（airportcha.com）是一家面向全球中文用户的独立网络科技内容与机场服务评测平台。我们致力于通过透明的测试方法、持续更新的品牌目录以及通俗易懂的技术教程，帮助用户在复杂的网络服务市场中做出理性、可靠的选择。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>我们的评测原则</h2><!-- /wp:heading -->' .
			'<!-- wp:list --><ul><li><strong>独立客观</strong>：同时收录与对比多个主流品牌，既呈现优势也如实指出不足。</li><li><strong>长期追踪</strong>：定期复测晚高峰带宽、丢包率及主流流媒体与 AI 服务解锁状态。</li><li><strong>推广透明</strong>：部分品牌链接包含推广合作关系，用于支持网站长期运营与测试节点开销，但不会影响我们对性能数据的客观记录。</li></ul><!-- /wp:list -->'
		);

		// 设置静态首页
		if ( $home_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_id );
		}

		// 4. 创建 5 个示例机场品牌（光年梯为重点推荐之一）
		$brand_guangnianti = self::ensure_cpt(
			'brand',
			'光年梯',
			'guangnianti',
			'光年梯是当前综合表现突出的高端专线机场之一，主打全链路 IPLC/IEPL 跨境专线与 AI 生产力工具稳定解锁。',
			'<!-- wp:heading --><h2>品牌概述</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>光年梯专注于为跨境开发者、远程办公团队以及对延迟敏感的中文用户提供稳定高速的全球网络接入。其核心线路采用多入口智能 BGP 搭配 IPLC 专线，在晚高峰时段依然能保持较低延迟与稳定的 4K 流媒体吞吐能力。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>线路与协议架构</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>支持主流的 Trojan 与 VLESS 协议，兼容 Clash Verge、Shadowrocket、Sing-box 等全平台客户端。节点覆盖香港、日本、新加坡、台湾、美国及欧洲主要金融与云计算中心，并对 ChatGPT、Claude、Netflix、Disney+ 提供专门的原生解锁路由。</p><!-- /wp:paragraph -->',
			array(
				'_ac_en_name'         => 'GuangNianTi',
				'_ac_tagline'         => '全链路 IPLC 专线，晚高峰低延迟与 AI 工具稳定解锁',
				'_ac_status'          => 'active',
				'_ac_tier'            => 'PRIMARY',
				'_ac_affiliate_status'=> 'active',
				'_ac_rating'          => '4.9',
				'_ac_price'           => '¥28 / 月起',
				'_ac_protocols'       => 'Trojan / VLESS / Shadowsocks',
				'_ac_nodes'           => '85+ 全球节点 / 14 个国家地区',
				'_ac_streaming'       => 'Netflix / Disney+ / ChatGPT / Claude 全解锁',
				'_ac_affiliate_url'   => 'https://gnt001.gntvipaff.cc/#/?code=FRrxGbbY',
				'_ac_coupon'          => 'AIRPORTCHA10',
				'_ac_coupon_desc'     => '站长合作专属优惠码（请以官网结算页为准）',
				'_ac_sort_order'      => 1,
				'_ac_featured'        => '1',
				'_ac_placements'      => array( 'home_primary', 'home_featured', 'recommend_rank', 'article_inline' ),
				'_ac_plans_json'      => '[{"name":"轻量月付","price":"¥28","cycle":"月付","traffic":"150GB/月","best_for":"日常浏览与 AI 办公"},{"name":"进阶专线","price":"¥48","cycle":"月付","traffic":"350GB/月","best_for":"4K 流媒体与重度开发"}]',
				'_ac_faq_json'        => '[{"q":"光年梯支持哪些客户端？","a":"支持 Clash Verge Rev、Shadowrocket、Sing-box 等主流全平台客户端。"},{"q":"光年梯是否支持 ChatGPT 与 Claude？","a":"日本、新加坡与美国核心节点均提供稳定 AI 解锁路由。"}]',
				'_ac_source'          => '公开套餐资料与 AIRPORTCHA 编辑部实测',
				'_ac_highlights'      => "晚高峰 IPLC 专线低丢包，适合远程办公与开发\n原生 IP 深度优化 ChatGPT、Claude 与 Gemini\n全平台 Clash / Shadowrocket 一键订阅导入\n提供灵活月付及大流量按年套餐",
				'_ac_best_for'        => '跨境开发者、远程办公人士、AI 重度用户、4K 流媒体观影人群',
			),
			array(
				'brand_feature' => array( $feat_iplc, $feat_ai, $feat_stream, $feat_lowlat ),
				'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg, $reg_us, $reg_tw ),
			)
		);

		$brand_xingyun = self::ensure_cpt(
			'brand',
			'星云加速',
			'xingyun-cloud',
			'星云加速定位均衡型中高端机场，兼顾公网中转与部分 IEPL 节点，价格亲民且流量配额充足。',
			'<!-- wp:heading --><h2>品牌概述</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>星云加速在亚太区域部署了较多大带宽中转节点，适合日常浏览、YouTube 4K 观影以及多设备共享。套餐门槛较低，对预算敏感的用户较为友好。</p><!-- /wp:paragraph -->',
			array(
				'_ac_tagline'       => '大流量高性价比中转 + 精选专线节点',
				'_ac_rating'        => '4.6',
				'_ac_price'         => '¥18 / 月起',
				'_ac_protocols'     => 'Trojan / Hysteria2',
				'_ac_nodes'         => '60+ 节点 / 10 个国家地区',
				'_ac_streaming'     => 'Netflix / YouTube / ChatGPT',
				'_ac_affiliate_url' => '',
				'_ac_affiliate_status' => 'pending',
				'_ac_tier'          => 'FEATURED',
				'_ac_coupon'        => '',
				'_ac_sort_order'    => 2,
				'_ac_featured'      => '1',
				'_ac_highlights'    => "入门套餐性价比高，月流量充裕\n支持 Hysteria2 协议，弱网环境提速明显\n亚太节点数量多，日常使用流畅",
				'_ac_best_for'      => '学生群体、日常流媒体用户、多设备家庭用户',
			),
			array(
				'brand_feature' => array( $feat_value, $feat_stream, $feat_ai ),
				'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg, $reg_us ),
			)
		);

		$brand_aurora = self::ensure_cpt(
			'brand',
			'极光网络',
			'aurora-net',
			'极光网络主打北美与欧洲商务线路，适合海外 SaaS 访问与跨国会议。',
			'<!-- wp:paragraph --><p>极光网络在欧美方向具备较强的骨干网优化能力，适合经常访问 AWS、GitHub、Figma 及海外企业服务的用户。</p><!-- /wp:paragraph -->',
			array(
				'_ac_tagline'       => '欧美商务骨干网优化，稳定支持海外 SaaS 与视频会议',
				'_ac_rating'        => '4.5',
				'_ac_price'         => '¥32 / 月起',
				'_ac_protocols'     => 'VLESS / Trojan',
				'_ac_nodes'         => '50+ 节点 / 12 个国家地区',
				'_ac_streaming'     => 'Netflix / ChatGPT / Spotify',
				'_ac_affiliate_url' => '',
				'_ac_affiliate_status' => 'pending',
				'_ac_tier'          => 'PARTNER',
				'_ac_coupon'        => '',
				'_ac_sort_order'    => 3,
				'_ac_featured'      => '0',
				'_ac_highlights'    => "欧美节点延迟控制优秀\n工单响应较快，适合商务场景\n支持多客户端订阅转换",
				'_ac_best_for'      => '外贸从业者、跨国远程会议用户',
			),
			array(
				'brand_feature' => array( $feat_iplc, $feat_lowlat ),
				'brand_region'  => array( $reg_us, $reg_sg, $reg_jp ),
			)
		);

		self::ensure_cpt(
			'brand',
			'蓝鲸专线',
			'bluewhale-link',
			'蓝鲸专线以按量不限时流量包为特色，非常适合作为备用机场使用。',
			'<!-- wp:paragraph --><p>蓝鲸专线提供灵活的不限时流量包方案，适合轻度使用或已经拥有主力机场、希望补充备用线路的用户。</p><!-- /wp:paragraph -->',
			array(
				'_ac_tagline'       => '不限时按量计费方案，理想的备用线路选择',
				'_ac_rating'        => '4.4',
				'_ac_price'         => '¥35 / 100GB 不限时',
				'_ac_protocols'     => 'Shadowsocks / Trojan',
				'_ac_nodes'         => '40+ 节点 / 8 个国家地区',
				'_ac_streaming'     => 'YouTube / Netflix 部分地区',
				'_ac_affiliate_url' => '',
				'_ac_affiliate_status' => 'pending',
				'_ac_tier'          => 'ARCHIVE',
				'_ac_coupon'        => '',
				'_ac_sort_order'    => 4,
				'_ac_featured'      => '0',
				'_ac_highlights'    => "按量不限时，零浪费\n节点配置简洁，易于上手\n适合作为主力机场的备用方案",
				'_ac_best_for'      => '轻度检索用户、需要备用线路的人群',
			),
			array(
				'brand_feature' => array( $feat_value ),
				'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg ),
			)
		);

		self::ensure_cpt(
			'brand',
			'量子节点',
			'quantum-node',
			'量子节点侧重新兴协议实验与低延迟游戏加速线路。',
			'<!-- wp:paragraph --><p>量子节点较早跟进 Hysteria2 与 TUIC 等基于 QUIC 的传输协议，在跨境游戏与移动网络切换场景下表现活跃。</p><!-- /wp:paragraph -->',
			array(
				'_ac_tagline'       => '前沿 QUIC 协议支持，适配移动网络与低延迟场景',
				'_ac_rating'        => '4.3',
				'_ac_price'         => '¥22 / 月起',
				'_ac_protocols'     => 'Hysteria2 / TUIC / VLESS',
				'_ac_nodes'         => '45+ 节点 / 9 个国家地区',
				'_ac_streaming'     => 'YouTube / Disney+ / ChatGPT',
				'_ac_affiliate_url' => '',
				'_ac_affiliate_status' => 'pending',
				'_ac_tier'          => 'ARCHIVE',
				'_ac_coupon'        => '',
				'_ac_sort_order'    => 5,
				'_ac_featured'      => '0',
				'_ac_highlights'    => "原生支持 Hysteria2 / TUIC 协议\n日韩节点延迟较低\n客户端教程文档完善",
				'_ac_best_for'      => '极客玩家、移动端高频切换网络用户',
			),
			array(
				'brand_feature' => array( $feat_lowlat, $feat_ai ),
				'brand_region'  => array( $reg_jp, $reg_hk, $reg_tw ),
			)
		);

		// 4.2 新增 5 个真实合作品牌（极连云、快狸、可信云、边缘节点、速界）
		$real_partners = array(
			array(
				'title'   => '极连云',
				'slug'    => 'jilianyun',
				'en_name' => 'JiLianYun',
				'tagline' => '多节点高速接入，兼顾日常办公与流媒体体验',
				'url'     => 'https://haozevpn02.jlyvipaff.com/#/?code=6aTAi9bW',
				'tier'    => 'FEATURED',
				'order'   => 2,
				'rating'  => '4.7',
				'feats'   => array( $feat_iplc, $feat_ai, $feat_stream ),
			),
			array(
				'title'   => '快狸',
				'slug'    => 'kuaili',
				'en_name' => 'KuaiLi',
				'tagline' => '轻量易用、多平台客户端适配的稳定网络服务',
				'url'     => 'https://aolaiyun01.kuailiaff.com/#/?code=AFR8PsiY',
				'tier'    => 'FEATURED',
				'order'   => 3,
				'rating'  => '4.6',
				'feats'   => array( $feat_value, $feat_stream, $feat_lowlat ),
			),
			array(
				'title'   => '可信云',
				'slug'    => 'kexinyun',
				'en_name' => 'Kosing / KeXinYun',
				'tagline' => '专注稳定连接与多地区节点覆盖的网络加速品牌',
				'url'     => 'https://everett7623.kosingaff.com/#/?code=4PQnpXx6',
				'tier'    => 'PARTNER',
				'order'   => 4,
				'rating'  => '4.5',
				'feats'   => array( $feat_lowlat, $feat_ai ),
			),
			array(
				'title'   => '边缘节点',
				'slug'    => 'edgenova',
				'en_name' => 'EdgeNova',
				'tagline' => '全球边缘路由优化，面向开发者与跨区域访问场景',
				'url'     => 'https://wep.edgenovaaff.com/#/?code=4wmwcLDL',
				'tier'    => 'PARTNER',
				'order'   => 5,
				'rating'  => '4.5',
				'feats'   => array( $feat_iplc, $feat_lowlat ),
			),
			array(
				'title'   => '速界',
				'slug'    => 'speedworld',
				'en_name' => 'SpeedWorld',
				'tagline' => '高性价比全球节点接入，适合日常浏览与多设备使用',
				'url'     => 'https://pygllc.speedworldaff.cc/#/?code=7s0EsI7w',
				'tier'    => 'PARTNER',
				'order'   => 6,
				'rating'  => '4.4',
				'feats'   => array( $feat_value, $feat_stream ),
			),
		);

		foreach ( $real_partners as $rp ) {
			self::ensure_cpt(
				'brand',
				$rp['title'],
				$rp['slug'],
				$rp['title'] . '品牌档案与官方推广入口，支持在 WordPress 后台持续完善套餐与评测资料。',
				'<!-- wp:heading --><h2>品牌简介</h2><!-- /wp:heading --><!-- wp:paragraph --><p>' . $rp['title'] . '已收录至 AIRPORTCHA 多品牌数据库，详细套餐、节点与实测数据可在 WordPress 后台随时补充更新。</p><!-- /wp:paragraph -->',
				array(
					'_ac_en_name'          => $rp['en_name'],
					'_ac_tagline'          => $rp['tagline'],
					'_ac_status'           => 'active',
					'_ac_tier'             => $rp['tier'],
					'_ac_affiliate_status' => 'active',
					'_ac_affiliate_url'    => $rp['url'],
					'_ac_rating'           => $rp['rating'],
					'_ac_price'            => '待补充（以官网为准）',
					'_ac_protocols'        => 'Clash / Shadowrocket 订阅支持',
					'_ac_nodes'            => '多地区全球节点',
					'_ac_streaming'        => '详见品牌官网说明',
					'_ac_sort_order'       => $rp['order'],
					'_ac_featured'         => in_array( $rp['tier'], array( 'PRIMARY', 'FEATURED' ), true ) ? '1' : '0',
					'_ac_placements'       => in_array( $rp['tier'], array( 'PRIMARY', 'FEATURED' ), true ) ? array( 'home_primary', 'recommend_rank' ) : array( 'recommend_rank' ),
					'_ac_best_for'         => '跨境办公、日常浏览与多设备用户',
					'_ac_source'           => 'AIRPORTCHA 合作品牌档案',
				),
				array(
					'brand_feature' => $rp['feats'],
					'brand_region'  => array( $reg_hk, $reg_jp, $reg_sg, $reg_us ),
				)
			);
		}

		// 5. 创建 3 篇品牌评测（含 /reviews/guangnianti-review/）
		self::ensure_cpt(
			'review',
			'光年梯深度评测（2026）：晚高峰速度、IPLC 稳定性与 AI 解锁实测',
			'guangnianti-review',
			'我们对光年梯进行了为期 14 天的多时段测速与流媒体、ChatGPT/Claude 解锁实测，客观分析其真实表现与适用人群。',
			'<!-- wp:heading --><h2>测试环境与方法</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>本次实测在千兆宽带与 5G 移动网络环境下进行，重点记录工作日晚高峰（20:00–23:30）香港、日本、新加坡、美国四组核心节点的吞吐速度、往返延迟（RTT）以及丢包情况。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>晚高峰速度与稳定性表现</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>得益于全链路 IPLC 专线架构，光年梯在晚高峰时段的香港与日本节点依然能够维持 300Mbps 以上的单线程峰值速度，YouTube 4K 60fps 拖拽缓冲几乎无感，SSH 与远程桌面连接无明显卡顿。</p><!-- /wp:paragraph -->' .
			'<!-- wp:heading --><h2>流媒体与 AI 工具解锁实测</h2><!-- /wp:heading -->' .
			'<!-- wp:paragraph --><p>实测期间，日本、新加坡与美国节点均可稳定访问 ChatGPT 网页版与 API、Claude 以及 Gemini，且未触发频繁的人机验证；Netflix 与 Disney+ 非自制剧解锁完整。</p><!-- /wp:paragraph -->',
			array(
				'_ac_review_brand_id' => $brand_guangnianti,
				'_ac_score_overall'   => '4.9',
				'_ac_score_speed'     => '4.9',
				'_ac_score_stability' => '4.9',
				'_ac_score_value'     => '4.7',
				'_ac_score_streaming' => '5.0',
				'_ac_pros'            => "晚高峰 IPLC 专线表现极其稳定，延迟低\nChatGPT、Claude 与主流流媒体解锁完整\n订阅更新机制顺畅，全平台客户端兼容性好",
				'_ac_cons'            => "起步价格高于普通公网中转机场\n不提供免费试用，需按月或按季订阅",
				'_ac_verdict'         => '如果你把稳定性和晚高峰体验放在第一位，光年梯是目前综合素质最值得长期作为主力使用的专线机场之一。',
			)
		);

		self::ensure_cpt(
			'review',
			'星云加速实测评测：高性价比中转机场能否满足日常 4K 与 AI 需求？',
			'xingyun-cloud-review',
			'针对预算敏感型用户，我们实测了星云加速的日间与晚高峰带宽表现，看看它在 20 元档位是否物有所值。',
			'<!-- wp:paragraph --><p>星云加速在 18 元起步的价位段提供了充裕的月度流量。实测显示其日间速度表现优异，晚高峰个别热门节点会有轻度波动，但切换到 Hysteria2 协议后流畅度提升明显。</p><!-- /wp:paragraph -->',
			array(
				'_ac_review_brand_id' => $brand_xingyun,
				'_ac_score_overall'   => '4.6',
				'_ac_score_speed'     => '4.5',
				'_ac_score_stability' => '4.4',
				'_ac_score_value'     => '4.9',
				'_ac_score_streaming' => '4.6',
				'_ac_pros'            => "价格亲民，流量配额充足\n支持 Hysteria2，弱网环境下吞吐良好",
				'_ac_cons'            => "晚高峰极少数热门节点延迟略升\n欧美冷门地区节点较少",
				'_ac_verdict'         => '非常适合预算有限、以日常浏览和流媒体观影为主的用户。',
			)
		);

		self::ensure_cpt(
			'review',
			'极光网络评测：欧美商务线路与跨国远程办公实测体验',
			'aurora-net-review',
			'深度体验极光网络的欧美节点质量，评估其在跨国视频会议、海外 SaaS 与代码仓库拉取中的稳定性。',
			'<!-- wp:paragraph --><p>极光网络在美西与欧洲方向路由优化扎实，Zoom 与 Teams 跨国会议连续 2 小时零断线，适合有明确欧美商务需求的用户。</p><!-- /wp:paragraph -->',
			array(
				'_ac_review_brand_id' => $brand_aurora,
				'_ac_score_overall'   => '4.5',
				'_ac_score_speed'     => '4.5',
				'_ac_score_stability' => '4.6',
				'_ac_score_value'     => '4.3',
				'_ac_score_streaming' => '4.4',
				'_ac_pros'            => "欧美节点路由干净，商务应用稳定\n售后工单响应及时",
				'_ac_cons'            => "亚太娱乐流媒体节点选择不如同类竞品丰富",
				'_ac_verdict'         => '偏向商务与外贸办公定位，欧美方向表现扎实。',
			)
		);

		// 6. 创建 2 篇品牌比较
		self::ensure_cpt(
			'comparison',
			'光年梯 vs 星云加速：专线稳健派与高性价比中转如何选？',
			'guangnianti-vs-xingyun',
			'从线路架构、晚高峰速度、AI 解锁、价格与适用人群五个维度，全面对比光年梯与星云加速。',
			'<!-- wp:paragraph --><p>很多读者在选购第一款主力机场时，会在主打全链路专线的<strong>光年梯</strong>与高性价比的<strong>星云加速</strong>之间犹豫。简而言之：追求极致稳定、晚高峰低延迟和 AI 办公效率，首选光年梯；预算优先、看重大流量日常观影，星云加速更划算。</p><!-- /wp:paragraph -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table slugs="guangnianti,xingyun-cloud"]<!-- /wp:shortcode -->',
			array(
				'_ac_comp_brand_a' => $brand_guangnianti,
				'_ac_comp_brand_b' => $brand_xingyun,
				'_ac_comp_summary' => '重度办公与 AI 开发者首选光年梯；高性价比日常观影可选星云加速。',
			)
		);

		self::ensure_cpt(
			'comparison',
			'2026 主流 IPLC 专线机场横向对比：光年梯、极光网络与量子节点',
			'iplc-airports-comparison-2026',
			'横向对比三款热门专线及前沿协议机场，帮你快速匹配最适合自己网络环境的方案。',
			'<!-- wp:paragraph --><p>下表实时同步自 AIRPORTCHA 品牌数据库，涵盖综合评分、起步价、协议支持与解锁能力：</p><!-- /wp:paragraph -->' .
			'<!-- wp:shortcode -->[airportcha_comparison_table slugs="guangnianti,aurora-net,quantum-node"]<!-- /wp:shortcode -->',
			array(
				'_ac_comp_brand_a' => $brand_guangnianti,
				'_ac_comp_brand_b' => $brand_aurora,
				'_ac_comp_summary' => '光年梯综合表现最均衡；极光网络偏重欧美商务；量子节点适合喜欢新协议的极客。',
			)
		);

		// 7. 创建 6 篇博客 / 使用教程 / 网络知识文章
		$posts = array(
			array(
				'title'   => '2026 年如何挑选靠谱的机场服务？五大核心指标全解析',
				'slug'    => 'how-to-choose-reliable-airport-2026',
				'cat'     => $cat_blog,
				'excerpt' => '从线路类型、协议生态、晚高峰实测到隐私与付费策略，一文掌握判断机场服务质量的核心方法。',
				'content' => '<!-- wp:paragraph --><p>面对市面上琳琅满目的网络加速服务，单看宣传页面的“千兆带宽”往往容易踩坑。真正决定日常体验的，是<strong>线路架构（IPLC/IEPL 还是普通公网中转）</strong>、<strong>晚高峰冗余容量</strong>以及<strong>出口 IP 的纯净度</strong>。</p><!-- /wp:paragraph --><!-- wp:heading --><h2>优先关注月付试错与多地区冗余</h2><!-- /wp:heading --><!-- wp:paragraph --><p>无论品牌口碑多好，建议新用户首次购买优先选择月付或季付套餐，亲自在自己的宽带与移动网络下完成一周晚高峰测试，再决定是否长期续费。</p><!-- /wp:paragraph -->',
			),
			array(
				'title'   => '跨境远程办公网络优化指南：降低延迟与丢包的实用方案',
				'slug'    => 'remote-work-network-optimization-guide',
				'cat'     => $cat_blog,
				'excerpt' => '面向海外协同办公、GitHub 与 AI 编程工具用户，分享稳定高效的网络分流与多节点容灾思路。',
				'content' => '<!-- wp:paragraph --><p>对于每天依赖海外云服务、代码仓库与 AI 助手的开发者而言，网络抖动直接影响工作效率。合理配置规则分流（国内外流量精准分流）+ 准备一条按量备用线路，是最具性价比的稳定性保障。</p><!-- /wp:paragraph -->',
			),
			array(
				'title'   => 'Clash Verge Rev 新手完全配置教程（Windows / macOS）',
				'slug'    => 'clash-verge-rev-setup-guide',
				'cat'     => $cat_guides,
				'excerpt' => '手把手教你安装 Clash Verge Rev、导入机场订阅链接、开启 TUN 模式并配置规则分流。',
				'content' => '<!-- wp:paragraph --><p>Clash Verge Rev 是目前桌面端体验最出色的开源代理客户端之一。只需三步即可完成配置：1）下载安装对应系统版本；2）在「订阅」页粘贴服务商提供的订阅链接并更新；3）在「代理」页选择「规则模式」并开启系统代理或 TUN 模式。</p><!-- /wp:paragraph -->',
			),
			array(
				'title'   => 'iOS Shadowrocket（小火箭）分流规则与延迟测速设置教程',
				'slug'    => 'shadowrocket-ios-rules-guide',
				'cat'     => $cat_guides,
				'excerpt' => '详解 Shadowrocket 订阅自动更新、按地区自动选优与流媒体/AI 专属分流配置技巧。',
				'content' => '<!-- wp:paragraph --><p>在 iPhone 与 iPad 上，Shadowrocket 以轻量稳定著称。建议开启「按需连接」与「自动测速（URL Test）」，并在全局路由中选择「配置（Config）」，既能保证国内应用直连不绕路，又能让海外应用自动走低延迟节点。</p><!-- /wp:paragraph -->',
			),
			array(
				'title'   => 'IPLC、IEPL 与公网中转有什么区别？一文读懂机场线路原理',
				'slug'    => 'iplc-iepl-vs-transit-explained',
				'cat'     => $cat_knowledge,
				'excerpt' => '深入浅出解析国际私用出租线路（IPLC）、以太网专线（IEPL）与普通 BGP 中转的本质差异。',
				'content' => '<!-- wp:paragraph --><p><strong>IPLC / IEPL</strong> 属于运营商级点对点跨境内网专线，不经过公网拥堵出口，因此晚高峰延迟极低、几乎不丢包，但带宽成本高；<strong>公网中转</strong>则是通过国内大带宽机房转发流量，性价比高，但在高峰期更容易受国际出口波动影响。</p><!-- /wp:paragraph -->',
			),
			array(
				'title'   => 'Trojan、VLESS 与 Hysteria2 协议对比：日常使用该怎么选？',
				'slug'    => 'trojan-vless-hysteria2-comparison',
				'cat'     => $cat_knowledge,
				'excerpt' => '对比三大主流传输协议的加密特性、抗丢包能力与客户端兼容性，帮你选出最适合当前网络环境的协议。',
				'content' => '<!-- wp:paragraph --><p><strong>Trojan / VLESS</strong> 基于 TCP + TLS，在专线环境下稳定且特征自然；<strong>Hysteria2</strong> 基于 UDP/QUIC，具备激进的拥塞控制能力，非常适合丢包率较高的弱网或公网直连环境。</p><!-- /wp:paragraph -->',
			),
		);

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

		// 8. 创建主导航菜单与页脚菜单
		self::setup_menus();

		update_option( 'airportcha_seeded_v1', '1' );
		flush_rewrite_rules( true );
	}

	private static function ensure_term( $name, $taxonomy, $slug, $desc = '' ) {
		$existing = term_exists( $slug, $taxonomy );
		if ( $existing ) {
			return is_array( $existing ) ? (int) $existing['term_id'] : (int) $existing;
		}
		$res = wp_insert_term(
			$name,
			$taxonomy,
			array(
				'slug'        => $slug,
				'description' => $desc,
			)
		);
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
