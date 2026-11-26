# 静态导出与发布工作流说明（STATIC_EXPORT）

## 1. 架构说明

```text
WordPress 本地编辑 (npm start, .wp-data/ 持久化)
  ↓ npm run export
静态导出产物 (dist/ — 纯 HTML/CSS/JS/Sitemap/Robots/_data)
  ↓ npm run validate
SEO 与发布前质量自动检查 (0 Errors 放行)
  ↓ git push origin main
GitHub 仓库 → Cloudflare Pages 自动部署 + Pages Functions (/go/{slug}/)
```

## 2. 导出覆盖范围

执行 `npm run export`（`scripts/export-static.mjs`）会自动：
1. 连接运行中的 `http://127.0.0.1:9400`（若未运行则自动拉起临时 WordPress 实例并挂载 `.wp-data/`）。
2. 通过 WordPress REST API 动态拉取全部已发布页面、文章、品牌、评测、对比路由（自动过滤 WordPress 默认 `/sample-page/` 与 `/hello-world/`）。
3. 导出完整 HTML 至 `dist/`，并将所有本地地址改写为正式域名 `https://airportcha.com`，同时将站内 `/wp-content/` 静态资源转为相对路径以兼容本地预览与生产环境。
4. 导出 `dist/404.html`、`dist/sitemap.xml`、`dist/robots.txt`、`dist/_headers`、`dist/_routes.json`。
5. 导出静态数据文件：`dist/_data/affiliates.json`（供 Cloudflare Function 跳转）、`dist/_data/search-index.json`（供离线全文搜索）、`dist/_data/manifest.json`（供 IndexNow 变更检测）。

## 3. 常用命令

- `npm start`：启动本地 WordPress（含 `.wp-data/` 持久化）
- `npm run export`：仅执行静态导出
- `npm run build`：执行静态导出 + 发布前 SEO 自动检查
- `npm run preview`：在 `http://127.0.0.1:4173/` 启动**完全脱离 WordPress** 的纯静态预览
- `npm run validate`：独立执行发布前 SEO 校验并输出 `reports/seo-validation.json`
- `npm run indexnow -- --dry-run`：预览待提交至 Bing IndexNow 的规范 URL
