# AIRPORTCHA 项目完整交接说明（PROJECT_HANDOVER）

> **交接日期**：2026-11-26  
> **适用对象**：接手本项目的下一任 AI 开发助手 / 工程师  
> **当前阶段**：第一阶段（WordPress 核心架构、定制主题、品牌核心插件、示例数据与本地可运行环境）已完成并通过实际运行验收，可安全进入下一阶段开发。

---

## 1. 快速了解本项目

- **网站名称**：AIRPORTCHA
- **目标域名**：`https://airportcha.com/`
- **网站语言**：简体中文为主，兼顾繁体中文（面向全球中文用户）
- **网站类型**：独立网络科技资讯、机场服务推荐、品牌目录、品牌评测、品牌比较与知识博客平台
- **技术栈**：原生 **WordPress**（PHP 8.0+ / Gutenberg 区块编辑器）+ 定制核心插件 `airportcha-core` + 定制响应式主题 `airportcha`
- **本地运行方式**：基于 Node.js + `@wp-playground/cli@3.1.65`（内置 PHP 8.3 + SQLite + WordPress latest），执行 `npm start` 即可在 `http://127.0.0.1:9400/` 启动完整前后台。
- **生产部署方式**：标准 WordPress（VPS / 宝塔面板 / 主机）或 `docker-compose.yml`（WordPress 6.7 + PHP 8.2 + MariaDB 10.11）。

---

## 2. 新 AI 助手必读文档清单

在开始任何代码修改前，请按以下顺序阅读根目录交接文件：

1. [AGENTS.md](./AGENTS.md) — **最高优先级开发规范与红线约束**
2. [PROJECT_CONTEXT.md](./PROJECT_CONTEXT.md) — 网站定位、业务背景与推广边界
3. [ARCHITECTURE.md](./ARCHITECTURE.md) — WordPress 插件/主题架构、CPT、字段与 URL 路由
4. [DESIGN_SYSTEM.md](./DESIGN_SYSTEM.md) — 配色、排版、组件与响应式规范
5. [DEVELOPMENT_STATUS.md](./DEVELOPMENT_STATUS.md) — 已完成、未完成、实测结果与当前已知问题
6. [SEO_GEO_STRATEGY.md](./SEO_GEO_STRATEGY.md) — Bing SEO、Schema 与 GEO 优化架构
7. [TODO.md](./TODO.md) & [NEXT_STEPS.md](./NEXT_STEPS.md) — 剩余任务优先级与下一阶段执行路线
8. [DEPLOYMENT.md](./DEPLOYMENT.md) — 本地启动与生产部署步骤

---

## 3. 完整项目目录结构（交接前基准）

```text
airportcha.com/
├── .gitignore
├── README.md
├── package.json                                    # 本地 npm start 启动脚本（锁定 @wp-playground/cli@3.1.65）
├── blueprint.json                                  # WordPress Playground 自动初始化蓝图
├── docker-compose.yml                              # 标准 Docker 生产/开发配置（WordPress + MariaDB）
└── wp-content/
    ├── plugins/airportcha-core/                    # 核心业务插件（与主题解耦）
    │   ├── airportcha-core.php                     # 插件主入口与激活/停用钩子
    │   └── includes/
    │       ├── post-types.php                      # CPT (brand/review/comparison) 与分类法注册
    │       ├── meta-boxes.php                      # 原生后台可视化字段表单与读取函数
    │       ├── shortcodes.php                      # 动态短代码 [airportcha_top_brands] / [airportcha_comparison_table]
    │       ├── seo.php                             # Bing SEO、JSON-LD Schema、robots.txt 与 Rank Math/Yoast 冲突抑制
    │       └── seeder.php                          # 一键初始化页面、菜单、分类与测试数据（幂等防覆盖）
    └── themes/airportcha/                          # 定制国际化科技媒体主题
        ├── style.css                               # 主题声明 + 完整响应式 CSS 设计系统
        ├── functions.php                           # 主题特性、菜单位置、资源加载、面包屑
        ├── assets/js/main.js                       # 轻量原生 JS（移动端菜单 + 搜索抽屉）
        ├── header.php / footer.php                 # 公共页头与页脚
        ├── front-page.php                          # 动态首页（100% 读取数据库）
        ├── archive-brand.php / single-brand.php    # 品牌目录（含特性筛选）与品牌详情模板
        ├── archive-review.php / single-review.php  # 品牌评测列表与 5 维评分详情模板
        ├── archive-comparison.php / single-comparison.php # 品牌比较列表与对比详情模板
        ├── page-templates/
        │   ├── template-recommend.php              # 机场推荐榜单页模板 (/recommend/)
        │   └── template-category-hub.php           # 博客/使用教程/网络知识聚合页模板
        └── single.php / page.php / archive.php / search.php / index.php / 404.php
```

---

## 4. 关键历史上下文与已修复问题记录

在上一阶段验收中，已实测发现并修复了以下 3 个关键问题（接手后请勿回退）：
1. **`seeder.php` 幂等性修复**：`ensure_page()` 与 `ensure_cpt()` 在目标内容已存在时直接返回 ID，不再覆盖用户已修改的自定义字段与分类。
2. **`package.json` 版本锁定**：因国内 npm 镜像源（`registry.npmmirror.com`）未同步 `@php-wasm/cli-util@3.1.66`，已将 `@wp-playground/cli` 锁定为实测可用的 `3.1.65`。
3. **`blueprint.json` 移除 `login` step**：避免 `@wp-playground/cli` 对无状态请求产生 302 循环重定向。

---

## 5. 特别提醒

- 当前本地目录**尚未初始化 Git 仓库**（无 `.git` 目录），建议在下一阶段开发前按需执行 `git init` 并关联远程仓库。
- `wp-content/plugins/airportcha-core/includes/seeder.php` 中写入的 `/go/guangnianti/` 等推广跳转链接目前为占位 URL，尚未实现 `/go/` 重定向管理器或替换真实推广链接。
