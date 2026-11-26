# Cloudflare Pages + GitHub 部署指南（CLOUDFLARE_DEPLOYMENT）

## 1. Cloudflare Pages 控制台推荐参数

- **Git Provider**：GitHub（连接你的 `airportcha.com` 仓库）
- **Production Branch**：`main`
- **Framework Preset**：`None`
- **Root Directory**：`/`（仓库根目录）
- **Build Command**：`npm run validate`（本地执行 `npm run build` 生成 `dist/` 并提交至 GitHub 后，Cloudflare 构建阶段仅校验产物完整性；无需在云端运行 PHP/MySQL）
- **Build Output Directory**：`dist`
- **Custom Domain**：`airportcha.com`

## 2. Pages Functions 与 D1 点击统计（可选）

- 推广跳转函数位于 `functions/go/[slug].js`，由 `dist/_routes.json` 自动路由 `/go/*`。
- **无 D1 降级**：即使不配置 D1 数据库，`/go/{slug}/` 302 跳转、防循环跳转与 `X-Robots-Tag: noindex, nofollow` 依然 100% 正常工作。
- **启用 D1 统计**：
  1. 在 Cloudflare 控制台创建 D1 数据库 `airportcha-stats`。
  2. 执行建表迁移：`migrations/0001_outbound_clicks.sql`。
  3. 在 Pages 项目 **Settings -> Functions -> D1 database bindings** 中添加绑定名称 `DB` 指向 `airportcha-stats`（或同步更新 `wrangler.toml`）。

## 3. Cloudflare 爬虫与域名兼容检查清单（需在控制台手动确认）

- [ ] 域名 `airportcha.com` 的 NS 名称服务器已生效。
- [ ] SSL/TLS 设为 **Full (Strict)** 并开启 **Always Use HTTPS**。
- [ ] 配置 `www.airportcha.com` → `https://airportcha.com` 的 301 跳转规则，统一规范域名。
- [ ] 确认 WAF / Bot Fight Mode 未误拦 Bingbot 与 Googlebot（避免对合法爬虫弹出 JS 挑战或验证码）。
- [ ] 禁止 `*.pages.dev` 预览域名被搜索引擎收录（可在 Cloudflare 配置仅正式域索引或通过正式域 Canonical 归一）。
