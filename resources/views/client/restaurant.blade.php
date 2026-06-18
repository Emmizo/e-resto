@extends('layouts.client')
@section('title', $restaurant->name)

@section('style')
<style>
/* ═══ Base ══════════════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; }
.rp { background: #f7f7f5; min-height: calc(100vh - 56px); }

/* ═══ Hero ══════════════════════════════════════════════════════ */
.rp-hero { position:relative; height:280px; overflow:hidden; background:linear-gradient(135deg,#0f3d45,#1a3a5c); }
.rp-hero img { position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;filter:brightness(.6); }
.rp-hero .overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.88) 0%,rgba(0,0,0,.15) 55%,transparent 100%); }
.rp-hero .back-btn {
    position:absolute;top:16px;left:16px;z-index:10;width:40px;height:40px;border-radius:50%;border:0;
    background:rgba(255,255,255,.18);backdrop-filter:blur(10px);color:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;transition:background .15s;
}
.rp-hero .back-btn:hover { background:rgba(255,255,255,.32);color:#fff; }
.rp-hero .fav-btn-hero {
    position:absolute;top:16px;right:16px;z-index:10;width:40px;height:40px;border-radius:50%;border:0;
    background:rgba(255,255,255,.18);backdrop-filter:blur(10px);color:#fff;
    display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;
}
.rp-hero .fav-btn-hero:hover { background:rgba(255,255,255,.32); }
.rp-hero .hero-content { position:absolute;bottom:0;left:0;right:0;padding:22px 28px;z-index:5; }
.rp-hero .r-name { color:#fff;font-size:1.75rem;font-weight:800;line-height:1.15;margin-bottom:8px;letter-spacing:-.03em; }
.rp-hero .r-meta-row { display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:0.79rem;color:rgba(255,255,255,.75); }
.rp-hero .r-meta-row .dot { opacity:.35; }
.rp-hero .r-chips { display:flex;gap:6px;margin-top:10px;flex-wrap:wrap; }
.r-chip {
    display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:99px;
    font-size:0.68rem;font-weight:700;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);
}
.r-chip-green  { background:rgba(255,255,255,.18);color:rgba(255,255,255,.9); }
.r-chip-purple { background:rgba(245,158,11,.3);color:#fcd34d; }
.r-chip-grey   { background:rgba(255,255,255,.12);color:rgba(255,255,255,.75); }
.r-chip-amber  { background:rgba(245,158,11,.25);color:#fcd34d; }

/* ═══ Sticky bar ════════════════════════════════════════════════ */
.rp-sticky { background:#fff;border-bottom:1px solid #e5e7eb;position:sticky;top:56px;z-index:80; }
.rp-mode-tabs { display:flex;align-items:center;padding:0 20px;gap:2px;overflow-x:auto;scrollbar-width:none; }
.rp-mode-tabs::-webkit-scrollbar { display:none; }
.mode-tab {
    height:46px;padding:0 18px;border:0;background:transparent;
    font-size:0.82rem;font-weight:600;color:#9ca3af;
    cursor:pointer;position:relative;transition:color .15s;
    display:flex;align-items:center;gap:7px;white-space:nowrap;flex-shrink:0;
    text-decoration:none;
}
.mode-tab::after {
    content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;
    background:#0f3d45;border-radius:3px 3px 0 0;transform:scaleX(0);transition:transform .2s;
}
.mode-tab.active { color:#0f3d45; }
.mode-tab.active::after { transform:scaleX(1); }
.mode-tab-reserve.active { color:#0f3d45; }
.mode-tab-reserve::after { background:#0f3d45; }
.mode-tab-reviews { margin-left:auto; }
.mode-tab-reviews.active { color:#0f3d45; }
.mode-tab-reviews::after { background:#0f3d45; }

.cat-scroll { display:flex;align-items:center;gap:6px;padding:8px 20px;overflow-x:auto;scrollbar-width:none; }
.cat-scroll::-webkit-scrollbar { display:none; }
.cat-pill {
    height:30px;padding:0 14px;border-radius:99px;border:1.5px solid #e5e7eb;background:#fff;
    font-size:0.75rem;font-weight:500;color:#6b7280;cursor:pointer;white-space:nowrap;flex-shrink:0;transition:all .15s;
}
.cat-pill.active { background:#0f3d45;border-color:#0f3d45;color:#fff; }

/* ═══ Body ══════════════════════════════════════════════════════ */
.rp-body { display:flex;align-items:flex-start; }

/* ── Menu column ── */
.rp-menu { flex:1;min-width:0;padding:20px 20px 60px;overflow-y:auto; }
.menu-section-title {
    font-size:0.68rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.1em;
    margin:0 0 12px;padding-bottom:9px;border-bottom:2px solid #f0f0ee;
    display:flex;align-items:center;gap:8px;
}
.menu-section-title::before {
    content:'';width:3px;height:14px;background:#0f3d45;border-radius:2px;flex-shrink:0;
}

/* ── Single full-row menu item card ── */
.mi-card {
    display:flex;align-items:stretch;width:100%;
    background:#fff;border-radius:16px;
    border:1.5px solid #efefed;overflow:hidden;cursor:pointer;
    margin-bottom:14px;
    transition:border-color .18s,box-shadow .18s,transform .18s;
    min-height:110px;
}
.mi-card:hover {
    border-color:#0f3d45;
    box-shadow:0 6px 28px rgba(15,61,69,.12);
    transform:translateY(-2px);
}
.mi-card.in-cart {
    border-color:#0f3d45;
    background:linear-gradient(to right,#f0faf9,#fff);
    box-shadow:0 4px 18px rgba(15,61,69,.08);
}

/* Left: text block */
.mi-info {
    flex:1;padding:16px 16px 14px;min-width:0;
    display:flex;flex-direction:column;justify-content:space-between;
}
.mi-info-top { flex:1; }
.mi-cat {
    display:inline-block;font-size:0.6rem;font-weight:800;letter-spacing:.04em;
    color:#0f3d45;background:#e6f3f4;padding:2px 9px;border-radius:99px;
    margin-bottom:6px;text-transform:uppercase;
}
.mi-name {
    font-size:0.95rem;font-weight:700;color:#111827;
    margin-bottom:5px;line-height:1.35;
}
.mi-desc {
    font-size:0.75rem;color:#9ca3af;line-height:1.55;
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    margin-bottom:0;
}
.mi-info-bottom {
    display:flex;align-items:center;justify-content:space-between;
    margin-top:10px;
}
.mi-price {
    font-size:1rem;font-weight:900;color:#0f3d45;letter-spacing:-.01em;
}
.mi-price-currency {
    font-size:0.7rem;font-weight:700;color:#0f3d45;opacity:.65;margin-right:2px;
}

/* Right: image block — fixed square */
.mi-img {
    width:130px;flex-shrink:0;
    position:relative;overflow:hidden;
    background:linear-gradient(135deg,#f3f4f6,#e9eaec);
}
.mi-img img {
    position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
    transition:transform .3s ease;
}
.mi-card:hover .mi-img img { transform:scale(1.04); }
.mi-img .no-img {
    width:100%;height:100%;display:flex;align-items:center;justify-content:center;
    font-size:2.2rem;opacity:.18;
}

/* Add button — sits in bottom-right corner of image */
.mi-img .add-badge {
    position:absolute;bottom:10px;right:10px;
    width:34px;height:34px;border-radius:50%;border:0;
    background:#0f3d45;color:#fff;
    display:flex;align-items:center;justify-content:center;
    font-size:0.9rem;font-weight:700;
    box-shadow:0 3px 10px rgba(0,0,0,.28);
    transition:transform .15s,background .15s;cursor:pointer;
}
.mi-card:hover .add-badge { transform:scale(1.15);background:#1a5c6a; }

/* Qty stepper — same position, shown when qty > 0 */
.mi-img .qty-ctrl {
    position:absolute;bottom:9px;right:9px;
    display:none;align-items:center;
    background:#0f3d45;border-radius:99px;
    box-shadow:0 3px 12px rgba(0,0,0,.28);overflow:hidden;
}
.mi-img .qty-ctrl button {
    width:30px;height:30px;border:0;background:transparent;color:#fff;
    font-size:1.05rem;font-weight:700;line-height:1;cursor:pointer;
    display:flex;align-items:center;justify-content:center;transition:background .12s;flex-shrink:0;
}
.mi-img .qty-ctrl button:hover { background:rgba(255,255,255,.2); }
.mi-img .qty-ctrl .qnum {
    min-width:24px;text-align:center;font-size:0.82rem;font-weight:800;color:#fff;
}

/* ── Right panel ── */
.rp-panel {
    width:340px;flex-shrink:0;min-height:calc(100vh - 56px - 280px - 88px);
    background:#fff;border-left:1px solid #e5e7eb;display:flex;flex-direction:column;
    position:sticky;top:calc(56px + 88px);max-height:calc(100vh - 56px - 88px);overflow:hidden;
}
.ot-selector { display:flex;gap:8px;padding:14px 16px 0; }
.ot-btn {
    flex:1;height:50px;border-radius:12px;border:1.5px solid #e5e7eb;background:#fff;
    color:#6b7280;font-size:0.72rem;font-weight:600;cursor:pointer;transition:all .15s;
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;
}
.ot-btn i { font-size:0.95rem; }
.ot-btn.active { border-color:#0f3d45;background:#0f3d45;color:#fff; }
.cart-empty-state {
    flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px 20px;text-align:center;
}
.cart-empty-state .empty-icon { font-size:2.5rem;margin-bottom:12px;opacity:.25; }
.cart-empty-state p { font-size:0.82rem;color:#9ca3af;line-height:1.5; }
.cart-scroll { flex:1;overflow-y:auto;padding:12px 16px; }
.ci {
    display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;
    background:#f8f9fa;margin-bottom:8px;border:1px solid #f0f0ee;
}
.ci-img { width:40px;height:40px;border-radius:8px;object-fit:cover;flex-shrink:0;background:#e5e7eb; }
.ci-img-placeholder { width:40px;height:40px;border-radius:8px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;opacity:.4; }
.ci-name { font-size:0.8rem;font-weight:600;color:#111827;line-height:1.3; }
.ci-price { font-size:0.75rem;color:#0f3d45;font-weight:700; }
.ci-qty { display:flex;align-items:center;gap:6px;margin-left:auto;flex-shrink:0; }
.qty-btn {
    width:26px;height:26px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    font-size:0.85rem;font-weight:700;color:#374151;display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:all .12s;line-height:1;
}
.qty-btn:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.qty-num { min-width:20px;text-align:center;font-size:0.82rem;font-weight:700;color:#111827; }
.cart-totals { padding:12px 16px;border-top:1px solid #f0f0ee;background:#fafafa; }
.total-row { display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;margin-bottom:4px; }
.total-row.grand { font-weight:800;font-size:0.95rem;color:#111827;border-top:1px dashed #e5e7eb;padding-top:8px;margin-top:4px; }
.total-row.grand .val { color:#0f3d45; }
.panel-footer { padding:12px 16px 18px; }
.btn-place {
    width:100%;height:50px;border-radius:14px;border:0;
    background:linear-gradient(135deg,#0f3d45,#1a5c6a);
    color:#fff;font-size:0.9rem;font-weight:800;cursor:pointer;transition:all .15s;
    display:flex;align-items:center;justify-content:center;gap:8px;
    box-shadow:0 4px 16px rgba(15,61,69,.35);
}
.btn-place:hover:not(:disabled) { transform:translateY(-1px);box-shadow:0 6px 24px rgba(15,61,69,.45); }
.btn-place:disabled { opacity:.5;cursor:not-allowed;transform:none; }
.panel-alert { margin:0 16px 8px;padding:10px 14px;border-radius:10px;font-size:0.78rem;font-weight:500; }
.panel-alert.success { background:#e6f3f4;color:#0f3d45;border:1px solid #9ccdd2; }
.panel-alert.error   { background:#fef2f2;color:#991b1b;border:1px solid #fecaca; }
.panel-alert.warning { background:#fffbeb;color:#92400e;border:1px solid #fde68a; }
.delivery-row { padding:10px 16px 0;display:none; }
.delivery-row .delivery-box {
    background:#f0f9fa;border:1.5px solid #9ccdd2;border-radius:12px;padding:12px 14px;
}
.delivery-row .delivery-box label {
    font-size:0.68rem;font-weight:800;color:#0f3d45;text-transform:uppercase;letter-spacing:.06em;
    display:flex;align-items:center;gap:6px;margin-bottom:8px;
}
.delivery-row textarea {
    width:100%;border-radius:10px;border:1.5px solid #c5e6ea;font-size:0.82rem;
    padding:9px 11px;resize:none;font-family:inherit;outline:none;background:#fff;
    transition:border-color .15s;
}
.delivery-row textarea:focus { border-color:#0f3d45;box-shadow:0 0 0 3px rgba(15,61,69,.1); }
.delivery-row .delivery-hint {
    font-size:0.69rem;color:#6b7280;margin-top:5px;display:flex;align-items:center;gap:5px;
}
.pickup-opt {
    height:28px;padding:0 12px;border-radius:99px;border:1.5px solid #e5e7eb;background:#fff;
    font-size:0.74rem;font-weight:600;color:#6b7280;cursor:pointer;transition:all .12s;
}
.pickup-opt.active { background:#0f3d45;border-color:#0f3d45;color:#fff; }
/* Flatpickr teal overrides */
.flatpickr-day.selected,.flatpickr-day.selected:hover,.flatpickr-day.startRange,.flatpickr-day.endRange {
    background:#0f3d45!important;border-color:#0f3d45!important;
}
.flatpickr-day:hover,.flatpickr-day.prevMonthDay:hover,.flatpickr-day.nextMonthDay:hover { background:#e6f3f4!important; }
.flatpickr-months .flatpickr-month,.flatpickr-months .flatpickr-next-month svg,.flatpickr-months .flatpickr-prev-month svg { fill:#0f3d45; }
.flatpickr-current-month .flatpickr-monthDropdown-months { color:#0f3d45; }
.flatpickr-time input:hover,.flatpickr-time .flatpickr-am-pm:hover,.flatpickr-time input:focus,.flatpickr-time .flatpickr-am-pm:focus { background:#e6f3f4; }
/* Order tracker */
.order-tracker {
    margin:12px 16px;padding:14px;border-radius:12px;
    background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:1px solid #bbf7d0;display:none;
}
.tracker-title { font-size:0.78rem;font-weight:700;color:#065f46;margin-bottom:10px; }
.tracker-steps { display:flex;align-items:center; }
.t-step { flex:1;text-align:center;position:relative; }
.t-step:not(:last-child)::after {
    content:'';position:absolute;top:12px;left:50%;right:-50%;height:2px;background:#d1fae5;z-index:0;
}
.t-step.done:not(:last-child)::after { background:#10b981; }
.t-dot {
    width:24px;height:24px;border-radius:50%;background:#d1fae5;border:2px solid #6ee7b7;
    margin:0 auto 4px;display:flex;align-items:center;justify-content:center;
    font-size:0.6rem;position:relative;z-index:1;transition:all .3s;
}
.t-step.done .t-dot   { background:#10b981;border-color:#10b981;color:#fff; }
.t-step.active .t-dot { background:#fff;border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.2);animation:pulse-dot .8s ease-in-out infinite; }
.t-label { font-size:0.6rem;font-weight:600;color:#6b7280; }
.t-step.done .t-label,.t-step.active .t-label { color:#065f46; }
@keyframes pulse-dot { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }

/* ═══ Reservation panel ════════════════════════════════════════ */
.res-panel { flex:1;overflow-y:auto;display:flex;flex-direction:column; }
.res-hero-strip {
    background:linear-gradient(135deg,#0f3d45 0%,#1a5c6a 100%);
    padding:20px 20px 16px;text-align:center;flex-shrink:0;
}
.res-hero-strip .res-icon {
    width:54px;height:54px;background:rgba(255,255,255,.15);border-radius:50%;
    display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:1.4rem;
}
.res-hero-strip h5 { color:#fff;font-weight:800;margin:0 0 2px;font-size:1rem; }
.res-hero-strip p  { color:rgba(255,255,255,.65);font-size:0.72rem;margin:0; }
.res-fields { padding:16px 18px;flex:1; }
.res-fields .rf-label { font-size:0.72rem;font-weight:700;color:#374151;margin-bottom:5px;display:block; }
.res-fields .form-control { border-radius:10px;border:1.5px solid #e5e7eb;font-size:0.82rem;padding:9px 12px; }
.res-fields .form-control:focus { border-color:#0f3d45;box-shadow:0 0 0 3px rgba(15,61,69,.12);outline:none; }
.guest-counter { display:flex;align-items:center;gap:14px; }
.guest-btn {
    width:38px;height:38px;border-radius:50%;border:2px solid #e5e7eb;background:#fff;
    font-size:1.1rem;font-weight:700;color:#374151;display:flex;align-items:center;justify-content:center;
    cursor:pointer;transition:all .12s;line-height:1;
}
.guest-btn:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.guest-num { font-size:1.5rem;font-weight:800;color:#111827;min-width:32px;text-align:center; }
/* booking summary */
.res-summary {
    margin:0 18px 14px;border-radius:12px;padding:12px 14px;
    background:#f0f9fa;border:1px solid #c5e6ea;
    display:none;
}
.res-summary-row { display:flex;align-items:center;gap:8px;font-size:0.78rem;color:#0f3d45;margin-bottom:4px; }
.res-summary-row:last-child { margin-bottom:0; }
.res-summary-row i { width:16px;text-align:center;flex-shrink:0;color:#1a7a8a; }
.btn-reserve {
    width:100%;height:52px;border-radius:14px;border:0;
    background:linear-gradient(135deg,#0f3d45,#1a5c6a);
    color:#fff;font-size:0.9rem;font-weight:800;cursor:pointer;transition:all .15s;
    display:flex;align-items:center;justify-content:center;gap:8px;
    box-shadow:0 4px 18px rgba(15,61,69,.35);
}
.btn-reserve:hover:not(:disabled) { transform:translateY(-1px);box-shadow:0 7px 28px rgba(15,61,69,.45); }
.btn-reserve:disabled { opacity:.5;cursor:not-allowed;transform:none; }

/* ═══ Reviews section (below body, full-width) ══════════════════ */
.reviews-section {
    background:#fff;border-top:1px solid #e5e7eb;
    padding:48px 32px 56px;
}
.reviews-section-inner { max-width:1100px;margin:0 auto; }
.reviews-heading {
    display:flex;align-items:center;gap:14px;margin-bottom:32px;
}
.reviews-heading h2 { font-size:1.4rem;font-weight:800;color:#111827;margin:0; }
.reviews-heading .rating-pill {
    display:inline-flex;align-items:center;gap:6px;padding:5px 14px;
    border-radius:99px;background:#e6f3f4;border:1.5px solid #9ccdd2;
    font-size:0.82rem;font-weight:700;color:#0f3d45;
}
.rv-overview {
    display:flex;align-items:flex-start;gap:32px;margin-bottom:36px;flex-wrap:wrap;
}
.rv-big-score {
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    background:linear-gradient(135deg,#0f3d45 0%,#1a5c6a 100%);
    border-radius:20px;padding:28px 32px;min-width:140px;flex-shrink:0;
    box-shadow:0 8px 24px rgba(15,61,69,.25);
}
.rv-big-score .score { font-size:3rem;font-weight:900;color:#fff;line-height:1; }
.rv-big-score .stars-row { display:flex;gap:3px;margin:8px 0 6px; }
.rv-big-score .star { font-size:0.9rem;color:#fbbf24; }
.rv-big-score .star.empty { color:rgba(255,255,255,.3); }
.rv-big-score .count { font-size:0.72rem;color:rgba(255,255,255,.65);font-weight:600; }
.rv-bars { flex:1;min-width:200px; }
.rv-bar-row { display:flex;align-items:center;gap:10px;margin-bottom:8px; }
.rv-bar-row .lbl { font-size:0.72rem;font-weight:700;color:#374151;width:14px;text-align:right;flex-shrink:0; }
.rv-bar-row .bar-track { flex:1;height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden; }
.rv-bar-row .bar-fill { height:100%;border-radius:4px;background:linear-gradient(90deg,#f59e0b,#fbbf24);transition:width .6s ease; }
.rv-bar-row .pct { font-size:0.7rem;color:#9ca3af;width:32px;text-align:right; }
/* review cards grid */
.rv-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px; }
.rv-card {
    background:#fafafa;border:1.5px solid #f0f0ee;border-radius:16px;padding:18px 18px 16px;
    transition:box-shadow .15s,border-color .15s;
}
.rv-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.07);border-color:#e0e0da; }
.rv-card-head { display:flex;align-items:center;gap:12px;margin-bottom:12px; }
.rv-avatar {
    width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    font-size:0.85rem;font-weight:800;color:#fff;flex-shrink:0;
}
.rv-author { font-size:0.85rem;font-weight:700;color:#111827;line-height:1.2; }
.rv-date   { font-size:0.7rem;color:#9ca3af; }
.rv-stars  { display:flex;gap:2px;margin-left:auto;flex-shrink:0; }
.rv-stars i { font-size:0.65rem; }
.rv-comment { font-size:0.8rem;color:#4b5563;line-height:1.6;margin:0; }
.rv-tag {
    display:inline-flex;margin-top:10px;padding:2px 9px;border-radius:99px;
    font-size:0.65rem;font-weight:700;background:#fffbeb;color:#92400e;
}
.rv-empty {
    grid-column:1/-1;text-align:center;padding:48px 0;
}
.rv-empty .rv-empty-icon { font-size:3rem;opacity:.2;margin-bottom:12px; }
.rv-empty p { font-size:0.85rem;color:#9ca3af; }
/* ─ no reviews yet placeholder ─ */
.no-reviews-strip {
    background:#f8fafc;border:2px dashed #e2e8f0;border-radius:16px;
    text-align:center;padding:40px 24px;
}

/* ═══ Responsive ════════════════════════════════════════════════ */
@media (max-width:900px) {
    .rp-body { flex-direction:column; }
    .rp-panel { width:100%;position:static;max-height:none;border-left:0;border-top:1px solid #e5e7eb;min-height:auto; }
    .rp-hero  { height:220px; }
    .reviews-section { padding:32px 16px 40px; }
    .rv-overview { gap:20px; }
    .rv-big-score { min-width:120px;padding:20px 24px; }
}
@media (max-width:640px) {
    .rp-menu { padding:16px 12px 40px; }
    .rp-hero .r-name { font-size:1.35rem; }
    .mi-img { width:80px; }
    .rv-grid { grid-template-columns:1fr; }
}
</style>
@endsection

@section('content')
<div class="rp">

{{-- ══════════════ HERO ══════════════ --}}
<div class="rp-hero">
    @if($restaurant->image)
    <img src="{{ $restaurant->image }}" alt="{{ $restaurant->name }}">
    @endif
    <div class="overlay"></div>
    <a href="{{ route('client.restaurants') }}" class="back-btn">
        <i class="fas fa-arrow-left" style="font-size:.85rem;"></i>
    </a>
    <button class="fav-btn-hero" id="favBtn" data-id="{{ $restaurant->id }}" data-fav="{{ $isFavorite ? '1' : '0' }}">
        <i class="fa{{ $isFavorite ? 's' : 'r' }} fa-heart" style="font-size:1rem;color:{{ $isFavorite ? '#f87171' : '#fff' }};"></i>
    </button>
    <div class="hero-content">
        <div class="r-name">{{ $restaurant->name }}</div>
        <div class="r-meta-row">
            @if($restaurant->cuisine)<span>{{ $restaurant->cuisine->name }}</span><span class="dot">·</span>@endif
            <span><i class="fas fa-star" style="color:#fbbf24;margin-right:3px;"></i>{{ $avgRating ?: '—' }} ({{ $restaurant->reviews->count() }} reviews)</span>
            @if($restaurant->price_range)<span class="dot">·</span><span>{{ $restaurant->price_range }}</span>@endif
            @if($restaurant->phone_number)<span class="dot">·</span><span><i class="fas fa-phone" style="margin-right:4px;"></i>{{ $restaurant->phone_number }}</span>@endif
        </div>
        <div class="r-chips">
            @if($restaurant->accepts_delivery)
            <span class="r-chip r-chip-green"><i class="fas fa-motorcycle"></i> Delivery</span>
            @endif
            @if($restaurant->accepts_reservations)
            <span class="r-chip r-chip-purple"><i class="fas fa-calendar-check"></i> Reservations</span>
            @endif
            @if($restaurant->address)
            <span class="r-chip r-chip-grey"><i class="fas fa-location-dot"></i> {{ Str::limit($restaurant->address, 38) }}</span>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════ STICKY TABS ══════════════ --}}
<div class="rp-sticky">
    <div class="rp-mode-tabs">
        <button class="mode-tab active" data-mode="order">
            <i class="fas fa-bag-shopping"></i> Order
        </button>
        @if($restaurant->accepts_reservations)
        <button class="mode-tab mode-tab-reserve" data-mode="reservation">
            <i class="fas fa-calendar-star"></i> Reserve a Table
        </button>
        @endif
        @if($restaurant->reviews->count())
        <a href="#reviews-section" class="mode-tab mode-tab-reviews" id="reviewsTabLink">
            <i class="fas fa-star"></i> Reviews
            <span style="background:#f59e0b;color:#fff;font-size:.62rem;font-weight:800;padding:1px 6px;border-radius:99px;margin-left:2px;">{{ $restaurant->reviews->count() }}</span>
        </a>
        @endif
    </div>
    @if($categories->count() > 1)
    <div class="cat-scroll" id="catScroll">
        <button class="cat-pill active" data-cat="all">All</button>
        @foreach($categories as $cat)
        <button class="cat-pill" data-cat="{{ $cat }}">{{ ucfirst($cat) }}</button>
        @endforeach
    </div>
    @endif
</div>

{{-- ══════════════ BODY ══════════════ --}}
<div class="rp-body">

    {{-- ── LEFT: Menu ── --}}
    <div class="rp-menu" id="menuSection">
        @forelse($restaurant->menus as $menu)
        @if($menu->menuItems->count())
        <div class="mb-5 menu-block">
            <div class="menu-section-title">{{ $menu->name }}</div>
            @foreach($menu->menuItems as $item)
            <div class="mi-card menu-item-col" id="mic-{{ $item->id }}"
                 data-cat="{{ $item->category ?? '' }}"
                 data-id="{{ $item->id }}"
                 data-name="{{ addslashes($item->name) }}"
                 data-price="{{ $item->price }}"
                 data-image="{{ $item->image ?? '' }}"
                 onclick="addToCart(parseInt(this.dataset.id),this.dataset.name,parseFloat(this.dataset.price),this.dataset.image)">

                {{-- Text side --}}
                <div class="mi-info">
                    <div class="mi-info-top">
                        @if($item->category)
                        <span class="mi-cat">{{ ucfirst($item->category) }}</span>
                        @endif
                        <div class="mi-name">{{ $item->name }}</div>
                        @if($item->description)
                        <div class="mi-desc">{{ $item->description }}</div>
                        @endif
                    </div>
                    <div class="mi-info-bottom">
                        <div class="mi-price">
                            <span class="mi-price-currency">RWF</span>{{ number_format($item->price, 0) }}
                        </div>
                        @if($item->dietary_info)
                        @php $diet = is_array($item->dietary_info) ? ($item->dietary_info['suitable_for'][0] ?? null) : null; @endphp
                        @if($diet)
                        <span style="font-size:.62rem;font-weight:700;color:#92400e;background:#fffbeb;padding:2px 7px;border-radius:99px;">{{ ucfirst($diet) }}</span>
                        @endif
                        @endif
                    </div>
                </div>

                {{-- Image side --}}
                <div class="mi-img">
                    @if($item->image)
                    <img src="{{ $item->image }}" alt="{{ $item->name }}">
                    @else
                    <div class="no-img">🍽️</div>
                    @endif
                    {{-- Add button --}}
                    <button class="add-badge" id="add-badge-{{ $item->id }}"
                        onclick="event.stopPropagation();addToCart({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }},'{{ $item->image ?? '' }}')">
                        <i class="fas fa-plus"></i>
                    </button>
                    {{-- Qty stepper --}}
                    <div class="qty-ctrl" id="qty-ctrl-{{ $item->id }}">
                        <button onclick="event.stopPropagation();cardChangeQty({{ $item->id }},-1)">−</button>
                        <span class="qnum" id="qnum-{{ $item->id }}">0</span>
                        <button onclick="event.stopPropagation();cardChangeQty({{ $item->id }},1)">+</button>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
        @endif
        @empty
        <div style="text-align:center;padding:60px 0;color:#9ca3af;">
            <div style="font-size:3rem;margin-bottom:12px;opacity:.2;">🍽️</div>
            <p>No menu items available yet.</p>
        </div>
        @endforelse
    </div>

    {{-- ── RIGHT: Panel ── --}}
    <div class="rp-panel" id="orderPanel">

        {{-- ── ORDER MODE ── --}}
        <div id="orderMode" style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
            <div class="ot-selector">
                <button class="ot-btn active" data-type="dine_in"><i class="fas fa-utensils"></i> Dine In</button>
                <button class="ot-btn" data-type="takeaway"><i class="fas fa-shopping-bag"></i> Takeaway</button>
                @if($restaurant->accepts_delivery)
                <button class="ot-btn" data-type="delivery"><i class="fas fa-motorcycle"></i> Delivery</button>
                @endif
            </div>

            <div class="delivery-row" id="deliveryRow">
                <div class="delivery-box">
                    <label>
                        <i class="fas fa-location-dot" style="color:#0f3d45;"></i>
                        Delivery address
                    </label>
                    <textarea id="deliveryAddr" rows="2"
                        placeholder="Street, building, district, any landmark that helps the rider find you…"></textarea>
                    <div class="delivery-hint">
                        <i class="fas fa-circle-info" style="font-size:.65rem;color:#9ccdd2;"></i>
                        Be specific — this is sent directly to the restaurant
                    </div>
                </div>
            </div>

            <div style="padding:10px 16px 0;" id="pickupRow">
                <label style="font-size:.72rem;font-weight:700;color:#374151;display:block;margin-bottom:5px;">
                    <i class="fas fa-clock me-1" style="color:#0f3d45;"></i> When do you want it?
                </label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button class="pickup-opt active" data-when="now" onclick="setPickupTime('now')">Now</button>
                    <button class="pickup-opt" data-when="schedule" onclick="setPickupTime('schedule')">Schedule</button>
                </div>
                <div id="scheduledTimePicker" style="display:none;margin-top:8px;">
                    <div style="position:relative;">
                        <i class="fas fa-calendar-day" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#0f3d45;font-size:.8rem;z-index:1;pointer-events:none;"></i>
                        <input type="text" id="scheduledTime" class="form-control" readonly placeholder="Pick date &amp; time…"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;font-size:.82rem;padding-left:32px;cursor:pointer;background:#fff;">
                    </div>
                </div>
                <p id="pickupNote" style="font-size:.7rem;color:#9ca3af;margin:4px 0 0;">Ready in ~20–30 min</p>
            </div>

            <div id="orderAlert"></div>

            <div class="order-tracker" id="orderTracker">
                <div class="tracker-title"><i class="fas fa-circle-dot me-1"></i> Order <span id="trackerOrderId">#</span> — live</div>
                <div class="tracker-steps">
                    <div class="t-step active" id="ts-pending"><div class="t-dot"><i class="fas fa-clock"></i></div><div class="t-label">Pending</div></div>
                    <div class="t-step" id="ts-processing"><div class="t-dot"><i class="fas fa-fire"></i></div><div class="t-label">Preparing</div></div>
                    <div class="t-step" id="ts-completed"><div class="t-dot"><i class="fas fa-check"></i></div><div class="t-label">Ready</div></div>
                </div>
            </div>

            <div class="cart-scroll" id="cartScroll">
                <div class="cart-empty-state" id="cartEmpty">
                    <div class="empty-icon">🛒</div>
                    <p>Your order is empty.<br>Tap any item to add it.</p>
                </div>
            </div>

            <div class="cart-totals" id="cartTotals" style="display:none;">
                <div class="total-row"><span style="color:#6b7280;">Subtotal</span><span id="cartSubtotal" style="font-weight:600;">RWF 0</span></div>
                <div class="total-row grand"><span>Total</span><span class="val" id="cartTotal">RWF 0</span></div>
                <textarea id="specialInstr" rows="2" placeholder="Special instructions? (optional)"
                    style="width:100%;margin-top:10px;border:1.5px solid #e5e7eb;border-radius:10px;padding:8px 10px;font-size:.76rem;resize:none;font-family:inherit;outline:none;"></textarea>
            </div>

            <div class="panel-footer">
                <button class="btn-place" id="placeOrderBtn" disabled>
                    <i class="fas fa-bag-shopping"></i> Place Order
                </button>
            </div>
        </div>

        {{-- ── RESERVATION MODE ── --}}
        <div id="reservationMode" style="display:none;flex-direction:column;flex:1;overflow:hidden;">
            <div class="res-panel">
                {{-- visual strip --}}
                <div class="res-hero-strip">
                    <div class="res-icon">🗓️</div>
                    <h5>Reserve a Table</h5>
                    <p>at {{ $restaurant->name }}</p>
                </div>

                <div class="res-fields">
                    <div class="mb-3">
                        <label class="rf-label"><i class="fas fa-calendar me-1" style="color:#0f3d45;"></i> Date &amp; Time</label>
                        <div style="position:relative;">
                        <i class="fas fa-calendar-day" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#0f3d45;font-size:.8rem;z-index:1;pointer-events:none;"></i>
                        <input type="text" class="form-control" id="resDate" readonly placeholder="Pick date &amp; time…"
                            style="padding-left:32px;cursor:pointer;background:#fff;" onchange="updateResSummary()">
                    </div>
                    </div>
                    <div class="mb-3">
                        <label class="rf-label"><i class="fas fa-users me-1" style="color:#0f3d45;"></i> Guests</label>
                        <div class="guest-counter">
                            <button class="guest-btn" onclick="changeGuests(-1)">−</button>
                            <span class="guest-num" id="guestCount">2</span>
                            <button class="guest-btn" onclick="changeGuests(1)">+</button>
                            <span style="font-size:.78rem;color:#9ca3af;font-weight:600;">people</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="rf-label"><i class="fas fa-phone me-1" style="color:#0f3d45;"></i> Phone number</label>
                        <input type="tel" class="form-control" id="resPhone"
                            placeholder="+250 7XX XXX XXX"
                            value="{{ auth()->user()->phone_number ?? '' }}"
                            onchange="updateResSummary()">
                    </div>
                    <div class="mb-3">
                        <label class="rf-label"><i class="fas fa-comment me-1" style="color:#0f3d45;"></i> Special requests <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                        <textarea class="form-control" id="resRequests" rows="2"
                            placeholder="Allergies, seating preference, occasion…"></textarea>
                    </div>

                    {{-- Pre-order food toggle --}}
                    <div class="mb-2">
                        <button type="button" id="togglePreorder"
                            style="width:100%;background:#f0f9fa;border:1.5px dashed #9ccdd2;border-radius:12px;padding:10px 14px;
                                   color:#0f3d45;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:8px;cursor:pointer;transition:all .15s;"
                            onclick="togglePreorderPanel()">
                            <i class="fas fa-utensils" style="font-size:.85rem;"></i>
                            <span>Pre-order food for your table</span>
                            <span style="margin-left:auto;font-size:.68rem;color:#6b7280;font-weight:400;">(optional)</span>
                            <i class="fas fa-chevron-down" id="preorderChevron" style="font-size:.65rem;transition:transform .2s;"></i>
                        </button>
                        <div id="preorderPanel" style="display:none;margin-top:8px;background:#fafafa;border-radius:12px;border:1px solid #e5e7eb;padding:10px;max-height:220px;overflow-y:auto;">
                            <p style="font-size:.71rem;color:#9ca3af;margin:0 0 8px;">Select items to have ready when you arrive:</p>
                            <div id="preorderItemsList">
                                @foreach($restaurant->menus as $menu)
                                    @foreach($menu->menuItems as $item)
                                    <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid #f0f0ee;">
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.78rem;font-weight:600;color:#111827;">{{ $item->name }}</div>
                                            <div style="font-size:.7rem;color:#0f3d45;font-weight:700;">RWF {{ number_format($item->price, 0) }}</div>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                                            <button type="button" onclick="resItemQty({{ $item->id }}, -1)" style="width:24px;height:24px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;font-size:.9rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;" onmouseover="this.style.background='#0f3d45';this.style.borderColor='#0f3d45';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.borderColor='#e5e7eb';this.style.color='#374151'">−</button>
                                            <span id="ri-qty-{{ $item->id }}" style="min-width:20px;text-align:center;font-size:.8rem;font-weight:800;color:#111827;">0</span>
                                            <button type="button" onclick="resItemQty({{ $item->id }}, 1)" style="width:24px;height:24px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;font-size:.9rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;" onmouseover="this.style.background='#0f3d45';this.style.borderColor='#0f3d45';this.style.color='#fff'" onmouseout="this.style.background='#fff';this.style.borderColor='#e5e7eb';this.style.color='#374151'">+</button>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div id="preorderSummaryLine" style="display:none;margin-top:5px;font-size:.72rem;color:#0f3d45;font-weight:600;padding:0 2px;"></div>
                    </div>
                </div>

                {{-- booking summary --}}
                <div class="res-summary" id="resSummary">
                    <div style="font-size:.7rem;font-weight:800;color:#0f3d45;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">Booking Summary</div>
                    <div class="res-summary-row"><i class="fas fa-store"></i><span id="sumRestaurant">{{ $restaurant->name }}</span></div>
                    <div class="res-summary-row"><i class="fas fa-calendar-day"></i><span id="sumDate">—</span></div>
                    <div class="res-summary-row"><i class="fas fa-users"></i><span id="sumGuests">2 guests</span></div>
                    <div class="res-summary-row" id="sumPreorderRow" style="display:none;"><i class="fas fa-utensils"></i><span id="sumPreorder">—</span></div>
                </div>

                <div id="reservationAlert" style="margin:0 18px;"></div>

                <div class="panel-footer">
                    <button class="btn-reserve" id="reserveBtn">
                        <i class="fas fa-calendar-check"></i> Confirm Reservation
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- /rp-panel --}}
</div>{{-- /rp-body --}}

{{-- ══════════════ REVIEWS SECTION — only shown when reviews exist ══════════════ --}}
@if($restaurant->reviews->count())
@php
    $reviews = $restaurant->reviews->load('user');
    $total   = $reviews->count();
    $distrib = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
    foreach($reviews as $rv) { $distrib[max(1,min(5,(int)$rv->rating))]++; }
    $avatarColors = ['#0f3d45','#1a5c6a','#0284c7','#d97706','#dc2626','#0f766e','#b45309','#2563eb'];
@endphp
<section class="reviews-section" id="reviews-section">
    <div class="reviews-section-inner">

        <div class="reviews-heading">
            <h2>Customer Reviews</h2>
            <span class="rating-pill">
                <i class="fas fa-star" style="color:#f59e0b;font-size:.75rem;"></i> {{ $avgRating }} / 5
            </span>
        </div>

        <div class="rv-overview">
            <div class="rv-big-score">
                <div class="score">{{ $avgRating }}</div>
                <div class="stars-row">
                    @for($s=1;$s<=5;$s++)
                    <span class="star {{ $s <= round($avgRating) ? '' : 'empty' }}">&#9733;</span>
                    @endfor
                </div>
                <div class="count">{{ $total }} review{{ $total!=1?'s':'' }}</div>
            </div>
            <div class="rv-bars">
                @for($star=5; $star>=1; $star--)
                @php $cnt = $distrib[$star]; $pct = $total ? round($cnt/$total*100) : 0; @endphp
                <div class="rv-bar-row">
                    <span class="lbl">{{ $star }}</span>
                    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;"></div></div>
                    <span class="pct">{{ $pct }}%</span>
                </div>
                @endfor
            </div>
        </div>

        <div class="rv-grid">
            @foreach($reviews->take(9) as $idx => $review)
            @php
                $initials = strtoupper(substr($review->user->first_name ?? 'U', 0, 1)) . strtoupper(substr($review->user->last_name ?? '', 0, 1));
                $color    = $avatarColors[$idx % count($avatarColors)];
                $rating   = max(1, min(5, (int)$review->rating));
            @endphp
            <div class="rv-card">
                <div class="rv-card-head">
                    <div class="rv-avatar" style="background:{{ $color }};">{{ $initials }}</div>
                    <div>
                        <div class="rv-author">{{ $review->user->first_name ?? 'Customer' }} {{ $review->user->last_name ?? '' }}</div>
                        <div class="rv-date">{{ $review->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="rv-stars">
                        @for($s=1;$s<=5;$s++)
                        <i class="fas fa-star" style="color:{{ $s<=$rating ? '#fbbf24' : '#e5e7eb' }};"></i>
                        @endfor
                    </div>
                </div>
                @if($review->comment)
                <p class="rv-comment">{{ $review->comment }}</p>
                @endif
                @if($review->rating >= 4)
                <span class="rv-tag"><i class="fas fa-thumbs-up" style="font-size:.6rem;margin-right:3px;"></i> Recommended</span>
                @endif
            </div>
            @endforeach
        </div>

        @if($total > 9)
        <div style="text-align:center;margin-top:28px;">
            <span style="font-size:.82rem;color:#9ca3af;">Showing 9 of {{ $total }} reviews</span>
        </div>
        @endif

    </div>
</section>
@endif

</div>{{-- /rp --}}
@endsection

@section('script')
<script>
(function () {
var cart          = [];
var guestCount    = 2;
var orderType     = 'dine_in';
var restaurantId  = {{ $restaurant->id }};
var userId        = {{ auth()->id() }};
var csrfToken     = '{{ csrf_token() }}';
var activeOrderId = null;

// ── Mode tabs ────────────────────────────────────────────────
document.querySelectorAll('.mode-tab[data-mode]').forEach(function(tab) {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.mode-tab[data-mode]').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        var mode = this.dataset.mode;
        var om = document.getElementById('orderMode');
        var rm = document.getElementById('reservationMode');
        om.style.display = mode === 'order'       ? 'flex'   : 'none';
        rm.style.display = mode === 'reservation' ? 'flex'   : 'none';
    });
});

// Reviews tab → smooth scroll
var reviewsTabLink = document.getElementById('reviewsTabLink');
if (reviewsTabLink) {
    reviewsTabLink.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('reviews-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.querySelectorAll('.mode-tab').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
    });
}

// ── Category filter ──────────────────────────────────────────
document.querySelectorAll('.cat-pill').forEach(function(pill) {
    pill.addEventListener('click', function() {
        document.querySelectorAll('.cat-pill').forEach(function(p) { p.classList.remove('active'); });
        this.classList.add('active');
        var cat = this.dataset.cat;
        document.querySelectorAll('.menu-item-col').forEach(function(el) {
            el.style.display = (cat === 'all' || el.dataset.cat === cat) ? '' : 'none';
        });
    });
});

// ── Order type ───────────────────────────────────────────────
document.querySelectorAll('.ot-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.ot-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        orderType = this.dataset.type;
        document.getElementById('deliveryRow').style.display = orderType === 'delivery' ? 'block' : 'none';
        document.getElementById('pickupRow').style.display   = orderType === 'delivery' ? 'none'  : 'block';
    });
});

// ── Pickup / scheduled time (Flatpickr) ──────────────────────
var scheduledPickupTime = null;
var scheduledFp = flatpickr('#scheduledTime', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    altInput: true,
    altFormat: 'D d M Y – H:i',
    minDate: new Date(Date.now() + 15 * 60 * 1000),
    minuteIncrement: 5,
    onChange: function(selectedDates, dateStr) {
        scheduledPickupTime = dateStr;
        var note = document.getElementById('pickupNote');
        if (note && dateStr) {
            note.textContent = 'Scheduled: ' + selectedDates[0].toLocaleString([], {weekday:'short',day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'});
        }
    }
});
window.setPickupTime = function(when) {
    document.querySelectorAll('.pickup-opt').forEach(function(b) { b.classList.remove('active'); });
    document.querySelector('.pickup-opt[data-when="' + when + '"]').classList.add('active');
    var picker = document.getElementById('scheduledTimePicker');
    var note   = document.getElementById('pickupNote');
    if (when === 'now') {
        picker.style.display = 'none';
        scheduledPickupTime = null;
        scheduledFp.clear();
        if (note) note.textContent = 'Ready in ~20–30 min';
    } else {
        picker.style.display = 'block';
        if (note) note.textContent = 'Pick a time at least 15 min from now';
    }
};

// ── Cart helpers ──────────────────────────────────────────────
function updateCardUI(id) {
    var item  = cart.find(function(i) { return i.id === id; });
    var qty   = item ? item.qty : 0;
    var card  = document.getElementById('mic-'      + id);
    var badge = document.getElementById('add-badge-'+ id);
    var ctrl  = document.getElementById('qty-ctrl-' + id);
    var qnum  = document.getElementById('qnum-'     + id);
    if (qty > 0) {
        if (card)  card.classList.add('in-cart');
        if (badge) badge.style.display = 'none';
        if (ctrl)  ctrl.style.display  = 'flex';
        if (qnum)  qnum.textContent    = qty;
    } else {
        if (card)  card.classList.remove('in-cart');
        if (badge) badge.style.display = 'flex';
        if (ctrl)  ctrl.style.display  = 'none';
        if (qnum)  qnum.textContent    = '0';
    }
}

window.addToCart = function(id, name, price, image) {
    var ex = cart.find(function(i) { return i.id === id; });
    if (ex) { ex.qty++; } else { cart.push({ id:id, name:name, price:price, image:image, qty:1 }); }
    updateCardUI(id);
    renderCart();
};

window.cardChangeQty = function(id, delta) {
    var ex = cart.find(function(i) { return i.id === id; });
    if (!ex) return;
    var nq = ex.qty + delta;
    if (nq <= 0) cart.splice(cart.indexOf(ex), 1);
    else ex.qty = nq;
    updateCardUI(id);
    renderCart();
};

window.changeQty = function(id, delta) { window.cardChangeQty(id, delta); };

function renderCart() {
    var scroll = document.getElementById('cartScroll');
    var empty  = document.getElementById('cartEmpty');
    var totals = document.getElementById('cartTotals');
    var btn    = document.getElementById('placeOrderBtn');

    if (!scroll.contains(empty)) scroll.appendChild(empty);

    if (cart.length === 0) {
        empty.style.display = 'flex';
        totals.style.display = 'none';
        btn.disabled = true;
        scroll.querySelectorAll('.ci').forEach(function(el) { el.remove(); });
        return;
    }
    empty.style.display  = 'none';
    totals.style.display = '';
    btn.disabled         = false;

    var total = 0;
    var existingIds = {};
    scroll.querySelectorAll('.ci[data-id]').forEach(function(el) { existingIds[el.dataset.id] = el; });
    Object.keys(existingIds).forEach(function(id) {
        if (!cart.find(function(i) { return i.id == id; })) existingIds[id].remove();
    });
    cart.forEach(function(item) {
        total += item.price * item.qty;
        var existing = existingIds[item.id];
        if (existing) {
            existing.querySelector('.qty-num').textContent = item.qty;
            existing.querySelector('.ci-price').textContent = 'RWF ' + (item.price * item.qty).toLocaleString();
        } else {
            var row = document.createElement('div');
            row.className  = 'ci';
            row.dataset.id = item.id;
            row.innerHTML  = (item.image ? '<img class="ci-img" src="' + item.image + '" alt="">' : '<div class="ci-img-placeholder">&#127374;</div>')
                + '<div style="flex:1;min-width:0;">'
                + '<div class="ci-name">' + item.name + '</div>'
                + '<div class="ci-price">RWF ' + (item.price * item.qty).toLocaleString() + '</div>'
                + '</div>'
                + '<div class="ci-qty">'
                + '<button class="qty-btn" data-id="' + item.id + '" data-delta="-1">&#8722;</button>'
                + '<span class="qty-num">' + item.qty + '</span>'
                + '<button class="qty-btn" data-id="' + item.id + '" data-delta="1">+</button>'
                + '</div>';
            row.querySelectorAll('.qty-btn').forEach(function(b) {
                b.addEventListener('click', function(e) {
                    e.stopPropagation();
                    window.cardChangeQty(parseInt(this.dataset.id), parseInt(this.dataset.delta));
                });
            });
            scroll.appendChild(row);
        }
    });
    document.getElementById('cartSubtotal').textContent = 'RWF ' + total.toLocaleString();
    document.getElementById('cartTotal').textContent    = 'RWF ' + total.toLocaleString();
}

// ── Place order ──────────────────────────────────────────────
document.getElementById('placeOrderBtn').addEventListener('click', function() {
    if (!cart.length) return;
    var btn = this;
    clearAlert('orderAlert');
    if (orderType === 'delivery' && !document.getElementById('deliveryAddr').value.trim()) {
        showAlert('orderAlert', 'warning', '<i class="fas fa-location-dot me-1"></i> Please enter your delivery address so the restaurant knows where to send your order.');
        document.getElementById('deliveryAddr').focus();
        return;
    }
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Placing…';
    $.ajax({
        url: '{{ route("client.order") }}',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: csrfToken,
            restaurant_id: restaurantId,
            order_type: orderType,
            delivery_address: document.getElementById('deliveryAddr').value || 'N/A',
            special_instructions: document.getElementById('specialInstr').value,
            scheduled_time: scheduledPickupTime || null,
            items: cart.map(function(i) { return { menu_item_id: i.id, quantity: i.qty }; })
        }),
        success: function(res) {
            if (res.status === 201) {
                activeOrderId = res.order_id;
                showAlert('orderAlert', 'success',
                    '<i class="fas fa-check-circle me-1"></i> Order #' + res.order_id + ' placed! '
                    + '<a href="{{ route("client.my-orders") }}" style="color:#065f46;font-weight:700;">View orders →</a>');
                cart = [];
                document.querySelectorAll('.mi-card').forEach(function(c) { c.classList.remove('in-cart'); });
                document.querySelectorAll('[id^="add-badge-"]').forEach(function(b) { b.style.display = 'flex'; });
                renderCart();
                showTracker(res.order_id, 'pending');
            }
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to place order.';
            showAlert('orderAlert', 'error', msg);
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-bag-shopping"></i> Place Order';
        }
    });
});

// ── Reservation ──────────────────────────────────────────────
var resFp = flatpickr('#resDate', {
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
    altInput: true,
    altFormat: 'D d M Y – H:i',
    minDate: new Date(Date.now() + 60 * 60 * 1000),
    minuteIncrement: 15,
    onChange: function() { updateResSummary(); }
});

// ── Pre-order items ───────────────────────────────────────────
var resPreorderItems = {};
window.togglePreorderPanel = function() {
    var panel    = document.getElementById('preorderPanel');
    var chevron  = document.getElementById('preorderChevron');
    var togBtn   = document.getElementById('togglePreorder');
    var open     = panel.style.display === 'block';
    panel.style.display  = open ? 'none' : 'block';
    chevron.style.transform = open ? '' : 'rotate(180deg)';
    togBtn.style.background = open ? '#f0f9fa' : '#e6f3f4';
    togBtn.style.borderStyle = open ? 'dashed' : 'solid';
};
window.resItemQty = function(id, delta) {
    var current = resPreorderItems[id] || 0;
    var next    = Math.max(0, current + delta);
    resPreorderItems[id] = next;
    var qEl = document.getElementById('ri-qty-' + id);
    if (qEl) qEl.textContent = next;
    if (next > 0) qEl.style.color = '#0f3d45';
    else qEl.style.color = '#111827';
    updatePreorderSummary();
};
function updatePreorderSummary() {
    var items = Object.entries(resPreorderItems).filter(function(e) { return e[1] > 0; });
    var summaryLine = document.getElementById('preorderSummaryLine');
    var sumRow = document.getElementById('sumPreorderRow');
    var sumPreorder = document.getElementById('sumPreorder');
    if (items.length === 0) {
        summaryLine.style.display = 'none';
        if (sumRow) sumRow.style.display = 'none';
        return;
    }
    var total = items.reduce(function(acc, e) { return acc + e[1]; }, 0);
    summaryLine.style.display = 'block';
    summaryLine.innerHTML = '<i class="fas fa-utensils me-1"></i>' + total + ' item' + (total !== 1 ? 's' : '') + ' pre-ordered';
    if (sumRow) sumRow.style.display = 'flex';
    if (sumPreorder) sumPreorder.textContent = total + ' item' + (total !== 1 ? 's' : '') + ' pre-ordered';
}

window.changeGuests = function(delta) {
    guestCount = Math.max(1, Math.min(50, guestCount + delta));
    document.getElementById('guestCount').textContent = guestCount;
    updateResSummary();
};

window.updateResSummary = function() {
    var date  = document.getElementById('resDate').value;
    var phone = document.getElementById('resPhone').value.trim();
    var sum   = document.getElementById('resSummary');
    if (!date && !phone) { sum.style.display = 'none'; return; }
    sum.style.display = 'block';
    if (date) {
        var d = new Date(date);
        document.getElementById('sumDate').textContent = d.toLocaleString([], {weekday:'long',month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});
    }
    document.getElementById('sumGuests').textContent = guestCount + ' guest' + (guestCount !== 1 ? 's' : '');
    updatePreorderSummary();
};

document.getElementById('reserveBtn').addEventListener('click', function() {
    var btn   = this;
    var date  = document.getElementById('resDate').value;
    var phone = document.getElementById('resPhone').value.trim();
    clearAlert('reservationAlert');
    if (!date)  { showAlert('reservationAlert', 'warning', 'Please select a date and time.'); return; }
    if (!phone) { showAlert('reservationAlert', 'warning', 'Please enter your phone number.');  return; }
    var preorderArr = Object.entries(resPreorderItems)
        .filter(function(e) { return e[1] > 0; })
        .map(function(e) { return { menu_item_id: parseInt(e[0]), quantity: e[1] }; });
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Booking…';
    $.ajax({
        url: '{{ route("client.reservation") }}',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: csrfToken,
            restaurant_id: restaurantId,
            reservation_time: date,
            number_of_people: guestCount,
            phone_number: phone,
            special_requests: document.getElementById('resRequests').value,
            preorder_items: preorderArr,
        }),
        success: function(res) {
            if (res.status === 201) {
                showAlert('reservationAlert', 'success',
                    '<i class="fas fa-check-circle me-1"></i> Table reserved! '
                    + '<a href="{{ route("client.my-reservations") }}" style="color:#065f46;font-weight:700;">View reservations →</a>');
                resFp.clear();
                document.getElementById('resRequests').value = '';
                document.getElementById('resSummary').style.display = 'none';
                resPreorderItems = {};
                document.querySelectorAll('[id^="ri-qty-"]').forEach(function(el) { el.textContent = '0'; el.style.color = '#111827'; });
                document.getElementById('preorderSummaryLine').style.display = 'none';
            }
        },
        error: function(xhr) {
            var errors = xhr.responseJSON && xhr.responseJSON.errors;
            var msg    = errors ? Object.values(errors).flat().join(' ') : 'Failed to make reservation.';
            showAlert('reservationAlert', 'error', msg);
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-calendar-check"></i> Confirm Reservation';
        }
    });
});

// ── Favorite ─────────────────────────────────────────────────
document.getElementById('favBtn').addEventListener('click', function() {
    var btn = this;
    $.post('{{ route("client.favorite") }}', { restaurant_id: restaurantId, _token: csrfToken }, function(res) {
        btn.dataset.fav = res.favorited ? '1' : '0';
        var icon = btn.querySelector('i');
        icon.className  = res.favorited ? 'fas fa-heart' : 'far fa-heart';
        icon.style.color = res.favorited ? '#f87171' : '#fff';
    });
});

// ── Order status tracker ─────────────────────────────────────
var statusOrder = ['pending','processing','completed'];
function showTracker(orderId, status) {
    var tracker = document.getElementById('orderTracker');
    document.getElementById('trackerOrderId').textContent = '#' + orderId;
    tracker.style.display = 'block';
    updateTrackerStatus(status);
}
function updateTrackerStatus(status) {
    var idx = statusOrder.indexOf(status);
    statusOrder.forEach(function(s, i) {
        var step = document.getElementById('ts-' + s);
        if (!step) return;
        step.classList.remove('done','active');
        if (i < idx) step.classList.add('done');
        else if (i === idx) step.classList.add('active');
    });
}

if (typeof window.Echo !== 'undefined') {
    window.Echo.private('user.' + userId)
        .listen('OrderStatusChanged', function(e) {
            var o = e.order;
            if (document.getElementById('orderTracker').style.display !== 'none') updateTrackerStatus(o.status);
            if (o.status === 'cancelled') {
                var t = document.getElementById('orderTracker');
                t.style.background  = 'linear-gradient(135deg,#fef2f2,#fee2e2)';
                t.style.borderColor = '#fecaca';
                document.querySelector('#orderTracker .tracker-title').style.color = '#991b1b';
                document.querySelector('#orderTracker .tracker-title').innerHTML =
                    '<i class="fas fa-times-circle me-1"></i> Order #' + o.id + ' was cancelled';
            }
        });
}

// ── Alert helpers ─────────────────────────────────────────────
function showAlert(id, type, html) {
    document.getElementById(id).innerHTML = '<div class="panel-alert ' + type + '">' + html + '</div>';
}
function clearAlert(id) { document.getElementById(id).innerHTML = ''; }

renderCart();
})();
</script>
@endsection
