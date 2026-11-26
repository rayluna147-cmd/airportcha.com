import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { resolve } from 'node:path';

const ROOT = process.cwd();
const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');
const keyFilePath = resolve(ROOT, 'config', 'indexnow-key.txt');
const KEY = (process.env.INDEXNOW_KEY || (existsSync(keyFilePath) ? readFileSync(keyFilePath, 'utf8').trim() : '')).trim();
const DRY_RUN = process.argv.includes('--dry-run') || !KEY;

const manifestPath = resolve(ROOT, 'dist', '_data', 'manifest.json');
const statePath = resolve(ROOT, 'config', 'indexnow-state.json');

if (!existsSync(manifestPath)) {
  console.error('[indexnow] 未找到 dist/_data/manifest.json，请先执行 npm run export');
  process.exit(1);
}

const current = JSON.parse(readFileSync(manifestPath, 'utf8'));
const prevState = existsSync(statePath) ? JSON.parse(readFileSync(statePath, 'utf8')) : { urls: {} };
const prev = prevState.urls || {};

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
  console.log('[indexnow] Dry-Run 模式，不发送外部请求。');
  console.log(changedUrls.slice(0, 10).join('\n'));
  process.exit(0);
}

const keyLocation = `${SITE_URL}/${KEY}.txt`;
console.log(`[indexnow] 正在验证线上密钥文件: ${keyLocation}`);
const verifyRes = await fetch(keyLocation);
const verifyBody = (await verifyRes.text()).trim();
if (verifyRes.status !== 200 || verifyBody !== KEY) {
  console.error(`[indexnow] 线上验证文件不可用或内容不匹配 (HTTP ${verifyRes.status})，中止提交。`);
  process.exit(1);
}
console.log('[indexnow] 线上密钥文件验证通过 (HTTP 200)！');

const host = new URL(SITE_URL).host;
const res = await fetch('https://api.indexnow.org/indexnow', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json; charset=utf-8' },
  body: JSON.stringify({
    host,
    key: KEY,
    keyLocation,
    urlList: changedUrls
  })
});

console.log(`[indexnow] API 真实响应: HTTP ${res.status} ${res.statusText}`);
if (res.status !== 200 && res.status !== 202) {
  const errText = await res.text().catch(() => '');
  console.error('[indexnow] 提交失败:', errText);
  process.exit(1);
}

mkdirSync(resolve(ROOT, 'config'), { recursive: true });
writeFileSync(
  statePath,
  JSON.stringify(
    {
      lastSubmittedAt: new Date().toISOString(),
      lastStatus: res.status,
      submittedCount: changedUrls.length,
      urls: current
    },
    null,
    2
  ),
  'utf8'
);
console.log(`[indexnow] 成功提交 ${changedUrls.length} 个 URL，已更新 config/indexnow-state.json！`);
