# 构建、运行与部署说明（DEPLOYMENT）

> 本项目为原生 WordPress 主题与插件，**无需前端打包构建步骤**（无 Webpack/Vite 编译依赖），可直接运行或上传至任意标准 WordPress 环境。

---

## 1. 本地免安装运行（推荐本地开发预览）

### 前置条件
- 已安装 **Node.js**（实测版本 `v24.13.1`）。无需安装本地 PHP、MySQL 或 Docker。

### 启动命令
在项目根目录运行：

```bash
npm start
```

- 实际执行：`npx --yes @wp-playground/cli@3.1.65 server --port=9400 --blueprint=./blueprint.json --mount=./wp-content/themes/airportcha:/wordpress/wp-content/themes/airportcha --mount=./wp-content/plugins/airportcha-core:/wordpress/wp-content/plugins/airportcha-core`
- 等待终端出现 `Ready! WordPress is running on http://127.0.0.1:9400` 即可访问：
  - **网站前台**：`http://127.0.0.1:9400/`
  - **WordPress 后台**：`http://127.0.0.1:9400/wp-admin/`（使用 WordPress Playground 默认本地测试管理员账号登录，生产环境请务必设置强密码）

> **注意**：WordPress Playground 为临时/内存运行环境，重启 `npm start` 后数据库会按 `blueprint.json` 重新初始化，适合开发调试与实时预览，**不用于持久化存储生产数据**。

---

## 2. Docker 本地/服务器部署（支持 MySQL 持久化）

### 前置条件
- 已安装 Docker 与 Docker Compose。

### 启动步骤
1. 修改 `docker-compose.yml` 中的数据库连接环境变量（`MYSQL_ROOT_PASSWORD`、`MYSQL_PASSWORD`、`WORDPRESS_DB_PASSWORD`），**生产环境务必替换为强密码**，或改用 `.env` 文件注入。
2. 在项目根目录执行：
   ```bash
   docker compose up -d
   ```
3. 浏览器访问 `http://localhost:8080`（或服务器 IP:8080）完成 WordPress 安装向导。
4. 登录后台完成以下 4 步初始化：
   - **插件** -> 启用 **AIRPORTCHA Core**
   - **外观 -> 主题** -> 启用 **AIRPORTCHA**
   - **设置 -> 固定链接** -> 选择 **文章名 (`/%postname%/`)** 并保存
   - **工具 -> AIRPORTCHA 初始化** -> 点击「一键初始化页面、菜单与示例数据」

---

## 3. 标准生产主机 / VPS / 宝塔面板部署

### 服务器环境要求
- PHP **8.1+**（推荐 PHP 8.2 / 8.3）
- MySQL **8.0+** 或 MariaDB **10.6+**
- Nginx 或 Apache（需开启 URL 重写 `mod_rewrite` / Nginx `try_files`）
- 已绑定域名 `https://airportcha.com/` 并配置 SSL/HTTPS 证书

### 部署步骤
1. 在服务器部署标准中文版 WordPress。
2. 将本项目中的两个核心目录上传至服务器对应路径：
   - `wp-content/plugins/airportcha-core` -> `<WP根目录>/wp-content/plugins/airportcha-core`
   - `wp-content/themes/airportcha` -> `<WP根目录>/wp-content/themes/airportcha`
3. 登录 `https://airportcha.com/wp-admin/`，按第 2 节第 4 步启用插件、主题、设置固定链接并运行一键初始化。
4. **Nginx 伪静态规则参考**（宝塔面板可直接在站点设置中选择 `wordpress` 伪静态模板）：
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$args;
   }
   ```

---

## 4. 上线后必做检查清单

- [ ] 在 WordPress 后台「品牌目录」中将所有占位推广链接替换为真实可用链接。
- [ ] 安装并配置 **Rank Math SEO** 与 **IndexNow** 插件。
- [ ] 在 Cloudflare 确认 `airportcha.com` 的 NS 名称服务器已生效，并开启 HTTPS（Full / Strict）。
- [ ] 在 **Bing Webmaster Tools** 验证域名并提交站点地图（`/wp-sitemap.xml` 或 Rank Math 的 `/sitemap_index.xml`）。
- [ ] 配置定期数据库与 `wp-content/` 自动备份。
