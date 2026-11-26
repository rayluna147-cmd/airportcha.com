# 剩余开发任务与优先级清单（TODO）

> 按 **P0（上线前必须完成）**、**P1（体验与转化增强）**、**P2（长期扩展）** 排序。

---

## P0 · 最高优先级（生产上线前必须解决）

- [ ] **推广链接跳转系统（`/go/{slug}/`）**：实现基于 WordPress Rewrite Rule 或后台表单的统一跳转路由（302 跳转 + `X-Robots-Tag: noindex, nofollow` + 点击计数），或替换 `seeder.php` 中的占位推广链接为真实 URL。
- [ ] **特色图片与品牌 Logo 展示**：在品牌卡片、品牌详情页、评测卡片、博客文章中接入 `the_post_thumbnail()`，并提供优雅的默认兜底图标/占位图，同时在 `seo.php` 中补充 `og:image`。
- [ ] **真实 MySQL / Docker 环境联调**：在安装有 Docker 或 VPS 的环境中实测 `docker-compose.yml` 与标准 MySQL/MariaDB 部署流程，确认固定链接与数据持久化正常。
- [ ] **初始化 Git 仓库**：在项目根目录执行 `git init` 并建立清晰的分支与提交记录（确保 `.gitignore` 忽略 `wp-content/uploads/`、`node_modules/` 等）。

---

## P1 · 高优先级（阅读体验、SEO 与商业转化提升）

- [ ] **文章阅读增强**：为 `single.php`、`single-review.php`、`single-comparison.php` 增加自动文章目录（TOC）、预计阅读时间、上下篇/相关文章推荐。
- [ ] **一键复制优惠码交互**：在 `single-brand.php` 与品牌卡片中为 `_ac_coupon` 添加轻量原生 JS「点击复制」按钮与复制成功提示。
- [ ] **完善 JSON-LD Schema**：在 `includes/seo.php` 中补齐 `BreadcrumbList` 结构化数据，以及评测/教程页的 `FAQPage` Schema 支持。
- [ ] **品牌目录增强筛选**：在 `archive-brand.php` 中支持按 `brand_region`（节点地区）筛选及按评分/价格排序。
- [ ] **移动端真机视觉回归**：在 375px（手机）与 768px（平板）视口下逐页检查表格滚动、按钮换行、抽屉菜单与页脚排版。

---

## P2 · 中长期优化（按需迭代）

- [ ] **繁体中文兼顾方案**：新增轻量化前端繁简切换（如 OpenCC/简繁转换词库）或评估多语言插件方案（并配置 `hreflang`）。
- [ ] **单品牌独立短代码 `[airportcha_brand_card slug="..."]`**：方便在任意博客文章或教程正文中精准插入单个品牌推荐卡片。
- [ ] **Gutenberg 自定义区块封装**：将现有短代码进一步封装为原生 Gutenberg Dynamic Blocks，让后台编辑所见即所得。
- [ ] **后台管理列增强**：在 WordPress 后台「品牌目录」列表页增加「综合评分」「起步价」「是否重点推荐」「排序权重」快捷列与快速编辑。
