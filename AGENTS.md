# AIRPORTCHA AI 开发助手持续工作规范（AGENTS.md）

> 本文件供所有后续接手本项目的 AI 编程助手在每次任务开始前读取并严格遵守。

---

## 1. 永久战略原则（不可违背）

1. **AIRPORTCHA 是长期可扩展的多品牌评测及商业转化平台**。
2. **光年梯是当前核心主推品牌（`PRIMARY`），而不是唯一推广品牌**；严禁将网站改成光年梯官网或单一产品销售落地页。
3. **全站第一核心关键词为「机场推荐」**（核心承接页 `/recommend/`），长期目标为争取 **Microsoft Bing 中文搜索自然排名第一**。
4. **正式发布架构为 WordPress 内容管理层 → 静态导出层 (`dist/`) → GitHub → Cloudflare Pages + Pages Functions**。
5. **持续保留第一阶段原有网站品牌、UI 视觉及有效页面结构**（深空蓝 `#101B32`、科技蓝 `#3789FF`、紫色 `#8067F5`、背景白 `#F5F8FD`、正文深灰 `#202B3C`）。
6. **后续所有开发必须继承前一阶段成果**，不得推倒重来或覆盖已有有效业务代码。

---

## 2. 核心架构与数据红线

1. **保持原生 WordPress 管理 + 插件/主题解耦**：
   - CPT、Taxonomy、Meta 字段、五级商业等级、`/go/` 跳转、短代码、SEO、Seeder 全部在 `wp-content/plugins/airportcha-core/`。
   - 模板、样式、前端交互在 `wp-content/themes/airportcha/`。
2. **严禁硬编码业务内容与推广链接**：
   - 所有推广链接统一通过 `/go/{brand-slug}/` 路由与 `_ac_affiliate_url` / `dist/_data/affiliates.json` 管理，禁止在模板中硬编码 Affiliate URL，禁止指向本站 `/go/` 造成循环跳转。
   - 所有商业外链必须保留 `rel="nofollow sponsored noopener"`。
3. **严禁伪造数据**：不得伪造真实推广注册链接、搜索量、排名曲线、测速证据或商业收入。
4. **数据安全与脱敏**：
   - `.wp-data/`、`.env`、数据库备份及任何密钥/Token 严禁提交至 GitHub。
   - 修改 `seeder.php` 必须保持幂等且不覆盖用户已修改内容。
   - 保留 `airportcha_has_external_seo_plugin()` 检测，避免与 Rank Math / Yoast 冲突。

---

## 3. 路由与运行兼容性保护

- 严禁随意更改 9 个核心 URL：`/`、`/recommend/`、`/brands/`、`/reviews/`、`/comparisons/`、`/blog/`、`/guides/`、`/knowledge/`、`/about/`。
- 保留 `package.json` 中 `@wp-playground/cli@3.1.65` 版本锁定与 `.wp-data/` 本地持久化挂载。
- 每次改动后必须执行 `npm run build`（静态导出 + SEO 自动检查）确保 `0 Errors` 通过。
