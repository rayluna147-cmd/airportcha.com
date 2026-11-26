# 全站 SEO 与 GEO 策略架构（SEO_GEO_STRATEGY）

> 核心实现文件：`wp-content/plugins/airportcha-core/includes/seo.php`  
> 核心目标：面向 **Bing 中文搜索** 与 **AI 搜索引擎（GEO：Generative Engine Optimization）** 获取持续自然流量。

---

## 1. 已实现的技术 SEO 基础

1. **与专业 SEO 插件零冲突**：
   - `airportcha_has_external_seo_plugin()` 自动检测 `RANK_MATH_VERSION` 与 `WPSEO_VERSION`。
   - 生产环境安装 **Rank Math SEO** 或 **Yoast SEO** 后，内置 Meta 标签自动让位，避免重复输出 `<meta name="description">` 和 `<link rel="canonical">`。
2. **基础 Meta 与社交标签（未装第三方插件时自动接管）**：
   - 自动按页面类型（首页、品牌目录、品牌评测、品牌比较、文章/详情页）生成不超过 155 字符的中文 `<meta name="description">`。
   - 自动输出 `<link rel="canonical">`、`og:site_name`、`og:title`、`og:description`、`og:url`、`og:type`、`og:locale="zh_CN"`、`twitter:card`。
3. **JSON-LD 结构化数据**：
   - 首页：`WebSite`（含 `SearchAction` 站内搜索声明）+ `Organization`
   - 品牌评测页 (`review`)：`Review`（含 `itemReviewed`、`reviewRating`、`datePublished`、`dateModified`）
   - 博客文章与品牌比较：`Article`
4. **抓取与索引引导**：
   - 启用 WordPress 原生 `/wp-sitemap.xml`（已验证 HTTP 200）。
   - 自定义 `/robots.txt`：禁止抓取 `/wp-admin/` 与站内搜索结果页 `/?s=`，并显式声明 `Sitemap: https://airportcha.com/wp-sitemap.xml`。
5. **外链合规**：
   - 所有品牌推广外链均自动附加 `rel="nofollow sponsored noopener"`，符合 Bing 与 Google 对联盟推广链接的合规要求。

---

## 2. Bing SEO 专项优化策略（待上线执行）

1. **Bing Webmaster Tools 验证与提交**：
   - 正式域名上线后，立即在 Bing 站长平台验证 `https://airportcha.com/`，提交 Sitemap。
2. **IndexNow 秒级推送**：
   - 生产环境建议安装官方 **IndexNow** 插件（或启用 Rank Math 内置 Instant Indexing），发布新品牌/新评测后自动通知 Bing。
3. **关键词与 URL 架构**：
   - 核心栏目与详情页采用简洁英文语义化 slug（如 `/brands/guangnianti/`、`/reviews/guangnianti-review/`），避免中文 URL 编码过长导致分享和抓取异常。
   - 标题（H1/Title）包含年份、核心词与用户搜索意图（如「光年梯深度评测（2026）：晚高峰速度、IPLC 稳定性与 AI 解锁实测」）。

---

## 3. GEO（生成式 AI 搜索优化）策略

针对 ChatGPT Search、Perplexity、Bing Copilot 等 AI 引擎的引用偏好：
1. **结构化结论前置**：
   - 每篇评测与品牌对比都在顶部提供「5 维评分卡 + 一句话编辑总结 + 优缺点列表」，便于 AI 直接提取事实摘要。
2. **实体与参数表格化**：
   - 品牌详情页与对比页使用语义化 HTML `<table>` 和参数列表展示价格、协议、节点数、流媒体/AI 解锁状态，大幅提升 AI 抽取准确率。
3. **独立客观背书与时间戳**：
   - 显式展示「发布日期」与「最近更新日期」（`get_the_modified_date()`），并保留独立评测披露声明，提升权威度与可信度（E-E-A-T）。

---

## 4. 后续待补齐的 SEO/GEO 能力

- 补充 **`BreadcrumbList` JSON-LD** 结构化数据。
- 在评测与教程页支持 **`FAQPage` Schema**（常见问题模块）。
- 为品牌与文章补充 `og:image`（特色图片或默认社交分享卡片图）。
- 若引入繁体中文支持，需规范配置 `hreflang`（`zh-Hans` / `zh-Hant`）避免重复内容惩罚。
