# Bing SEO 核心竞争战略（BING_SEO_STRATEGY）

## 1. 战略目标

- **第一搜索市场**：Microsoft Bing 中文搜索（重点覆盖 Microsoft Edge 默认搜索用户）。
- **第一核心关键词**：「机场推荐」。
- **核心承接页面**：`https://airportcha.com/recommend/`（永久固定 URL，不随年份更改路径）。

## 2. 技术落地现状（Phase 02 已完成）

- 所有静态导出页面初始 HTML 均包含完整正文、表格、参数、内链与结构化数据（不依赖客户端 JS 渲染核心内容）。
- 全站 Canonical 统一规范为 `https://airportcha.com/...`，`validate-dist.mjs` 强制拦截本地地址残留。
- 自动生成 `dist/sitemap.xml`（仅收录可索引规范页，排除 `/go/`、搜索页、草稿）与 `dist/robots.txt`。
- 已配置 `scripts/indexnow.mjs`，正式上线并在 Bing Webmaster Tools 配置 `INDEXNOW_KEY` 后可实现秒级推送。

## 3. 上线后运营步骤

1. 在 Bing Webmaster Tools 验证 `https://airportcha.com/` 并提交 `https://airportcha.com/sitemap.xml`。
2. 在根目录生成 `<INDEXNOW_KEY>.txt` 密钥验证文件并配置 `.env` 中的 `INDEXNOW_KEY`。
3. 每次重大内容更新部署完成后执行 `npm run indexnow` 推送变更 URL。
