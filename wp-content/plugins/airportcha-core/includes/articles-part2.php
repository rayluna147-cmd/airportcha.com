<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 核心关键词深度长文（Part 2：3 篇，去 AI 化真人实战风格）
 */
function airportcha_get_keyword_articles_part2( $cat_guides, $cat_knowledge ) {
	return array(
		array(
			'title'   => 'iOS Shadowrocket（小火箭）分流规则、延迟测速与防断流完全攻略（2026）',
			'slug'    => 'shadowrocket-ios-rules-guide',
			'cat'     => $cat_guides,
			'excerpt' => '苹果手机装好小火箭却老是断网、耗电飞快、国内 App 变慢？这篇大白话实战攻略手把手教你调教全局路由、按需连接、自动测速与流媒体/AI 分流，一次配好省心一整年。',
			'content' => <<<'HTML'
<!-- wp:heading --><h2>为什么苹果手机上大家都认准 Shadowrocket（小火箭）？</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>在 iPhone 和 iPad 上，代理软件其实不少，比如 Quantumult X、Stash、Surge、Loon，但如果你问十个老用户推荐哪一款，八个人都会说：<strong>新手闭眼买 Shadowrocket（俗称小火箭）就够了</strong>。原因很实在：第一，价格最亲民（美区 App Store 一次性买断只要 $2.99，终身免费更新，不用按年交订阅费）；第二，体积小、极省电，挂在后台一整天几乎感觉不到掉电；第三，协议跟进速度全平台第一，不管机场用 Trojan、VLESS、Shadowsocks 还是 Hysteria2 / TUIC，小火箭全都能直接跑。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>但很多朋友买完小火箭，导入订阅点个连接就不管了，结果用着用着发现：刷抖音变卡了、出门从 Wi-Fi 切 5G 网页打不开了、一个月 50G 流量十天就烧光了。其实这些问题 99% 都不是机场的锅，而是小火箭里几个关键开关没设对。下面一步步带你把小火箭调教到最舒服的状态。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>第一步：正确导入订阅并开启自动更新</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>去你的机场官网（比如 <a href="/brands/guangnianti/">光年梯</a>、<a href="/brands/jilianyun/">极连云</a> 或 <a href="/brands/kuaili/">快狸</a>），在首页点“一键导入 Shadowrocket”，或者复制通用订阅链接。打开小火箭，点右上角的 <strong>「+」号</strong>，把“类型”改成 <strong>Subscribe（订阅）</strong>，把刚复制的链接粘贴进 URL 栏，备注填个好记的名字（比如“光年梯主力”），点右上角保存。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>接下来做一件非常关键的事：点底部菜单的<strong>「设置」→「订阅」</strong>，把<strong>「打开时更新」</strong>和<strong>「自动后台更新」</strong>全部打开！很多人导入完订阅半年不更新，机场早就换了更快的入口 IP，你手机里还留着半年前的旧节点，当然越用越卡。开了自动更新后，小火箭会自动帮你拉取最新节点，省心多了。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>第二步：全局路由千万别选“代理”，必须选“配置”！</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>回到小火箭首页，你会看到一个叫<strong>「全局路由」</strong>的选项，点进去有四个模式：配置（Config）、代理（Proxy）、直连（Direct）、场景（Scene）。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>记住：日常 99% 的时间，必须把全局路由锁定在「配置（Config）」！</strong>“配置”就是电脑端 Clash 里的“规则模式”——国内网站、微信、支付宝、美团、抖音全部走你手机自己的 5G/Wi-Fi 直连，一点不绕路也不耗流量；只有访问 Google、YouTube、ChatGPT、X 这些海外网站时，才会自动走机场节点。如果你手滑选成了“代理（Proxy）”，那就是全局代理，不仅国内 App 变卡、淘宝刷不出图，还会把你的月度流量瞬间吸干。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>第三步：开启“按需连接”与“连通性测试”，彻底告别切网假死</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>苹果手机用户最常吐槽的一件事就是：“在家里连着 Wi-Fi 用得好好的，一出门切到 5G 流量，右上角 VPN 图标还在，但网页死活转圈打不开！”</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>解决办法特别简单，改两个设置就行：第一，进入小火箭<strong>「设置」→「按需连接」</strong>，把「按需连接」开关打开。这样哪怕 iOS 后台为了省电暂时休眠了 VPN 进程，只要你打开浏览器或海外 App，系统会在毫秒级自动重新拉起连接；第二，在首页点一下<strong>「连通性测试」</strong>（默认用的是 TCP 测速，建议在「设置 → 延迟测试方法」里改成 <strong>CONNECT</strong>），这样测出来的延迟数字才是真实的握手响应时间，而不是虚高的 Ping 值。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>第四步：把 AI 和流媒体固定到日本/新加坡/美国节点</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>在手机上用 ChatGPT 官方 App、Claude、Gemini 或者看 Netflix 时，如果你选的是香港节点，十有八九会提示“当前地区不支持服务”。建议直接在节点列表里往下翻，选一个延迟在 60ms–120ms 之间的<strong>日本、新加坡或美国节点</strong>，日常刷推文和用 AI 都能一步到位，不用来回切来切去。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>如果你同时在手机里存了两家机场订阅（比如主力用 18 元 120G 的光年梯，备用存一个 15 元 60G 的可信云或者 15 元 50G 的快狸），小火箭首页会把两家订阅分组折叠得清清楚楚，哪天主力临时维护，点开备用分组选一个绿灯节点，两秒钟满血复活。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>第五步：苹果手机最容易偷跑流量的 3 个地方</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>1. iCloud 照片图库自动备份</strong>：手机拍完一堆 4K 视频，连上 Wi-Fi 挂着代理开始往 iCloud 传，几分钟就能吃掉十几个 G。确保全局路由在「配置」模式下，苹果国内云上贵州服务器默认直连，完全不走代理。</li><li><strong>2. YouTube 自动选 4K 画质</strong>：iPhone 屏幕本来就不大，1080P 和 4K 肉眼几乎看不出区别，但流量消耗差三倍！进 YouTube 设置把默认画质调成 1080P，50G 流量能用出 150G 的感觉。</li><li><strong>3. Telegram 自动下载媒体</strong>：电报群里动不动有人发几百 MB 的高清视频，进 Telegram「设置 → 数据与存储」把自动下载媒体关掉，想看哪个再手动点哪个。</li></ul><!-- /wp:list -->

<!-- wp:heading --><h2>第六步：小火箭常见报错 1 分钟排障</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>如果某天小火箭连上后所有海外网页都打不开，按顺序查这三样：第一，看顶部「全局路由」是不是误点成了「直连」；第二，拉一下首页刷新订阅，或者换一个同地区其他节点；第三，去 iPhone「设置 → 通用 → 日期与时间」确认开启了「自动设置」，时间偏差会导致加密协议握手失败。还没选好订阅的朋友，可以直接去 <a href="/recommend/">机场推荐总榜</a> 挑一家支持小火箭一键导入的白名单品牌月付试水。</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[airportcha_top_brands limit="3" slot="article_inline"]<!-- /wp:shortcode -->
HTML,
		),

		array(
			'title'   => 'IPLC、IEPL 与公网中转彻底讲透：别再被机场宣传词忽悠（2026 真人科普）',
			'slug'    => 'iplc-iepl-vs-transit-explained',
			'cat'     => $cat_knowledge,
			'excerpt' => '买机场天天看到 IPLC、IEPL、BGP 中转、公网直连这些缩写，到底有什么区别？是不是非得买专线不可？这篇大白话科普帮你把底层原理和省钱逻辑一次讲明白。',
			'content' => <<<'HTML'
<!-- wp:heading --><h2>别被缩写吓住：其实就跟“坐高铁”和“走国道”一个道理</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>很多刚接触机场的朋友，一打开各大品牌的介绍页就头晕：满屏都是 <strong>IPLC、IEPL、BGP 多线中转、CN2 GIA、公网直连</strong>。商家最喜欢拿这些专业名词来抬高身价，动不动就说自己是“顶级专线”。今天咱们不背教科书定义，用最接地气的交通比喻，五分钟让你彻底看懂它们的真实区别，以后买机场再也不会被割韭菜。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>一、三种主流机场线路的本质区别</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>1. 普通公网直连（相当于“自己开车走免费国道”）</strong><br />你的电脑直接通过国内运营商（电信、联通、移动）的普通国际公网出口，连到海外的落地服务器。白天路上车少，开得还行；可一到晚上八点半晚高峰，全国几千万人一起挤在狭窄的国际出口收费站，堵车、丢包、高延迟全来了。而且因为直接走公网过境，遇到敏感时期非常容易被干扰断流。现在市面上几块钱的廉价小机场大多就是这种直连，便宜是真便宜，晚高峰卡也是真卡。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>2. 国内 BGP 公网中转（相当于“先上高架快速路，再换大巴出境”）</strong><br />为了解决普通宽带国际出口烂的问题，机场主会在国内买一台大带宽 BGP 中转机房服务器（电信联通移动三网连过去都很快），你的流量先快速传到这台国内中转机，再由机房用质量更好的商业带宽转发到海外落地节点。这种方案体验比纯直连好一大截，成本也控制得住，是目前 <strong>15 元–20 元高性价比档位</strong>最常见、最务实的架构。平时白天飞快，晚高峰绝大多数时候也很稳，只是极端拥堵时段偶尔会有轻度波动。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>3. IPLC / IEPL 跨境内网专线（相当于“走点对点封闭高铁隧道”）</strong><br />IPLC（国际私用出租线路）和 IEPL（国际以太网专线，可以理解为 IPLC 的升级以太网版），是直接向基础电信运营商租用的点对点物理内网专线（比如深圳直连香港、上海直连日本）。流量从国内入口进去后，全程走运营商封闭内网直达海外机房，<strong>完全不经过拥堵的公共国际出口</strong>！它的最大优势就是：哪怕是晚上九点半全国网络堵成一锅粥，专线的延迟依然稳如直线、丢包率几乎为零，打游戏、开跨国会议、连 SSH 写代码丝滑得像访问国内网站一样。唯一的缺点就是贵——运营商按兆卖钱，带宽成本极高。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>二、普通人到底该怎么选？一定要买最贵的专线吗？</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>绝对不是！买机场跟买车一样，天天在市区买菜代步，非要花钱买台越野车纯属浪费。你可以直接按自己的真实需求对号入座：</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li><strong>如果你是程序员、外贸人、远程办公或重度 AI 用户</strong>：晚高峰断一次线比多花几块钱难受多了，优先选择带高质量专线或优化架构的主力品牌，比如咱们白名单排第 1 的 <a href="/brands/guangnianti/">光年梯</a>（原价 ¥18/月含 120G，优惠码 <code>GNT80</code>）或者排第 2 的 <a href="/brands/jilianyun/">极连云</a>（原价 ¥18/月含 100G，优惠码 <code>JLY888</code>），再配一条独立线路做备用。</li><li><strong>如果你是学生党或轻度日常浏览用户</strong>：平时就查查资料、刷刷推特、看会儿视频，根本没必要为纯专线溢价买单，15 元档位的 <a href="/brands/kuaili/">快狸</a>（码 <code>kl888</code>）、<a href="/brands/speedworld/">速界</a>（码 <code>sujie888</code>）或者多给 10G 流量的 <a href="/brands/kexinyun/">可信云</a>（码 <code>kxy80</code>）就已经非常实用。</li><li><strong>如果你看重开发者跨区域技术路由</strong>：可以同步考察 <a href="/brands/edgenova/">边缘节点</a>（原价 ¥25/月含 100G，码 <code>xk808</code>）。</li></ul><!-- /wp:list -->

<!-- wp:heading --><h2>三、如何一眼识破“假专线”营销套路？</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>现在市面上有很多不良商家，拿着普通公网机房改个节点名字叫“【IPLC 专线】香港 01”来忽悠新手。教你两个最实用的辨别办法：第一，<strong>看价格常识</strong>——如果某家号称全站 IPLC 专线，却卖 5 块钱 500G 且不限倍率，不用想，100% 是假的，物理成本都不够；第二，<strong>看晚高峰真实抖动</strong>——买一个月付，晚上 9 点连续 Ping 或者打开网页、拉代码测一周。真正的优质线路，白天和晚高峰的延迟波动极小、不会突然掉包卡死。记住，名字叫什么不重要，在你自家宽带晚高峰跑得稳才是硬道理。</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[airportcha_comparison_table limit="6"]<!-- /wp:shortcode -->
HTML,
		),

		array(
			'title'   => 'Trojan、VLESS 与 Hysteria2 协议怎么选？2026 真人大白话对比指南',
			'slug'    => 'trojan-vless-hysteria2-comparison',
			'cat'     => $cat_knowledge,
			'excerpt' => '机场节点列表里经常看到 Trojan、VLESS、Shadowsocks、Hysteria2，到底选哪个更快更稳？这篇大白话指南不讲晦涩加密算法，只告诉你日常不同网络环境下该怎么挑。',
			'content' => <<<'HTML'
<!-- wp:heading --><h2>别纠结底层密码学：用户只需要知道什么时候选哪个协议</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>很多朋友导入机场订阅后，打开 Clash Verge Rev 或小火箭，看到节点名字后面跟着各种英文标签：<code>SS</code>（Shadowsocks）、<code>Trojan</code>、<code>VLESS</code>、<code>Hysteria2</code>，一下子犯了选择困难症：“我到底该连哪个？是不是名字越长越高级？”</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>其实作为普通用户，你完全不需要去背什么握手流程和加密套件，只要记住一句话：<strong>协议本身没有绝对的谁秒杀谁，关键看你当前的网络环境是“稳健宽带”还是“丢包弱网”</strong>。下面用大白话把目前 2026 年最主流的几个协议给你讲透。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>一、Trojan 与 VLESS（TCP + TLS 稳健双雄）</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>Trojan</strong> 和 <strong>VLESS（特别是 VLESS-Reality）</strong>是目前中高端机场用得最广、口碑最稳的两大主力协议。它们底层都跑在 TCP 协议之上，最大的特点就是把你的代理流量伪装成再普通不过的 HTTPS 正常网页浏览流量（就像你平时打开淘宝、百度一样自然）。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>它们适合什么场景？</strong>只要你连的是质量不错的家里宽带、公司网络，或者机场本身提供的是稳健中转/专线入口，Trojan 和 VLESS 的表现极其扎实：<strong>长连接不断流、SSH 稳、开跨国视频会议不卡顿、几乎所有客户端（Clash / 小火箭 / 软路由）都完美兼容</strong>。比如我们站里排第一的 <a href="/brands/guangnianti/">光年梯</a>，主力节点就全面支持 Trojan 与 VLESS，日常办公闭眼连就行。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>二、Hysteria2 与 TUIC（基于 UDP/QUIC 的弱网提速猛兽）</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>传统的 TCP 协议有个天生脾气：一旦网络发生哪怕 3% 的丢包，它就会很“老实”地主动降速重传，结果在信号不好的地方（比如地铁 5G、学校晚高峰拥堵的校园网、酒店老旧 Wi-Fi）速度直接掉到几百 KB。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>而 <strong>Hysteria2</strong>（以及同类的 TUIC）基于新一代 UDP / QUIC 协议，采用了非常激进的拥塞控制算法——简单说就是<strong>“越是丢包弱网，它越能把带宽硬生生抢回来”</strong>！在移动网络、校园网或者跨洋直连丢包严重的环境下，切到 Hysteria2 节点看 YouTube 4K 往往能瞬间起飞。</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>但它也有两个小缺点：第一，国内部分宽带运营商或公司防火墙会对高频 UDP 流量进行限速（俗称 UDP QOS），遇到这种情况 Hysteria2 反而可能连不上或变慢；第二，老旧版本的代理客户端不支持它，必须用最新版的 Clash Verge Rev（Mihomo 内核）或新版小火箭。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>三、Shadowsocks（老牌轻量常青树）</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Shadowsocks（SS）是最早流行起来的轻量协议，现在依然在大量 IPLC/IEPL 专线机场里广泛服役。为什么专线机场反而爱用 SS？因为专线本身走的是运营商封闭内网，不需要复杂的伪装开销，用轻量级的 Shadowsocks（配合 AEAD 加密）反而<strong>CPU 占用最低、手机最省电、延迟最干脆</strong>。</p><!-- /wp:paragraph -->

<!-- wp:heading --><h2>四、一张口诀表：日常选协议照着做就行</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>在家/公司稳健宽带办公、写代码、用 AI</strong>：优先选 <strong>Trojan / VLESS / 专线 SS</strong>，连接最稳、省电且极少被运营商限速。</li><li><strong>在地铁 5G、酒店 Wi-Fi、校园网等丢包弱网环境</strong>：如果机场提供 <strong>Hysteria2</strong> 节点，优先切过去试试，提速效果立竿见影；万一发现被运营商 QOS 限速，再切回 Trojan / VLESS。</li><li><strong>不想动脑子研究协议？</strong>其实选对靠谱机场比纠结协议重要十倍！直接去 <a href="/recommend/">AIRPORTCHA 机场推荐总榜</a> 挑一家像光年梯（原价 18 元 120G，码 <code>GNT80</code>）或极连云（原价 18 元 100G，码 <code>JLY888</code>）这样全协议兼容的白名单品牌，客户端自动测速选绿灯节点用就完事了。</li></ul><!-- /wp:list -->
<!-- wp:shortcode -->[airportcha_top_brands limit="3" slot="article_inline"]<!-- /wp:shortcode -->
HTML,
		),
	);
}
