import { existsSync, readFileSync } from 'node:fs';
import { resolve } from 'node:path';

const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');
const KEY = process.env.INDEXNOW_KEY || '';
const DRY_RUN = process.argv.includes('--dry-run') || !KEY;

const manifestPath = resolve(process.cwd(), 'dist', '_data', 'manifest.json');
if (!existsSync(manifestPath)) {
  console.error('[indexnow] 未找到 dist/_data/manifest.json，请先执行 npm run export');
  process.exit(1);
}

const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
const urlList = Object.keys(manifest).map(p => `${SITE_URL}${p}`);

console.log(`[indexnow] 准备提交 ${urlList.length} 个规范 URL 至 Bing / IndexNow`);
if (DRY_RUN) {
  console.log('[indexnow] 当前为 Dry-Run 模式（未配置 INDEXNOW_KEY 或传入 --dry-run），不发送外部请求。');
  console.log(urlList.slice(0, 10).join('\n'));
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
    urlList
  })
});

console.log(`[indexnow] HTTP ${res.status} ${res.statusText}`);
if (!res.ok) process.exit(1);
