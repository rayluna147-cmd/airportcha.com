import { existsSync, readdirSync, readFileSync, statSync } from 'node:fs';
import { join, resolve } from 'node:path';

const DIST = resolve(process.cwd(), 'dist');

const WHITELIST = {
  guangnianti: { name: '光年梯', expectedCode: 'FRrxGbbY' },
  jilianyun: { name: '极连云', expectedCode: '6aTAi9bW' },
  kuaili: { name: '快狸', expectedCode: 'AFR8PsiY' },
  kexinyun: { name: '可信云', expectedCode: '4PQnpXx6' },
  edgenova: { name: '边缘节点', expectedCode: '4wmwcLDL' },
  speedworld: { name: '速界', expectedCode: '7s0EsI7w' }
};

const errors = [];

// 1. 严格检查 dist/brands/ 下是否仅包含白名单品牌
const brandsDir = join(DIST, 'brands');
const exportedBrands = readdirSync(brandsDir).filter(name => statSync(join(brandsDir, name)).isDirectory());

for (const slug of exportedBrands) {
  if (!WHITELIST[slug]) {
    errors.push(`发现非白名单品牌目录残留：dist/brands/${slug}/`);
  }
}

// 2. 检查 dist/_data/affiliates.json 映射与邀请参数保留
const affPath = join(DIST, '_data', 'affiliates.json');
const affiliates = existsSync(affPath) ? JSON.parse(readFileSync(affPath, 'utf8')) : {};

console.log('\n=== AIRPORTCHA Phase 04 品牌白名单、评测字数与推广链接验收 ===');
for (const [slug, info] of Object.entries(WHITELIST)) {
  const brandHtml = join(DIST, 'brands', slug, 'index.html');
  const reviewHtml = join(DIST, 'reviews', `${slug}-review`, 'index.html');

  if (!existsSync(brandHtml)) {
    errors.push(`缺少白名单品牌档案页：/brands/${slug}/`);
  }
  if (!existsSync(reviewHtml)) {
    errors.push(`缺少白名单品牌评测页：/reviews/${slug}-review/`);
    continue;
  }

  // 统计评测正文纯中文字符数（去除 HTML 标签与短代码卡片）
  const raw = readFileSync(reviewHtml, 'utf8');
  const bodyMatch = raw.match(/id="ac-review-body">([\s\S]*?)<\/div>\s*(?:<div class="ac-score-box"|<\/article>)/i);
  const bodyHtml = bodyMatch ? bodyMatch[1].replace(/<article[\s\S]*?<\/article>/gi, '') : '';
  const plainText = bodyHtml.replace(/<[^>]+>/g, '').replace(/\s+/g, '');
  const zhCount = (plainText.match(/[\u4e00-\u9fa5]/g) || []).length;

  const aff = affiliates[slug];
  const affOk = aff && aff.status === 'active' && aff.targetUrl && aff.targetUrl.includes(info.expectedCode);
  if (!affOk) {
    errors.push(`品牌 ${info.name} (${slug}) 推广链接未生效或缺少邀请参数 ${info.expectedCode}`);
  }

  if (zhCount < 2000) {
    errors.push(`品牌 ${info.name} (${slug}) 评测正文中文字符不足 2000 字（当前 ${zhCount} 字）`);
  }

  console.log(`- ${info.name} (${slug}): 档案=OK | 评测正文中文字符=${zhCount} | 推广链接=${affOk ? 'ACTIVE (code保留)' : 'FAIL'}`);
}

if (errors.length) {
  console.log('\n[品牌验收失败]');
  errors.forEach(e => console.log('  - ' + e));
  process.exit(1);
}

console.log('\n[PASS] 全部 6 个正式白名单品牌、深度评测与推广链接验收通过，零非白名单品牌残留！');
