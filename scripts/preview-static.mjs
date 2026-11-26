import http from 'node:http';
import { existsSync, readFileSync, statSync } from 'node:fs';
import { extname, join, resolve } from 'node:path';

const DIST = resolve(process.cwd(), 'dist');
const PORT = Number(process.env.PREVIEW_PORT || 4173);

const MIME = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.xml': 'application/xml; charset=utf-8',
  '.txt': 'text/plain; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.webp': 'image/webp',
  '.svg': 'image/svg+xml'
};

if (!existsSync(DIST)) {
  console.error('[preview] 错误：dist/ 目录不存在，请先运行 npm run export');
  process.exit(1);
}

const server = http.createServer((req, res) => {
  const urlObj = new URL(req.url || '/', `http://127.0.0.1:${PORT}`);
  const pathname = decodeURIComponent(urlObj.pathname);

  // 模拟 Cloudflare Pages Function: /go/{slug}/
  const goMatch = pathname.match(/^\/go\/([^/]+)\/?$/);
  if (goMatch) {
    const slug = goMatch[1];
    const affPath = join(DIST, '_data', 'affiliates.json');
    let target = '/brands/';
    if (existsSync(affPath)) {
      const aff = JSON.parse(readFileSync(affPath, 'utf8'));
      const item = aff[slug];
      if (item && item.status === 'active' && item.targetUrl) {
        target = item.targetUrl;
      } else if (item && item.brandUrl) {
        target = item.brandUrl;
      }
    }
    res.writeHead(302, {
      Location: target,
      'X-Robots-Tag': 'noindex, nofollow',
      'Cache-Control': 'no-store'
    });
    res.end();
    return;
  }

  let filePath = join(DIST, pathname);
  if (existsSync(filePath) && statSync(filePath).isDirectory()) {
    filePath = join(filePath, 'index.html');
  }

  if (existsSync(filePath) && statSync(filePath).isFile()) {
    const ext = extname(filePath).toLowerCase();
    res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream' });
    res.end(readFileSync(filePath));
    return;
  }

  const page404 = join(DIST, '404.html');
  res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
  res.end(existsSync(page404) ? readFileSync(page404) : '<h1>404 Not Found</h1>');
});

server.listen(PORT, '127.0.0.1', () => {
  console.log(`[AIRPORTCHA] 脱离 WordPress 的纯静态预览已启动: http://127.0.0.1:${PORT}/`);
});
