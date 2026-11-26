import { spawn } from 'node:child_process';
import { copyFileSync, existsSync, mkdirSync, readdirSync, readFileSync, rmSync, statSync, writeFileSync } from 'node:fs';
import { createHash } from 'node:crypto';
import { join, resolve } from 'node:path';

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

const ROOT = process.cwd();
const DIST = resolve(ROOT, 'dist');
const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');
const WP_PORT = 9405;
const WP_BASE = `http://127.0.0.1:${WP_PORT}`;

async function isReachable(url) {
  try {
    const r = await fetch(url);
    return r.status === 200;
  } catch {
    return false;
  }
}

async function startTempWp() {
  mkdirSync(resolve(ROOT, '.wp-data', 'database'), { recursive: true });
  mkdirSync(resolve(ROOT, '.wp-data', 'uploads'), { recursive: true });

  const child = spawn(
    'npx',
    [
      '--yes',
      '@wp-playground/cli@3.1.65',
      'server',
      `--port=${WP_PORT}`,
      '--blueprint=./blueprint.json',
      '--mount=./wp-content/themes/airportcha:/wordpress/wp-content/themes/airportcha',
      '--mount=./wp-content/plugins/airportcha-core:/wordpress/wp-content/plugins/airportcha-core',
      '--mount=./.wp-data/uploads:/wordpress/wp-content/uploads'
    ],
    { stdio: ['ignore', 'pipe', 'pipe'], shell: true }
  );

  await new Promise((resolveReady, rejectReady) => {
    const timer = setTimeout(() => rejectReady(new Error('WordPress Playground 启动超时')), 120000);
    child.stdout.on('data', d => {
      if (d.toString().includes('Ready!')) {
        clearTimeout(timer);
        resolveReady();
      }
    });
    child.on('exit', code => {
      clearTimeout(timer);
      rejectReady(new Error(`WordPress Playground 提前退出 (code=${code})`));
    });
  });

  return child;
}

async function fetchJson(base, endpoint) {
  const res = await fetch(`${base}${endpoint}`);
  if (!res.ok) return [];
  return res.json();
}

function rewriteHtml(html, wpBase) {
  const escapedBase = wpBase.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const siteHost = new URL(SITE_URL).host;
  const siteEscaped = SITE_URL.replace(/\//g, '\\/');
  const siteEncoded = encodeURIComponent(SITE_URL);
  return html
    .replace(new RegExp(escapedBase, 'g'), SITE_URL)
    .replace(/http%3A%2F%2F127\.0\.0\.1(%3A\d+)?/gi, siteEncoded)
    .replace(/http%3A%2F%2Flocalhost(%3A\d+)?/gi, siteEncoded)
    .replace(/http:\/\/127\.0\.0\.1(:\d+)?/g, SITE_URL)
    .replace(/http:\/\/localhost(:\d+)?/g, SITE_URL)
    .replace(/http:\\\/\\\/127\.0\.0\.1(:\d+)?/g, siteEscaped)
    .replace(/http:\\\/\\\/localhost(:\d+)?/g, siteEscaped)
    .replace(/\/\/127\.0\.0\.1(:\d+)?/g, `//${siteHost}`)
    .replace(/\/\/localhost(:\d+)?/g, `//${siteHost}`)
    .replace(/127\.0\.0\.1(:\d+)?/g, siteHost)
    .replace(new RegExp(`(href|src)=["']${SITE_URL.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}(/wp-content/[^"']+)["']`, 'g'), '$1="$2"')
    .replace(/<link[^>]+href=["'][^"']*\/wp-includes\/blocks\/[^"']+["'][^>]*>\s*/gi, '');
}

function writeRouteHtml(routePath, html) {
  const clean = routePath.replace(/^\/+|\/+$/g, '');
  const targetDir = clean ? join(DIST, clean) : DIST;
  mkdirSync(targetDir, { recursive: true });
  writeFileSync(join(targetDir, 'index.html'), html, 'utf8');
}

function stripTags(str) {
  return String(str || '').replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
}

async function run() {
  let wpBase = process.env.WP_LOCAL_URL || 'http://127.0.0.1:9400';
  let tempProc = null;

  if (!(await isReachable(`${wpBase}/wp-json/`))) {
    console.log('[export] 未检测到运行中的本地 WordPress，正在启动临时导出实例...');
    wpBase = WP_BASE;
    tempProc = await startTempWp();
  } else {
    console.log(`[export] 已连接到运行中的 WordPress: ${wpBase}`);
  }

  try {
    rmSync(DIST, { recursive: true, force: true });
    mkdirSync(join(DIST, '_data'), { recursive: true });

    // 1. 从 WordPress REST API 采集全部公开内容路由
    const [pages, posts, brands, reviews, comparisons] = await Promise.all([
      fetchJson(wpBase, '/wp-json/wp/v2/pages?per_page=100'),
      fetchJson(wpBase, '/wp-json/wp/v2/posts?per_page=100'),
      fetchJson(wpBase, '/wp-json/wp/v2/brand?per_page=100'),
      fetchJson(wpBase, '/wp-json/wp/v2/review?per_page=100'),
      fetchJson(wpBase, '/wp-json/wp/v2/comparison?per_page=100')
    ]);

    const routeSet = new Map();
    const addRoute = (path, lastmod) => {
      const norm = path === '/' ? '/' : '/' + path.replace(/^\/+|\/+$/g, '') + '/';
      if (!routeSet.has(norm)) {
        routeSet.set(norm, lastmod || new Date().toISOString().slice(0, 10));
      }
    };

    addRoute('/');
    addRoute('/brands/');
    addRoute('/reviews/');
    addRoute('/comparisons/');

    const searchIndex = [];
    const registerItems = (items, typeLabel) => {
      for (const item of items) {
        if (!item?.link) continue;
        const u = new URL(item.link);
        if (u.pathname === '/sample-page/' || u.pathname === '/hello-world/') continue;
        const mod = (item.modified || item.date || '').slice(0, 10);
        addRoute(u.pathname, mod);
        if (u.pathname !== '/') {
          searchIndex.push({
            title: stripTags(item.title?.rendered),
            excerpt: stripTags(item.excerpt?.rendered).slice(0, 140),
            url: u.pathname,
            type: typeLabel
          });
        }
      }
    };

    registerItems(pages, '页面');
    registerItems(posts, '文章');
    registerItems(brands, '品牌');
    registerItems(reviews, '评测');
    registerItems(comparisons, '对比');

    // 2. 导出所有公开 HTML 页面
    const manifest = {};
    for (const [routePath, lastmod] of routeSet.entries()) {
      const res = await fetch(`${wpBase}${routePath}`);
      if (!res.ok) {
        console.warn(`[export] 跳过异常路由 ${routePath} (HTTP ${res.status})`);
        continue;
      }
      const rawHtml = await res.text();
      const finalHtml = rewriteHtml(rawHtml, wpBase);
      writeRouteHtml(routePath, finalHtml);
      manifest[routePath] = {
        lastmod,
        hash: createHash('sha256').update(finalHtml).digest('hex').slice(0, 16)
      };
      console.log(`[export] OK ${routePath}`);
    }

    // 3. 导出 404.html
    const res404 = await fetch(`${wpBase}/__airportcha_404_probe__/`);
    const html404 = rewriteHtml(await res404.text(), wpBase);
    writeFileSync(join(DIST, '404.html'), html404, 'utf8');

    // 4. 拷贝主题静态资源与 uploads 图片
    const themeDst = join(DIST, 'wp-content', 'themes', 'airportcha');
    mkdirSync(themeDst, { recursive: true });
    copyFileSync(resolve(ROOT, 'wp-content', 'themes', 'airportcha', 'style.css'), join(themeDst, 'style.css'));
    copyDirSafe(resolve(ROOT, 'wp-content', 'themes', 'airportcha', 'assets'), join(themeDst, 'assets'));

    const uploadsSrc = resolve(ROOT, '.wp-data', 'uploads');
    if (existsSync(uploadsSrc)) {
      copyDirSafe(uploadsSrc, join(DIST, 'wp-content', 'uploads'));
    }

    // 5. 生成品牌推广跳转映射表 (_data/affiliates.json) 供 Cloudflare Pages Functions 使用
    const affiliates = {};
    for (const b of brands) {
      const affInfo = b.ac_affiliate || {};
      affiliates[b.slug] = {
        name: stripTags(b.title?.rendered),
        brandUrl: `/brands/${b.slug}/`,
        targetUrl: affInfo.targetUrl || '',
        status: affInfo.status || 'pending'
      };
    }
    writeFileSync(join(DIST, '_data', 'affiliates.json'), JSON.stringify(affiliates, null, 2), 'utf8');
    writeFileSync(join(DIST, '_data', 'search-index.json'), JSON.stringify(searchIndex, null, 2), 'utf8');
    writeFileSync(join(DIST, '_data', 'manifest.json'), JSON.stringify(manifest, null, 2), 'utf8');

    // 6. 生成正式 sitemap.xml 与 robots.txt
    const sitemapUrls = Object.entries(manifest)
      .map(([p, info]) => `  <url><loc>${SITE_URL}${p}</loc><lastmod>${info.lastmod}</lastmod></url>`)
      .join('\n');
    const sitemapXml = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${sitemapUrls}\n</urlset>\n`;
    writeFileSync(join(DIST, 'sitemap.xml'), sitemapXml, 'utf8');

    const robotsTxt = `User-agent: *\nDisallow: /wp-admin/\nDisallow: /go/\nDisallow: /?s=\nSitemap: ${SITE_URL}/sitemap.xml\n`;
    writeFileSync(join(DIST, 'robots.txt'), robotsTxt, 'utf8');

    // 6.5 拷贝持久化 IndexNow 公开验证密钥文件至 dist/<KEY>.txt
    const keyFile = resolve(ROOT, 'config', 'indexnow-key.txt');
    if (existsSync(keyFile)) {
      const keyVal = readFileSync(keyFile, 'utf8').trim();
      if (/^[a-zA-Z0-9-]{8,128}$/.test(keyVal)) {
        writeFileSync(join(DIST, `${keyVal}.txt`), keyVal, 'utf8');
      }
    }

    // 7. 生成 Cloudflare Pages _headers 与 _routes.json
    const headersTxt = `/*\n  X-Content-Type-Options: nosniff\n  X-Frame-Options: SAMEORIGIN\n  Referrer-Policy: strict-origin-when-cross-origin\n\n/wp-content/*\n  Cache-Control: public, max-age=31536000, immutable\n\n/_data/*\n  Cache-Control: public, max-age=300\n`;
    writeFileSync(join(DIST, '_headers'), headersTxt, 'utf8');

    const routesJson = {
      version: 1,
      include: ['/go/*', '/api/*'],
      exclude: []
    };
    writeFileSync(join(DIST, '_routes.json'), JSON.stringify(routesJson, null, 2), 'utf8');

    console.log(`\n[export] 完成！共导出 ${Object.keys(manifest).length} 个静态页面至 dist/`);
  } finally {
    if (tempProc) {
      tempProc.kill();
      setTimeout(() => process.exit(0), 500);
    }
  }
}

run().catch(err => {
  console.log('[export] 失败:', err && err.stack ? err.stack : err);
  process.exitCode = 1;
});
