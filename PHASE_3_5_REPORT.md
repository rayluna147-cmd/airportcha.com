# Phase 03.5 静态网站排查修复与真实浏览器验收报告（PHASE_3_5_REPORT）

> **验收日期**：2026-11-26  
> **版本**：`v3.0.1`

## 1. 问题真实原因

1. **直接双击 `dist/index.html` 无样式的主因（`file://` 协议路径解析）**：
   - `dist/index.html` 中采用标准网站根相对路径 `/wp-content/themes/airportcha/style.css` 与 `/wp-content/themes/airportcha/assets/js/main.js`（符合 HTTP 预览与 Cloudflare Pages 正式部署规范）。
   - 直接在资源管理器双击 HTML 文件（`file:///D:/.../dist/index.html`）时，浏览器会将 `/wp-content/...` 解析到磁盘根目录 `file:///D:/wp-content/...`，导致无法加载样式。
2. **顺带发现并修复的静态导出细节**：
   - WordPress 输出的 `/wp-includes/blocks/paragraph/style.min.css` 外链在静态目录 `dist/` 中不存在，已在 `scripts/export-static.mjs` 中自动清理。

## 2. 已完成的实际修复与质量检查升级

- **升级 `scripts/export-static.mjs`**：导出时自动移除不存在的 `/wp-includes/blocks/*.css` 外链。
- **升级 `scripts/validate-dist.mjs`**：新增静态资源完整性校验，强制校验 `style.css`、`main.js` 及所有 HTML 中引用的 `/wp-content/` CSS/JS/图片文件物理存在且非空，缺失即报严重错误阻止发布。

## 3. 真实浏览器验收结果（纯静态预览 `http://127.0.0.1:4173/`，脱离 WordPress）

- **构建与校验**：`npm run build` 导出 **30 个公开页面 + `404.html`（共 31 个 HTML）**，**0 Errors, 0 Warnings**。
- **桌面端（1440×900）**：
  - `/` 首页：`style.css` 与 `main.js` 均 HTTP 200，零 JS 报错；完整渲染导航栏、深空蓝渐变 Hero、搜索框、光年梯/极连云/快狸品牌卡片、动态排行榜（实测 `IPLC 专线` 筛选正常）与页脚。
  - `/recommend/` 机场推荐页：结论先行框、编辑精选、10 行排行榜（筛选正常）与 9 行套餐矩阵均完整渲染。
  - `/brands/guangnianti/` 光年梯详情页：`核心主推` 徽章、评分 `4.9/5.0`、`/go/guangnianti/` 按钮、优惠码复制条与套餐网格正常。
- **移动端（390×844）**：零横向溢出（页面宽度 `374.67px <= 390px`），移动端菜单按钮与抽屉展开/收起正常。

## 4. 永久执行标准

自本阶段起，**静态资源物理存在性校验（`validate-dist.mjs`）+ 纯静态 HTTP 预览真实浏览器视觉验收**设为后续所有开发阶段的必选门禁。
