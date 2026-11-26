import { existsSync, mkdirSync, readdirSync, readFileSync, statSync, writeFileSync } from 'node:fs';
import { join, relative, resolve } from 'node:path';

const ROOT = process.cwd();
const DIST = resolve(ROOT, 'dist');
const SITE_URL = (process.env.SITE_URL || 'https://airportcha.com').replace(/\/$/, '');

const errors = [];
const warnings = [];

function walkHtml(dir, list = []) {
  for (const entry of readdirSync(dir)) {
    const full = join(dir, entry);
    const st = statSync(full);
    if (st.isDirectory()) {
      walkHtml(full, list);
    } else if (entry.endsWith('.html')) {
      list.push(full);
    }
  }
  return list;
}

if (!existsSync(DIST)) {
  console.error('[validate] 错误：dist/ 目录不存在，请先运行 npm run export');
  process.exit(1);
}

// 1. 核心必需文件检查
const requiredFiles = [
  'index.html',
  'recommend/index.html',
  'brands/index.html',
  'brands/guangnianti/index.html',
  'reviews/index.html',
  'comparisons/index.html',
  'blog/index.html',
  'guides/index.html',
  'knowledge/index.html',
  'about/index.html',
  '404.html',
  'sitemap.xml',
  'robots.txt',
  '_headers',
  '_routes.json',
  '_data/affiliates.json',
  '_data/search-index.json',
  'wp-content/themes/airportcha/style.css',
  'wp-content/themes/airportcha/assets/js/main.js'
];

for (const rf of requiredFiles) {
  const full = join(DIST, rf);
  if (!existsSync(full) || statSync(full).size === 0) {
    errors.push(`缺少核心静态产物或文件为空：dist/${rf}`);
  }
}

// 2. 逐页检查 SEO、Canonical、JSON-LD 与本地残留
const htmlFiles = walkHtml(DIST);
const titles = new Map();

for (const file of htmlFiles) {
  const rel = relative(DIST, file).replace(/\\/g, '/');
  const html = readFileSync(file, 'utf8');

  if (/127\.0\.0\.1|localhost:\d+/i.test(html)) {
    errors.push(`${rel}: 检测到本地开发地址残留 (127.0.0.1 / localhost)`);
  }
  if (/<\?php/i.test(html)) {
    errors.push(`${rel}: 检测到未解析的 PHP 代码残留`);
  }

  const titleMatch = html.match(/<title[^>]*>([\s\S]*?)<\/title>/i);
  const title = titleMatch ? titleMatch[1].trim() : '';
  if (!title) {
    errors.push(`${rel}: 缺少 <title>`);
  } else if (rel !== '404.html') {
    if (titles.has(title)) {
      warnings.push(`重复 Title：「${title}」出现在 ${titles.get(title)} 与 ${rel}`);
    } else {
      titles.set(title, rel);
    }
  }

  if (rel !== '404.html') {
    if (!/<h1[\s>]/i.test(html)) {
      errors.push(`${rel}: 缺少 <h1> 标题`);
    }
    if (!/<meta\s+name="description"\s+content="[^"]+"/i.test(html)) {
      errors.push(`${rel}: 缺少 Meta Description`);
    }
    const canonMatch = html.match(/<link\s+rel="canonical"\s+href="([^"]+)"/i);
    if (!canonMatch) {
      errors.push(`${rel}: 缺少 Canonical 链接`);
    } else if (!canonMatch[1].startsWith(SITE_URL)) {
      errors.push(`${rel}: Canonical 未使用正式域名 (${canonMatch[1]})`);
    }
  }

  const ldMatches = html.matchAll(/<script\s+type="application\/ld\+json">([\s\S]*?)<\/script>/gi);
  for (const m of ldMatches) {
    try {
      JSON.parse(m[1]);
    } catch {
      errors.push(`${rel}: JSON-LD 结构化数据语法非法`);
    }
  }

  // 检查页面引用的本地 /wp-content/ CSS、JS、图片文件是否真实存在
  const assetMatches = html.matchAll(/(?:href|src)=["'](\/wp-content\/[^"'?#]+)(?:[?#][^"']*)?["']/gi);
  for (const am of assetMatches) {
    const assetRel = am[1].replace(/^\/+/, '');
    if (!existsSync(join(DIST, assetRel))) {
      errors.push(`${rel}: 引用的静态资源不存在 -> ${am[1]}`);
    }
  }
}

// 3. 检查 affiliates.json 无循环跳转
const affFile = join(DIST, '_data', 'affiliates.json');
if (existsSync(affFile)) {
  const aff = JSON.parse(readFileSync(affFile, 'utf8'));
  for (const [slug, item] of Object.entries(aff)) {
    if (item.targetUrl && /\/go\//i.test(item.targetUrl) && item.targetUrl.includes('airportcha.com')) {
      errors.push(`affiliates.json[${slug}]: 检测到 /go/ 循环跳转`);
    }
  }
}

// 4. 输出报告
const report = {
  checkedAt: new Date().toISOString(),
  htmlPageCount: htmlFiles.length,
  errorCount: errors.length,
  warningCount: warnings.length,
  errors,
  warnings
};

mkdirSync(resolve(ROOT, 'reports'), { recursive: true });
writeFileSync(resolve(ROOT, 'reports', 'seo-validation.json'), JSON.stringify(report, null, 2), 'utf8');

console.log(`\n=== AIRPORTCHA SEO 与发布前质量检查报告 ===`);
console.log(`检查 HTML 页面数: ${htmlFiles.length}`);
console.log(`严重错误 (Errors): ${errors.length}`);
console.log(`优化建议 (Warnings): ${warnings.length}`);

if (errors.length) {
  console.log('\n[严重错误]');
  errors.forEach(e => console.log('  - ' + e));
}
if (warnings.length) {
  console.log('\n[一般提示]');
  warnings.forEach(w => console.log('  - ' + w));
}

if (errors.length > 0) {
  process.exit(1);
}
console.log('\n[PASS] 所有发布前 SEO 与静态产物检查均已通过！');
