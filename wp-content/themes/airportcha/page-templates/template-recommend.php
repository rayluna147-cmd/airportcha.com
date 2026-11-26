<?php
/**
 * Template Name: 机场推荐榜单页（核心竞争页面 /recommend/）
 */
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="ac-page-header">
		<div class="ac-container">
			<?php airportcha_breadcrumbs(); ?>
			<h1>机场推荐与品牌排行榜</h1>
			<p>围绕晚高峰稳定性、价格套餐、协议兼容与流媒体/AI 解锁维度，持续更新的多品牌机场推荐与选型中心。最近更新：<?php echo esc_html( get_the_modified_date() ); ?></p>
		</div>
	</section>

	<main class="ac-section">
		<div class="ac-container">

			<!-- 第一部分：结论先行 -->
			<div class="ac-tldr-box">
				<h2 style="margin:0 0 10px;font-size:20px;color:var(--ac-navy);">核心结论与快速选型建议</h2>
				<ul style="margin:0;padding-left:20px;display:flex;flex-direction:column;gap:6px;font-size:15px;">
					<li><strong>追求晚高峰稳定与 AI/开发办公</strong>：优先考虑全链路 IPLC/IEPL 专线品牌（如当前核心主推的 <a href="<?php echo esc_url( home_url( '/brands/guangnianti/' ) ); ?>">光年梯</a>）。</li>
					<li><strong>预算有限、日常 4K 观影与大流量</strong>：优先选择高性价比中转 + 精选专线方案（如 <a href="<?php echo esc_url( home_url( '/brands/xingyun-cloud/' ) ); ?>">星云加速</a>）。</li>
					<li><strong>轻度使用或作为主力备用线路</strong>：优先考虑不限时按量流量包方案（如 <a href="<?php echo esc_url( home_url( '/brands/bluewhale-link/' ) ); ?>">蓝鲸专线</a>）。</li>
					<li><strong>避坑原则</strong>：首次购买建议优先月付实测一周晚高峰表现，确认延迟与解锁符合预期后再考虑长期续费。</li>
				</ul>
			</div>

			<!-- 后台可编辑引导内容（保持站长零代码维护能力） -->
			<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
				<div class="ac-prose" style="margin-bottom:36px;">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<!-- 第二部分：编辑精选品牌（商业合作展示与独立评分分离） -->
			<section style="margin-bottom:48px;">
				<div class="ac-section-head">
					<div>
						<h2>编辑精选与重点推荐品牌</h2>
						<p>由 WordPress 后台推荐位动态读取，商业合作等级与独立评测评分严格分离</p>
					</div>
				</div>
				<?php echo do_shortcode( '[airportcha_placement slot="recommend_rank" limit="3"]' ); ?>
			</section>

			<!-- 第三 & 四部分：综合品牌排行榜 + 快速筛选排序工具 -->
			<section style="margin-bottom:48px;">
				<div class="ac-section-head">
					<div>
						<h2>综合机场品牌排行榜（支持按特性筛选与多维排序）</h2>
						<p>所有品牌参数实时同步自 AIRPORTCHA 品牌数据库，自动排除风险或停止运营品牌</p>
					</div>
				</div>
				<?php echo do_shortcode( '[airportcha_ranking_board limit="12" slot="recommend_table"]' ); ?>
			</section>

			<!-- 第五部分：同周期套餐价格横向对比 -->
			<section style="margin-bottom:48px;">
				<div class="ac-section-head">
					<div>
						<h2>主流品牌套餐价格横向对比</h2>
						<p>按套餐计费周期与流量配额透明对比，未收录完整套餐信息的品牌标示「待补充」</p>
					</div>
				</div>
				<?php echo do_shortcode( '[airportcha_plans_matrix limit="8"]' ); ?>
			</section>

			<!-- 第六 & 八部分：品牌选择指南与风险注意事项 -->
			<section style="margin-bottom:48px;">
				<div class="ac-two-col">
					<div class="ac-card">
						<h2 style="margin:0 0 12px;font-size:20px;color:var(--ac-navy);">机场服务选择指南</h2>
						<ul style="margin:0;padding-left:20px;font-size:14px;color:var(--ac-text);display:flex;flex-direction:column;gap:8px;">
							<li><strong>明确主线路架构</strong>：IPLC/IEPL 专线晚高峰稳健但单价高；公网中转性价比高但高峰期可能波动。</li>
							<li><strong>核对协议与客户端</strong>：确认支持 Clash Verge Rev、Shadowrocket 或 Sing-box 一键订阅。</li>
							<li><strong>检查解锁需求</strong>：如需使用 ChatGPT、Claude 或 Netflix，优先查看品牌档案中的实测解锁项。</li>
						</ul>
					</div>
					<div class="ac-notice-box">
						<h2 style="margin:0 0 12px;font-size:20px;color:var(--ac-navy);">购买风险与注意事项</h2>
						<ul style="margin:0;padding-left:20px;font-size:14px;color:var(--ac-text);display:flex;flex-direction:column;gap:8px;">
							<li><strong>避免盲目大额年付</strong>：任何网络服务均存在线路波动风险，建议先月付验证再决定长期方案。</li>
							<li><strong>准备备用线路</strong>：重度远程办公或开发者建议主用专线 + 按量不限时备用，降低单点中断影响。</li>
							<li><strong>商业关系透明披露</strong>：本站部分有效推广链接通过 <code>/go/</code> 跳转并标注 <code>nofollow sponsored</code>，不影响客观记录。</li>
						</ul>
					</div>
				</div>
			</section>

			<!-- 第九 & 十一部分：推荐方法与高频 FAQ -->
			<section style="margin-bottom:48px;">
				<div class="ac-section-head">
					<div>
						<h2>机场推荐常见问题（FAQ）</h2>
						<p>解答选购机场服务时最常遇到的疑问</p>
					</div>
				</div>
				<div class="ac-faq-list">
					<div class="ac-faq-item">
						<h4>新手第一次选择机场服务，应该先买月付还是年付？</h4>
						<p>建议优先选择月付套餐，在自己的宽带与移动网络下实测至少一周晚高峰体验后，再考虑是否升级季付或年付以降低风险。</p>
					</div>
					<div class="ac-faq-item">
						<h4>商业推荐位与独立评测评分有什么区别？</h4>
						<p>商业合作等级仅决定编辑精选等展示位置并明确标注合作关系；独立评测评分基于线路稳定性、协议兼容、解锁表现与性价比独立评定，不受商业权重影响。</p>
					</div>
					<div class="ac-faq-item">
						<h4>IPLC 专线机场和普通中转机场该怎么选？</h4>
						<p>如果经常在晚高峰进行远程办公、代码开发或使用 ChatGPT/Claude，优先选择 IPLC/IEPL 专线；如果以日常浏览和预算优先为主，高性价比中转机场即可满足需求。</p>
					</div>
				</div>
			</section>

			<!-- 第十二 & 十三部分：相关专题内链与维护声明 -->
			<section class="ac-score-box" style="margin-bottom:0;">
				<h3 style="margin:0 0 10px;font-size:17px;color:var(--ac-navy);">延伸阅读与相关栏目</h3>
				<div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
					<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">全部品牌目录</a>
					<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>">深度品牌评测</a>
					<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/comparisons/' ) ); ?>">品牌横向比较</a>
					<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">客户端使用教程</a>
					<a class="ac-btn ac-btn-sm ac-btn-outline" href="<?php echo esc_url( home_url( '/knowledge/' ) ); ?>">网络协议与知识</a>
				</div>
				<p style="margin:0;font-size:12px;color:var(--ac-muted);">
					资料来源：各品牌公开套餐信息与 AIRPORTCHA 编辑部实测记录 · 页面最近更新：<?php echo esc_html( get_the_modified_date() ); ?>
				</p>
			</section>

		</div>
	</main>
<?php
endwhile;
get_footer();
