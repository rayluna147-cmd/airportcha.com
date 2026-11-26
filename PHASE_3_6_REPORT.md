# Phase 03.6 本地离线预览与正式部署双模式报告（PHASE_3_6_REPORT）

> **完成日期**：2026-11-26  
> **版本**：`v3.0.2`

## 1. 双模式目录架构

- **正式部署目录（Cloudflare Pages + GitHub）**：`dist/`（保持标准根路径 `/wp-content/...`、正式域名 `https://airportcha.com` Canonical、Sitemap、Robots、`_headers`、`_routes.json` 与 `_data/affiliates.json`）。
- **本地离线预览目录（支持直接双击 `file://` 打开）**：`dist-offline/`（已加入 `.gitignore`，不污染 GitHub 与 Cloudflare 正式部署）。
  - 由 `scripts/build-offline.mjs` 根据每个 HTML 文件的目录深度自动转换：
    - 根目录 (`depth=0`)：`./wp-content/...`、`./recommend/index.html`
    - 一级目录 (`depth=1`)：`../wp-content/...`、`../index.html`
    - 二级目录 (`depth=2`)：`../../wp-content/...`、`../../index.html`
  - 离线模式下 `/go/{slug}/` 自动映射为真实外部推广链接，方便本地双击直接体验。

## 2. Windows 一键预览工具

- **`打开离线首页.html`**：双击直接跳转至 `./dist-offline/index.html`，无需启动终端或服务器即可查看完整 AIRPORTCHA UI。
- **`打开网站预览.bat`**：双击自动检测或启动本地 HTTP 预览服务（`http://127.0.0.1:4173/`）并自动打开默认浏览器，体验与 Cloudflare Pages 一致的正式环境（含 `/go/{slug}/` 跳转与离线全文搜索）。

## 3. 验收结果

- `npm run build:offline`：**PASS** — 生成 31 个离线 HTML 页面至 `dist-offline/`。
- `npm run validate`：**PASS** — 正式目录 `dist/` 31 个 HTML、CSS、JS 完整性与 SEO 校验 **0 Errors, 0 Warnings**。
- **正式 HTTP 浏览器验收**：**PASS**（`http://127.0.0.1:4173/` 桌面端 1440px 与移动端 390px 均完整通过）。
- **`file://` 协议验收说明**：内置安全浏览器因策略限制禁止 `file:` 协议导航（`ERR_DISALLOWED_URL`），已通过脚本核验 0/1/2 层目录相对 CSS/JS/页面路径全部指向真实物理文件；用户可在 Windows 资源管理器直接双击 `打开离线首页.html` 或 `dist-offline/index.html` 查看完整 UI。
