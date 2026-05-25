@extends('layouts.app')

@section('title', 'DigiLibrary - Perpustakaan Digital')

@section('content')

{{-- HERO --}}
<section class="hero-v2">
    <div class="hero-v2-inner">
        <div class="hero-v2-text">
            <div class="hero-tag">✨ Perpustakaan Digital Modern</div>
            <h1>Perpustakaan Digital<br><span>Mudah & Modern</span></h1>
            <p>Pinjam buku kapan saja dan dimana saja dengan mudah</p>
            <form action="{{ route('home') }}" method="GET" class="hero-search">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Cari judul buku..." value="{{ request('search') }}">
                <button type="submit">Cari</button>
            </form>
            <div class="hero-stats">
                <div class="hero-stat"><strong>500+</strong><span>Koleksi Buku</span></div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat"><strong>1.2K+</strong><span>Anggota</span></div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat"><strong>10+</strong><span>Kategori</span></div>
            </div>
        </div>
        <div class="hero-v2-image">
            <div class="book-animation-wrap">
                <!-- Buku utama yang membuka -->
                <div class="main-book">
                    <div class="book-left-page">
                        <div class="page-lines">
                            <div class="page-line"></div>
                            <div class="page-line"></div>
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                        </div>
                    </div>
                    <div class="book-spine"></div>
                    <div class="book-right-page">
                        <div class="page-lines">
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                            <div class="page-line"></div>
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                            <div class="page-line"></div>
                            <div class="page-line short"></div>
                            <div class="page-line"></div>
                        </div>
                    </div>
                    <!-- Halaman yang berputar -->
                    <div class="turning-page page1"></div>
                    <div class="turning-page page2"></div>
                    <div class="turning-page page3"></div>
                </div>

               
            </div>
        </div>
    </div>
</section>

{{-- BUKU POPULER --}}
<section class="section-v2 buku-populer-section" id="katalog">
    <div class="container">
        <div class="section-header-flex">
            <div>
                <span class="section-tag">Rekomendasi</span>
                <h2 class="section-title-v2">Buku Populer</h2>
            </div>
            <a href="#semua-buku" class="btn-lihat-semua">Lihat Semua →</a>
        </div>

        <div class="auto-slider">
            <div class="slider-track" id="sliderTrack">
                @php $popularBooks = $books->take(4); @endphp
                @foreach($popularBooks as $book)
                @php
                    $colors = [
                        ['bg'=>'#fef3c7','text'=>'#92400e','accent'=>'#f59e0b'],
                        ['bg'=>'#dbeafe','text'=>'#1e40af','accent'=>'#3b82f6'],
                        ['bg'=>'#dcfce7','text'=>'#166534','accent'=>'#22c55e'],
                        ['bg'=>'#fce7f3','text'=>'#9d174d','accent'=>'#ec4899'],
                    ];
                    $c = $colors[$loop->index % 4];
                @endphp
                <div class="slider-card">
                    <div class="slider-card-inner">
                        <div class="slider-book-visual" style="background:{{ $c['bg'] }}">
                            @if($book->gambar)
                                <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                            @else
                                <div class="book-3d-wrap">
                                    <div class="book-3d" style="--book-color:{{ $c['accent'] }}">
                                        <div class="book-3d-front" style="background:{{ $c['accent'] }}">
                                            <div class="book-3d-title">{{ strtoupper(substr($book->judul,0,2)) }}</div>
                                        </div>
                                        <div class="book-3d-side" style="background:{{ $c['text'] }}"></div>
                                    </div>
                                </div>
                            @endif
                            <div class="slider-badge" style="background:{{ $c['accent'] }}">
                                {{ $book->category->nama_kategori ?? 'Buku' }}
                            </div>
                        </div>
                        <div class="slider-book-info">
                            <div class="slider-book-title">{{ $book->judul }}</div>
                            <div class="slider-book-author">{{ $book->penulis }}</div>
                            @if($book->penerbit)
                            <div class="slider-book-publisher">{{ $book->penerbit }}</div>
                            @endif
                            <div class="slider-rating">
                                @php $rating = round($book->averageRating(), 1); @endphp
                                <div class="stars-row">
                                    @for($i=1;$i<=5;$i++)
                                        <span class="{{ $i <= $rating ? 'star-on' : 'star-off' }}">★</span>
                                    @endfor
                                    <span class="rating-val">{{ $rating > 0 ? $rating : 'Baru' }}</span>
                                </div>
                            </div>
                            <div class="slider-stok">
                                <span class="stok-dot {{ $book->stok > 0 ? 'dot-green' : 'dot-red' }}"></span>
                                {{ $book->stok > 0 ? 'Tersedia ('.$book->stok.' stok)' : 'Stok habis' }}
                            </div>
                            @auth
                                @if($book->stok > 0)
                                    <a href="/user/pinjam/{{ $book->id }}" class="btn-pinjam-slider">Pinjam Sekarang</a>
                                @else
                                    <span class="btn-pinjam-slider disabled">Stok Habis</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-pinjam-slider">Login untuk Pinjam</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Dot indicators --}}
            <div class="slider-dots" id="sliderDots">
                @foreach($popularBooks as $book)
                <button class="dot {{ $loop->first ? 'dot-active' : '' }}"
                    onclick="goToSlide({{ $loop->index }})"
                    data-index="{{ $loop->index }}"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI --}}
<section class="section-v2 section-gray" id="tentang">
    <div class="container">
        <h2 class="section-title-v2">Kategori Buku</h2>
        <div class="category-grid">
            @php
            $catIcons = ['Fiksi'=>'📚','Non-Fiksi'=>'📖','Sejarah'=>'🏛️','Biografi'=>'👤','Ilmu Pengetahuan'=>'🔬','Teknologi'=>'💻','Agama'=>'🕌','Sastra'=>'✍️','Hukum'=>'⚖️','Kesehatan'=>'🏥'];
            $catColors = [
                'Fiksi'=>['bg'=>'#eff6ff','border'=>'#bfdbfe','icon_bg'=>'#dbeafe','color'=>'#1d4ed8'],
                'Non-Fiksi'=>['bg'=>'#fdf4ff','border'=>'#e9d5ff','icon_bg'=>'#f3e8ff','color'=>'#7e22ce'],
                'Sejarah'=>['bg'=>'#f0fdf4','border'=>'#bbf7d0','icon_bg'=>'#dcfce7','color'=>'#15803d'],
                'Biografi'=>['bg'=>'#fff7ed','border'=>'#fed7aa','icon_bg'=>'#ffedd5','color'=>'#c2410c'],
                'Ilmu Pengetahuan'=>['bg'=>'#f0f9ff','border'=>'#bae6fd','icon_bg'=>'#e0f2fe','color'=>'#0369a1'],
                'Teknologi'=>['bg'=>'#ecfdf5','border'=>'#a7f3d0','icon_bg'=>'#d1fae5','color'=>'#065f46'],
                'Agama'=>['bg'=>'#fefce8','border'=>'#fde68a','icon_bg'=>'#fef9c3','color'=>'#854d0e'],
                'Sastra'=>['bg'=>'#fdf2f8','border'=>'#f9a8d4','icon_bg'=>'#fce7f3','color'=>'#9d174d'],
                'Hukum'=>['bg'=>'#f8fafc','border'=>'#cbd5e1','icon_bg'=>'#e2e8f0','color'=>'#334155'],
                'Kesehatan'=>['bg'=>'#fff1f2','border'=>'#fecdd3','icon_bg'=>'#fee2e2','color'=>'#b91c1c'],
            ];
            @endphp
            @foreach($categories as $cat)
            @php
                $c = $catColors[$cat->nama_kategori] ?? ['bg'=>'#f3f4f6','border'=>'#e5e7eb','icon_bg'=>'#e5e7eb','color'=>'#374151'];
                $icon = $catIcons[$cat->nama_kategori] ?? '📚';
                $jumlah = $cat->books()->count();
            @endphp
            <a href="{{ route('home', ['kategori' => $cat->id]) }}" class="category-card"
               style="background:{{ $c['bg'] }};border-color:{{ $c['border'] }}">
                <div class="category-icon" style="background:{{ $c['icon_bg'] }}">{{ $icon }}</div>
                <div class="category-name" style="color:{{ $c['color'] }}">{{ $cat->nama_kategori }}</div>
                <div class="category-count">{{ $jumlah }} buku</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- SEMUA BUKU (filter) --}}
<section class="section-v2" id="semua-buku">
    <div class="container">
        <h2 class="section-title-v2">Semua Koleksi</h2>

        <div class="filter-bar">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-search">Cari</button>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('home') }}" class="btn-search" style="background:var(--gray-500);text-decoration:none">Reset</a>
                @endif
            </form>
        </div>

        @if($books->count() > 0)
        <div class="books-grid">
            @foreach($books as $book)
            <div class="book-card reveal">
                <div class="book-cover-placeholder" style="background:{{ ['#1a56db','#0e9f6e','#ff6b35','#7c3aed','#dc2626','#0891b2'][($loop->index % 6)] }}">
                    @if($book->gambar)
                        <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                    @else
                        📚
                    @endif
                </div>
                <div class="book-info">
                    <div class="book-category">{{ $book->category->nama_kategori ?? '-' }}</div>
                    <div class="book-title">{{ $book->judul }}</div>
                    <div class="book-author">{{ $book->penulis }}</div>
                    <div class="book-footer">
                        <span class="book-stok {{ $book->stok == 0 ? 'habis' : '' }}">
                            {{ $book->stok > 0 ? 'Stok: '.$book->stok : 'Habis' }}
                        </span>
                        @auth
                            @if($book->stok > 0)
                                <a href="/user/pinjam/{{ $book->id }}" class="btn-pinjam">Pinjam</a>
                            @else
                                <span class="btn-pinjam disabled">Habis</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-pinjam">Login dulu</a>
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
            <p>Coba kata kunci lain atau reset filter</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
@guest
<section class="cta">
    <div class="container">
        <h2>Siap Mulai Membaca?</h2>
        <p>Daftarkan dirimu sekarang dan nikmati akses ke ratusan koleksi buku pilihan.</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-hero-primary">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">Sudah Punya Akun</a>
        </div>
    </div>
</section>
@endguest

@endsection