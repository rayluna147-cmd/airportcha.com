/**
 * Cloudflare Pages Function: /go/{slug}/
 * 统一品牌推广跳转 + 安全校验（防开放重定向、防循环跳转）+ 可选 D1 点击统计（无 D1 时优雅降级）
 */
export async function onRequestGet(context) {
  const { params, request, env } = context;
  const slug = String(params.slug || '').toLowerCase().replace(/[^a-z0-9-_]/g, '');
  const url = new URL(request.url);
  const slot = (url.searchParams.get('slot') || 'direct').slice(0, 48);
  const referer = request.headers.get('Referer') || '';

  const headers = {
    'X-Robots-Tag': 'noindex, nofollow',
    'Cache-Control': 'no-store, no-cache, must-revalidate, max-age=0'
  };

  if (!slug) {
    return new Response(null, { status: 302, headers: { ...headers, Location: '/brands/' } });
  }

  let affiliates = {};
  try {
    const assetRes = await env.ASSETS.fetch(new URL('/_data/affiliates.json', url.origin));
    if (assetRes.ok) {
      affiliates = await assetRes.json();
    }
  } catch (_) {}

  const record = affiliates[slug];
  if (!record) {
    return new Response(null, { status: 302, headers: { ...headers, Location: '/brands/' } });
  }

  // 安全校验：禁止非法协议与本站 /go/ 循环跳转
  let safeTarget = '';
  if (record.status === 'active' && typeof record.targetUrl === 'string' && record.targetUrl.trim()) {
    try {
      const parsed = new URL(record.targetUrl.trim());
      const isHttp = parsed.protocol === 'https:' || parsed.protocol === 'http:';
      const isSelfGo = parsed.hostname.endsWith('airportcha.com') && parsed.pathname.startsWith('/go/');
      if (isHttp && !isSelfGo) {
        safeTarget = parsed.toString();
      }
    } catch (_) {}
  }

  if (!safeTarget) {
    return new Response(null, {
      status: 302,
      headers: { ...headers, Location: record.brandUrl || `/brands/${slug}/` }
    });
  }

  // 可选 D1 点击统计（未配置 DB 或写入失败绝不阻断跳转）
  if (env.DB) {
    const day = new Date().toISOString().slice(0, 10);
    const sourcePath = (() => {
      try { return referer ? new URL(referer).pathname.slice(0, 180) : ''; } catch { return ''; }
    })();
    context.waitUntil(
      env.DB.prepare(
        `INSERT INTO outbound_clicks_daily (brand_slug, click_date, slot, source_path, clicks)
         VALUES (?1, ?2, ?3, ?4, 1)
         ON CONFLICT(brand_slug, click_date, slot, source_path)
         DO UPDATE SET clicks = clicks + 1`
      ).bind(slug, day, slot, sourcePath).run().catch(() => {})
    );
  }

  return new Response(null, {
    status: 302,
    headers: { ...headers, Location: safeTarget }
  });
}
