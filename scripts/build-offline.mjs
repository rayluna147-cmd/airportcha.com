import { copyFileSync, existsSync, mkdirSync, readdirSync, readFileSync, rmSync, statSync, writeFileSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';

const ROOT = process.cwd();
const DIST = resolve(ROOT, 'dist');
const OFFLINE = resolve(ROOT, 'dist-offline');
const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');

function copyDirSafe(src, dst) {
  if (!existsSync(src)) return;
  mkdirSync(dst, { recursive: true });
  for (const name of readdirSync(src)) {
    const s = join(src, name);
    const d = join(dst, name);
    if (statSync(s).isDirectory()) {
      copyDirSafe(s, d);
    } else {
      copyFileSync(s, d);
    }
  }
}

function walkHtml(dir, list = []) {
  for (const name of readdirSync(dir)) {
    const full = join(dir, name);
    if (statSync(full).isDirectory()) {
      walkHtml(full, list);
    } else if (name.endsWith('.html')) {
      list.push(full);
    }
  }
  return list;
}

if (!existsSync(DIST)) {
  console.error('[offline] 错误：dist/ 不存在，请先运行 npm run export');
  process.exit(1);
}

rmSync(OFFLINE, { recursive: true, force: true });
copyDirSafe(DIST, OFFLINE);

const affPath = join(DIST, '_data', 'affiliates.json');
const affiliates = existsSync(affPath) ? JSON.parse(readFileSync(affPath, 'utf8')) : {};

const htmlFiles = walkHtml(OFFLINE);
const escapedSite = SITE_URL.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

for (const file of htmlFiles) {
  const relDir = relative(OFFLINE, dirname(file)).replace(/\\/g, '/');
  const depth = relDir === '' ? 0 : relDir.split('/').length;
  const prefix = depth === 0 ? './' : '../'.repeat(depth);

  let html = readFileSync(file, 'utf8');

  // 1. 转换 /wp-content/ 静态资源为相对路径（支持 file:// 加载 CSS/JS/图片）
  html = html.replace(/(href|src)=["']\/wp-content\/([^"']+)["']/gi, `$1="${prefix}wp-content/$2"`);

  // 2. 离线模式下将 /go/{slug}/ 转换为真实外部推广链接或品牌档案页，方便本地预览体验
  html = html.replace(
    new RegExp(`href=["'](?:${escapedSite})?\\/go\\/([^\\/"'?]+)\\/?(?:\\?[^"']*)?["']`, 'gi'),
    (_, slug) => {
      const rec = affiliates[slug];
      if (rec && rec.status === 'active' && rec.targetUrl) {
        return `href="${rec.targetUrl}"`;
      }
      return `href="${prefix}brands/${slug}/index.html"`;
    }
  );

  // 3. 转换站内页面链接（https://airportcha.com/... 或 /...）为相对 index.html 路径，支持 file:// 跳转
  html = html.replace(
    new RegExp(`href=["']${escapedSite}(\\/[^"'#?]*)?(\\?[^"'#]*)?(#[^"']*)?["']`, 'gi'),
    (match, pathPart = '/', query = '', hash = '') => {
      if (pathPart.startsWith('/wp-content/') || pathPart.startsWith('/wp-admin/') || pathPart.startsWith('/wp-json/')) {
        return match;
      }
      const clean = pathPart.replace(/^\/+|\/+$/g, '');
      if (!clean) {
        return `href="${prefix}index.html${query}${hash}"`;
      }
      if (/\.[a-z0-9]+$/i.test(clean)) {
        return `href="${prefix}${clean}${query}${hash}"`;
      }
      return `href="${prefix}${clean}/index.html${query}${hash}"`;
    }
  );

  writeFileSync(file, html, 'utf8');
}

console.log(`[offline] 完成！已生成 ${htmlFiles.length} 个支持 file:// 直接双击预览的离线页面至 dist-offline/`);
