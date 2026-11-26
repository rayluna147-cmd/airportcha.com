# 后续开发执行路线（NEXT_STEPS）

> 面向接手本项目的下一任 AI 助手，建议按以下 4 个阶段稳步推进，每完成一个阶段均在本地 `npm start`（`http://127.0.0.1:9400`）进行实际运行验收。

---

## 阶段一：内容与商业转化闭环（建议作为提示词 2 重点）

1. **实现 `/go/{slug}/` 推广跳转模块**（在 `airportcha-core/includes/` 新增 `redirects.php`）：
   - 自动读取 `brand` 的 `_ac_affiliate_url`（或独立目标链接字段），返回 302 跳转并附加 `X-Robots-Tag: noindex, nofollow`。
   - 记录基础点击次数至 Post Meta（`_ac_outbound_clicks`），在后台品牌列表中可见。
2. **新增单品牌嵌入式卡片短代码 `[airportcha_brand_card slug="guangnianti"]`**：
   - 支持在任意文章/评测/教程中嵌入高转化品牌推荐卡（含评分、核心卖点、优惠码一键复制、详情与官网按钮）。
3. **品牌详情与卡片接入特色图片/Logo**：
   - 在 `single-brand.php`、`archive-brand.php`、`shortcodes.php` 中支持显示品牌 Logo（`post-thumbnail`），未上传时优雅回退至品牌首字渐变徽标。

---

## 阶段二：阅读体验与交互精细化

1. **长文阅读体验升级**：
   - 为 `single.php`、`single-review.php`、`single-comparison.php` 增加自动提取 H2/H3 的侧边/顶部目录（TOC）、阅读时长估算、相关内容推荐。
2. **品牌目录筛选与排序升级**：
   - 在 `archive-brand.php` 中补充按节点地区 (`brand_region`) 筛选与按评分/推荐权重排序。
3. **后台运维体验升级**：
   - 在 WordPress 后台 `brand` / `review` 列表页添加自定义管理列，方便站长一眼查看评分、价格、排序与点击量。

---

## 阶段三：SEO / GEO 深化与繁简兼容

1. 在 `includes/seo.php` 中补齐 `BreadcrumbList` JSON-LD 与 `og:image`。
2. 为评测与教程增加常见问题（FAQ）区块/字段并自动输出 `FAQPage` Schema。
3. 实现轻量级的简体/繁体中文切换体验，兼顾港台及海外繁体中文读者。

---

## 阶段四：生产部署与搜索引擎提交

1. 在目标服务器（VPS / 宝塔 / Docker）部署标准 WordPress + MySQL/MariaDB，上传 `airportcha-core` 与 `airportcha`。
2. 启用 HTTPS、绑定正式域名 `https://airportcha.com/`（Cloudflare DNS 生效后开启代理与缓存规则）。
3. 后台安装 **Rank Math SEO** 与 **IndexNow**，在 **Bing Webmaster Tools** 验证域名并提交 Sitemap。
4. 替换所有占位推广链接为真实链接，正式对外发布。
