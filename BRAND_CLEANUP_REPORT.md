# 非指定品牌清理报告（BRAND_CLEANUP_REPORT）

> 执行日期：2026-11-26

## 1. 保留的正式白名单品牌（6 个）
- 光年梯（`guangnianti`）
- 极连云（`jilianyun`）
- 快狸（`kuaili`）
- 可信云（`kexinyun`）
- 边缘节点（`edgenova`）
- 速界（`speedworld`）

## 2. 已彻底移除的非白名单示例品牌与内容
- 移除品牌：`xingyun-cloud`（星云加速）、`aurora-net`（极光网络）、`bluewhale-link`（蓝鲸专线）、`quantum-node`（量子节点）
- 移除旧示例评测：`xingyun-cloud-review`、`aurora-net-review`
- 移除旧示例对比：`guangnianti-vs-xingyun`、`iplc-airports-comparison-2026`
- 清理原因：以上为第一阶段架构测试用的虚拟示例品牌，不属于用户授权的正式运营品牌白名单。

## 3. 防恢复与验证结果
- `seeder.php` 已全面切换为仅初始化 6 个正式白名单品牌；
- `scripts/validate-brands.mjs` 已纳入 `npm run validate` 与 `npm run build` 强校验，实测确认 `dist/brands/` 与 `dist/_data/affiliates.json` **零非白名单品牌残留**。
