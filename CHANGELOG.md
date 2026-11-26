# 变更日志（CHANGELOG）

## [3.0.0] - 2026-11-26（Phase 03：Bing 核心关键词页 `/recommend/`、首页 12 模块与动态品牌排行榜升级）

### 新增
- **动态排行榜与套餐矩阵短代码**：在 `includes/shortcodes.php` 新增 `[airportcha_ranking_board]` 与 `[airportcha_plans_matrix]`，支持按特性筛选与按推荐权重/独立评分/起步价排序（离线静态页面同样可用）。
- **首页 12 模块升级**：重构 `front-page.php`，新增 Hero 站内搜索、编辑精选位、综合排行榜、6 大场景需求选型、套餐对比矩阵、4 栏评测方法论、FAQ 与底部转化横幅。
- **核心竞争页 `/recommend/` 升级**：重构 `template-recommend.php` 为 13 部分「机场推荐」综合选型中心，并在 `includes/seo.php` 配置独立 Title/Description、`ItemList` 与 `FAQPage` JSON-LD。
- **Phase 3 专题文档**：新增 `HOMEPAGE_ARCHITECTURE.md`、`RECOMMEND_PAGE_STRATEGY.md`、`RANKING_METHODOLOGY.md`、`COMPETITOR_ANALYSIS.md`、`PHASE_3_REPORT.md`、`PHASE_4_PLAN.md`。

## [2.0.0] - 2026-11-26（Phase 02：多品牌商业系统、SEO/GEO 底层与 Cloudflare Pages 静态发布工程）

### 新增
- **本地数据持久化**：新增 `scripts/start-wp.mjs`，通过 `.wp-data/database` 与 `.wp-data/uploads` 挂载实现本地 WordPress 数据持久化（已加入 `.gitignore`）。
- **五级品牌商业体系**：升级 `includes/meta-boxes.php`，新增 `PRIMARY` / `FEATURED` / `PARTNER` / `ARCHIVE` / `RISK` 商业等级、运营状态、推广状态、套餐 JSON、优惠说明、推荐位置配置、SEO/GEO 实体字段与后台品牌列表自定义列。
- **统一推广跳转与点击统计**：新增 `includes/redirects.php`（WordPress `/go/{slug}/` 302 跳转 + `X-Robots-Tag: noindex, nofollow` + 循环跳转防护 + 本地点击统计）与 Cloudflare Pages Function `functions/go/[slug].js` + D1 迁移 `migrations/0001_outbound_clicks.sql`。
- **动态商业组件与主题升级**：新增 `[airportcha_brand_card]` 与 `[airportcha_placement]` 短代码；接入品牌 Logo / 首字科技徽标回退、套餐价格卡片、FAQ 展示、优惠码一键复制、静态品牌排序与离线静态搜索。
- **静态发布与自动质量验收**：新增 `scripts/export-static.mjs`、`scripts/validate-dist.mjs`、`scripts/preview-static.mjs`、`scripts/indexnow.mjs`、`wrangler.toml`、`.env.example` 与 `.github/workflows/validate-and-deploy.yml`。
- **SEO/GEO 升级**：`includes/seo.php` 补齐 `BreadcrumbList`、`FAQPage` JSON-LD、`og:image` 与自定义品牌 SEO Title/Description。

### 修复
- 修复 Windows 中文路径下 Node.js `fs.cpSync` 兼容异常，改为纯 JS 递归拷贝 `copyDirSafe`。
- 彻底清理静态导出 HTML 中 URL 编码及无端口 `127.0.0.1` 残留，所有 Canonical 统一规范为 `https://airportcha.com`。
- 清理 `seeder.php` 中的 `/go/` 自身占位链接，默认标记为 `pending` 防止循环跳转。

## [1.0.0] - 2026-11-26（Phase 01：WordPress 基础架构、主题与核心插件）
- 完成 `airportcha-core` 插件、`airportcha` 主题、9 个核心栏目、示例数据与 16/16 路由实测验收。
