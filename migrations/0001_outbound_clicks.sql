-- AIRPORTCHA Cloudflare D1 商业推广点击聚合统计表
-- 不保存任何用户个人身份信息（无 IP、无 Cookie、无指纹）

CREATE TABLE IF NOT EXISTS outbound_clicks_daily (
  brand_slug TEXT NOT NULL,
  click_date TEXT NOT NULL,
  slot TEXT NOT NULL DEFAULT 'direct',
  source_path TEXT NOT NULL DEFAULT '',
  clicks INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (brand_slug, click_date, slot, source_path)
);

CREATE INDEX IF NOT EXISTS idx_outbound_clicks_date ON outbound_clicks_daily (click_date);
CREATE INDEX IF NOT EXISTS idx_outbound_clicks_brand ON outbound_clicks_daily (brand_slug);
