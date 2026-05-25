<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Peminjaman - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/riwayat.css')) !!}
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
        <a href="{{ route('user.riwayat') }}" class="sn-item active"><span class="sn-icon">🕐</span><span>Riwayat Peminjaman</span></a>
        <a href="{{ route('user.favorit') }}" class="sn-item"><span class="sn-icon">❤️</span><span>Buku Favorit</span></a>
        <a href="{{ route('user.ulasan') }}" class="sn-item"><span class="sn-icon">💬</span><span>Ulasan Saya</span></a>
        <a href="{{ route('user.notifikasi') }}" class="sn-item">
            <span class="sn-icon">🔔</span><span>Notifikasi</span>
            @if($unreadNotif > 0)<span class="sn-badge">{{ $unreadNotif }}</span>@endif
        </a>
    </nav>
    <div class="sidebar-cta">
        <div class="scta-text"><strong>Baca lebih banyak,<br>wawasan lebih luas!</strong><br><small style="opacity:.7;font-size:.72rem">Pinjam buku kapan saja dan dimana saja.</small></div>
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
        {{-- STRUKTUR UTAMA: 2 KOLOM --}}
        <div class="riwayat-layout">

            {{-- ========== KOLOM KIRI: KONTEN UTAMA ========== --}}
            <div class="riwayat-main">

                <div class="riwayat-header">
                    <h2>Riwayat Peminjaman</h2>
                    <p>Daftar semua buku yang pernah Anda pinjam.</p>
                </div>

                @if(session('success'))
                <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:10px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px">
                    ✓ {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:10px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px">
                    ✕ {{ session('error') }}
                </div>
                @endif

                {{-- TABS --}}
                <div class="riwayat-tabs">
                    @php $tabs = ['semua'=>'Semua','dipinjam'=>'Dipinjam','pending'=>'Menunggu Konfirmasi','dikembalikan'=>'Selesai','terlambat'=>'Terlambat','ditolak'=>'Ditolak']; @endphp
                    @foreach($tabs as $key => $label)
                    <a href="{{ route('user.riwayat', ['status'=>$key]) }}"
                       class="rt-tab {{ $status===$key ? 'rt-active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>

                @if($peminjaman->count() > 0)
                <div class="riwayat-list">
                    @foreach($peminjaman as $p)
                    @php
                        $bcolors  = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                        $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
                        $idx = ($p->book->id ?? 0) % 6;
                        $sudahUlasan = null;
                        if ($p->status === 'dikembalikan') {
                            $sudahUlasan = \App\Models\Review::where('user_id', auth()->id())
                                ->where('book_id', $p->book_id)->first();
                        }
                    @endphp

                    <div class="riwayat-item-wrap">
                        <div class="riwayat-item">
                            {{-- Cover --}}
                            <div class="ri2-cover" style="background:{{ $bcolors[$idx] }}">
                                @if($p->book && $p->book->gambar)
                                    <img src="{{ asset('storage/'.$p->book->gambar) }}" alt="{{ $p->book->judul }}">
                                @else
                                    <span style="font-size:1.1rem;font-weight:900;color:{{ $btcolors[$idx] }};opacity:.5">
                                        {{ strtoupper(substr($p->book->judul ?? 'B', 0, 2)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="ri2-info">
                                <div class="ri2-title">{{ $p->book->judul ?? '-' }}</div>
                                <div class="ri2-author">{{ $p->book->penulis ?? '-' }}</div>
                                @if($p->book && $p->book->category)
                                <span class="ri2-cat">{{ $p->book->category->nama_kategori }}</span>
                                @endif
                            </div>

                            {{-- Tanggal --}}
                            <div class="ri2-dates">
                                <div class="ri2-date-item">
                                    <span class="ri2-date-label">📅 Tanggal Pinjam</span>
                                    <span class="ri2-date-val">{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d M Y') : '-' }}</span>
                                </div>
                                <div class="ri2-date-item">
                                    <span class="ri2-date-label">📅 {{ $p->status==='pending' ? 'Estimasi Kembali' : 'Tanggal Kembali' }}</span>
                                    <span class="ri2-date-val">{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}</span>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="ri2-status-wrap">
                                <span class="ri2-status
                                    @if($p->status=='dipinjam') rs-pinjam
                                    @elseif($p->status=='pending') rs-pending
                                    @elseif($p->status=='terlambat') rs-terlambat
                                    @elseif($p->status=='dikembalikan') rs-kembali
                                    @elseif($p->status=='ditolak') rs-tolak
                                    @else rs-default @endif">
                                    @if($p->status=='dipinjam') Dipinjam
                                    @elseif($p->status=='pending') Menunggu Konfirmasi
                                    @elseif($p->status=='terlambat') Terlambat
                                    @elseif($p->status=='dikembalikan') Dikembalikan
                                    @elseif($p->status=='ditolak') Ditolak
                                    @else {{ ucfirst($p->status) }} @endif
                                </span>

                                @if($p->status==='dikembalikan' && $p->tanggal_dikembalikan)
                                <div class="ri2-returned">Dikembalikan pada {{ $p->tanggal_dikembalikan->format('d M Y') }}</div>
                                @endif
                                @if($p->status==='terlambat')
                                <div class="ri2-returned" style="color:#dc2626">Terlambat {{ now()->diffInDays($p->tanggal_kembali) }} hari</div>
                                @endif
                                @if($p->status==='pending')
                                <div class="ri2-returned">Menunggu persetujuan petugas.</div>
                                @endif

                                <div class="ri2-actions">
                                    <a href="{{ route('user.buku.detail', $p->book_id) }}" class="ri2-btn-detail">📖 Detail</a>
                                    @if($p->status === 'dikembalikan')
                                    <a href="{{ route('user.peminjaman.bukti', $p->id) }}" class="ri2-btn-detail"
                                        style="background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8">📄 Bukti</a>
                                    @endif
                                    @if($p->status==='terlambat' && !$p->bayar_denda)
                                    <a href="#" class="ri2-btn-denda">Bayar Denda</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- FORM ULASAN --}}
                        @if($p->status === 'dikembalikan')
                            @if(!$sudahUlasan)
                            <button class="ulasan-toggle-btn" onclick="toggleUlasanForm({{ $p->id }})">
                                <span>⭐</span>
                                Beri Ulasan & Rating untuk "{{ Str::limit($p->book->judul ?? '', 25) }}"
                                <span id="toggle-arrow-{{ $p->id }}" style="margin-left:auto;transition:transform .2s">▼</span>
                            </button>
                            <div id="ulasan-form-{{ $p->id }}" style="display:none" class="ulasan-form-section">
                                <form action="{{ route('user.ulasan.simpan', $p->book_id) }}" method="POST">
                                    @csrf
                                    <div style="display:flex;align-items:center;gap:12px;padding:14px 0 16px;border-bottom:1px solid #f3f4f6;margin-bottom:16px">
                                        <div style="width:44px;height:56px;border-radius:6px;background:{{ $bcolors[$idx] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.9rem;font-weight:900;color:{{ $btcolors[$idx] }};opacity:.6">
                                            {{ strtoupper(substr($p->book->judul ?? 'B', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-size:.9rem;font-weight:700;color:#111827">{{ $p->book->judul ?? '-' }}</div>
                                            <div style="font-size:.78rem;color:#6b7280">{{ $p->book->penulis ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:16px">
                                        <div style="font-size:.82rem;font-weight:700;color:#374151;margin-bottom:10px">Berikan Rating <span style="color:#ef4444">*</span></div>
                                        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
                                            <div class="star-pick" id="stars-{{ $p->id }}">
                                                @for($s=1;$s<=5;$s++)
                                                <button type="button" class="star-pick-btn"
                                                    onclick="setRating({{ $p->id }}, {{ $s }})"
                                                    onmouseover="hoverRating({{ $p->id }}, {{ $s }})"
                                                    onmouseout="unhoverRating({{ $p->id }})">★</button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating-{{ $p->id }}" value="0">
                                            <div id="rating-badge-{{ $p->id }}" style="display:none;padding:5px 14px;border-radius:99px;font-size:.8rem;font-weight:700;background:#fef3c7;color:#d97706"></div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:16px">
                                        <div style="font-size:.82rem;font-weight:700;color:#374151;margin-bottom:6px">Komentar <span style="color:#9ca3af;font-weight:400">(opsional)</span></div>
                                        <textarea name="komentar" rows="3" maxlength="500"
                                            placeholder="Ceritakan pengalamanmu membaca buku ini..."
                                            style="width:100%;padding:12px;border:1.5px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.875rem;resize:none;outline:none;transition:all .2s"
                                            onfocus="this.style.borderColor='#1a56db'"
                                            onblur="this.style.borderColor='#e5e7eb'"
                                            oninput="document.getElementById('count-{{ $p->id }}').textContent=this.value.length"></textarea>
                                        <div style="text-align:right;font-size:.72rem;color:#9ca3af;margin-top:3px"><span id="count-{{ $p->id }}">0</span>/500</div>
                                    </div>
                                    <div style="display:flex;gap:10px">
                                        <button type="button" onclick="toggleUlasanForm({{ $p->id }})"
                                            style="padding:11px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:white;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer">Batal</button>
                                        <button type="submit" id="submit-{{ $p->id }}" disabled
                                            style="flex:1;padding:11px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;opacity:.5;transition:all .2s">
                                            ⭐ Kirim Ulasan
                                        </button>
                                    </div>
                                </form>
                            </div>
                            @else
                            <div class="ulasan-sudah">
                                <div style="display:flex;gap:3px">
                                    @for($s=1;$s<=5;$s++)
                                    <span style="color:{{ $s<=$sudahUlasan->rating ? '#f59e0b' : '#e5e7eb' }};font-size:1.1rem">★</span>
                                    @endfor
                                </div>
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:.78rem;font-weight:700;color:#15803d">✓ Sudah diulas</div>
                                    @if($sudahUlasan->komentar)
                                    <div style="font-size:.75rem;color:#6b7280;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">"{{ $sudahUlasan->komentar }}"</div>
                                    @endif
                                </div>
                                <a href="{{ route('user.ulasan') }}" style="font-size:.78rem;color:#1a56db;text-decoration:none;font-weight:600;white-space:nowrap">Edit →</a>
                            </div>
                            @endif
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="katalog-pagination" style="margin-top:20px">
                    @if($peminjaman->onFirstPage())
                        <span class="kp-btn kp-disabled">‹</span>
                    @else
                        <a href="{{ $peminjaman->previousPageUrl() }}" class="kp-btn">‹</a>
                    @endif
                    @foreach($peminjaman->getUrlRange(1, $peminjaman->lastPage()) as $page => $url)
                        @if($page == $peminjaman->currentPage())
                            <span class="kp-btn kp-active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $peminjaman->lastPage() || abs($page - $peminjaman->currentPage()) <= 1)
                            <a href="{{ $url }}" class="kp-btn">{{ $page }}</a>
                        @elseif(abs($page - $peminjaman->currentPage()) == 2)
                            <span class="kp-dots">...</span>
                        @endif
                    @endforeach
                    @if($peminjaman->hasMorePages())
                        <a href="{{ $peminjaman->nextPageUrl() }}" class="kp-btn">›</a>
                    @else
                        <span class="kp-btn kp-disabled">›</span>
                    @endif
                </div>

                @else
                <div class="katalog-empty" style="padding:60px 20px">
                    <div class="ke-emoji">📭</div>
                    <h3>Belum ada peminjaman</h3>
                    <p>Kamu belum pernah meminjam buku{{ $status !== 'semua' ? ' dengan status ini' : '' }}.</p>
                    <a href="{{ route('user.katalog') }}" class="ke-btn">Jelajahi Katalog</a>
                </div>
                @endif

            </div>
            {{-- ========== AKHIR KOLOM KIRI ========== --}}

            {{-- ========== KOLOM KANAN: PANEL SIDEBAR ========== --}}
            <div class="riwayat-right">

                <div class="dr-card">
                    <h4 class="dr-title">Ringkasan Peminjaman</h4>
                    <div class="summary-list">
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dbeafe">📖</div>
                            <div class="sum-info">Sedang Dipinjam</div>
                            <span class="sum-val" style="color:#1d4ed8">{{ $ringkasan['dipinjam'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fef3c7">⏳</div>
                            <div class="sum-info">Menunggu Konfirmasi</div>
                            <span class="sum-val" style="color:#d97706">{{ $ringkasan['pending'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fee2e2">⚠️</div>
                            <div class="sum-info">Terlambat</div>
                            <span class="sum-val" style="color:#dc2626">{{ $ringkasan['terlambat'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dcfce7">✅</div>
                            <div class="sum-info">Selesai</div>
                            <span class="sum-val" style="color:#16a34a">{{ $ringkasan['dikembalikan'] }}</span>
                        </div>
                    </div>
                    <a href="#" class="rr-stat-btn">📊 Lihat Statistik</a>
                </div>

                <div class="dr-card">
                    <h4 class="dr-title">Ingat Tanggal Kembali</h4>
                    <div class="rr-calendar">
                        <div class="rr-cal-icon">📅</div>
                        @if($nearDue->count() > 0)
                            @foreach($nearDue as $nd)
                            <div class="rr-due-item">
                                <div class="rr-due-title">{{ $nd->book->judul ?? '-' }}</div>
                                <div class="rr-due-date {{ now()->gt($nd->tanggal_kembali) ? 'text-red' : '' }}">
                                    Kembali: {{ $nd->tanggal_kembali->format('d M Y') }}
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="rr-cal-text">Kembalikan buku tepat waktu untuk menghindari denda keterlambatan.</p>
                        @endif
                    </div>
                    <a href="#" class="rr-denda-btn">💰 Lihat Denda</a>
                </div>

                <div class="dr-card">
                    <h4 class="dr-title">Butuh Bantuan?</h4>
                    <p class="rr-help-text">Jika ada pertanyaan seputar peminjaman, hubungi petugas perpustakaan.</p>
                    <a href="#" class="rr-help-btn">🎧 Hubungi Petugas</a>
                </div>

            </div>
            {{-- ========== AKHIR KOLOM KANAN ========== --}}

        </div>
    </div>
</div>

<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-open'); }
function toggleUserMenu() { document.getElementById('userMenu').classList.toggle('show'); }
document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-user')) document.getElementById('userMenu')?.classList.remove('show');
});

const ratingLabels = {1:'Sangat Buruk',2:'Buruk',3:'Cukup',4:'Bagus',5:'Sangat Bagus'};
const ratingColors = {1:'#fee2e2',2:'#fef3c7',3:'#fef9c3',4:'#dcfce7',5:'#d1fae5'};
const ratingTextColors = {1:'#b91c1c',2:'#d97706',3:'#a16207',4:'#15803d',5:'#065f46'};
let currentRating = {};

function setRating(pId, val) {
    currentRating[pId] = val;
    document.getElementById('rating-' + pId).value = val;
    const badge = document.getElementById('rating-badge-' + pId);
    badge.style.display = 'block';
    badge.textContent = '★ ' + ratingLabels[val];
    badge.style.background = ratingColors[val];
    badge.style.color = ratingTextColors[val];
    updateStars(pId, val);
    const btn = document.getElementById('submit-' + pId);
    btn.disabled = false;
    btn.style.opacity = '1';
}

function hoverRating(pId, val) { updateStars(pId, val); }
function unhoverRating(pId) { updateStars(pId, currentRating[pId] || 0); }

function updateStars(pId, val) {
    document.querySelectorAll('#stars-' + pId + ' .star-pick-btn').forEach((s, i) => {
        s.classList.toggle('lit', i < val);
        s.style.transform = i < val ? 'scale(1.1)' : 'scale(1)';
    });
}

function toggleUlasanForm(id) {
    const form = document.getElementById('ulasan-form-' + id);
    const arrow = document.getElementById('toggle-arrow-' + id);
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    if (arrow) arrow.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    if (isHidden) form.scrollIntoView({ behavior:'smooth', block:'nearest' });
}
</script>
</body>
</html>