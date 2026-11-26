# Pillar-Cluster 专题与内部链接架构图（CONTENT_CLUSTER_MAP）

## 1. 全站内容层级模型

```text
首页 (/) — 品牌定位、核心栏目入口、商业精选
  ├── 一级核心承接页：机场推荐 (/recommend/) ← 第一核心词「机场推荐」
  ├── 品牌数据库中心：品牌目录 (/brands/) → 品牌详情 (/brands/{slug}/) → /go/{slug}/
  ├── 深度评测中心：品牌评测 (/reviews/) → 评测详情 (/reviews/{slug}/) ↔ 双向关联品牌档案
  ├── 横向对比中心：品牌比较 (/comparisons/) → 对比详情 (/comparisons/{slug}/) ↔ 关联品牌 A/B
  └── 知识与教程集群：
        ├── 博客资讯 (/blog/) — 选购指南与行业趋势
        ├── 使用教程 (/guides/) — Clash / Shadowrocket / 多平台客户端教程
        └── 网络知识 (/knowledge/) — IPLC/IEPL、Trojan/VLESS/Hysteria2 原理
```

## 2. 内链约束原则

- 每篇深度评测自动通过 `_ac_review_brand_id` 与对应品牌详情页双向关联。
- 品牌详情页通过 `related_reviews` 自动回链至对应评测报告。
- 博客、教程、知识文章中通过 `[airportcha_brand_card slug="..."]` 按内容相关性嵌入品牌卡片，避免全站机械重复同一锚文本。
