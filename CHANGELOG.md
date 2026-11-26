# 变更日志（CHANGELOG）

## [3.0.2] - 2026-11-26（Phase 03.6：本地离线预览 `dist-offline/` 与 Windows 一键预览工具）

### 新增
- 新增 `scripts/build-offline.mjs` 与 `npm run build:offline`（并自动集成至 `npm run build`），每次构建自动生成支持 `file://` 直接双击打开与页面跳转的 `dist-offline/`。
- 新增根目录 `打开网站预览.bat`（一键启动 HTTP 预览并打开浏览器）与 `打开离线首页.html`（直接双击离线预览）。
- 将 `dist-offline/` 加入 `.gitignore`，保持 GitHub 与 Cloudflare Pages 正式部署目录 `dist/` 干净独立。
- 新增 `PHASE_3_6_REPORT.md`。

## [3.0.1] - 2026-11-26（Phase 03.5：静态资源完整性校验升级与真实浏览器视觉验收）

### 修复与升级
- 定位 `dist/index.html` 直接双击无样式为 `file://` 协议下根路径解析所致，通过纯静态 HTTP 预览（`http://127.0.0.1:4173/`）实测确认桌面端 1440px 与移动端 390px 均完整渲染定制 UI。
- `scripts/export-static.mjs` 自动清理静态站不存在的 `/wp-includes/blocks/*.css` 外链。
- `scripts/validate-dist.mjs` 升级为强制校验 `style.css`、`main.js` 及所有页面引用的 `/wp-content/` 静态资源真实存在且非空。
- 新增 `PHASE_3_5_REPORT.md`。

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
