# SEO 发布前自动质量检查清单（SEO_QUALITY_CHECKLIST）

> 对应自动化脚本：`scripts/validate-dist.mjs`（命令：`npm run check` / `npm run validate`）  
> 机器可读报告输出路径：`reports/seo-validation.json`

## 阻止发布的严重错误（Errors，任一失败均阻断构建）

- [x] 核心页面产物完整存在（`/`、`/recommend/`、`/brands/`、`/brands/guangnianti/`、`/reviews/`、`/comparisons/`、`/blog/`、`/guides/`、`/knowledge/`、`/about/`、`404.html`、`sitemap.xml`、`robots.txt`、`_headers`、`_routes.json`、`_data/affiliates.json`、`_data/search-index.json`）。
- [x] 任意 HTML 页面均无 `127.0.0.1` 或 `localhost` 本地开发地址残留。
- [x] 任意 HTML 页面均无未解析的 `<?php` 代码残留。
- [x] 每个公开页面均包含非空 `<title>`、`<meta name="description">`、`<h1>` 与指向 `https://airportcha.com` 的 `<link rel="canonical">`。
- [x] 页面中全部 `<script type="application/ld+json">` 均为合法 JSON。
- [x] `dist/_data/affiliates.json` 中不存在指向本站 `/go/` 的循环跳转。

## 一般优化建议（Warnings）

- [x] 检查不同公开页面是否存在重复 `<title>`。
