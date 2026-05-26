@extends('layouts.app')

@section('title', 'DigiLibrary - Perpustakaan Digital')

@push('styles')
<style>
/* ===========================
   SECTION SHARED
=========================== */
.section-v2 { padding: 80px 0; }
.section-gray { background: #f7f8fc; }
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.section-tag {
    display: inline-block;
    background: rgba(26,86,219,.1);
    color: #1a56db;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 5px 14px; border-radius: 99px;
    margin-bottom: 10px;
}
.section-title-v2 {
    font-size: clamp(1.5rem, 2.5vw, 2.1rem);
    font-weight: 800; color: #111827;
    letter-spacing: -.5px; line-height: 1.2;
    margin-bottom: 8px;
}

/* ===========================
   HERO
=========================== */
.hero-v2 {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    min-height: 100vh;
    display: flex; align-items: stretch;
    position: relative; overflow: hidden;
}

/* Animated bg dots */
.hero-v2::before {
    content: '';
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(255,255,255,.05) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: bgShift 30s linear infinite;
}
@keyframes bgShift {
    from { background-position: 0 0; }
    to   { background-position: 40px 40px; }
}

/* Orbs */
.hero-v2::after {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(26,86,219,.25) 0%, transparent 70%);
    top: -200px; right: -100px;
    pointer-events: none;
}

.hero-orb2 {
    position: absolute;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(14,159,110,.2) 0%, transparent 70%);
    bottom: -100px; left: 10%;
    pointer-events: none;
}

.hero-v2-inner {
    position: relative; z-index: 2;
    width: 100%; padding: 0 40px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px; align-items: center;
    min-height: 100vh;
}

.hero-v2-text {
    display: flex; flex-direction: column;
    gap: 24px; padding: 80px 0;
}

.hero-tag {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.75);
    padding: 8px 16px; border-radius: 99px;
    font-size: .82rem; font-weight: 600;
    width: fit-content;
    backdrop-filter: blur(8px);
    animation: fadeUp .6s ease .2s both;
}
.hero-tag-pulse {
    width: 6px; height: 6px;
    border-radius: 50%; background: #0e9f6e;
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.5);opacity:.6} }

.hero-v2-text h1 {
    font-size: clamp(2.2rem, 4vw, 3.4rem);
    font-weight: 800; line-height: 1.1;
    letter-spacing: -1.5px; color: white;
    animation: fadeUp .6s ease .4s both;
}
.hero-v2-text h1 .txt-gradient {
    background: linear-gradient(135deg, #60a5fa, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-sub {
    font-size: 1rem; color: rgba(255,255,255,.55);
    line-height: 1.8; max-width: 420px;
    animation: fadeUp .6s ease .6s both;
}

/* Search */
.hero-search {
    display: flex; align-items: center;
    background: rgba(255,255,255,.08);
    border: 1.5px solid rgba(255,255,255,.15);
    border-radius: 99px;
    padding: 6px 6px 6px 20px;
    max-width: 460px;
    backdrop-filter: blur(12px);
    transition: border-color .3s, box-shadow .3s;
    animation: fadeUp .6s ease .8s both;
}
.hero-search:focus-within {
    border-color: rgba(96,165,250,.6);
    box-shadow: 0 0 0 4px rgba(26,86,219,.15);
}
.hero-search input {
    flex: 1; background: none; border: none; outline: none;
    color: white; font-size: .95rem;
    font-family: inherit; padding-right: 12px;
}
.hero-search input::placeholder { color: rgba(255,255,255,.3); }
.hero-search button {
    padding: 11px 22px; border-radius: 99px;
    background: linear-gradient(135deg, #1a56db, #0e9f6e);
    color: white; border: none;
    font-family: inherit; font-size: .875rem; font-weight: 700;
    cursor: pointer; white-space: nowrap;
    transition: transform .2s, box-shadow .2s;
}
.hero-search button:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 20px rgba(26,86,219,.4);
}
.search-icon { color: rgba(255,255,255,.4); flex-shrink: 0; }

/* Stats */
.hero-stats {
    display: flex; align-items: center; gap: 28px;
    animation: fadeUp .6s ease 1s both;
}
.hero-stat { display: flex; flex-direction: column; gap: 2px; }
.hero-stat strong {
    font-size: 1.4rem; font-weight: 800;
    background: linear-gradient(135deg, #60a5fa, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-stat span { font-size: .75rem; color: rgba(255,255,255,.4); font-weight: 500; }
.hero-stat-divider { width: 1px; height: 32px; background: rgba(255,255,255,.1); }

/* Hero Right — Featured Book */
.hero-v2-image {
    display: flex; align-items: center; justify-content: center;
    padding: 60px 0;
    animation: fadeIn .8s ease .5s both;
}

.hero-book-showcase {
    position: relative; width: 100%; max-width: 420px;
}

.hbs-main {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 24px;
    padding: 28px;
    backdrop-filter: blur(16px);
    display: grid; grid-template-columns: 120px 1fr; gap: 20px;
    align-items: center;
}
.hbs-cover {
    width: 120px; height: 168px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0,0,0,.4);
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; font-weight: 900;
    color: rgba(255,255,255,.5);
}
.hbs-cover img { width:100%;height:100%;object-fit:cover; }
.hbs-info { display:flex;flex-direction:column;gap:10px; }
.hbs-badge {
    display: inline-flex; align-items: center;
    background: rgba(14,159,110,.2); border: 1px solid rgba(14,159,110,.3);
    color: #34d399; font-size: .68rem; font-weight: 700;
    padding: 4px 10px; border-radius: 99px;
    width: fit-content; letter-spacing: .05em;
    text-transform: uppercase;
}
.hbs-title {
    font-size: 1rem; font-weight: 800; color: white;
    line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.hbs-author { font-size: .82rem; color: rgba(255,255,255,.5); }
.hbs-stars { display:flex; align-items:center; gap:2px; }
.hbs-star-on  { color: #fbbf24; font-size: .9rem; }
.hbs-star-off { color: rgba(255,255,255,.2); font-size: .9rem; }
.hbs-rating-num { font-size: .75rem; color: rgba(255,255,255,.4); margin-left: 5px; }
.hbs-stok {
    display:flex; align-items:center; gap:6px;
    font-size: .78rem; color: rgba(255,255,255,.45);
}
.hbs-dot { width:6px;height:6px;border-radius:50%; }
.hbs-dot.green { background:#34d399; }
.hbs-dot.red   { background:#f87171; }
.btn-hero-pinjam {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 18px; border-radius: 10px;
    background: linear-gradient(135deg, #1a56db, #0e9f6e);
    color: white; font-size: .82rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    font-family: inherit;
    transition: transform .2s, box-shadow .2s;
    width: fit-content;
}
.btn-hero-pinjam:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(26,86,219,.4);
}
.btn-hero-pinjam.disabled {
    background: rgba(255,255,255,.1);
    cursor: not-allowed; transform: none;
}

/* Floating mini cards */
.hbs-float-card {
    position: absolute;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px;
    padding: 10px 14px;
    backdrop-filter: blur(12px);
    display: flex; align-items: center; gap: 8px;
    color: white;
}
.hbs-fc-1 { top: -20px; right: -10px; animation: floatCard 3s ease-in-out infinite; }
.hbs-fc-2 { bottom: -16px; left: -10px; animation: floatCard 3.5s ease-in-out infinite .5s; }
.hbs-fc-icon { font-size: 1.2rem; }
.hbs-fc-text { font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.8); }
.hbs-fc-sub  { font-size: .65rem; color: rgba(255,255,255,.4); }
@keyframes floatCard {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-8px); }
}

/* Scroll hint */
.hero-scroll {
    position: absolute; bottom: 32px; left: 50%;
    transform: translateX(-50%);
    z-index: 3; display: flex; flex-direction: column;
    align-items: center; gap: 8px;
    color: rgba(255,255,255,.25);
    font-size: .68rem; letter-spacing: .12em;
    text-transform: uppercase;
    animation: fadeIn 1s ease 1.5s both;
}
.hero-scroll-line {
    width: 1px; height: 36px;
    background: rgba(255,255,255,.1);
    position: relative; overflow: hidden;
}
.hero-scroll-line::after {
    content: '';
    position: absolute; top: -50%; width: 100%; height: 50%;
    background: rgba(255,255,255,.4);
    animation: scrollDown 1.8s ease-in-out infinite;
}
@keyframes scrollDown { from{top:-50%} to{top:150%} }

@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }

/* ===========================
   TICKER
=========================== */
.ticker {
    background: #1a56db; padding: 13px 0;
    overflow: hidden; white-space: nowrap;
}
.ticker-track {
    display: inline-flex; gap: 40px;
    animation: ticker 22s linear infinite;
}
.ticker-item {
    display: flex; align-items: center; gap: 12px;
    font-size: .82rem; font-weight: 700;
    color: rgba(255,255,255,.8); flex-shrink: 0;
}
.ticker-sep { color: rgba(255,255,255,.3); }
@keyframes ticker { from{transform:translateX(0)} to{transform:translateX(-50%)} }

/* ===========================
   BUKU POPULER
=========================== */
.populer-section { background: white; }

.populer-header {
    display: flex; align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 40px;
}
.btn-lihat-semua {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 20px; border-radius: 10px;
    border: 2px solid #1a56db; color: #1a56db;
    font-size: .875rem; font-weight: 700;
    text-decoration: none;
    transition: all .25s;
}
.btn-lihat-semua:hover {
    background: #1a56db; color: white;
}

/* Slider */
.populer-slider-wrap { position: relative; }
.populer-slider-nav {
    display: flex; gap: 8px; margin-top: 24px;
    justify-content: center; align-items: center;
}
.psn-btn {
    width: 40px; height: 40px; border-radius: 50%;
    border: 2px solid #e5e7eb; background: white;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; font-size: .9rem;
    color: #374151; transition: all .2s;
}
.psn-btn:hover { border-color: #1a56db; color: #1a56db; background: #eff6ff; }
.psn-dots { display: flex; gap: 6px; }
.psn-dot {
    width: 8px; height: 8px; border-radius: 99px;
    background: #e5e7eb; border: none; cursor: pointer;
    padding: 0; transition: all .3s;
}
.psn-dot.active { width: 26px; background: #1a56db; }

.populer-track-outer { overflow: hidden; border-radius: 20px; }
.populer-track {
    display: flex; gap: 20px;
    transition: transform .6s cubic-bezier(.25,.46,.45,.94);
}

/* Buku Card Besar */
.pop-card {
    min-width: calc(33.333% - 14px);
    background: #fafafa;
    border: 1.5px solid #f1f5f9;
    border-radius: 20px;
    overflow: hidden;
    transition: all .3s;
    cursor: pointer;
    display: flex; flex-direction: column;
}
.pop-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(0,0,0,.1);
    border-color: #dbeafe;
}

.pop-card-cover {
    height: 200px;
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.pop-card-cover img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .4s;
}
.pop-card:hover .pop-card-cover img { transform: scale(1.05); }
.pop-cover-abbr {
    font-size: 2.8rem; font-weight: 900;
    color: rgba(255,255,255,.4);
    letter-spacing: -2px; position: relative; z-index: 1;
}
.pop-cat-pill {
    position: absolute; top: 12px; left: 12px;
    background: white; color: #111827;
    font-size: .65rem; font-weight: 700;
    padding: 4px 10px; border-radius: 99px;
    letter-spacing: .04em; text-transform: uppercase;
}
.pop-stok-pill {
    position: absolute; top: 12px; right: 12px;
    font-size: .65rem; font-weight: 700;
    padding: 4px 10px; border-radius: 99px;
}
.stok-ok { background: rgba(22,197,131,.12); color: #059669; }
.stok-xs { background: rgba(245,158,11,.15); color: #d97706; }
.stok-no { background: rgba(239,68,68,.12); color: #dc2626; }

.pop-card-body { padding: 18px 20px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
.pop-kat { font-size: .68rem; font-weight: 700; color: #1a56db; text-transform: uppercase; letter-spacing: .08em; }
.pop-title {
    font-size: .98rem; font-weight: 800; color: #111827; line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.pop-author { font-size: .8rem; color: #6b7280; }
.pop-meta {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-top: auto; padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}
.pop-stars { display: flex; align-items: center; gap: 2px; }
.pop-star-on  { color: #f59e0b; font-size: .85rem; }
.pop-star-off { color: #e5e7eb; font-size: .85rem; }
.pop-rnum { font-size: .72rem; color: #9ca3af; margin-left: 4px; }
.btn-pop-pinjam {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 7px 14px; border-radius: 99px;
    background: #111827; color: white;
    font-size: .75rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    font-family: inherit; transition: all .2s;
}
.btn-pop-pinjam:hover { background: #1a56db; transform: scale(1.04); }
.btn-pop-pinjam.disabled {
    background: #e5e7eb; color: #9ca3af;
    cursor: not-allowed; transform: none;
}

/* ===========================
   KATEGORI
=========================== */
.cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 14px;
    margin-top: 36px;
}
.category-card {
    display: flex; flex-direction: column;
    align-items: center; gap: 10px;
    padding: 22px 16px; border-radius: 16px;
    border: 2px solid transparent;
    text-decoration: none; text-align: center;
    transition: all .3s;
}
.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}
.category-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center;
    justify-content: center; font-size: 1.5rem;
    transition: transform .3s;
}
.category-card:hover .category-icon { transform: rotate(-5deg) scale(1.1); }
.category-name { font-size: .875rem; font-weight: 700; }
.category-count { font-size: .72rem; color: #9ca3af; }

/* ===========================
   SEMUA BUKU
=========================== */
.filter-bar {
    background: white;
    border: 1.5px solid #f1f5f9;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 32px;
}
.filter-bar form {
    display: flex; align-items: center;
    gap: 10px; flex-wrap: wrap;
}
.search-input {
    flex: 1; min-width: 180px;
    padding: 10px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-family: inherit; font-size: .875rem;
    outline: none; transition: border-color .2s;
}
.search-input:focus { border-color: #1a56db; }
.filter-select {
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-family: inherit; font-size: .875rem;
    outline: none; cursor: pointer;
    background: white; color: #111827;
    transition: border-color .2s;
}
.filter-select:focus { border-color: #1a56db; }
.btn-search {
    padding: 10px 20px; border-radius: 10px;
    background: #111827; color: white;
    font-family: inherit; font-size: .875rem; font-weight: 700;
    border: none; cursor: pointer; transition: background .2s;
    text-decoration: none; display: inline-flex; align-items: center;
}
.btn-search:hover { background: #1a56db; }

/* Books Grid */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 20px;
}
.book-card {
    background: white; border-radius: 14px;
    overflow: hidden; border: 1.5px solid #f1f5f9;
    transition: all .3s; cursor: pointer;
}
.book-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,.1);
    border-color: #dbeafe;
}
.book-cover-placeholder {
    height: 180px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem; color: rgba(255,255,255,.5);
    font-weight: 900; position: relative; overflow: hidden;
}
.book-cover-placeholder img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover;
}
.book-info { padding: 14px; }
.book-category {
    font-size: .65rem; font-weight: 700;
    color: #1a56db; text-transform: uppercase;
    letter-spacing: .08em; margin-bottom: 5px;
}
.book-title {
    font-size: .9rem; font-weight: 700; color: #111827;
    line-height: 1.3; margin-bottom: 3px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.book-author { font-size: .75rem; color: #9ca3af; margin-bottom: 12px; }
.book-footer { display: flex; align-items: center; justify-content: space-between; }
.book-stok { font-size: .72rem; font-weight: 600; color: #059669; }
.book-stok.habis { color: #dc2626; }
.btn-pinjam {
    padding: 6px 14px; border-radius: 99px;
    background: #111827; color: white;
    font-size: .75rem; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    font-family: inherit; transition: background .2s;
    display: inline-flex;
}
.btn-pinjam:hover { background: #1a56db; }
.btn-pinjam.disabled {
    background: #e5e7eb; color: #9ca3af; cursor: not-allowed;
}

/* Empty */
.empty-state {
    text-align: center; padding: 60px 20px; color: #9ca3af;
}
.empty-state .emoji { font-size: 3rem; display: block; margin-bottom: 12px; }
.empty-state h3 { font-size: 1.1rem; color: #374151; margin-bottom: 6px; }

/* Pagination */
.pagination-wrap { display: flex; justify-content: center; margin-top: 40px; }

/* ===========================
   CTA
=========================== */
.cta {
    background: linear-gradient(135deg, #0f172a, #1e1b4b);
    padding: 100px 0; text-align: center;
    position: relative; overflow: hidden;
}
.cta::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(26,86,219,.3) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 50%, rgba(14,159,110,.2) 0%, transparent 60%);
}
.cta-inner { position: relative; z-index: 1; }
.cta h2 {
    font-size: clamp(1.8rem, 3vw, 2.8rem);
    font-weight: 800; color: white;
    letter-spacing: -1px; line-height: 1.2;
    margin-bottom: 14px;
}
.cta p { font-size: 1rem; color: rgba(255,255,255,.5); margin-bottom: 36px; }
.cta-buttons {
    display: flex; gap: 14px;
    justify-content: center; flex-wrap: wrap;
}
.btn-hero-primary {
    padding: 14px 32px; border-radius: 10px;
    background: linear-gradient(135deg, #1a56db, #0e9f6e);
    color: white; font-weight: 700; font-size: .95rem;
    text-decoration: none; transition: all .3s;
    box-shadow: 0 8px 24px rgba(26,86,219,.3);
    display: inline-flex; align-items: center;
}
.btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(26,86,219,.4); }
.btn-hero-secondary {
    padding: 14px 28px; border-radius: 10px;
    border: 1.5px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.05);
    color: white; font-weight: 600; font-size: .95rem;
    text-decoration: none; transition: all .3s;
    backdrop-filter: blur(8px);
    display: inline-flex; align-items: center;
}
.btn-hero-secondary:hover { border-color: rgba(255,255,255,.4); background: rgba(255,255,255,.1); }

/* ===========================
   SCROLL TOP
=========================== */
.scroll-top {
    position: fixed; bottom: 28px; right: 28px;
    width: 42px; height: 42px; border-radius: 50%;
    background: #111827; color: white;
    border: none; cursor: pointer; font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    opacity: 0; visibility: hidden;
    transition: all .3s; z-index: 100;
}
.scroll-top.show { opacity: 1; visibility: visible; }
.scroll-top:hover { background: #1a56db; transform: translateY(-2px); }

/* ===========================
   REVEAL ANIMATION
=========================== */
.reveal {
    opacity: 0; transform: translateY(30px);
    transition: opacity .7s ease, transform .7s ease;
}
.reveal.visible { opacity: 1; transform: translateY(0); }

/* ===========================
   RESPONSIVE
=========================== */
@media (max-width: 1024px) {
    .pop-card { min-width: calc(50% - 10px); }
}
@media (max-width: 900px) {
    .hero-v2-inner { grid-template-columns: 1fr; gap: 0; padding: 0 24px; }
    .hero-v2-image { display: none; }
    .hero-v2-text { padding: 80px 0 40px; }
    .populer-header { flex-direction: column; align-items: flex-start; gap: 12px; }
}
@media (max-width: 640px) {
    .pop-card { min-width: 100%; }
    .books-grid { grid-template-columns: repeat(2, 1fr); }
    .hero-v2-text h1 { font-size: 2rem; }
    .cat-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hero-v2">
    <div class="hero-orb2"></div>

    <div class="hero-v2-inner">
        {{-- KIRI --}}
        <div class="hero-v2-text">
            <div class="hero-tag">
                <span class="hero-tag-pulse"></span>
                Perpustakaan Digital SMK RPL
            </div>

            <h1>
                Baca Lebih Banyak.<br>
                <span class="txt-gradient">Belajar Lebih Pintar.</span>
            </h1>

            <p class="hero-sub">
                Akses ratusan koleksi buku digital, pinjam dengan mudah,
                dan tingkatkan pengetahuanmu kapan saja dan di mana saja.
            </p>

            <form action="{{ route('home') }}" method="GET" class="hero-search">
                <span class="search-icon">🔍</span>
                <input type="text" name="search"
                    placeholder="Cari judul buku, penulis..."
                    value="{{ request('search') }}">
                <button type="submit">Cari →</button>
            </form>

            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>{{ $books->total() }}+</strong>
                    <span>Koleksi Buku</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <strong>{{ $categories->count() }}+</strong>
                    <span>Kategori</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <strong>100%</strong>
                    <span>Gratis</span>
                </div>
            </div>
        </div>

        {{-- KANAN — Showcase buku featured --}}
        <div class="hero-v2-image">
            @if($bukuPopuler->count() > 0)
            @php $featured = $bukuPopuler->first(); $rating = round($featured->averageRating(),1); @endphp
            <div class="hero-book-showcase">

                {{-- Float card atas --}}
                <div class="hbs-float-card hbs-fc-1">
                    <span class="hbs-fc-icon">🏆</span>
                    <div>
                        <div class="hbs-fc-text">Paling Populer</div>
                        <div class="hbs-fc-sub">Bulan Ini</div>
                    </div>
                </div>

                {{-- Kartu utama --}}
                <div class="hbs-main">
                    <div class="hbs-cover"
                        style="background:linear-gradient(135deg,#1a46d8,#0d35a8)">
                        @if($featured->gambar)
                            <img src="{{ asset('storage/'.$featured->gambar) }}" alt="{{ $featured->judul }}">
                        @else
                            {{ strtoupper(substr($featured->judul,0,2)) }}
                        @endif
                    </div>
                    <div class="hbs-info">
                        <span class="hbs-badge">{{ $featured->category->nama_kategori ?? 'Buku' }}</span>
                        <div class="hbs-title">{{ $featured->judul }}</div>
                        <div class="hbs-author">{{ $featured->penulis }}</div>
                        <div class="hbs-stars">
                            @for($s=1;$s<=5;$s++)
                            <span class="{{ $s<=$rating?'hbs-star-on':'hbs-star-off' }}">★</span>
                            @endfor
                            <span class="hbs-rating-num">{{ $rating > 0 ? $rating : 'Baru' }}</span>
                        </div>
                        <div class="hbs-stok">
                            <span class="hbs-dot {{ $featured->stok > 0 ? 'green':'red' }}"></span>
                            {{ $featured->stok > 0 ? 'Tersedia ('.$featured->stok.' stok)' : 'Stok Habis' }}
                        </div>
                        @auth
                            @if($featured->stok > 0)
                            <a href="{{ route('user.buku.detail', $featured->id) }}"
                                class="btn-hero-pinjam">📖 Pinjam Sekarang</a>
                            @else
                            <span class="btn-hero-pinjam disabled">Stok Habis</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-pinjam">Login untuk Pinjam</a>
                        @endauth
                    </div>
                </div>

                {{-- Float card bawah --}}
                <div class="hbs-float-card hbs-fc-2">
                    <span class="hbs-fc-icon">📚</span>
                    <div>
                        <div class="hbs-fc-text">{{ $bukuPopuler->count() }} Buku Populer</div>
                        <div class="hbs-fc-sub">Tersedia sekarang</div>
                    </div>
                </div>

            </div>
            @endif
        </div>
    </div>

    <div class="hero-scroll">
        <div class="hero-scroll-line"></div>
        <span>scroll</span>
    </div>
</section>

{{-- TICKER --}}
<div class="ticker">
    <div class="ticker-track">
        @foreach(['Perpustakaan Digital','Pinjam Online','Ratusan Koleksi','Gratis & Mudah','Baca Kapan Saja','SMK RPL','Perpustakaan Digital','Pinjam Online','Ratusan Koleksi','Gratis & Mudah','Baca Kapan Saja','SMK RPL'] as $t)
        <span class="ticker-item"><span class="ticker-sep">◆</span> {{ $t }}</span>
        @endforeach
    </div>
</div>

{{-- ========================
     BUKU POPULER
======================== --}}
<section class="section-v2 populer-section reveal" id="katalog">
    <div class="container">
        <div class="populer-header">
            <div>
                <span class="section-tag">Rekomendasi Terbaik</span>
                <h2 class="section-title-v2">Buku Populer</h2>
                <p style="color:#6b7280;font-size:.9rem;margin-top:6px">
                    Koleksi yang paling banyak dipinjam oleh anggota
                </p>
            </div>
            <a href="#semua-buku" class="btn-lihat-semua">Lihat Semua →</a>
        </div>

        <div class="populer-slider-wrap">
            <div class="populer-track-outer">
                <div class="populer-track" id="populerTrack">
                    @php
                        $coverBg = [
                            ['#dbeafe','#eff6ff'],['#dcfce7','#f0fdf4'],
                            ['#fef9c3','#fefce8'],['#fce7f3','#fdf2f8'],
                            ['#ede9fe','#f5f3ff'],['#ffedd5','#fff7ed'],
                        ];
                    @endphp
                    @foreach($bukuPopuler as $i => $bk)
                    @php
                        $bg = $coverBg[$i % 6];
                        $rt = round($bk->averageRating(), 1);
                        $stokClass = $bk->stok > 3 ? 'stok-ok' : ($bk->stok > 0 ? 'stok-xs' : 'stok-no');
                        $stokLabel = $bk->stok > 3 ? '✓ Tersedia' : ($bk->stok > 0 ? '⚡ Stok Terbatas' : '✗ Habis');
                    @endphp
                    <div class="pop-card">
                        <div class="pop-card-cover"
                            style="background:linear-gradient(145deg,{{ $bg[0] }},{{ $bg[1] }})">
                            @if($bk->gambar)
                                <img src="{{ asset('storage/'.$bk->gambar) }}" alt="{{ $bk->judul }}">
                            @else
                                <span class="pop-cover-abbr">{{ strtoupper(substr($bk->judul,0,2)) }}</span>
                            @endif
                            <span class="pop-cat-pill">{{ $bk->category->nama_kategori ?? 'Buku' }}</span>
                            <span class="pop-stok-pill {{ $stokClass }}">{{ $stokLabel }}</span>
                        </div>
                        <div class="pop-card-body">
                            <div class="pop-kat">{{ $bk->category->nama_kategori ?? '-' }}</div>
                            <div class="pop-title">{{ $bk->judul }}</div>
                            <div class="pop-author">{{ $bk->penulis }}</div>
                            @if($bk->penerbit)
                            <div style="font-size:.75rem;color:#d1d5db">{{ $bk->penerbit }}</div>
                            @endif
                            <div class="pop-meta">
                                <div class="pop-stars">
                                    @for($s=1;$s<=5;$s++)
                                    <span class="{{ $s<=$rt?'pop-star-on':'pop-star-off' }}">★</span>
                                    @endfor
                                    <span class="pop-rnum">{{ $rt > 0 ? $rt : 'Baru' }}</span>
                                </div>
                                @auth
                                    @if($bk->stok > 0)
                                    <a href="{{ route('user.buku.detail', $bk->id) }}"
                                        class="btn-pop-pinjam">Pinjam</a>
                                    @else
                                    <span class="btn-pop-pinjam disabled">Habis</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-pop-pinjam">Login</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Nav --}}
            <div class="populer-slider-nav">
                <button class="psn-btn" onclick="populerSlide(-1)">←</button>
                <div class="psn-dots" id="populerDots">
                    @for($d=0; $d < ceil($bukuPopuler->count()/3); $d++)
                    <button class="psn-dot {{ $d===0?'active':'' }}"
                        onclick="goPopuler({{ $d }})"></button>
                    @endfor
                </div>
                <button class="psn-btn" onclick="populerSlide(1)">→</button>
            </div>
        </div>
    </div>
</section>

{{-- ========================
     KATEGORI
======================== --}}
<section class="section-v2 section-gray reveal" id="tentang">
    <div class="container">
        <div style="text-align:center;margin-bottom:8px">
            <span class="section-tag">Jelajahi</span>
        </div>
        <h2 class="section-title-v2" style="text-align:center">Kategori Buku</h2>
        <p style="text-align:center;color:#6b7280;font-size:.9rem;margin-top:8px">
            Temukan buku sesuai minat dan bidang ilmumu
        </p>

        <div class="cat-grid">
            @php
            $catMeta = [
                'Fiksi'            =>['icon'=>'📚','bg'=>'#dbeafe','border'=>'#bfdbfe','color'=>'#1d4ed8'],
                'Non-Fiksi'        =>['icon'=>'📖','bg'=>'#f3e8ff','border'=>'#e9d5ff','color'=>'#7e22ce'],
                'Sejarah'          =>['icon'=>'🏛️','bg'=>'#dcfce7','border'=>'#bbf7d0','color'=>'#15803d'],
                'Biografi'         =>['icon'=>'👤','bg'=>'#ffedd5','border'=>'#fed7aa','color'=>'#c2410c'],
                'Ilmu Pengetahuan' =>['icon'=>'🔬','bg'=>'#e0f2fe','border'=>'#bae6fd','color'=>'#0369a1'],
                'Teknologi'        =>['icon'=>'💻','bg'=>'#ecfdf5','border'=>'#a7f3d0','color'=>'#065f46'],
                'Agama'            =>['icon'=>'🕌','bg'=>'#fef9c3','border'=>'#fde68a','color'=>'#854d0e'],
                'Sastra'           =>['icon'=>'✍️','bg'=>'#fce7f3','border'=>'#f9a8d4','color'=>'#9d174d'],
                'Hukum'            =>['icon'=>'⚖️','bg'=>'#f1f5f9','border'=>'#cbd5e1','color'=>'#334155'],
                'Kesehatan'        =>['icon'=>'🏥','bg'=>'#fff1f2','border'=>'#fecdd3','color'=>'#b91c1c'],
            ];
            @endphp
            @foreach($categories as $cat)
            @php
                $cm = $catMeta[$cat->nama_kategori] ?? ['icon'=>'📚','bg'=>'#f3f4f6','border'=>'#e5e7eb','color'=>'#374151'];
                $jumlah = $cat->books()->count();
            @endphp
            <a href="{{ route('home', ['kategori'=>$cat->id]) }}"
                class="category-card"
                style="background:{{ $cm['bg'] }};border-color:{{ $cm['border'] }}">
                <div class="category-icon" style="background:rgba(255,255,255,.7)">{{ $cm['icon'] }}</div>
                <div class="category-name" style="color:{{ $cm['color'] }}">{{ $cat->nama_kategori }}</div>
                <div class="category-count">{{ $jumlah }} buku</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================
     SEMUA BUKU
======================== --}}
<section class="section-v2 reveal" id="semua-buku">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px">
            <div>
                <span class="section-tag">Koleksi Lengkap</span>
                <h2 class="section-title-v2" style="margin-bottom:0">Semua Buku</h2>
            </div>
            <span style="font-size:.82rem;color:#9ca3af">
                Menampilkan {{ $books->total() }} buku
            </span>
        </div>

        <div class="filter-bar">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="search" class="search-input"
                    placeholder="🔍  Cari judul atau penulis..."
                    value="{{ request('search') }}">
                <select name="kategori" class="filter-select"
                    onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('kategori') == $cat->id ? 'selected':'' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-search">Filter</button>
                @if(request('search') || request('kategori'))
                <a href="{{ route('home') }}" class="btn-search"
                    style="background:#6b7280">✕ Reset</a>
                @endif
            </form>
        </div>

        @if($books->count() > 0)
        <div class="books-grid">
            @foreach($books as $i => $bk)
            @php
                $colors = ['#1a46d8','#059669','#d97706','#7c3aed','#dc2626','#0891b2'];
                $bg = $colors[$i % 6];
            @endphp
            <div class="book-card reveal">
                <div class="book-cover-placeholder"
                    style="background:{{ $bg }}">
                    @if($bk->gambar)
                        <img src="{{ asset('storage/'.$bk->gambar) }}" alt="{{ $bk->judul }}">
                    @else
                        {{ strtoupper(substr($bk->judul,0,2)) }}
                    @endif
                </div>
                <div class="book-info">
                    <div class="book-category">{{ $bk->category->nama_kategori ?? '-' }}</div>
                    <div class="book-title">{{ $bk->judul }}</div>
                    <div class="book-author">{{ $bk->penulis }}</div>
                    <div class="book-footer">
                        <span class="book-stok {{ $bk->stok == 0 ? 'habis':'' }}">
                            {{ $bk->stok > 0 ? 'Stok: '.$bk->stok : 'Habis' }}
                        </span>
                        @auth
                            @if($bk->stok > 0)
                            <a href="{{ route('user.buku.detail', $bk->id) }}"
                                class="btn-pinjam">Pinjam</a>
                            @else
                            <span class="btn-pinjam disabled">Habis</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-pinjam">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-wrap">
            {{ $books->appends(request()->query())->links('pagination.custom') }}
        </div>
        @else
        <div class="empty-state">
            <span class="emoji">📭</span>
            <h3>Buku tidak ditemukan</h3>
            <p>Coba kata kunci lain atau reset filter pencarian</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
@guest
<section class="cta reveal">
    <div class="container">
        <div class="cta-inner">
            <h2>Siap Mulai Membaca?</h2>
            <p>Daftarkan dirimu sekarang dan nikmati akses gratis ke seluruh koleksi buku.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn-hero-primary">✨ Daftar Gratis</a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">Sudah Punya Akun →</a>
            </div>
        </div>
    </div>
</section>
@endguest

{{-- Scroll top --}}
<button class="scroll-top" id="scrollTop"
    onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

@endsection

@push('scripts')
<script>
// ===== POPULER SLIDER =====
let popIdx = 0;
const popTrack = document.getElementById('populerTrack');
const popCards = popTrack ? popTrack.querySelectorAll('.pop-card') : [];
const popTotal = Math.ceil(popCards.length / 3);

function populerSlide(dir) {
    popIdx = (popIdx + dir + popTotal) % popTotal;
    updatePopuler();
}
function goPopuler(idx) {
    popIdx = idx;
    updatePopuler();
}
function updatePopuler() {
    if (!popTrack) return;
    const w = popTrack.parentElement.offsetWidth;
    const cardW = (w - 40) / 3;
    popTrack.style.transform = `translateX(-${popIdx * (cardW + 20) * 3}px)`;
    document.querySelectorAll('.psn-dot').forEach((d,i) =>
        d.classList.toggle('active', i === popIdx));
}

// Auto slide
setInterval(() => populerSlide(1), 4500);

// ===== SCROLL REVEAL =====
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: .1 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

// ===== SCROLL TOP =====
const stBtn = document.getElementById('scrollTop');
window.addEventListener('scroll', () => {
    stBtn?.classList.toggle('show', window.scrollY > 400);
});

// ===== NAVBAR SCROLL =====
window.addEventListener('scroll', () => {
    document.querySelector('.navbar')?.classList.toggle('scrolled', window.scrollY > 60);
});
</script>
@endpush