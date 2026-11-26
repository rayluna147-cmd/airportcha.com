import { existsSync, readFileSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';

const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');
const KEY = process.env.INDEXNOW_KEY || '';
const DRY_RUN = process.argv.includes('--dry-run') || !KEY;

const manifestPath = resolve(process.cwd(), 'dist', '_data', 'manifest.json');
const prevPath = resolve(process.cwd(), 'reports', 'indexnow-last-manifest.json');

if (!existsSync(manifestPath)) {
  console.error('[indexnow] 未找到 dist/_data/manifest.json，请先执行 npm run export');
  process.exit(1);
}

const current = JSON.parse(readFileSync(manifestPath, 'utf8'));
const prev = existsSync(prevPath) ? JSON.parse(readFileSync(prevPath, 'utf8')) : {};

const changedUrls = [];
for (const [path, info] of Object.entries(current)) {
  if (!prev[path] || prev[path].hash !== info.hash) {
    changedUrls.push(`${SITE_URL}${path}`);
  }
}
for (const oldPath of Object.keys(prev)) {
  if (!current[oldPath]) {
    changedUrls.push(`${SITE_URL}${oldPath}`);
  }
}

console.log(`[indexnow] 检测到 ${changedUrls.length} 个新增/变更/删除的规范 URL`);
if (changedUrls.length === 0) {
  console.log('[indexnow] 无内容变更，跳过重复提交。');
  process.exit(0);
}

if (DRY_RUN) {
  console.log('[indexnow] Dry-Run 模式（未配置 INDEXNOW_KEY 或传入 --dry-run），不发送外部请求。');
  console.log(changedUrls.slice(0, 10).join('\n'));
  process.exit(0);
}

const host = new URL(SITE_URL).host;
const res = await fetch('https://api.indexnow.org/indexnow', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json; charset=utf-8' },
  body: JSON.stringify({
    host,
    key: KEY,
    keyLocation: `${SITE_URL}/${KEY}.txt`,
    urlList: changedUrls
  })
});

console.log(`[indexnow] HTTP ${res.status} ${res.statusText}`);
if (!res.ok) process.exit(1);
writeFileSync(prevPath, JSON.stringify(current, null, 2), 'utf8');
