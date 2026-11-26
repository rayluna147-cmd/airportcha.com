import { existsSync, readdirSync, readFileSync, statSync, writeFileSync } from 'node:fs';
import { join, relative, resolve } from 'node:path';

const ROOT = process.cwd();
const DIST = resolve(ROOT, 'dist');
const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');

function walkHtml(dir, list = []) {
  for (const name of readdirSync(dir)) {
    const full = join(dir, name);
    if (statSync(full).isDirectory()) {
      walkHtml(full, list);
    } else if (name.endsWith('.html') && name !== '404.html') {
      list.push(full);
    }
  }
  return list;
}

if (!existsSync(DIST)) {
  console.error('[audit-seo] dist/ 不存在，请先执行 npm run export');
  process.exit(1);
}

const files = walkHtml(DIST);
const urlRegistry = [];
const knownPaths = new Set();
const outEdges = new Map();
const inCount = new Map();

for (const f of files) {
  const rel = relative(DIST, f).replace(/\\/g, '/');
  const route = rel === 'index.html' ? '/' : '/' + rel.replace(/\/index\.html$/, '') + '/';
  knownPaths.add(route);
  inCount.set(route, 0);
}

const brokenInternalLinks = [];

for (const f of files) {
  const rel = relative(DIST, f).replace(/\\/g, '/');
  const route = rel === 'index.html' ? '/' : '/' + rel.replace(/\/index\.html$/, '') + '/';
  const html = readFileSync(f, 'utf8');

  const title = (html.match(/<title[^>]*>([\s\S]*?)<\/title>/i) || [])[1]?.trim() || '';
  const desc = (html.match(/<meta\s+name="description"\s+content="([^"]*)"/i) || [])[1]?.trim() || '';
  const canonical = (html.match(/<link\s+rel="canonical"\s+href="([^"]*)"/i) || [])[1]?.trim() || '';
  const h1 = (html.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [])[1]?.replace(/<[^>]+>/g, '').trim() || '';

  const targets = new Set();
  const linkMatches = html.matchAll(/<a\b[^>]*\bhref=["']([^"'#]+)["']/gi);
  for (const m of linkMatches) {
    const href = m[1].trim();
    let path = '';
    if (href.startsWith(SITE_URL + '/')) {
      path = new URL(href).pathname;
    } else if (href.startsWith('/') && !href.startsWith('//')) {
      path = href.split('?')[0];
    } else {
      continue;
    }
    if (path.startsWith('/wp-content/') || path.startsWith('/wp-admin/') || path.startsWith('/go/') || path.startsWith('/_data/') || path.startsWith('/wp-json/') || path.endsWith('/feed/')) {
      continue;
    }
    const norm = path === '/' ? '/' : '/' + path.replace(/^\/+|\/+$/g, '') + '/';
    if (norm === route) continue;
    targets.add(norm);
    if (knownPaths.has(norm)) {
      inCount.set(norm, (inCount.get(norm) || 0) + 1);
    } else {
      brokenInternalLinks.push({ from: route, to: norm });
    }
  }

  outEdges.set(route, Array.from(targets));
  urlRegistry.push({
    url: `${SITE_URL}${route}`,
    path: route,
    title,
    description: desc,
    h1,
    canonical,
    bytes: Buffer.byteLength(html, 'utf8')
  });
}

// BFS 计算距首页点击深度
const depth = new Map([['/', 0]]);
const queue = ['/'];
while (queue.length) {
  const cur = queue.shift();
  const d = depth.get(cur);
  for (const nxt of outEdges.get(cur) || []) {
    if (knownPaths.has(nxt) && !depth.has(nxt)) {
      depth.set(nxt, d + 1);
      queue.push(nxt);
    }
  }
}

const orphans = [];
const graphNodes = Array.from(knownPaths).sort().map(p => {
  const inc = inCount.get(p) || 0;
  const outc = (outEdges.get(p) || []).length;
  const dp = depth.has(p) ? depth.get(p) : -1;
  if (p !== '/' && inc === 0) orphans.push(p);
  return { path: p, inbound: inc, outbound: outc, clickDepth: dp };
});

writeFileSync(resolve(ROOT, 'URL_REGISTRY.json'), JSON.stringify({ generatedAt: new Date().toISOString(), count: urlRegistry.length, urls: urlRegistry }, null, 2), 'utf8');
writeFileSync(resolve(ROOT, 'INTERNAL_LINK_GRAPH.json'), JSON.stringify({ generatedAt: new Date().toISOString(), pageCount: graphNodes.length, orphanCount: orphans.length, brokenLinkCount: brokenInternalLinks.length, orphans, brokenInternalLinks, nodes: graphNodes }, null, 2), 'utf8');

const recommendNode = graphNodes.find(n => n.path === '/recommend/');
const md = `# 全站 SEO 与内部链接自动审计报告（SEO_AUDIT_FINAL）

> 生成时间：${new Date().toISOString()}

## 1. 审计总览

- **扫描公开页面数**：${graphNodes.length} 页
- **孤立页面数（入站内链 = 0）**：${orphans.length}
- **失效内部链接数**：${brokenInternalLinks.length}
- **核心竞争页 \`/recommend/\` 状态**：入站内链 **${recommendNode ? recommendNode.inbound : 0}**，出站内链 **${recommendNode ? recommendNode.outbound : 0}**，距首页点击深度 **${recommendNode ? recommendNode.clickDepth : -1}**

## 2. 全部公开页面内链分布

| 页面路径 | 入站内链 | 出站内链 | 距首页深度 |
| --- | --- | --- | --- |
${graphNodes.map(n => `| \`${n.path}\` | ${n.inbound} | ${n.outbound} | ${n.clickDepth} |`).join('\n')}
`;

writeFileSync(resolve(ROOT, 'SEO_AUDIT_FINAL.md'), md, 'utf8');
console.log(`[audit-seo] 完成！扫描 ${graphNodes.length} 页 | 孤立页=${orphans.length} | 失效内链=${brokenInternalLinks.length} | /recommend/ 入站内链=${recommendNode ? recommendNode.inbound : 0}`);
