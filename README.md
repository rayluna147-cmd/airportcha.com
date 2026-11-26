# AIRPORTCHA (`https://airportcha.com/`) WordPress 完整项目

面向全球中文用户的独立网络科技资讯、机场服务评测与品牌目录 WordPress 平台。

## 架构概览

项目严格遵循 WordPress 长期可维护架构（内容与表现解耦，所有内容均在 WordPress 后台管理，无需改代码）：

- **核心业务插件**：`wp-content/plugins/airportcha-core/`
  - 自定义文章类型：`brand`（`/brands/`）、`review`（`/reviews/`）、`comparison`（`/comparisons/`）
  - 自定义分类法：`brand_feature`（品牌特性）、`brand_region`（节点地区）
  - 原生后台表单（Meta Boxes）：评分、价格、协议、节点规模、解锁情况、推广链接（自动加 `nofollow sponsored`）、优惠码、评测 5 维评分与优缺点
  - 动态短代码：`[airportcha_top_brands]`、`[airportcha_comparison_table]`
  - Bing SEO & Schema：兼容 Rank Math / Yoast SEO，未安装时自动输出 Meta Description、Open Graph、Canonical、JSON-LD 结构化数据与 `robots.txt`
  - 一键初始化工具：后台 **工具 -> AIRPORTCHA 初始化**
- **定制主题**：`wp-content/themes/airportcha/`
  - 国际化网络科技媒体设计（深空蓝 `#101B32` / 科技蓝 `#3789FF` / 紫色 `#8067F5` / 背景白 `#F5F8FD` / 正文深灰 `#202B3C`）
  - 完整适配桌面端、平板端与手机端，兼容 Gutenberg 块编辑器

## 本地一键启动（无需安装 PHP / MySQL / Docker）

本机只需有 Node.js，在项目根目录运行：

```bash
npm start
```

启动后访问：
- **网站前台**：`http://127.0.0.1:9400/`
- **WordPress 后台**：`http://127.0.0.1:9400/wp-admin/`（默认账号 `admin`，密码 `password`）

`blueprint.json` 会自动启用 `airportcha-core` 插件、`airportcha` 主题，并自动导入全部核心页面、导航菜单与测试数据。

## 生产环境部署（VPS / 宝塔面板 / Docker）

### 方式 A：标准 WordPress 主机 / 宝塔面板
1. 在服务器安装标准 WordPress（PHP 8.1+，MySQL/MariaDB），绑定域名 `https://airportcha.com/` 并开启 HTTPS。
2. 将本项目中的以下两个目录上传到服务器的 `wp-content/` 对应位置：
   - `wp-content/plugins/airportcha-core`
   - `wp-content/themes/airportcha`
3. 登录 WordPress 后台：
   - **插件** -> 启用 **AIRPORTCHA Core**
   - **外观 -> 主题** -> 启用 **AIRPORTCHA**
   - **设置 -> 固定链接** -> 选择 **文章名 (`/%postname%/`)** 并保存
   - **工具 -> AIRPORTCHA 初始化** -> 点击一键初始化（创建全部核心页面、导航菜单与示例数据）

### 方式 B：Docker Compose
```bash
docker compose up -d
```
访问 `http://localhost:8080` 完成 WordPress 安装向导后，按方式 A 第 3 步启用插件与主题即可。

## Bing SEO 推荐配置

1. 建议在生产后台安装 **Rank Math SEO**（本主题与插件已自动兼容，不会产生重复 Meta 标签）。
2. 在 **Bing Webmaster Tools** 验证 `https://airportcha.com/` 并提交站点地图：`https://airportcha.com/wp-sitemap.xml`（安装 Rank Math 后为 `/sitemap_index.xml`）。
3. 启用 IndexNow 插件，实现发布新评测/新品牌后秒级推送给 Bing。

## 日常后台维护指南（无需改代码）

- **添加新机场品牌**：后台「品牌目录 -> 添加新品牌」，填写标题、Gutenberg 介绍正文，并在下方「品牌核心参数与推广配置」表单填写评分、价格、协议、官网推广链接与排序权重，发布后首页与 `/brands/`、`/recommend/` 自动更新。
- **发布品牌评测**：后台「品牌评测 -> 撰写新评测」，在下方表单关联对应品牌并填写 5 项评分与优缺点，发布后自动在对应品牌详情页底部关联展示。
- **发布博客 / 教程 / 网络知识**：后台「文章 -> 写文章」，勾选分类（博客 / 使用教程 / 网络知识）即可自动出现在对应栏目页。
