@extends('layouts.client')

@section('title', 'Browse Restaurants')

@section('style')
<style>
/* ── Page base ──────────────────────────────────────── */
.browse-page { background: #f5f5f0; min-height: 100vh; }

/* ── Hero search bar ────────────────────────────────── */
.hero-bar {
    background: linear-gradient(120deg, #184C55 0%, #0f3d45 40%, #1a3a5c 75%, #2d2060 100%);
    padding: 40px 24px 36px;
    position: relative; overflow: hidden;
}
/* Decorative food-pattern dots */
.hero-bar::before {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image:
        radial-gradient(circle, rgba(255,255,255,.06) 1.5px, transparent 1.5px),
        radial-gradient(circle, rgba(255,255,255,.04) 1px, transparent 1px);
    background-size: 36px 36px, 18px 18px;
    background-position: 0 0, 9px 9px;
}
/* Warm accent glow bottom-right */
.hero-bar::after {
    content: ''; position: absolute; bottom: -40px; right: -40px;
    width: 260px; height: 260px; border-radius: 50%;
    background: radial-gradient(circle, rgba(245,158,11,.18) 0%, transparent 70%);
    pointer-events: none;
}
.hero-inner { position: relative; z-index: 1; max-width: 680px; margin: 0 auto; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.12); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.85); font-size: 0.72rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    padding: 4px 12px; border-radius: 99px; margin-bottom: 12px;
}
.hero-heading { color: #fff; font-size: 1.8rem; font-weight: 800; letter-spacing: -.025em; margin-bottom: 6px; line-height: 1.2; }
.hero-heading span { color: #fbbf24; }
.hero-sub { color: rgba(255,255,255,.55); font-size: 0.87rem; margin-bottom: 22px; line-height: 1.5; }
.search-wrap { position: relative; }
.search-wrap .search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1rem; pointer-events: none; z-index: 2; }
#globalSearch {
    width: 100%; height: 54px; border-radius: 16px; border: 0;
    padding: 0 170px 0 52px;
    font-size: 0.95rem; font-family: inherit;
    background: rgba(255,255,255,.97);
    box-shadow: 0 10px 40px rgba(0,0,0,.3), 0 2px 8px rgba(0,0,0,.15);
    outline: none; transition: box-shadow .2s, background .2s;
}
#globalSearch:focus { background: #fff; box-shadow: 0 10px 40px rgba(0,0,0,.35), 0 0 0 3px rgba(251,191,36,.5); }
#globalSearch::placeholder { color: #b0b8c4; }
.search-actions { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); display: flex; gap: 6px; }
.btn-locme {
    height: 38px; border-radius: 11px; border: 0; padding: 0 14px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #fff; font-size: 0.8rem; font-weight: 700;
    cursor: pointer; display: flex; align-items: center; gap: 6px; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(245,158,11,.4);
    transition: all .15s;
}
.btn-locme:hover { background: linear-gradient(135deg, #f59e0b, #d97706); transform: scale(1.03); }
.btn-locme.locating { opacity: .7; pointer-events: none; }

/* ── Filter pill bar ───────────────────────────────── */
.filter-bar-wrap {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky; top: 56px; z-index: 90;
}
.filter-bar {
    display: flex; align-items: center; gap: 0;
    padding: 0 20px;
    overflow-x: auto; scrollbar-width: none;
}
.filter-bar::-webkit-scrollbar { display: none; }
.filter-section {
    display: flex; align-items: center; gap: 6px;
    padding: 10px 0; flex-shrink: 0;
}
.filter-divider {
    width: 1px; height: 28px; background: #e5e7eb;
    margin: 0 12px; flex-shrink: 0;
}
.filter-label {
    font-size: 0.72rem; font-weight: 700; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .06em;
    white-space: nowrap; flex-shrink: 0;
}
.pill {
    height: 34px; padding: 0 14px; border-radius: 99px;
    border: 1.5px solid #e5e7eb; background: #fff;
    font-size: 0.8rem; font-weight: 500; color: #4b5563;
    cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: all .15s; display: flex; align-items: center; gap: 6px;
    user-select: none;
}
.pill:hover { border-color: #6366f1; color: #6366f1; background: #f5f3ff; }
.pill.active {
    border-color: #6366f1; background: #6366f1; color: #fff;
    box-shadow: 0 2px 8px rgba(99,102,241,.3);
}
.pill.active i { color: #fff; }
.pill i { font-size: 0.72rem; }

/* ── Results bar ───────────────────────────────────── */
.results-bar {
    padding: 14px 24px 0;
    display: flex; align-items: center; justify-content: space-between;
}
.results-count { font-size: 0.85rem; color: #6b7280; }
.results-count strong { color: #111827; }
.taste-tag {
    font-size: 0.75rem; color: #f59e0b; font-weight: 600;
    display: flex; align-items: center; gap: 5px;
}

/* ── Promo carousel ────────────────────────────────── */
.promo-wrap { padding: 16px 24px 0; }
#promoCarousel { border-radius: 20px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,.14); }
#promoCarousel .carousel-inner { border-radius: 0; }
/* Each slide: fixed height, no overflow */
.banner-slide {
    height: 240px;
    display: flex;
    overflow: hidden;
    background: linear-gradient(120deg, #0f3d45 0%, #1a3a5c 100%);
}
/* LEFT: text content panel */
.banner-slide .banner-text {
    flex: 0 0 52%;
    display: flex; flex-direction: column; justify-content: center;
    padding: 28px 28px 28px 32px;
    background: linear-gradient(120deg, #0f2d35 0%, #152a48 100%);
    position: relative; z-index: 1;
}
.banner-slide .banner-text::after {
    content: ''; position: absolute; top: 0; right: -30px; bottom: 0;
    width: 60px;
    background: inherit;
    clip-path: polygon(0 0, 0% 100%, 100% 100%);
    z-index: 2;
}
/* RIGHT: image panel — fully contained, never overflows */
.banner-slide .banner-img {
    flex: 1;
    position: relative;
    overflow: hidden;
}
.banner-slide .banner-img img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover; object-position: center;
    display: block;
    transition: transform .6s ease;
}
.carousel-item.active .banner-slide .banner-img img { transform: scale(1.04); }
/* Fade left edge of image into the text panel */
.banner-slide .banner-img::before {
    content: ''; position: absolute; inset: 0; z-index: 1;
    background: linear-gradient(to right, #152a48 0%, transparent 35%);
}
/* Text styles */
.banner-slide .badge-promo {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 99px;
    background: rgba(251,191,36,.2); border: 1px solid rgba(251,191,36,.35);
    color: #fbbf24; font-size: 0.65rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase; margin-bottom: 10px;
    width: fit-content;
}
.banner-slide .banner-title {
    color: #fff; font-size: 1.25rem; font-weight: 800;
    line-height: 1.25; margin-bottom: 8px;
}
.banner-slide .banner-desc {
    color: rgba(255,255,255,.65); font-size: 0.78rem;
    line-height: 1.55; margin-bottom: 16px;
    display: -webkit-box; -webkit-line-clamp: 3;
    -webkit-box-orient: vertical; overflow: hidden;
}
.banner-slide .btn-banner {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fbbf24; color: #0f2d35;
    font-size: 0.76rem; font-weight: 800; padding: 8px 18px;
    border-radius: 99px; text-decoration: none;
    transition: transform .15s, box-shadow .15s;
    width: fit-content;
    box-shadow: 0 3px 12px rgba(251,191,36,.4);
}
.banner-slide .btn-banner:hover { transform: scale(1.04); box-shadow: 0 6px 20px rgba(251,191,36,.5); }
/* Carousel controls */
.carousel-control-prev, .carousel-control-next {
    width: 36px; height: 36px; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,.15); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 50%; margin: 0 10px;
    opacity: 1; transition: background .15s;
}
.carousel-control-prev:hover, .carousel-control-next:hover { background: rgba(255,255,255,.3); }
.carousel-control-prev-icon, .carousel-control-next-icon { width: 14px; height: 14px; }
.carousel-indicators { bottom: 10px; margin: 0; }
.carousel-indicators [data-bs-slide-to] {
    width: 6px; height: 6px; border-radius: 50%; border: 0;
    background: rgba(255,255,255,.4); margin: 0 3px; transition: all .25s;
}
.carousel-indicators [data-bs-slide-to].active { background: #fbbf24; width: 20px; border-radius: 99px; }

/* ── Restaurant grid ───────────────────────────────── */
.grid-wrap { padding: 18px 24px 40px; }
.restaurant-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(295px, 1fr)); gap: 20px; }

/* ── Restaurant card ───────────────────────────────── */
.r-card {
    background: #fff; border-radius: 18px; overflow: hidden;
    text-decoration: none; display: flex; flex-direction: column;
    border: 1px solid rgba(0,0,0,.05);
    transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.r-card:hover {
    transform: translateY(-5px) scale(1.005);
    box-shadow: 0 16px 48px rgba(0,0,0,.12);
    text-decoration: none;
}
.r-card-img {
    height: 192px; position: relative; overflow: hidden;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    flex-shrink: 0;
}
.r-card-img img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: center;
    display: block; transition: transform .4s ease;
}
.r-card:hover .r-card-img img { transform: scale(1.05); }
.r-card-img .no-img {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; opacity: .3;
}
.r-card-tags { position: absolute; top: 10px; left: 10px; display: flex; gap: 5px; flex-wrap: wrap; }
.r-tag {
    padding: 3px 9px; border-radius: 99px; font-size: 0.64rem; font-weight: 700;
    backdrop-filter: blur(8px); letter-spacing: .02em;
}
.r-tag-delivery { background: rgba(16,185,129,.9); color: #fff; }
.r-tag-reservation { background: rgba(99,102,241,.9); color: #fff; }
.r-tag-taste { background: rgba(245,158,11,.92); color: #fff; }
.fav-btn {
    position: absolute; top: 10px; right: 10px;
    width: 36px; height: 36px; border-radius: 50%; border: 0;
    background: rgba(255,255,255,.92); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: transform .15s, background .15s;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.fav-btn:hover { transform: scale(1.18); background: #fff; }
.r-card-body { padding: 16px 16px 12px; flex: 1; }
.r-name { font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 6px; line-height: 1.3; }
.r-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap; }
.r-cuisine-pill {
    padding: 2px 9px; border-radius: 99px; font-size: 0.68rem; font-weight: 600;
    background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
}
.r-rating { display: flex; align-items: center; gap: 4px; font-size: 0.78rem; color: #374151; font-weight: 600; }
.r-rating i { color: #f59e0b; font-size: 0.72rem; }
.r-reviews { font-size: 0.72rem; color: #9ca3af; font-weight: 400; }
.r-addr { font-size: 0.76rem; color: #9ca3af; display: flex; align-items: flex-start; gap: 5px; line-height: 1.4; }
.r-addr i { flex-shrink: 0; margin-top: 2px; font-size: 0.7rem; }
/* Distance strip — shown only when location is known */
.r-distance {
    display: none; /* JS shows it */
    align-items: center; gap: 8px;
    margin-top: 8px; padding: 7px 10px;
    background: #f8fafc; border-radius: 10px;
    border: 1px solid #e2e8f0;
}
.r-distance .dist-km {
    font-size: 0.78rem; font-weight: 700; color: #0f3d45;
    display: flex; align-items: center; gap: 4px;
}
.r-distance .dist-km i { color: #6366f1; font-size: 0.7rem; }
.r-distance .dist-modes { display: flex; gap: 6px; margin-left: auto; }
.r-distance .dist-mode {
    display: flex; align-items: center; gap: 4px;
    font-size: 0.7rem; color: #6b7280; font-weight: 500;
    background: #fff; border: 1px solid #e5e7eb;
    padding: 2px 7px; border-radius: 99px;
}
.r-distance .dist-mode i { font-size: 0.65rem; }
.r-card-footer {
    padding: 10px 16px 14px;
    border-top: 1px solid #f3f4f6;
    display: flex; align-items: center; justify-content: space-between;
}
.r-footer-info { font-size: 0.73rem; color: #9ca3af; display: flex; align-items: center; gap: 5px; }
.r-footer-cta {
    font-size: 0.73rem; font-weight: 700; color: #6366f1;
    display: flex; align-items: center; gap: 4px; text-decoration: none;
}

/* ── Empty state ───────────────────────────────────── */
.empty-state { padding: 80px 24px; text-align: center; }
.empty-state .icon { font-size: 3.5rem; margin-bottom: 16px; opacity: .3; }
.empty-state h5 { color: #374151; font-weight: 700; margin-bottom: 6px; }
.empty-state p { color: #9ca3af; font-size: 0.88rem; }
.empty-state .btn-reset { margin-top: 16px; padding: 9px 24px; border-radius: 99px; border: 0; background: #6366f1; color: #fff; font-weight: 600; font-size: 0.85rem; cursor: pointer; }

/* ── Card hide/show animation ──────────────────────── */
.r-card-wrap { transition: opacity .2s, transform .2s; }
.r-card-wrap.hidden { display: none; }

/* ── Responsive ───────────────────────────────────── */
@media (max-width: 640px) {
    .hero-bar { padding: 28px 16px 24px; }
    .hero-heading { font-size: 1.3rem; }
    #globalSearch { padding-right: 56px; height: 48px; }
    .search-actions .btn-locme span { display: none; }
    .btn-locme { padding: 0 12px; width: 40px; justify-content: center; }
    .banner-slide { height: 210px; }
    .promo-wrap { padding: 12px 12px 0; }
    .banner-slide { height: auto; flex-direction: column; }
    .banner-slide .banner-text { flex: none; padding: 20px 18px 16px; order: 2; }
    .banner-slide .banner-text::after { display: none; }
    .banner-slide .banner-img { flex: none; height: 150px; order: 1; }
    .banner-slide .banner-img::before { background: linear-gradient(to bottom, transparent 50%, #152a48 100%); }
    .banner-slide .banner-title { font-size: 1rem; }
    .banner-slide .banner-desc { -webkit-line-clamp: 2; }
    .grid-wrap { padding: 14px 12px 32px; }
    .restaurant-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .r-card-img { height: 140px; }
    .filter-bar { padding: 0 12px; }
    .results-bar { padding: 12px 16px 0; }
}
@media (max-width: 400px) {
    .restaurant-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
{{-- ═══════════════════════ HERO SEARCH ═══════════════════════ --}}
<div class="browse-page">
<div class="hero-bar">
    <div class="hero-inner">
        <div class="hero-eyebrow">
            <i class="fas fa-utensils"></i> RestoFinder
        </div>
        <h1 class="hero-heading">What are you <span>craving</span> today?</h1>
        <p class="hero-sub">Discover the best restaurants near you — browse menus, book a table, or order delivery.</p>
        <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="globalSearch" placeholder="Search restaurants, cuisine, or area…" autocomplete="off">
            <div class="search-actions">
                <button class="btn-locme" id="locBtn">
                    <i class="fas fa-location-dot"></i>
                    <span id="locLabel">Near me</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════ PROMO CAROUSEL ═══════════════════════ --}}
@if($banners->isNotEmpty())
<div class="promo-wrap">
    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner promo-carousel-inner">
            @foreach($banners as $i => $banner)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <div class="banner-slide">
                    {{-- Left: text --}}
                    <div class="banner-text">
                        <div class="badge-promo">
                            <i class="fas fa-tag"></i> Promotion
                        </div>
                        <div class="banner-title">{{ $banner->title }}</div>
                        @if($banner->description)
                        <div class="banner-desc">{{ $banner->description }}</div>
                        @endif
                        @if($banner->restaurant)
                        <a href="{{ route('client.restaurant', $banner->restaurant_id) }}" class="btn-banner">
                            <i class="fas fa-store-alt"></i>
                            {{ $banner->restaurant->name }}
                            <i class="fas fa-arrow-right" style="font-size:.6rem;"></i>
                        </a>
                        @endif
                    </div>
                    {{-- Right: image --}}
                    <div class="banner-img">
                        @if($banner->image_path)
                        <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}">
                        @else
                        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#1a3a5c,#4f46e5);"></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        <div class="carousel-indicators">
            @foreach($banners as $i => $b)
            <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="{{ $i }}"
                {{ $i===0 ? 'class=active aria-current=true' : '' }}></button>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif

{{-- ═══════════════════════ FILTER PILL BAR ═══════════════════════ --}}
<div class="filter-bar-wrap">
    <div class="filter-bar" id="filterBar">
        {{-- Sort pills --}}
        <div class="filter-section">
            <span class="filter-label">Sort</span>
            <button class="pill active" data-filter="sort" data-value="recommended">
                <i class="fas fa-star"></i> Recommended
            </button>
            <button class="pill" data-filter="sort" data-value="rating">
                <i class="fas fa-trophy"></i> Top Rated
            </button>
            <button class="pill" data-filter="sort" data-value="nearby">
                <i class="fas fa-location-dot"></i> Nearest
            </button>
        </div>
        <div class="filter-divider"></div>
        {{-- Cuisine pills --}}
        <div class="filter-section">
            <span class="filter-label">Cuisine</span>
            <button class="pill active" data-filter="cuisine" data-value="">
                All
            </button>
            @foreach($cuisines as $cuisine)
            <button class="pill" data-filter="cuisine" data-value="{{ $cuisine->id }}" data-name="{{ strtolower($cuisine->name) }}">
                {{ $cuisine->name }}
            </button>
            @endforeach
        </div>
        {{-- Service pills --}}
        <div class="filter-divider"></div>
        <div class="filter-section">
            <span class="filter-label">Features</span>
            <button class="pill" data-filter="service" data-value="delivery">
                <i class="fas fa-motorcycle"></i> Delivery
            </button>
            <button class="pill" data-filter="service" data-value="reservation">
                <i class="fas fa-calendar-check"></i> Reservations
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════ RESULTS COUNT ═══════════════════════ --}}
<div class="results-bar">
    <p class="results-count mb-0">
        Showing <strong id="visibleCount">{{ $restaurants->count() }}</strong>
        of <strong>{{ $restaurants->count() }}</strong> restaurant{{ $restaurants->count()!=1?'s':'' }}
    </p>
    @if(is_array(auth()->user()->preferences) && count(auth()->user()->preferences))
    <span class="taste-tag">
        <i class="fas fa-heart-pulse"></i> Sorted by your taste
    </span>
    @endif
</div>

{{-- ═══════════════════════ RESTAURANT GRID ═══════════════════════ --}}
<div class="grid-wrap">
    <div class="restaurant-grid" id="restaurantGrid">
        @forelse($restaurants as $r)
        <div class="r-card-wrap"
             data-name="{{ strtolower($r->name) }}"
             data-addr="{{ strtolower($r->address ?? '') }}"
             data-cuisine-id="{{ $r->cuisine_id }}"
             data-cuisine="{{ strtolower($r->cuisine->name ?? '') }}"
             data-rating="{{ $r->avg_rating }}"
             data-pref="{{ $r->pref_score }}"
             data-lat="{{ $r->latitude ?? '' }}"
             data-lng="{{ $r->longitude ?? '' }}"
             data-delivery="{{ $r->accepts_delivery ? '1' : '0' }}"
             data-reservation="{{ $r->accepts_reservations ? '1' : '0' }}">
            <a href="{{ route('client.restaurant', $r->id) }}" class="r-card">
                {{-- Image --}}
                <div class="r-card-img">
                    @if($r->image)
                    <img src="{{ $r->image }}" alt="{{ $r->name }}" loading="lazy">
                    @else
                    <div class="no-img">🍽️</div>
                    @endif
                    {{-- Tags --}}
                    <div class="r-card-tags">
                        @if($r->accepts_delivery)
                        <span class="r-tag r-tag-delivery"><i class="fas fa-motorcycle me-1"></i>Delivery</span>
                        @endif
                        @if($r->accepts_reservations)
                        <span class="r-tag r-tag-reservation"><i class="fas fa-calendar-check me-1"></i>Book</span>
                        @endif
                        @if($r->pref_score > 0)
                        <span class="r-tag r-tag-taste"><i class="fas fa-fire me-1"></i>Matches taste</span>
                        @endif
                    </div>
                    {{-- Favorite --}}
                    <button class="fav-btn" data-id="{{ $r->id }}" data-fav="{{ $r->is_favorite ? '1' : '0' }}"
                        onclick="event.preventDefault();event.stopPropagation();toggleFav(this)">
                        <i class="fa{{ $r->is_favorite ? 's' : 'r' }} fa-heart"
                           style="font-size:0.9rem;color:{{ $r->is_favorite ? '#ef4444' : '#d1d5db' }};"></i>
                    </button>
                </div>
                {{-- Body --}}
                <div class="r-card-body">
                    <div class="r-name">{{ $r->name }}</div>
                    <div class="r-meta">
                        @if($r->cuisine)
                        <span class="r-cuisine-pill">{{ $r->cuisine->name }}</span>
                        @endif
                        <span class="r-rating">
                            <i class="fas fa-star"></i>
                            {{ $r->avg_rating > 0 ? $r->avg_rating : '—' }}
                            <span class="r-reviews">({{ $r->review_count }})</span>
                        </span>
                        @if($r->price_range)
                        <span style="font-size:.72rem;color:#9ca3af;">{{ $r->price_range }}</span>
                        @endif
                    </div>
                    <div class="r-addr">
                        <i class="fas fa-location-dot"></i>
                        <span>{{ Str::limit($r->address, 55) }}</span>
                    </div>
                    {{-- Distance strip — populated by JS when user shares location --}}
                    @if($r->latitude && $r->longitude)
                    <div class="r-distance" id="dist-{{ $r->id }}">
                        <span class="dist-km">
                            <i class="fas fa-route"></i>
                            <span class="dist-val">—</span>
                        </span>
                        <div class="dist-modes">
                            <span class="dist-mode walk">
                                <i class="fas fa-person-walking"></i>
                                <span class="walk-time">—</span>
                            </span>
                            <span class="dist-mode car">
                                <i class="fas fa-car"></i>
                                <span class="car-time">—</span>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                {{-- Footer --}}
                <div class="r-card-footer">
                    <span class="r-footer-info">
                        <i class="fas fa-utensils"></i>
                        {{ $r->menu_items_count }} items
                    </span>
                    <span class="r-footer-cta">
                        View menu <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>
        @empty
        @endforelse
    </div>

    {{-- Empty state --}}
    <div class="empty-state" id="emptyState" style="display:none;">
        <div class="icon">🔍</div>
        <h5>No restaurants found</h5>
        <p>Try a different search term or adjust your filters.</p>
        <button class="btn-reset" onclick="resetAllFilters()">Clear filters</button>
    </div>
</div>
</div>

{{-- Embed all restaurant data for client-side sorting --}}
@php
$restaurantJsonData = $restaurants->map(function($r) {
    return [
        'id'         => $r->id,
        'name'       => $r->name,
        'avg_rating' => $r->avg_rating,
        'pref_score' => $r->pref_score,
        'lat'        => $r->latitude,
        'lng'        => $r->longitude,
    ];
})->values();
@endphp
<script id="allRestaurantsData" type="application/json">@json($restaurantJsonData)</script>
@endsection

@section('script')
<script>
(function() {
    // ── State ──────────────────────────────────────────────────
    var state = {
        search: '',
        sort: 'recommended',
        cuisine: '',
        service: '',
        userLat: null,
        userLng: null,
    };

    var allData = JSON.parse(document.getElementById('allRestaurantsData').textContent || '[]');
    var grid    = document.getElementById('restaurantGrid');
    var countEl = document.getElementById('visibleCount');
    var emptyEl = document.getElementById('emptyState');
    var searchDebounce;

    // ── Filter pill clicks ─────────────────────────────────────
    document.querySelectorAll('.pill').forEach(function(pill) {
        pill.addEventListener('click', function() {
            var filter = this.dataset.filter;
            var value  = this.dataset.value;

            if (filter === 'service') {
                // Toggle
                if (this.classList.contains('active')) {
                    this.classList.remove('active');
                    state.service = '';
                } else {
                    document.querySelectorAll('.pill[data-filter="service"]').forEach(function(p) { p.classList.remove('active'); });
                    this.classList.add('active');
                    state.service = value;
                }
            } else {
                // Single-select group
                document.querySelectorAll('.pill[data-filter="' + filter + '"]').forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                if (filter === 'sort') {
                    if (value === 'nearby') {
                        requestLocation();
                    } else {
                        state.sort = value;
                    }
                }
                if (filter === 'cuisine') state.cuisine = value;
            }
            applyFilters();
        });
    });

    // ── Search ─────────────────────────────────────────────────
    document.getElementById('globalSearch').addEventListener('input', function() {
        clearTimeout(searchDebounce);
        var val = this.value;
        searchDebounce = setTimeout(function() {
            state.search = val.toLowerCase().trim();
            applyFilters();
        }, 180);
    });

    // ── Geolocation ────────────────────────────────────────────
    document.getElementById('locBtn').addEventListener('click', function() {
        requestLocation(true); // manual click → also sort by nearby
    });

    function requestLocation(sortOnSuccess) {
        var btn   = document.getElementById('locBtn');
        var label = document.getElementById('locLabel');
        if (!navigator.geolocation) return;
        btn.classList.add('locating');
        navigator.geolocation.getCurrentPosition(function(pos) {
            state.userLat = pos.coords.latitude;
            state.userLng = pos.coords.longitude;
            label.textContent = 'Near me ✓';
            btn.classList.remove('locating');
            updateDistanceBadges();
            if (sortOnSuccess) {
                state.sort = 'nearby';
                document.querySelectorAll('.pill[data-filter="sort"]').forEach(function(p) { p.classList.remove('active'); });
                document.querySelector('.pill[data-value="nearby"]').classList.add('active');
                applyFilters();
            }
        }, function() {
            label.textContent = 'Near me';
            btn.classList.remove('locating');
        });
    }

    // Auto-request location silently on page load — just populate distance badges
    requestLocation(false);

    // ── Distance badges ────────────────────────────────────────
    function fmtDist(km) {
        if (km < 1) return Math.round(km * 1000) + ' m';
        return km.toFixed(1) + ' km';
    }
    function fmtTime(minutes) {
        if (minutes < 60) return Math.round(minutes) + ' min';
        return Math.floor(minutes / 60) + 'h ' + Math.round(minutes % 60) + 'm';
    }

    function updateDistanceBadges() {
        if (state.userLat === null) return;
        var wraps = Array.from(grid.querySelectorAll('.r-card-wrap'));
        wraps.forEach(function(w) {
            var rLat = parseFloat(w.dataset.lat);
            var rLng = parseFloat(w.dataset.lng);
            if (!rLat || !rLng) return;

            var km      = haversine(state.userLat, state.userLng, rLat, rLng);
            // Walking: 5 km/h average. Driving: 30 km/h urban average.
            var walkMin = (km / 5) * 60;
            var carMin  = (km / 30) * 60;

            // Find the card's restaurant id from its dist element
            var distEl = w.querySelector('[id^="dist-"]');
            if (!distEl) return;

            distEl.querySelector('.dist-val').textContent   = fmtDist(km);
            distEl.querySelector('.walk-time').textContent  = fmtTime(walkMin);
            distEl.querySelector('.car-time').textContent   = fmtTime(carMin);
            distEl.style.display = 'flex';
        });
    }

    // ── Core filter + sort ─────────────────────────────────────
    function applyFilters() {
        var wraps = Array.from(grid.querySelectorAll('.r-card-wrap'));
        var visible = [];

        wraps.forEach(function(w) {
            var ok = true;

            // Search
            if (state.search) {
                var haystack = (w.dataset.name + ' ' + w.dataset.addr + ' ' + w.dataset.cuisine);
                if (haystack.indexOf(state.search) === -1) ok = false;
            }

            // Cuisine
            if (state.cuisine && w.dataset.cuisineId !== state.cuisine) ok = false;

            // Service
            if (state.service === 'delivery'    && w.dataset.delivery !== '1')    ok = false;
            if (state.service === 'reservation' && w.dataset.reservation !== '1') ok = false;

            if (ok) visible.push(w);
            w.classList.toggle('hidden', !ok);
        });

        // Sort visible set
        sortVisible(visible);

        // Update count
        countEl.textContent = visible.length;
        emptyEl.style.display = visible.length === 0 ? 'block' : 'none';
        grid.style.display    = visible.length === 0 ? 'none'  : 'grid';
    }

    function sortVisible(visibleWraps) {
        if (visibleWraps.length === 0) return;

        visibleWraps.sort(function(a, b) {
            if (state.sort === 'rating') {
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            }
            if (state.sort === 'nearby' && state.userLat !== null) {
                var distA = haversine(state.userLat, state.userLng, parseFloat(a.dataset.lat || 0), parseFloat(a.dataset.lng || 0));
                var distB = haversine(state.userLat, state.userLng, parseFloat(b.dataset.lat || 0), parseFloat(b.dataset.lng || 0));
                return distA - distB;
            }
            // recommended: pref desc, then rating desc
            var prefDiff = parseFloat(b.dataset.pref) - parseFloat(a.dataset.pref);
            if (prefDiff !== 0) return prefDiff;
            return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
        });

        visibleWraps.forEach(function(w) { grid.appendChild(w); });
    }

    function haversine(lat1, lng1, lat2, lng2) {
        if (!lat2 || !lng2) return 99999;
        var R = 6371, dLat = (lat2-lat1)*Math.PI/180, dLng = (lng2-lng1)*Math.PI/180;
        var a = Math.sin(dLat/2)*Math.sin(dLat/2) +
                Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)*Math.sin(dLng/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // ── Reset helper ────────────────────────────────────────────
    window.resetAllFilters = function() {
        state = { search: '', sort: 'recommended', cuisine: '', service: '', userLat: null, userLng: null };
        document.getElementById('globalSearch').value = '';
        document.querySelectorAll('.pill').forEach(function(p) { p.classList.remove('active'); });
        document.querySelector('.pill[data-value="recommended"]').classList.add('active');
        document.querySelector('.pill[data-filter="cuisine"][data-value=""]').classList.add('active');
        applyFilters();
    };

    // ── Favorite toggle ─────────────────────────────────────────
    window.toggleFav = function(btn) {
        var id  = btn.dataset.id;
        var isFav = btn.dataset.fav === '1';
        $.ajax({
            url: "{{ route('client.favorite') }}",
            type: 'POST',
            data: { restaurant_id: id, _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.dataset.fav = res.favorited ? '1' : '0';
                var icon = btn.querySelector('i');
                icon.className = res.favorited ? 'fas fa-heart' : 'far fa-heart';
                icon.style.color = res.favorited ? '#ef4444' : '#d1d5db';
            }
        });
    };

})();
</script>
@endsection
