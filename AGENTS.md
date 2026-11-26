# AIRPORTCHA AI 开发助手持续工作规范（AGENTS.md）

> 本文件供所有后续接手本项目的 AI 编程助手在每次任务开始前读取并严格遵守。

---

## 1. 十大永久开发原则（不可违背）

1. **仅运营用户明确提供及授权的机场品牌**：当前正式白名单严格为 **光年梯、极连云、快狸、可信云、边缘节点、速界**（6 个品牌），未经用户授权不得擅自新增其他机场品牌。
2. **光年梯是当前核心主推品牌（`PRIMARY`），但不是唯一合作品牌**：严禁将网站改成光年梯官网或单一产品销售落地页。
3. **每个正式品牌拥有独立档案及独立深度评测**：严禁模板批量替换或虚构测速/套餐数据，真实资料充足时目标正文 ≥ 2000 中文字符。
4. **所有推广链接必须使用用户提供的真实地址**：通过 `/go/{brand-slug}/` 与 `dist/_data/affiliates.json` 统一管理，完整保留邀请码（`code=` 等参数），商业外链必须保留 `rel="nofollow sponsored noopener"`。
5. **禁止恢复已经清理的示例品牌**：严禁恢复星云加速、极光网络、蓝鲸专线、量子节点等旧示例数据。
6. **所有内容必须保存在可持续管理的数据系统中**：保持 WordPress 原生文章/页面/CPT/Post Meta 与插件/主题解耦架构。
7. **所有终端、构建及测试操作由 AI 开发助手自动执行**：不得要求用户手动输入终端命令或手动修改代码。
8. **每个阶段必须进行静态资源完整性校验与真实浏览器视觉验收**：双模式维护 `dist/`（正式部署）与 `dist-offline/`（本地离线内联样式预览）。
9. **正式部署使用 GitHub + Cloudflare Pages**：WordPress 内容管理 → `npm run build` 导出 `dist/` → GitHub → Cloudflare Pages + Pages Functions + D1。
10. **全站第一核心关键词为「机场推荐」**（核心承接页 `/recommend/`），长期目标为争取 **Microsoft Bing 中文搜索自然排名第一**。

---

## 2. 路由与安全红线

- 严禁随意更改 9 个核心 URL：`/`、`/recommend/`、`/brands/`、`/reviews/`、`/comparisons/`、`/blog/`、`/guides/`、`/knowledge/`、`/about/`。
- `.wp-data/`、`.env`、`dist-offline/`、`reports/` 及任何敏感凭证严禁提交至 GitHub。
- 保留 `package.json` 中 `@wp-playground/cli@3.1.65` 版本锁定与 `airportcha_has_external_seo_plugin()` 检测。
