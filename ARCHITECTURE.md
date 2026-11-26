# 技术架构及页面结构说明（ARCHITECTURE）

## 1. 核心架构原则

本项目采用 **WordPress 插件与主题解耦架构**：
- **数据层（核心插件）**：`wp-content/plugins/airportcha-core/` 负责所有自定义文章类型（CPT）、分类法（Taxonomies）、自定义字段（Meta Boxes）、动态短代码、SEO 结构化数据与初始化脚本。即使未来更换主题，核心业务数据零丢失。
- **表现层（定制主题）**：`wp-content/themes/airportcha/` 负责布局、视觉设计系统、页面模板与交互，不包含任何硬编码业务数据。

---

## 2. 技术栈与依赖版本

- **CMS 核心**：WordPress 6.2+（实测运行于 WordPress latest + PHP 8.3，生产 Docker 镜像 `wordpress:6.7-php8.2-apache`）
- **数据库**：生产推荐 MariaDB 10.11 / MySQL 8.0；本地免安装开发通过 `@wp-playground/cli@3.1.65` 使用内置 SQLite
- **编辑器**：WordPress 原生 Gutenberg 区块编辑器（所有 CPT 与 Taxonomy 均已开启 `show_in_rest => true`）
- **前端技术**：纯语义化 HTML5 + 原生 CSS3（CSS Custom Properties）+ 零依赖原生 JS（`assets/js/main.js`），无 React/Vue 构建打包负担

---

## 3. 内容数据库与自定义数据结构

### 3.1 自定义文章类型（CPT）

| CPT Key | 中文名称 | 归档 URL | 详情 URL 示例 | 对应模板 |
| --- | --- | --- | --- | --- |
| `brand` | 品牌目录 | `/brands/` | `/brands/guangnianti/` | `archive-brand.php` / `single-brand.php` |
| `review` | 品牌评测 | `/reviews/` | `/reviews/guangnianti-review/` | `archive-review.php` / `single-review.php` |
| `comparison` | 品牌比较 | `/comparisons/` | `/comparisons/guangnianti-vs-xingyun/` | `archive-comparison.php` / `single-comparison.php` |

### 3.2 自定义分类法（Taxonomies）

- `brand_feature`（层级分类，slug: `brand-feature`）：品牌特性，如 `IPLC 专线`、`ChatGPT/AI 解锁`、`4K 流媒体解锁`、`高性价比`、`低延迟稳定`。
- `brand_region`（标签型，slug: `brand-region`）：节点地区，如 `香港`、`日本`、`新加坡`、`美国`、`台湾`。

### 3.3 原生 Post Meta 字段 Schema（`includes/meta-boxes.php`）

**品牌 (`brand`) 字段**（辅助读取函数 `airportcha_get_brand_meta($post_id)`）：
- `_ac_tagline`：一句话定位
- `_ac_rating`：综合评分（1.0–5.0，默认 `4.5`）
- `_ac_price`：起步价格
- `_ac_protocols`：支持协议
- `_ac_nodes`：节点规模
- `_ac_streaming`：流媒体与 AI 解锁
- `_ac_affiliate_url`：官网/推广链接（前端自动加 `target="_blank" rel="nofollow sponsored noopener"`）
- `_ac_coupon`：专属优惠码
- `_ac_sort_order`：推荐排序权重（数字越小越靠前，默认 `10`）
- `_ac_featured`：是否编辑重点推荐（`'1'` 或 `'0'`）
- `_ac_highlights`：核心优势（按行分隔）
- `_ac_best_for`：适用人群

**评测 (`review`) 字段**（辅助读取函数 `airportcha_get_review_meta($post_id)`）：
- `_ac_review_brand_id`：关联品牌 `post_id`（用于双向关联：评测页底部展示品牌卡，品牌详情页自动查询并展示关联评测）
- `_ac_score_overall` / `_ac_score_speed` / `_ac_score_stability` / `_ac_score_value` / `_ac_score_streaming`：5 维评分
- `_ac_pros` / `_ac_cons`：优点与不足（按行分隔）
- `_ac_verdict`：编辑实测总结

**比较 (`comparison`) 字段**（辅助读取函数 `airportcha_get_comparison_meta($post_id)`）：
- `_ac_comp_brand_a` / `_ac_comp_brand_b`：对比品牌 A/B 的 `post_id`
- `_ac_comp_summary`：一句话对比结论

---

## 4. 动态短代码体系（`includes/shortcodes.php`）

- `[airportcha_top_brands limit="6" featured_only="0"]`：按 `_ac_sort_order` 升序动态渲染品牌卡片网格，支持仅显示重点推荐。
- `[airportcha_comparison_table slugs="guangnianti,xingyun-cloud" limit="5"]`：按指定 slug 或排序动态渲染横向参数对比表格。

---

## 5. 全站导航与页面路由层级

| 导航名称 | URL 路径 | 实现方式 | 模板文件 |
| --- | --- | --- | --- |
| 首页 | `/` | 静态首页 (`page_on_front`) | `front-page.php` |
| 机场推荐 | `/recommend/` | Page + 短代码 | `page-templates/template-recommend.php` |
| 品牌目录 | `/brands/` | CPT Archive | `archive-brand.php` |
| 品牌评测 | `/reviews/` | CPT Archive | `archive-review.php` |
| 品牌比较 | `/comparisons/` | CPT Archive | `archive-comparison.php` |
| 博客 | `/blog/` | Page 聚合 `category=blog` | `page-templates/template-category-hub.php` |
| 使用教程 | `/guides/` | Page 聚合 `category=guides` | `page-templates/template-category-hub.php` |
| 网络知识 | `/knowledge/` | Page 聚合 `category=knowledge` | `page-templates/template-category-hub.php` |
| 关于我们 | `/about/` | 标准 Page | `page.php` |
| 全站搜索 | `/?s=...` | 扩展覆盖 `post/brand/review/comparison` | `search.php` |
| 404 页面 | 任意不存在路径 | 标准 404 | `404.php` |
