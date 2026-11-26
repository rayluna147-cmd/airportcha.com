# 核心竞争页面 `/recommend/` 策略说明（RECOMMEND_PAGE_STRATEGY）

> 正式 URL：`https://airportcha.com/recommend/`（永久固定，不随年份更改）  
> 核心模板：`wp-content/themes/airportcha/page-templates/template-recommend.php`

## 1. SEO 定位（与首页严格区隔）

- **第一核心关键词**：「机场推荐」
- **独立 SEO Title**：`机场推荐与排行榜：稳定性、价格、套餐及品牌对比 | AIRPORTCHA`
- **独立 H1**：`机场推荐与品牌排行榜`
- **结构化数据**：自动输出 `BreadcrumbList`、`ItemList`（动态读取全部有效品牌）与 `FAQPage`（与页面可见问答 100% 一致）。

## 2. 13 部分内容结构

1. 结论先行摘要框（按场景直给选型结论与避坑原则）
2. WordPress 后台可编辑引导正文（`the_content()`，支持零代码维护）
3. 编辑精选品牌（`[airportcha_placement slot="recommend_rank" limit="3"]`）
4. 综合机场品牌排行榜 + 前端特性筛选与多维排序（`[airportcha_ranking_board]`）
5. 同周期套餐价格横向对比表（`[airportcha_plans_matrix]`）
6. 机场服务选择指南（线路架构、协议兼容、解锁核对）
7. 购买风险与注意事项（避免盲目大额年付、备用线路、商业披露）
8. 高频常见问题（FAQ）
9. 延伸阅读与内部链接矩阵（品牌目录、评测、对比、教程、网络知识）
10. 资料来源与真实更新时间
