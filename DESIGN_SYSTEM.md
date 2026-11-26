# UI 与设计系统规范（DESIGN_SYSTEM）

> 对应样式文件：`wp-content/themes/airportcha/style.css`  
> 视觉定位：**国际化网络科技媒体风格**——高级、简洁、克制渐变、充足留白、强可读性。

---

## 1. 品牌配色系统（CSS Variables）

| 变量名 | 色值 | 用途说明 |
| --- | --- | --- |
| `--ac-navy` | `#101B32` | 深空蓝主色：标题、评分胶囊、页脚背景、激活态筛选按钮 |
| `--ac-navy-soft` | `#1B2A4A` | 深空蓝过渡色 |
| `--ac-blue` | `#3789FF` | 科技蓝主色：链接、主交互高亮、分页激活态 |
| `--ac-blue-dark` | `#256EE0` | 科技蓝深色态 |
| `--ac-purple` | `#8067F5` | 科技紫辅色：渐变终点、链接 Hover、重点推荐徽章文字 |
| `--ac-bg` | `#F5F8FD` | 背景白（淡蓝灰）：整站页面底色、输入框底色、交替区块背景 |
| `--ac-surface` | `#FFFFFF` | 纯白卡片/文章容器/顶部吸顶导航背景 |
| `--ac-text` | `#202B3C` | 正文深灰：主阅读正文颜色 |
| `--ac-muted` | `#5C6B82` | 次要说明文字、元信息、时间戳 |
| `--ac-border` | `#E2E9F4` | 卡片描边、分割线、表格边框 |

### 科技感渐变（克制使用）
- **按钮与 Logo 渐变**：`--ac-gradient: linear-gradient(135deg, #3789FF 0%, #8067F5 100%)`
- **Hero 与页面页头渐变**：`--ac-hero-gradient: linear-gradient(135deg, #101B32 0%, #192B4E 55%, #26235C 100%)`

---

## 2. 排版与容器尺寸

- **字体栈**：`-apple-system, BlinkMacSystemFont, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Noto Sans SC", "Segoe UI", Roboto, sans-serif`
- **基础字号与行高**：`16px` / `line-height: 1.75`
- **主容器宽度**：`--ac-container: 1200px`（左右内边距 `24px`）
- **文章阅读窄容器**：`.ac-container-narrow`（`max-width: 820px`，保证中文长文阅读舒适度）
- **圆角规范**：主卡片 `--ac-radius: 14px`，小组件/按钮 `--ac-radius-sm: 8px` / `10px`，徽章/筛选标签 `999px`

---

## 3. 核心公共组件类名

- **顶部导航**：`.ac-header`（吸顶毛玻璃 `backdrop-filter: blur(12px)`）、`.ac-logo`、`.ac-nav`、`.ac-search-drawer`
- **按钮系统**：`.ac-btn` + `.ac-btn-primary`（蓝紫渐变主按钮）/ `.ac-btn-outline`（白底描边按钮）/ `.ac-btn-ghost`（深色背景半透明按钮）/ `.ac-btn-sm`
- **卡片与网格**：`.ac-brand-grid` / `.ac-post-grid`（桌面 3 列、平板 2 列、手机 1 列）、`.ac-two-col`（2 列对比布局）、`.ac-card`、`.ac-brand-card.is-featured`
- **徽章与标签**：`.ac-badge`、`.ac-badge-accent`（编辑重点推荐）、`.ac-chip`（特性/地区标签）、`.ac-rating-pill`（深空蓝评分胶囊）
- **评测与数据展示**：`.ac-score-box`、`.ac-score-row` + `.ac-score-bar` / `.ac-score-fill`（5 维进度条）、`.ac-pros-cons`（绿底 `.ac-box-pros` + 红底 `.ac-box-cons`）、`.ac-table-wrap` + `.ac-table`（可横向滚动对比表）
- **阅读容器**：`.ac-article-wrap` + `.ac-prose`

---

## 4. 响应式断点

- **桌面端**（`> 1024px`）：9 项横向导航完整展开，卡片 3 列网格。
- **平板端**（`<= 1024px`）：导航折叠为「菜单」按钮弹出抽屉，卡片切换为 2 列网格。
- **手机端**（`<= 720px`）：所有网格、优缺点框、页脚切换为单列布局，文章容器内边距收窄为 `24px 20px`。

---

## 5. 设计禁忌（接手开发必须遵守）

- 不要过度使用花哨动画（仅保留克制的 `0.15s–0.18s` Hover 过渡）。
- 不要把首页或全站改成单一产品的营销落地页。
- 不要引入臃肿的外部 CSS 框架（如全量 Bootstrap）破坏当前轻量化样式体系。
