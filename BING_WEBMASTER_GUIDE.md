# Bing Webmaster Tools 与 Google Search Console 接入指南（BING_WEBMASTER_GUIDE）

> 说明：因涉及站长平台账号登录与 DNS 记录配置，需你在正式域名 `https://airportcha.com/` 生效后按以下步骤完成授权绑定。

## 1. Bing Webmaster Tools 接入步骤
1. 访问 [Bing Webmaster Tools](https://www.bing.com/webmasters) 登录 Microsoft 账号，添加站点 `https://airportcha.com/`。
2. 选择 **DNS 验证（CNAME/TXT）**（推荐，直接在 Cloudflare DNS 添加一条记录即可，无需修改代码）。
3. 验证通过后，在 **Sitemaps** 提交：`https://airportcha.com/sitemap.xml`。
4. 在 **URL Inspection** 中优先检查 `https://airportcha.com/` 与 `https://airportcha.com/recommend/`。

## 2. Google Search Console 接入步骤
1. 访问 [Google Search Console](https://search.google.com/search-console) 添加资源 `airportcha.com`（通过 Cloudflare DNS TXT 验证）。
2. 提交站点地图：`https://airportcha.com/sitemap.xml`。
