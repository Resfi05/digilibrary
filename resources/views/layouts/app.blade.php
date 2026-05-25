<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DigiLibrary')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════
     LOADING SCREEN
════════════════════════════════════════════════════════════ --}}
<div id="dl-loader">
    <div class="dll-bg"></div>
    <div class="dll-vignette"></div>
    <div class="dll-shelf dll-shelf-left">
        @foreach(range(1,14) as $i)
        @php
            $bh = [90,110,85,120,95,105,88,115,92,108,86,118,96,102][$i-1] ?? 100;
            $bc = ['#1a3a6b','#2d5a9b','#8b1a1a','#1a5c2e','#6b4c1a','#1a2d6b','#5c1a6b','#1a5c5c','#6b1a4c','#3a6b1a','#6b3a1a','#1a4a6b','#4a1a6b','#1a6b3a'][$i-1] ?? '#1a3a6b';
        @endphp
        <div class="dll-book" style="height:{{ $bh }}px;background:{{ $bc }};animation-delay:{{ ($i-1)*0.15 }}s"></div>
        @endforeach
    </div>
    <div class="dll-shelf dll-shelf-right">
        @foreach(range(1,14) as $i)
        @php
            $bh = [105,88,118,92,108,86,115,95,120,85,110,90,100,98][$i-1] ?? 100;
            $bc = ['#5c1a6b','#1a5c5c','#6b1a4c','#3a6b1a','#6b3a1a','#1a4a6b','#1a3a6b','#2d5a9b','#8b1a1a','#1a5c2e','#6b4c1a','#1a2d6b','#4a1a6b','#1a6b3a'][$i-1] ?? '#1a3a6b';
        @endphp
        <div class="dll-book" style="height:{{ $bh }}px;background:{{ $bc }};animation-delay:{{ ($i-1)*0.12 }}s"></div>
        @endforeach
    </div>

    <div class="dll-center">
        <div class="dll-logo-wrap">
            <div class="dll-ring dll-ring-outer"></div>
            <div class="dll-ring dll-ring-inner"></div>
            <div class="dll-logo-body">
                <svg class="dll-laurel dll-laurel-l" viewBox="0 0 56 130" fill="none">
                    <path d="M48 8 C28 20, 8 44, 6 66 C4 88, 18 108, 38 122" stroke="#c8a84b" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <ellipse cx="40" cy="16"  rx="10" ry="5" fill="#c8a84b" transform="rotate(-45 40 16)"  opacity=".95"/>
                    <ellipse cx="26" cy="30"  rx="10" ry="5" fill="#c8a84b" transform="rotate(-30 26 30)"  opacity=".9"/>
                    <ellipse cx="15" cy="48"  rx="10" ry="5" fill="#c8a84b" transform="rotate(-12 15 48)"  opacity=".85"/>
                    <ellipse cx="9"  cy="68"  rx="10" ry="5" fill="#c8a84b" transform="rotate(4 9 68)"     opacity=".85"/>
                    <ellipse cx="13" cy="88"  rx="10" ry="5" fill="#c8a84b" transform="rotate(18 13 88)"   opacity=".9"/>
                    <ellipse cx="25" cy="105" rx="10" ry="5" fill="#c8a84b" transform="rotate(32 25 105)"  opacity=".95"/>
                </svg>
                <div class="dll-monogram">
                    <span class="dll-r">R</span>
                    <div class="dll-book-icon">
                        <svg viewBox="0 0 36 46" fill="none" width="36" height="46">
                            <rect x="3" y="2" width="30" height="42" rx="3" fill="#0d1f3c" stroke="#c8a84b" stroke-width="1.5"/>
                            <rect x="3" y="2" width="7"  height="42" rx="2" fill="#c8a84b" opacity=".5"/>
                            <line x1="14" y1="13" x2="28" y2="13" stroke="#c8a84b" stroke-width="1.2" stroke-linecap="round" opacity=".55"/>
                            <line x1="14" y1="19" x2="28" y2="19" stroke="#c8a84b" stroke-width="1.2" stroke-linecap="round" opacity=".55"/>
                            <line x1="14" y1="25" x2="22" y2="25" stroke="#c8a84b" stroke-width="1.2" stroke-linecap="round" opacity=".55"/>
                            <path d="M18 2 Q26 23 18 44" stroke="#c8a84b" stroke-width=".8" fill="none" opacity=".35"/>
                            <path d="M18 2 Q30 23 18 44" stroke="#c8a84b" stroke-width=".7" fill="none" opacity=".2"/>
                        </svg>
                    </div>
                    <span class="dll-d">D</span>
                </div>
                <svg class="dll-laurel dll-laurel-r" viewBox="0 0 56 130" fill="none">
                    <path d="M8 8 C28 20, 48 44, 50 66 C52 88, 38 108, 18 122" stroke="#c8a84b" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <ellipse cx="16" cy="16"  rx="10" ry="5" fill="#c8a84b" transform="rotate(45 16 16)"   opacity=".95"/>
                    <ellipse cx="30" cy="30"  rx="10" ry="5" fill="#c8a84b" transform="rotate(30 30 30)"   opacity=".9"/>
                    <ellipse cx="41" cy="48"  rx="10" ry="5" fill="#c8a84b" transform="rotate(12 41 48)"   opacity=".85"/>
                    <ellipse cx="47" cy="68"  rx="10" ry="5" fill="#c8a84b" transform="rotate(-4 47 68)"   opacity=".85"/>
                    <ellipse cx="43" cy="88"  rx="10" ry="5" fill="#c8a84b" transform="rotate(-18 43 88)"  opacity=".9"/>
                    <ellipse cx="31" cy="105" rx="10" ry="5" fill="#c8a84b" transform="rotate(-32 31 105)" opacity=".95"/>
                </svg>
            </div>
        </div>

        <div class="dll-brand">
            <span class="dll-brand-digi">Digi</span><span class="dll-brand-lib">Library</span>
        </div>

        <div class="dll-bar-section">
            <div class="dll-bar-ornament dll-bar-ornament-l">
                <svg viewBox="0 0 30 40" width="28" height="36" fill="none">
                    <rect x="1" y="1" width="28" height="38" rx="3" fill="#0d1f3c" stroke="#c8a84b" stroke-width="1.5"/>
                    <rect x="1" y="1" width="6"  height="38" rx="2" fill="#c8a84b" opacity=".45"/>
                    <rect x="1" y="6" width="24" height="30" rx="2" fill="#091629" stroke="#c8a84b" stroke-width="1" opacity=".6"/>
                    <rect x="1" y="6" width="5"  height="30" rx="1" fill="#c8a84b" opacity=".3"/>
                </svg>
            </div>
            <div class="dll-bar-track">
                <div class="dll-bar-fill" id="dllBarFill"></div>
                <div class="dll-bar-glow"></div>
                <div class="dll-bar-shine"></div>
            </div>
            <div class="dll-bar-ornament dll-bar-ornament-r">
                <svg viewBox="0 0 30 50" width="26" height="44" fill="none">
                    <path d="M15 3 C21 7, 27 18, 25 32 L15 47 L5 32 C3 18, 9 7, 15 3Z" fill="#0d1f3c" stroke="#c8a84b" stroke-width="1.5"/>
                    <path d="M15 7 C19 11, 23 20, 21 32 L15 44" stroke="#c8a84b" stroke-width=".8" fill="none" opacity=".4"/>
                    <circle cx="15" cy="18" r="3" fill="#c8a84b" opacity=".35"/>
                    <path d="M15 47 L13 52 L15 49 L17 52Z" fill="#c8a84b"/>
                </svg>
            </div>
        </div>

        <div class="dll-percent" id="dllPercent">0%</div>
        <div class="dll-tagline">MEMUAT DUNIA PENGETAHUAN...</div>

        <div class="dll-sparkles">
            <span class="dll-sp dll-sp1">✦</span>
            <span class="dll-sp dll-sp2">✧</span>
            <span class="dll-sp dll-sp3">✦</span>
            <span class="dll-sp dll-sp4">✧</span>
            <span class="dll-sp dll-sp5">✦</span>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════
   LOADING SCREEN — DIGILIBRARY
═══════════════════════════════════════════ */
#dl-loader {
    position: fixed; inset: 0; z-index: 99999;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    transition: opacity .8s cubic-bezier(.4,0,.2,1), visibility .8s;
}
#dl-loader.hide { opacity: 0; visibility: hidden; pointer-events: none; }

/* Background — dark library */
.dll-bg {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 100% 55% at 50% 0%, rgba(200,168,75,.09) 0%, transparent 55%),
        radial-gradient(ellipse 60%  50% at 50% 100%, rgba(10,20,50,.8) 0%, transparent 70%),
        linear-gradient(180deg, #080d14 0%, #0d1a28 25%, #162234 50%, #0d1a28 75%, #060b12 100%);
}

/* Vignette */
.dll-vignette {
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 65% 65% at 50% 50%, transparent 20%, rgba(4,8,16,.82) 100%);
}

/* ── BOOKSHELVES ─────────────────────────── */
.dll-shelf {
    position: absolute; top: 0; bottom: 0;
    width: 180px; display: flex; align-items: flex-end;
    gap: 4px; padding: 0 12px 0;
    opacity: .45; filter: blur(3px) brightness(.7);
    align-items: flex-start; padding-top: 30px;
    flex-wrap: nowrap; overflow: hidden;
}
.dll-shelf-left  { left: 0;  padding-left: 16px; }
.dll-shelf-right { right: 0; padding-right: 16px; justify-content: flex-end; }
.dll-book {
    flex-shrink: 0; width: 18px; border-radius: 2px 3px 3px 2px;
    box-shadow: inset -2px 0 4px rgba(0,0,0,.4), 2px 0 6px rgba(0,0,0,.3);
    animation: bookWave 4s ease-in-out infinite;
}
@keyframes bookWave {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-6px); }
}

/* ── CENTER ──────────────────────────────── */
.dll-center {
    position: relative; z-index: 2;
    display: flex; flex-direction: column; align-items: center;
    gap: 0; animation: dllFadeIn .9s cubic-bezier(.4,0,.2,1);
}
@keyframes dllFadeIn { from{opacity:0;transform:scale(.92) translateY(12px)} to{opacity:1;transform:none} }

/* ── LOGO ────────────────────────────────── */
.dll-logo-wrap {
    position: relative; width: 240px; height: 240px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 4px;
}
.dll-ring {
    position: absolute; border-radius: 50%; pointer-events: none;
}
.dll-ring-outer {
    inset: 0;
    background: conic-gradient(
        rgba(200,168,75,.25) 0deg, rgba(200,168,75,.04) 120deg,
        rgba(200,168,75,.18) 240deg, rgba(200,168,75,.04) 300deg,
        rgba(200,168,75,.25) 360deg
    );
    animation: ringSpin 10s linear infinite;
}
.dll-ring-outer::after {
    content:''; position:absolute; inset:8px; border-radius:50%;
    border: 1px solid rgba(200,168,75,.15);
}
.dll-ring-inner {
    inset: 18px;
    border: 1.5px dashed rgba(200,168,75,.12);
    animation: ringSpinR 16s linear infinite;
}
@keyframes ringSpin  { to{transform:rotate(360deg)} }
@keyframes ringSpinR { to{transform:rotate(-360deg)} }

.dll-logo-body {
    position: relative; display: flex;
    align-items: center; justify-content: center;
    gap: 0; width: 190px; height: 190px;
}

/* Laurels */
.dll-laurel {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 56px; height: 130px;
    filter: drop-shadow(0 0 6px rgba(200,168,75,.35));
    animation: laurelPulse 3.5s ease-in-out infinite;
}
.dll-laurel-l { left: -4px; }
.dll-laurel-r { right: -4px; }
@keyframes laurelPulse {
    0%,100% { filter: drop-shadow(0 0 4px rgba(200,168,75,.25)) brightness(1); }
    50%      { filter: drop-shadow(0 0 14px rgba(200,168,75,.7)) brightness(1.2); }
}

/* Monogram */
.dll-monogram {
    display: flex; align-items: center; gap: 3px;
    position: relative; z-index: 2;
}
.dll-r, .dll-d {
    font-family: 'Georgia', 'Times New Roman', serif;
    font-size: 3.8rem; font-weight: 700; line-height: 1;
    color: #1a3a6b;
    text-shadow: 0 0 24px rgba(26,58,107,.9), 0 3px 6px rgba(0,0,0,.6);
    animation: letterGlow 3.5s ease-in-out infinite;
}
.dll-d { animation-delay: .4s; }
@keyframes letterGlow {
    0%,100% { filter: brightness(1); }
    50%      { filter: brightness(1.2) drop-shadow(0 0 10px rgba(26,58,107,.8)); }
}
.dll-book-icon {
    animation: bookIconFloat 3s ease-in-out infinite;
    filter: drop-shadow(0 0 8px rgba(200,168,75,.45));
}
@keyframes bookIconFloat {
    0%,100% { transform: translateY(0) rotateY(0deg); }
    50%      { transform: translateY(-5px) rotateY(8deg); }
}

/* ── BRAND ───────────────────────────────── */
.dll-brand {
    font-size: 1.6rem; font-weight: 700; letter-spacing: .1em;
    margin-bottom: 4px;
}
.dll-brand-digi { color: #c8a84b; text-shadow: 0 0 12px rgba(200,168,75,.4); }
.dll-brand-lib  { color: rgba(255,255,255,.82); }

/* ── PROGRESS BAR ────────────────────────── */
.dll-bar-section {
    display: flex; align-items: center; gap: 10px;
    width: 400px; margin: 18px 0 8px;
}
.dll-bar-ornament { flex-shrink: 0; filter: drop-shadow(0 2px 8px rgba(200,168,75,.25)); }

.dll-bar-track {
    flex: 1; height: 20px; border-radius: 99px; position: relative; overflow: hidden;
    background: rgba(5,10,22,.9);
    border: 2px solid rgba(200,168,75,.3);
    box-shadow: 0 0 20px rgba(200,168,75,.12), inset 0 2px 6px rgba(0,0,0,.5), 0 0 0 1px rgba(0,0,0,.4);
}
.dll-bar-fill {
    position: absolute; top: 0; left: 0; bottom: 0; width: 0%;
    background: linear-gradient(90deg,
        #6b4c10 0%, #c8a84b 25%, #f0cc6a 50%, #e8bc50 70%, #c8a84b 85%, #7a5c18 100%
    );
    border-radius: 99px;
    transition: width .25s ease;
    box-shadow: 0 0 14px rgba(200,168,75,.55), inset 0 1px 3px rgba(255,255,255,.2);
}
.dll-bar-glow {
    position: absolute; inset: 0; border-radius: 99px;
    background: linear-gradient(90deg, transparent, rgba(200,168,75,.08), transparent);
    animation: barGlow 2.5s ease-in-out infinite;
}
@keyframes barGlow { 0%,100%{opacity:.4} 50%{opacity:1} }
.dll-bar-shine {
    position: absolute; top: 2px; left: -100%; right: 0; height: 6px;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.18) 50%, transparent 100%);
    border-radius: 99px;
    animation: barShine 2.2s linear infinite;
    width: 60%;
}
@keyframes barShine { from{left:-60%} to{left:110%} }

/* ── PERCENT + TAGLINE ───────────────────── */
.dll-percent {
    font-family: 'Georgia', serif;
    font-size: 1.2rem; font-weight: 700;
    color: #c8a84b; letter-spacing: .06em;
    text-shadow: 0 0 18px rgba(200,168,75,.55);
    min-width: 56px; text-align: center;
    margin-bottom: 6px;
}
.dll-tagline {
    font-size: .65rem; font-weight: 600;
    letter-spacing: .2em; text-transform: uppercase;
    color: rgba(200,168,75,.45);
    animation: taglinePulse 3s ease-in-out infinite;
}
@keyframes taglinePulse { 0%,100%{opacity:.45} 50%{opacity:.8} }

/* ── SPARKLES ─────────────────────────────── */
.dll-sparkles { position: absolute; inset: -80px; pointer-events: none; }
.dll-sp {
    position: absolute; color: rgba(200,168,75,.5);
    animation: spTwinkle 2.8s ease-in-out infinite;
}
.dll-sp1 { top:8%;  left:5%;  font-size:1.2rem; animation-delay:0s;   }
.dll-sp2 { top:12%; right:8%; font-size:.9rem;  animation-delay:.6s;  }
.dll-sp3 { bottom:18%; left:10%; font-size:.8rem; animation-delay:1.2s; }
.dll-sp4 { bottom:10%; right:5%; font-size:1rem;  animation-delay:1.8s; }
.dll-sp5 { top:50%;   left:2%;  font-size:.75rem; animation-delay:2.4s; }
@keyframes spTwinkle {
    0%,100% { opacity:.15; transform:scale(.7) rotate(0deg); }
    50%      { opacity:1;   transform:scale(1.4) rotate(20deg); }
}

/* Ambient top glow */
#dl-loader::before {
    content:''; position:absolute; top:0; left:50%; transform:translateX(-50%);
    width:400px; height:300px; pointer-events:none;
    background: radial-gradient(ellipse 70% 80% at 50% 0%, rgba(200,168,75,.06) 0%, transparent 70%);
}

/* ── RESPONSIVE ───────────────────────────── */
@media(max-width:600px) {
    .dll-logo-wrap { width:190px; height:190px; }
    .dll-r, .dll-d { font-size:3rem; }
    .dll-book-icon  { width:28px; height:34px; }
    .dll-laurel     { width:44px; height:105px; }
    .dll-bar-section { width:300px; }
    .dll-shelf      { width:110px; }
    .dll-brand      { font-size:1.25rem; }
}
</style>

<script>
(function(){
    var fill = document.getElementById('dllBarFill');
    var pct  = document.getElementById('dllPercent');
    var loader = document.getElementById('dl-loader');
    var progress = 0, speed = 1.4;

    var timer = setInterval(function(){
        if      (progress < 25) speed = 2.2;
        else if (progress < 55) speed = 1.1;
        else if (progress < 78) speed = 0.6;
        else if (progress < 92) speed = 0.28;
        else                    speed = 0.12;

        progress += speed * (Math.random() * .7 + .65);
        if (progress > 99) progress = 99;

        fill.style.width = progress + '%';
        pct.textContent  = Math.floor(progress) + '%';
    }, 38);

    function finish(){
        clearInterval(timer);
        fill.style.width = '100%';
        pct.textContent  = '100%';
        setTimeout(function(){
            loader.classList.add('hide');
            setTimeout(function(){ if(loader.parentNode) loader.parentNode.removeChild(loader); }, 900);
        }, 380);
    }

    if (document.readyState === 'complete') {
        setTimeout(finish, 500);
    } else {
        window.addEventListener('load', function(){ setTimeout(finish, 500); });
        setTimeout(finish, 6000); // fallback max 6 detik
    }
})();
</script>

{{-- ═══════════════════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════════════════ --}}
<nav class="navbar">
    <div class="navbar-inner">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo" onerror="this.style.display='none'">
            <div class="brand-icon-new">
                <span class="brand-icon-book">📚</span>
            </div>
            <span class="brand-name">Digi<strong>Library</strong></span>
        </a>

        <div class="navbar-menu">
            <a href="{{ route('home') }}"  class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="#katalog"             class="nav-link">Buku</a>
            <a href="#tentang"             class="nav-link">Kategori</a>
            <a href="#features"            class="nav-link">Keunggulan</a>
            <a href="#about"               class="nav-link">Tentang</a>
        </div>

        <div class="navbar-auth">
            @auth
                <a href="{{ Auth::user()->role === 'admin' ? '/admin/dashboard' : (Auth::user()->role === 'petugas' ? '/petugas/dashboard' : '/user/dashboard') }}"
                   class="btn-dashboard">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login-nav"><span>Login</span></a>
            @endauth
        </div>

        <button class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('home') }}">Home</a>
    <a href="#katalog">Buku</a>
    <a href="#tentang">Kategori</a>
    <a href="#features">Keunggulan</a>
    <a href="#about">Tentang</a>
    @auth
        <a href="{{ Auth::user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard' }}">Dashboard</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    @else
        <a href="{{ route('login') }}">Login</a>
    @endauth
</div>

{{-- ═══════════════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════════════ --}}
@yield('content')

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════ --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand-icon">📚</div>
                <span>Digi<strong>Library</strong></span>
                <p>Perpustakaan digital modern untuk akses buku yang mudah dan menyenangkan.</p>
            </div>
            <div class="footer-links">
                <h4>Menu</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="#katalog">Katalog</a>
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            </div>
            <div class="footer-links">
                <h4>Informasi</h4>
                <a href="#">Tentang Kami</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Kontak</a>
            </div>
            <div class="footer-contact">
                <h4>Kontak</h4>
                <p>📧 info@digilibrary.id</p>
                <p>📞 (021) 1234-5678</p>
                <p>📍 Jakarta, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} DigiLibrary. All rights reserved.</p>
        </div>
    </div>
</footer>

<button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">↑</button>

<script>
// ── Hamburger ────────────────────────────────────────────
const hamburger  = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');
hamburger.addEventListener('click', () => {
    mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('open');
});

// ── Navbar + scroll-top + reveal ────────────────────────
const navbar      = document.querySelector('.navbar');
const scrollTopBtn= document.getElementById('scrollTop');

function checkReveal() {
    document.querySelectorAll('.reveal').forEach((el, i) => {
        if (el.getBoundingClientRect().top < window.innerHeight - 50)
            setTimeout(() => el.classList.add('visible'), i * 80);
    });
}
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 50);
    scrollTopBtn.classList.toggle('show', window.scrollY > 300);
    checkReveal();
});
checkReveal();

// ── Smooth anchor scroll ─────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
        const t = document.querySelector(this.getAttribute('href'));
        if (t) { e.preventDefault(); t.scrollIntoView({ behavior:'smooth', block:'start' }); }
    });
});

// ── Carousel ─────────────────────────────────────────────
const carTrack = document.getElementById('carouselTrack');
const prevBtn  = document.getElementById('prevBtn');
const nextBtn2 = document.getElementById('nextBtn');
if (carTrack && prevBtn && nextBtn2) {
    let pos = 0; const cardW = 200;
    const visW  = () => carTrack.parentElement.offsetWidth;
    const maxP  = () => Math.max(0, carTrack.scrollWidth - visW());
    nextBtn2.addEventListener('click', () => { pos = Math.min(pos + cardW*2, maxP()); carTrack.style.transform=`translateX(-${pos}px)`; });
    prevBtn.addEventListener( 'click', () => { pos = Math.max(0, pos - cardW*2);       carTrack.style.transform=`translateX(-${pos}px)`; });
}

// ── Auto Slider (Buku Populer) ───────────────────────────
(function(){
    const sliderTrack = document.getElementById('sliderTrack');
    const dots        = document.querySelectorAll('.dot');
    if (!sliderTrack || !dots.length) return;

    let current = 0, autoPlay;
    const total = dots.length;

    function goToSlide(i) {
        current = i;
        sliderTrack.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, j) => d.classList.toggle('dot-active', j === current));
    }
    window.goToSlide = goToSlide;

    function startAuto() { autoPlay = setInterval(() => goToSlide((current+1)%total), 4000); }
    function stopAuto()  { clearInterval(autoPlay); }

    sliderTrack.addEventListener('mouseenter', stopAuto);
    sliderTrack.addEventListener('mouseleave', startAuto);

    let startX = 0;
    sliderTrack.addEventListener('touchstart', e => { startX = e.touches[0].clientX; stopAuto(); });
    sliderTrack.addEventListener('touchend',   e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) goToSlide(diff > 0 ? (current+1)%total : (current-1+total)%total);
        startAuto();
    });
    startAuto();
})();
</script>

@stack('scripts')

<link rel="stylesheet" href="{{ asset('css/petugas-dashboard.css') }}">

</body>
</html>