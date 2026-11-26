# IndexNow 自动索引提交指南（INDEXNOW_GUIDE）

## 1. 工作流原理
`scripts/indexnow.mjs` 会自动对比当前 `dist/_data/manifest.json` 与上次成功提交记录 `reports/indexnow-last-manifest.json`，**仅提取新增、内容实质变更或已删除的规范 URL**，避免重复提交未变化页面。

## 2. 启用步骤（正式域名上线后）
1. 在 Bing Webmaster Tools 生成一个 IndexNow Key（或使用 32 位随机十六进制字符串）。
2. 在 `dist/` 中放置 `<KEY>.txt`（内容为该 Key 本身）并部署上线，确认 `https://airportcha.com/<KEY>.txt` 返回 HTTP 200。
3. 在本地 `.env` 中配置 `INDEXNOW_KEY=<你的Key>`。
4. 预览待提交队列：`npm run indexnow -- --dry-run`
5. 正式提交（需确认 Cloudflare Pages 已部署生效）：`npm run indexnow`
