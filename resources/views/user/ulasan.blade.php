<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ulasan Saya - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/ulasan.css')) !!}
        .star-pick { display:flex; align-items:center; gap:4px; }
        .star-pick-btn { font-size:1.6rem; background:none; border:none; cursor:pointer; color:#e5e7eb; padding:0; transition:all .15s; line-height:1; }
        .star-pick-btn.lit { color:#f59e0b; }
        .star-pick-btn:hover { transform:scale(1.15); }
    </style>
</head>
<body class="dash-body">

<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sb-icon">📚</div>
        <div><div class="sb-name">DigiLibrary</div><div class="sb-sub">Perpustakaan Digital</div></div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('user.dashboard') }}" class="sn-item"><span class="sn-icon">🏠</span><span>Beranda</span></a>
        <a href="{{ route('user.katalog') }}" class="sn-item"><span class="sn-icon">📖</span><span>Katalog Buku</span></a>
        <a href="{{ route('user.riwayat') }}" class="sn-item"><span class="sn-icon">🕐</span><span>Riwayat Peminjaman</span></a>
        <a href="{{ route('user.favorit') }}" class="sn-item"><span class="sn-icon">❤️</span><span>Buku Favorit</span></a>
        <a href="{{ route('user.ulasan') }}" class="sn-item active"><span class="sn-icon">💬</span><span>Ulasan Saya</span></a>
        <a href="{{ route('user.notifikasi') }}" class="sn-item">
            <span class="sn-icon">🔔</span><span>Notifikasi</span>
            @if($unreadNotif > 0)<span class="sn-badge">{{ $unreadNotif }}</span>@endif
        </a>
    </nav>
    <div class="sidebar-cta">
        <div class="scta-text"><strong>Baca lebih banyak,<br>wawasan lebih luas!</strong></div>
        <a href="{{ route('user.katalog') }}" class="scta-btn">Jelajahi Buku</a>
        <div class="scta-illustration">📚</div>
    </div>
</aside>

<div class="dash-main">
    <header class="dash-topbar">
        <div class="topbar-left">
            <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
            <div class="topbar-brand-mobile">
                <span style="font-size:1.2rem">📚</span>
                <span style="font-weight:700;font-size:.95rem;color:#111827">Digi<strong style="color:#1a56db">Library</strong></span>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('user.notifikasi') }}" class="topbar-notif">
                🔔@if($unreadNotif > 0)<span class="tn-badge">{{ $unreadNotif }}</span>@endif
            </a>
            <div class="topbar-user" onclick="toggleUserMenu()">
                @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}"
            style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb">
    @else
        <div class="tu-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
    @endif
    <span class="tu-name">{{ explode(' ',$user->name)[0] }}</span>
    <span>▾</span>
                <div class="tu-dropdown" id="userMenu">
                    <a href="{{ route('user.profil') }}">👤 Profil Saya</a>
                    <a href="#">⚙️ Pengaturan</a>
                    <div class="tu-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf<button type="submit">🚪 Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="dash-content">
        <div class="ulasan-layout">

            {{-- KONTEN UTAMA --}}
            <div class="ulasan-main">

                @if(session('success'))
                <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:10px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px">
                    ✓ {{ session('success') }}
                </div>
                @endif

                <div class="ulasan-header">
                    <div>
                        <h2>💬 Ulasan Saya</h2>
                        <p>Lihat semua ulasan yang pernah Anda berikan untuk buku.</p>
                    </div>
                    <div class="uh-right">
                        <span class="ul-count">Menampilkan {{ $reviews->firstItem() ?? 0 }}-{{ $reviews->lastItem() ?? 0 }} dari {{ $reviews->total() }} ulasan</span>
                        <form action="{{ route('user.ulasan') }}" method="GET">
                            <select name="sort" onchange="this.form.submit()" class="ul-sort">
                                <option value="terbaru" {{ request('sort','terbaru')=='terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="rating_tinggi" {{ request('sort')=='rating_tinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                                <option value="rating_rendah" {{ request('sort')=='rating_rendah' ? 'selected' : '' }}>Rating Terendah</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($reviews->count() > 0)
                <div class="ulasan-list">
                    @foreach($reviews as $review)
                    @php
                        $bcolors  = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                        $btcolors = ['#92400e','#1e40af','#166634','#9d174d','#5b21b6','#9a3412'];
                        $idx = ($review->book->id ?? 0) % 6;
                        $ratingLabelsArr = [1=>'Sangat Buruk',2=>'Buruk',3=>'Cukup',4=>'Bagus',5=>'Sangat Bagus'];
                    @endphp

                    {{-- ITEM ULASAN --}}
                    <div class="ulasan-item" id="ulasan-{{ $review->id }}">
                        <div class="ul-cover" style="background:{{ $bcolors[$idx] }}">
                            @if($review->book && $review->book->gambar)
                                <img src="{{ asset('storage/'.$review->book->gambar) }}" alt="{{ $review->book->judul }}">
                            @else
                                <span style="font-size:1rem;font-weight:900;color:{{ $btcolors[$idx] }};opacity:.5">
                                    {{ strtoupper(substr($review->book->judul ?? 'B', 0, 2)) }}
                                </span>
                            @endif
                        </div>

                        <div class="ul-book-info">
                            <div class="ul-title">{{ $review->book->judul ?? '-' }}</div>
                            <div class="ul-author">{{ $review->book->penulis ?? '-' }}</div>
                            @if($review->book && $review->book->category)
                            <span class="ul-cat">{{ $review->book->category->nama_kategori }}</span>
                            @endif
                        </div>

                        <div class="ul-review-content">
                            <div class="ul-review-header">
                                <div class="ul-review-label">Ulasan Anda</div>
                                <div class="ul-review-date">{{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}</div>
                            </div>
                            <div class="ul-stars">
                                @for($i=1;$i<=5;$i++)
                                    <span class="{{ $i<=$review->rating ? 'us-on' : 'us-off' }}">★</span>
                                @endfor
                            </div>
                            <div class="ul-komentar">{{ $review->komentar ?? 'Tidak ada komentar.' }}</div>
                        </div>

                        <div class="ul-actions">
                            <div class="ul-menu-wrap">
                                <button class="ul-menu-btn" onclick="toggleMenu({{ $review->id }})">⋮</button>
                                <div class="ul-dropdown" id="menu-{{ $review->id }}">
                                    <button onclick="editUlasan({{ $review->id }})">✏️ Edit</button>
                                    <button onclick="hapusUlasan({{ $review->id }})" class="ud-hapus">🗑 Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FORM EDIT (di luar .ulasan-item, dalam foreach) --}}
                    <div id="edit-form-{{ $review->id }}"
                        style="display:none;background:#f8faff;border:1.5px solid #c7d7fb;border-radius:14px;padding:20px;margin-top:-8px">
                        <div style="font-size:.875rem;font-weight:700;color:#1d4ed8;margin-bottom:14px">✏️ Edit Ulasan — {{ $review->book->judul ?? '' }}</div>
                        <form action="{{ route('user.ulasan.edit', $review->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Rating --}}
                            <div style="margin-bottom:14px">
                                <div style="font-size:.82rem;font-weight:700;color:#374151;margin-bottom:8px">
                                    Rating <span style="color:#ef4444">*</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                                    <div class="star-pick" id="edit-stars-{{ $review->id }}">
                                        @for($s=1;$s<=5;$s++)
                                        <button type="button"
                                            class="star-pick-btn {{ $s <= $review->rating ? 'lit' : '' }}"
                                            onclick="setEditRating({{ $review->id }}, {{ $s }})"
                                            onmouseover="hoverEditRating({{ $review->id }}, {{ $s }})"
                                            onmouseout="unhoverEditRating({{ $review->id }})">★</button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="edit-rating-{{ $review->id }}" value="{{ $review->rating }}">
                                    <div id="edit-badge-{{ $review->id }}"
                                        style="padding:4px 12px;border-radius:99px;font-size:.8rem;font-weight:700;background:#fef3c7;color:#d97706">
                                        ★ {{ $ratingLabelsArr[$review->rating] ?? '' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Komentar --}}
                            <div style="margin-bottom:14px">
                                <div style="font-size:.82rem;font-weight:700;color:#374151;margin-bottom:6px">
                                    Komentar <span style="color:#9ca3af;font-weight:400">(opsional)</span>
                                </div>
                                <textarea name="komentar" rows="3" maxlength="500"
                                    placeholder="Tulis ulasanmu di sini..."
                                    style="width:100%;padding:12px;border:1.5px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.875rem;resize:none;outline:none;transition:all .2s;line-height:1.6"
                                    onfocus="this.style.borderColor='#1a56db';this.style.boxShadow='0 0 0 3px rgba(26,86,219,.08)'"
                                    onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                                    oninput="document.getElementById('edit-count-{{ $review->id }}').textContent=this.value.length"
                                    >{{ $review->komentar }}</textarea>
                                <div style="text-align:right;font-size:.72rem;color:#9ca3af;margin-top:3px">
                                    <span id="edit-count-{{ $review->id }}">{{ strlen($review->komentar ?? '') }}</span>/500
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <div style="display:flex;gap:10px">
                                <button type="button" onclick="tutupEditForm({{ $review->id }})"
                                    style="padding:10px 20px;border-radius:8px;border:1.5px solid #e5e7eb;background:white;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;color:#374151;transition:all .2s"
                                    onmouseover="this.style.background='#f3f4f6'"
                                    onmouseout="this.style.background='white'">
                                    Batal
                                </button>
                                <button type="submit"
                                    style="flex:1;padding:10px;border-radius:8px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;transition:all .25s">
                                    💾 Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="katalog-pagination" style="margin-top:24px">
                    @if($reviews->onFirstPage())
                        <span class="kp-btn kp-disabled">‹</span>
                    @else
                        <a href="{{ $reviews->previousPageUrl() }}" class="kp-btn">‹</a>
                    @endif
                    @foreach($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                        @if($page == $reviews->currentPage())
                            <span class="kp-btn kp-active">{{ $page }}</span>
                        @elseif($page==1 || $page==$reviews->lastPage() || abs($page-$reviews->currentPage())<=1)
                            <a href="{{ $url }}" class="kp-btn">{{ $page }}</a>
                        @elseif(abs($page-$reviews->currentPage())==2)
                            <span class="kp-dots">...</span>
                        @endif
                    @endforeach
                    @if($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}" class="kp-btn">›</a>
                    @else
                        <span class="kp-btn kp-disabled">›</span>
                    @endif
                </div>

                @else
                <div class="katalog-empty" style="padding:80px 20px">
                    <div class="ke-emoji">💬</div>
                    <h3>Belum ada ulasan</h3>
                    <p>Pinjam dan kembalikan buku dulu, lalu berikan ulasanmu!</p>
                    <a href="{{ route('user.katalog') }}" class="ke-btn">Jelajahi Katalog</a>
                </div>
                @endif
            </div>

            {{-- PANEL KANAN --}}
            <div class="ulasan-right">
                <div class="dr-card">
                    <h4 class="dr-title">Ringkasan Ulasan</h4>
                    <div class="summary-list">
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#ede9fe">💬</div>
                            <div class="sum-info">Total Ulasan</div>
                            <span class="sum-val" style="color:#7c3aed">{{ $totalUlasan }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fef3c7">⭐</div>
                            <div class="sum-info">Rata-rata Rating</div>
                            <span class="sum-val" style="color:#d97706">{{ $avgRating ? number_format($avgRating,1) : '-' }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dcfce7">📖</div>
                            <div class="sum-info">Buku Diulas</div>
                            <span class="sum-val" style="color:#16a34a">{{ $totalUlasan }}</span>
                        </div>
                    </div>
                </div>

                <div class="dr-card">
                    <h4 class="dr-title">Tips Memberikan Ulasan</h4>
                    <div class="tips-list">
                        <div class="tip-item">
                            <div class="tip-icon" style="background:#ede9fe">💬</div>
                            <div class="tip-text">Berikan pendapat yang jujur dan membantu pembaca lain.</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon" style="background:#fef3c7">⭐</div>
                            <div class="tip-text">Sebutkan hal yang Anda sukai atau tidak sukai dari buku.</div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon" style="background:#dcfce7">✅</div>
                            <div class="tip-text">Jaga bahasa tetap sopan dan positif.</div>
                        </div>
                    </div>
                </div>

                @if($lastReview)
                <div class="dr-card">
                    <div class="dr-header">
                        <h4 class="dr-title">Ulasan Terbaru</h4>
                        <a href="{{ route('user.ulasan') }}" class="dr-more">Lihat semua →</a>
                    </div>
                    @php $idx2 = ($lastReview->book->id ?? 0) % 6; $bcolors2 = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5']; @endphp
                    <div class="last-review-card">
                        <div class="lrc-cover" style="background:{{ $bcolors2[$idx2] }}">
                            @if($lastReview->book && $lastReview->book->gambar)
                                <img src="{{ asset('storage/'.$lastReview->book->gambar) }}" alt="">
                            @else
                                <span style="font-size:.9rem;font-weight:900;opacity:.4">{{ strtoupper(substr($lastReview->book->judul ?? 'B', 0, 2)) }}</span>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:.82rem;font-weight:700;color:#111827;margin-bottom:3px">{{ $lastReview->book->judul ?? '-' }}</div>
                            <div class="ul-stars" style="font-size:.9rem">
                                @for($i=1;$i<=5;$i++)<span class="{{ $i<=$lastReview->rating ? 'us-on' : 'us-off' }}">★</span>@endfor
                            </div>
                            <div style="font-size:.72rem;color:#9ca3af;margin-top:2px">{{ $lastReview->created_at ? $lastReview->created_at->format('d M Y') : '' }}</div>
                        </div>
                    </div>
                    <a href="{{ route('user.ulasan') }}" class="fr-explore-btn" style="margin-top:12px">Lihat Semua Ulasan →</a>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-open'); }
function toggleUserMenu() { document.getElementById('userMenu').classList.toggle('show'); }
document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-user')) document.getElementById('userMenu')?.classList.remove('show');
    if (!e.target.closest('.ul-menu-wrap')) document.querySelectorAll('.ul-dropdown').forEach(d => d.classList.remove('show'));
});

function toggleMenu(id) {
    const menu = document.getElementById('menu-' + id);
    document.querySelectorAll('.ul-dropdown').forEach(d => { if (d !== menu) d.classList.remove('show'); });
    menu.classList.toggle('show');
}

function editUlasan(id) {
    // Tutup semua form edit lain
    document.querySelectorAll('[id^="edit-form-"]').forEach(f => f.style.display = 'none');
    // Tutup dropdown menu
    document.querySelectorAll('.ul-dropdown').forEach(d => d.classList.remove('show'));
    const form = document.getElementById('edit-form-' + id);
    form.style.display = 'block';
    setTimeout(() => form.scrollIntoView({ behavior:'smooth', block:'nearest' }), 50);
}

function tutupEditForm(id) {
    document.getElementById('edit-form-' + id).style.display = 'none';
}

// Edit rating
const editLabels = {1:'Sangat Buruk',2:'Buruk',3:'Cukup',4:'Bagus',5:'Sangat Bagus'};
let currentEditRating = {};

function setEditRating(id, val) {
    currentEditRating[id] = val;
    document.getElementById('edit-rating-' + id).value = val;
    const badge = document.getElementById('edit-badge-' + id);
    badge.textContent = '★ ' + editLabels[val];
    updateEditStars(id, val);
}

function hoverEditRating(id, val) { updateEditStars(id, val); }

function unhoverEditRating(id) {
    updateEditStars(id, currentEditRating[id] || parseInt(document.getElementById('edit-rating-' + id).value) || 0);
}

function updateEditStars(id, val) {
    document.querySelectorAll('#edit-stars-' + id + ' .star-pick-btn').forEach((s, i) => {
        s.classList.toggle('lit', i < val);
        s.style.transform = i < val ? 'scale(1.1)' : 'scale(1)';
    });
}

// Inisialisasi currentEditRating dari nilai awal
document.querySelectorAll('[id^="edit-rating-"]').forEach(input => {
    const id = input.id.replace('edit-rating-', '');
    currentEditRating[id] = parseInt(input.value) || 0;
});

function hapusUlasan(id) {
    if (!confirm('Hapus ulasan ini? Tindakan tidak bisa dibatalkan.')) return;
    fetch('/user/ulasan/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(r => {
        if (r.ok || r.status === 200 || r.status === 302) {
            const item = document.getElementById('ulasan-' + id);
            const editForm = document.getElementById('edit-form-' + id);
            [item, editForm].forEach(el => {
                if (el) {
                    el.style.transition = 'all .3s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(20px)';
                    setTimeout(() => el.remove(), 300);
                }
            });
            setTimeout(() => location.reload(), 400);
        }
    }).catch(() => location.reload());
}
</script>
</body>
</html>