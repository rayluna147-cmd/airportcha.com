<?php
get_header();
?>

<!-- 模块一：专业 Hero 首屏 -->
<section class="ac-hero">
	<div class="ac-container">
		<div class="ac-hero-inner">
			<span class="ac-hero-eyebrow">独立网络科技媒体 · 多品牌机场推荐与评测数据库</span>
			<h1>以透明实测与多品牌对比，做好每一次机场推荐与网络服务选择</h1>
			<p>
				AIRPORTCHA 面向全球中文用户持续追踪主流机场品牌，提供独立晚高峰评测、同周期套餐对比、客户端教程与网络协议知识库。
			</p>
			<div class="ac-hero-actions">
				<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">查看机场推荐排行榜</a>
				<a class="ac-btn ac-btn-ghost" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">浏览品牌数据库</a>
				<a class="ac-btn ac-btn-ghost" href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>">阅读深度评测</a>
			</div>
			<form role="search" method="get" class="ac-hero-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" name="s" placeholder="搜索品牌（如：光年梯）、评测、Clash 教程或 IPLC 知识..." />
				<button type="submit" class="ac-btn ac-btn-primary">站内搜索</button>
			</form>
		</div>
	</div>
</section>

<main>
	<!-- 模块二：编辑精选与商业合作品牌（动态读取 home_primary 推荐位） -->
	<section class="ac-section">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>编辑精选与商业合作品牌</h2>
					<p>由后台推荐位置动态管理；含商业合作展示标识，独立评测评分与商业权重相互分离</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">完整推荐榜单 →</a>
			</div>
			<?php echo do_shortcode( '[airportcha_placement slot="home_primary" limit="3"]' ); ?>
		</div>
	</section>

	<!-- 模块三：综合机场推荐榜单 -->
	<section class="ac-section ac-section-alt">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>综合机场推荐排行榜</h2>
					<p>支持按核心特性筛选，并可在推荐权重、独立评测评分与起步价格间自由切换排序</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">进入机场推荐中心 →</a>
			</div>
			<?php echo do_shortcode( '[airportcha_ranking_board limit="6" slot="home_ranking"]' ); ?>
		</div>
	</section>

	<!-- 模块四：按需求选择品牌（仅链接至已存在真实页面/筛选） -->
	<section class="ac-section">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>按使用需求快速选型</h2>
					<p>根据预算、稳定性与使用场景直达对应筛选结果或核心专题</p>
				</div>
			</div>
			<div class="ac-intent-grid">
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">
					<strong>综合机场推荐</strong>
					<span>查看多品牌综合评分、价格与场景建议</span>
				</a>
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/brands/?feature=iplc' ) ); ?>">
					<strong>稳定性 / IPLC 专线</strong>
					<span>适合远程办公、开发与晚高峰低延迟</span>
				</a>
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/brands/?feature=high-value' ) ); ?>">
					<strong>高性价比与低预算</strong>
					<span>入门月付、大流量与不限时备用方案</span>
				</a>
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/brands/?feature=ai-unlock' ) ); ?>">
					<strong>ChatGPT / AI 解锁</strong>
					<span>稳定访问 ChatGPT、Claude 与海外 SaaS</span>
				</a>
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/brands/?feature=streaming' ) ); ?>">
					<strong>4K 流媒体观影</strong>
					<span>Netflix、Disney+、YouTube 4K 流畅解锁</span>
				</a>
				<a class="ac-intent-card" href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">
					<strong>多设备客户端教程</strong>
					<span>Windows、macOS、iOS 与路由器配置指南</span>
				</a>
			</div>
		</div>
	</section>

	<!-- 模块五：品牌价格及套餐对比 -->
	<section class="ac-section ac-section-alt">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>主流品牌套餐价格对比</h2>
					<p>同步自品牌数据库真实套餐资料，未补充完整套餐的品牌明确标示「待补充」</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/comparisons/' ) ); ?>">查看更多对比 →</a>
			</div>
			<?php echo do_shortcode( '[airportcha_plans_matrix limit="5"]' ); ?>
		</div>
	</section>

	<!-- 模块六：最新深度评测 -->
	<section class="ac-section">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>最新品牌深度评测</h2>
					<p>多时段测速、丢包监测与流媒体/AI 解锁实测报告</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>">全部评测 →</a>
			</div>
			<?php
			$reviews = new WP_Query( array( 'post_type' => 'review', 'posts_per_page' => 3 ) );
			if ( $reviews->have_posts() ) :
				?>
				<div class="ac-post-grid">
					<?php
					while ( $reviews->have_posts() ) :
						$reviews->the_post();
						$rmeta = airportcha_get_review_meta( get_the_ID() );
						?>
						<article class="ac-card">
							<div class="ac-post-meta">
								<span class="ac-badge">深度评测</span>
								<span>独立评分 <?php echo esc_html( $rmeta['score_overall'] ); ?> / 5.0</span>
								<span>更新于 <?php echo esc_html( get_the_modified_date() ); ?></span>
							</div>
							<h3 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36 ) ); ?></p>
							<div class="ac-card-actions">
								<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">阅读完整评测</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<?php
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<!-- 模块七：热门品牌对比 -->
	<section class="ac-section ac-section-alt">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>热门品牌横向对比</h2>
					<p>直接对比不同服务商的线路架构、价格梯度与适用人群</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/comparisons/' ) ); ?>">全部对比 →</a>
			</div>
			<?php
			$comparisons = new WP_Query( array( 'post_type' => 'comparison', 'posts_per_page' => 2 ) );
			if ( $comparisons->have_posts() ) :
				?>
				<div class="ac-two-col">
					<?php
					while ( $comparisons->have_posts() ) :
						$comparisons->the_post();
						$cmeta = airportcha_get_comparison_meta( get_the_ID() );
						?>
						<article class="ac-card">
							<div class="ac-post-meta">
								<span class="ac-badge ac-badge-accent">品牌对比</span>
								<span><?php echo esc_html( get_the_date() ); ?></span>
							</div>
							<h3 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="ac-card-desc"><?php echo esc_html( $cmeta['summary'] ?: get_the_excerpt() ); ?></p>
							<div class="ac-card-actions">
								<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">查看对比详情</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<?php
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<!-- 模块八：网络知识及新手教程 -->
	<section class="ac-section">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>网络知识与客户端教程</h2>
					<p>从 Clash / Shadowrocket 配置到 IPLC 专线与传输协议原理</p>
				</div>
				<div style="display:flex;gap:8px;flex-wrap:wrap;">
					<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">使用教程</a>
					<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/knowledge/' ) ); ?>">网络知识</a>
					<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">博客资讯</a>
				</div>
			</div>
			<?php
			$articles = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6 ) );
			if ( $articles->have_posts() ) :
				?>
				<div class="ac-post-grid">
					<?php
					while ( $articles->have_posts() ) :
						$articles->the_post();
						$cats = get_the_category();
						?>
						<article class="ac-card">
							<div class="ac-post-meta">
								<?php if ( ! empty( $cats ) ) : ?>
									<span class="ac-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( get_the_date() ); ?></span>
							</div>
							<h3 class="ac-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p class="ac-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>
							<div class="ac-card-actions">
								<a class="ac-btn ac-btn-outline" href="<?php the_permalink(); ?>">阅读全文</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<?php
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<!-- 模块九 & 十：品牌数据库入口 + 网站评测与推荐方法 -->
	<section class="ac-section ac-section-alt">
		<div class="ac-container">
			<div class="ac-section-head">
				<div>
					<h2>AIRPORTCHA 评测与推荐方法</h2>
					<p>透明的资料收集、测试维度与商业合作披露机制</p>
				</div>
				<a class="ac-btn ac-btn-outline" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">了解编辑政策 →</a>
			</div>
			<div class="ac-method-grid">
				<div class="ac-card">
					<h3 class="ac-card-title" style="margin-top:0;">1. 公开资料核验</h3>
					<p class="ac-card-desc" style="margin-bottom:0;">记录各品牌公开套餐、协议、节点覆盖与运营状态，缺失信息明确标示待补充。</p>
				</div>
				<div class="ac-card">
					<h3 class="ac-card-title" style="margin-top:0;">2. 晚高峰实测维度</h3>
					<p class="ac-card-desc" style="margin-bottom:0;">围绕晚高峰吞吐速度、稳定性、流媒体与 AI 解锁及性价比四个维度独立记录。</p>
				</div>
				<div class="ac-card">
					<h3 class="ac-card-title" style="margin-top:0;">3. 商业与评测分离</h3>
					<p class="ac-card-desc" style="margin-bottom:0;">商业等级（PRIMARY/FEATURED/PARTNER）与独立评测评分分别管理，不以合作抬高评分。</p>
				</div>
				<div class="ac-card">
					<h3 class="ac-card-title" style="margin-top:0;">4. 风险动态下架</h3>
					<p class="ac-card-desc" style="margin-bottom:0;">对停止运营或风险状态品牌自动停止商业推荐，仅保留历史档案供用户参考。</p>
				</div>
			</div>
		</div>
	</section>

	<!-- 模块十一 & 十二：常见问题与底部转化引导 -->
	<section class="ac-section">
		<div class="ac-container ac-container-narrow">
			<div class="ac-section-head">
				<div>
					<h2>常见问题（FAQ）</h2>
					<p>关于品牌选择、套餐周期与本站评测原则的常见疑问</p>
				</div>
			</div>
			<div class="ac-faq-list">
				<div class="ac-faq-item">
					<h4>AIRPORTCHA 是某一家机场的官方网站吗？</h4>
					<p>不是。AIRPORTCHA 是独立的多品牌网络科技媒体与评测数据库，同时收录并对比多个主流品牌，帮助用户按需选择。</p>
				</div>
				<div class="ac-faq-item">
					<h4>新手选购机场服务最需要注意什么？</h4>
					<p>建议优先选择支持月付的方案测试自己所在网络环境的晚高峰表现，避免首次购买即大额年付；同时关注客户端兼容性与售后渠道。</p>
				</div>
				<div class="ac-faq-item">
					<h4>为什么部分品牌页面没有「访问官网」购买按钮？</h4>
					<p>当品牌处于非合作档案状态、推广链接待配置或已停止推荐时，本站会自动隐藏商业购买按钮，仅保留客观的品牌档案与评测供参考。</p>
				</div>
			</div>

			<div class="ac-cta-banner">
				<div>
					<h3 style="margin:0 0 6px;color:#fff;">准备好对比并挑选适合自己的网络服务了吗？</h3>
					<p style="margin:0;color:#CBD7EC;font-size:14px;">前往机场推荐中心查看完整排行榜、同周期套餐对比与场景选型指南。</p>
				</div>
				<div style="display:flex;gap:10px;flex-wrap:wrap;">
					<a class="ac-btn ac-btn-primary" href="<?php echo esc_url( home_url( '/recommend/' ) ); ?>">查看机场推荐排行榜</a>
					<a class="ac-btn ac-btn-ghost" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">进入品牌目录</a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
