<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/profil.css')) !!}
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
        <a href="{{ route('user.ulasan') }}" class="sn-item"><span class="sn-icon">💬</span><span>Ulasan Saya</span></a>
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
            <div class="topbar-search">
                <span>🔍</span>
                <input type="text" placeholder="Cari buku, penulis, atau kategori...">
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
        <div class="profil-layout">

            {{-- KONTEN UTAMA --}}
            <div class="profil-main">
                <div class="profil-header-title">
                    <h2>👤 Profil Saya</h2>
                    <p>Kelola informasi profil dan preferensi akun Anda.</p>
                </div>

                @if(session('success'))
                <div class="profil-alert">✓ {{ session('success') }}</div>
                @endif

                {{-- KARTU PROFIL --}}
                <div class="profil-card">
                    <div class="pc-avatar-wrap">
    @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}"
            alt="{{ $user->name }}"
            style="width:90px;height:90px;border-radius:50%;object-fit:cover;box-shadow:0 4px 16px rgba(26,86,219,.3)">
    @else
        <div class="pc-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
    @endif

    {{-- Tombol upload --}}
    <label for="avatarInput" class="pc-avatar-edit" title="Ubah foto" style="cursor:pointer">
        📷
    </label>
    <form id="avatarForm" action="{{ route('user.profil.avatar') }}" method="POST" enctype="multipart/form-data" style="display:none">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/*"
            onchange="document.getElementById('avatarForm').submit()">
    </form>
</div>
                    <div class="pc-info">
                        <div class="pc-name-row">
                            <h3>{{ $user->name }}</h3>
                            <span class="pc-badge">Anggota</span>
                        </div>
                        <div class="pc-details">
                            <div class="pc-detail-item">
                                <span class="pdi-icon">✉️</span>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="pc-detail-item">
                                <span class="pdi-icon">📞</span>
                                <span>{{ $user->phone ?? 'Belum diisi' }}</span>
                            </div>
                            <div class="pc-detail-item">
                                <span class="pdi-icon">📅</span>
                                <span>Bergabung sejak {{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="pc-detail-item">
                                <span class="pdi-icon">📍</span>
                                <span>{{ $user->address ?? 'Belum diisi' }}</span>
                            </div>
                        </div>
                    </div>
                    <button class="pc-edit-btn" onclick="toggleEdit()">✏️ Edit Profil</button>
                </div>

                {{-- FORM EDIT (tersembunyi) --}}
<div class="edit-form-wrap" id="editForm" style="display:none">
    <div class="ef-header">
        <h4>✏️ Edit Profil</h4>
        <button onclick="toggleEdit()" class="ef-close">✕</button>
    </div>
    <form action="{{ route('user.profil.update') }}" method="POST" class="ef-form">
        @csrf
        @method('PUT')

        <div class="ef-row">
            {{-- Nama --}}
            <div class="lf-group">
                <label>Nama Lengkap</label>
                <div class="lf-input-wrap">
                    <span class="lf-icon">👤</span>
                    <input type="text" name="name"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Nama lengkap"
                        required
                        style="padding:12px 14px 12px 44px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.9rem;width:100%;background:#fafafa;outline:none;transition:all .25s"
                        onfocus="this.style.borderColor='#1a56db';this.style.background='white'"
                        onblur="this.style.borderColor='#e5e7eb';this.style.background='#fafafa'">
                </div>
            </div>

            {{-- No Telepon --}}
            <div class="lf-group">
                <label>No. Telepon</label>
                <div class="lf-input-wrap">
                    <span class="lf-icon">📞</span>
                    <input type="text" name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="08xxxxxxxxxx"
                        style="padding:12px 14px 12px 44px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.9rem;width:100%;background:#fafafa;outline:none;transition:all .25s"
                        onfocus="this.style.borderColor='#1a56db';this.style.background='white'"
                        onblur="this.style.borderColor='#e5e7eb';this.style.background='#fafafa'">
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="lf-group">
            <label>Alamat</label>
            <div class="lf-input-wrap">
                <span class="lf-icon" style="top:14px;align-self:flex-start">📍</span>
                <textarea name="address" rows="3"
                    placeholder="Alamat lengkap Anda..."
                    style="padding:12px 14px 12px 44px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.9rem;width:100%;resize:none;background:#fafafa;outline:none;transition:all .25s;line-height:1.6"
                    onfocus="this.style.borderColor='#1a56db';this.style.background='white'"
                    onblur="this.style.borderColor='#e5e7eb';this.style.background='#fafafa'"
                    >{{ old('address', $user->address) }}</textarea>
            </div>
        </div>

        {{-- Preview perubahan --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 16px;display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <span style="font-size:1.2rem">ℹ️</span>
            <span style="font-size:.8rem;color:#0369a1;line-height:1.5">
                Perubahan akan langsung tersimpan dan ditampilkan di profil Anda.
            </span>
        </div>

        <div class="ef-actions">
            <button type="button" onclick="toggleEdit()" class="ef-btn-cancel">Batal</button>
            <button type="submit" class="ef-btn-save">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

                {{-- RINGKASAN AKTIVITAS --}}
                <div class="aktivitas-stats">
                    <div class="as-item">
                        <div class="as-icon" style="background:#dbeafe;color:#1d4ed8">📖</div>
                        <div class="as-num">{{ $stats['dipinjam'] }}</div>
                        <div class="as-label">Buku Dipinjam</div>
                        <div class="as-sub">Total peminjaman</div>
                    </div>
                    <div class="as-item">
                        <div class="as-icon" style="background:#dcfce7;color:#15803d">✅</div>
                        <div class="as-num">{{ $stats['dikembalikan'] }}</div>
                        <div class="as-label">Buku Dikembalikan</div>
                        <div class="as-sub">Selesai dipinjam</div>
                    </div>
                    <div class="as-item">
                        <div class="as-icon" style="background:#fee2e2;color:#b91c1c">❤️</div>
                        <div class="as-num">{{ $stats['favorit'] }}</div>
                        <div class="as-label">Buku Favorit</div>
                        <div class="as-sub">Disimpan</div>
                    </div>
                    <div class="as-item">
                        <div class="as-icon" style="background:#fef3c7;color:#d97706">⭐</div>
                        <div class="as-num">{{ $stats['ulasan'] }}</div>
                        <div class="as-label">Ulasan Dibuat</div>
                        <div class="as-sub">Total ulasan</div>
                    </div>
                </div>

                {{-- AKTIVITAS TERBARU --}}
                <div class="dr-card" style="margin-top:20px">
                    <div class="dr-header">
                        <h4 class="dr-title">Aktivitas Terbaru</h4>
                        <a href="{{ route('user.riwayat') }}" class="dr-more">Lihat Semua</a>
                    </div>
                    @forelse($aktivitas as $ak)
                    <div class="ak-item">
                        <div class="ak-icon
                            @if($ak['tipe']=='kembali') ak-green
                            @elseif($ak['tipe']=='pinjam') ak-blue
                            @elseif($ak['tipe']=='favorit') ak-red
                            @else ak-yellow @endif">
                            {{ $ak['icon'] }}
                        </div>
                        <div class="ak-content">
                            <div class="ak-msg">{!! $ak['pesan'] !!}</div>
                        </div>
                        <div class="ak-time">
                            {{ $ak['waktu'] ? \Carbon\Carbon::parse($ak['waktu'])->format('d M Y, H:i') : '' }}
                        </div>
                    </div>
                    @empty
                    <div class="empty-small">Belum ada aktivitas.</div>
                    @endforelse
                </div>
            </div>

            {{-- PANEL KANAN --}}
<div class="profil-right">

    {{-- Keamanan --}}
    <div class="dr-card">
        <h4 class="dr-title">Keamanan Akun</h4>
        <div class="keamanan-status">
            <div class="ks-icon">🛡️</div>
            <div>
                <div class="ks-label">Akun Anda aman</div>
                <div class="ks-sub">Terakhir aktif: Baru saja</div>
            </div>
        </div>

        @if(session('success_password'))
        <div class="profil-alert" style="margin-bottom:10px">✓ {{ session('success_password') }}</div>
        @endif
        @if(session('error_password'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:8px 12px;border-radius:8px;font-size:.8rem;margin-bottom:10px">
            ✕ {{ session('error_password') }}
        </div>
        @endif
        @if(session('success_email'))
        <div class="profil-alert" style="margin-bottom:10px">✓ {{ session('success_email') }}</div>
        @endif
        @if(session('success_telpon'))
        <div class="profil-alert" style="margin-bottom:10px">✓ {{ session('success_telpon') }}</div>
        @endif

        <div class="keamanan-list">
            <div class="kl-item" onclick="toggleSection('formPassword')" style="cursor:pointer">
                <span class="kl-icon">🔒</span>
                <span>Ubah Password</span>
                <span class="kl-arrow" id="arrowPassword">›</span>
            </div>
            <div id="formPassword" style="display:none;padding:12px;background:#f9fafb;border-radius:10px;margin:4px 0">
                <form action="{{ route('user.profil.password') }}" method="POST">
                    @csrf
                    <div class="lf-group" style="margin-bottom:10px">
                        <label style="font-size:.78rem;font-weight:600;color:#374151">Password Lama</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">🔒</span>
                            <input type="password" name="password_lama" placeholder="Password lama" required>
                            <button type="button" class="lf-eye" onclick="togglePass('pl',this)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <input type="password" id="pl" name="password_lama" style="display:none">
                        </div>
                    </div>
                    <div class="lf-group" style="margin-bottom:10px">
                        <label style="font-size:.78rem;font-weight:600;color:#374151">Password Baru</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">🔑</span>
                            <input type="password" id="passBaruInput" name="password_baru" placeholder="Min. 6 karakter" required>
                            <button type="button" class="lf-eye" onclick="togglePassById('passBaruInput',this)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="lf-group" style="margin-bottom:12px">
                        <label style="font-size:.78rem;font-weight:600;color:#374151">Konfirmasi Password</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">🔑</span>
                            <input type="password" id="konfPassInput" name="password_baru_confirmation" placeholder="Ulangi password baru" required>
                            <button type="button" class="lf-eye" onclick="togglePassById('konfPassInput',this)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="ef-btn-save" style="width:100%">💾 Simpan Password</button>
                </form>
            </div>

            <div class="kl-item" onclick="toggleSection('formEmail')" style="cursor:pointer">
                <span class="kl-icon">✉️</span>
                <span>Ubah Email</span>
                <span class="kl-arrow" id="arrowEmail">›</span>
            </div>
            <div id="formEmail" style="display:none;padding:12px;background:#f9fafb;border-radius:10px;margin:4px 0">
                <form action="{{ route('user.profil.email') }}" method="POST">
                    @csrf
                    <div style="font-size:.78rem;color:#6b7280;margin-bottom:8px">
                        Email saat ini: <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="lf-group" style="margin-bottom:12px">
                        <label style="font-size:.78rem;font-weight:600;color:#374151">Email Baru</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">✉️</span>
                            <input type="email" name="email_baru" placeholder="Email baru" required>
                        </div>
                    </div>
                    <button type="submit" class="ef-btn-save" style="width:100%">💾 Simpan Email</button>
                </form>
            </div>

            <div class="kl-item" onclick="toggleSection('formTelpon')" style="cursor:pointer">
                <span class="kl-icon">📞</span>
                <span>Ubah Nomor Telepon</span>
                <span class="kl-arrow" id="arrowTelpon">›</span>
            </div>
            <div id="formTelpon" style="display:none;padding:12px;background:#f9fafb;border-radius:10px;margin:4px 0">
                <form action="{{ route('user.profil.telpon') }}" method="POST">
                    @csrf
                    <div style="font-size:.78rem;color:#6b7280;margin-bottom:8px">
                        No. telp saat ini: <strong>{{ $user->phone ?? 'Belum diisi' }}</strong>
                    </div>
                    <div class="lf-group" style="margin-bottom:12px">
                        <label style="font-size:.78rem;font-weight:600;color:#374151">Nomor Telepon Baru</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">📞</span>
                            <input type="text" name="no_telp_baru" value="{{ $user->phone }}" ...>
                        </div>
                    </div>
                    <button type="submit" class="ef-btn-save" style="width:100%">💾 Simpan Nomor</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Preferensi --}}
    <div class="dr-card">
        <h4 class="dr-title">Preferensi</h4>
        @if(session('success_preferensi'))
        <div class="profil-alert" style="margin-bottom:10px">✓ {{ session('success_preferensi') }}</div>
        @endif
        <form action="{{ route('user.profil.preferensi') }}" method="POST">
            @csrf
            <div class="keamanan-list" style="margin-bottom:14px">
                <div class="kl-item" onclick="toggleSection('formBahasa')" style="cursor:pointer">
                    <span class="kl-icon">🌐</span>
                    <span>Bahasa</span>
                    <span class="kl-val">{{ session('bahasa', 'Indonesia') }}</span>
                    <span class="kl-arrow">›</span>
                </div>
                <div id="formBahasa" style="display:none;padding:10px 12px;background:#f9fafb;border-radius:8px;margin:4px 0">
                    <select name="bahasa" class="ul-sort" style="width:100%">
                        <option value="Indonesia" {{ session('bahasa','Indonesia') == 'Indonesia' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                        <option value="English" {{ session('bahasa') == 'English' ? 'selected' : '' }}>🇺🇸 English</option>
                    </select>
                </div>

                <div class="kl-item" onclick="toggleSection('formMode')" style="cursor:pointer">
                    <span class="kl-icon">🌙</span>
                    <span>Mode Tampilan</span>
                    <span class="kl-val">{{ session('mode_tampilan', 'Terang') }}</span>
                    <span class="kl-arrow">›</span>
                </div>
                <div id="formMode" style="display:none;padding:10px 12px;background:#f9fafb;border-radius:8px;margin:4px 0">
                    <select name="mode_tampilan" class="ul-sort" style="width:100%">
                        <option value="Terang" {{ session('mode_tampilan','Terang') == 'Terang' ? 'selected' : '' }}>☀️ Terang</option>
                        <option value="Gelap" {{ session('mode_tampilan') == 'Gelap' ? 'selected' : '' }}>🌙 Gelap</option>
                        <option value="Otomatis" {{ session('mode_tampilan') == 'Otomatis' ? 'selected' : '' }}>🔄 Otomatis</option>
                    </select>
                </div>

                <a href="{{ route('user.notifikasi') }}" class="kl-item">
                    <span class="kl-icon">🔔</span>
                    <span>Pengaturan Notifikasi</span>
                    <span class="kl-arrow">›</span>
                </a>
            </div>
            <button type="submit" class="ef-btn-save" style="width:100%">💾 Simpan Preferensi</button>
        </form>
    </div>

    {{-- Tentang Akun --}}
    <div class="dr-card">
        <h4 class="dr-title">Tentang Akun</h4>
        <div class="tentang-list">
            <div class="tl-item">
                <span class="tl-icon">👤</span>
                <span class="tl-label">Jenis Akun</span>
                <span class="tl-val">Anggota</span>
            </div>
            <div class="tl-item">
                <span class="tl-icon">📅</span>
                <span class="tl-label">Bergabung Sejak</span>
                <span class="tl-val">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div class="tl-item">
                <span class="tl-icon">🪪</span>
                <span class="tl-label">ID Anggota</span>
                <span class="tl-val">DL-{{ str_pad($user->id, 8, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:14px">
            @csrf
            <button type="submit" class="keluar-btn">🚪 Keluar Akun</button>
        </form>
    </div>

</div>

<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-open'); }
function toggleUserMenu() { document.getElementById('userMenu').classList.toggle('show'); }
document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-user')) document.getElementById('userMenu')?.classList.remove('show');
});

function toggleEdit() {
    const form = document.getElementById('editForm');
    const isHidden = form.style.display === 'none';
    form.style.display = isHidden ? 'block' : 'none';
    if (isHidden) form.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function toggleSection(id) {
    const el = document.getElementById(id);
    const isHidden = el.style.display === 'none';
    // Tutup semua dulu
    ['formPassword','formEmail','formTelpon','formBahasa','formMode'].forEach(f => {
        document.getElementById(f).style.display = 'none';
    });
    // Buka yang diklik
    if (isHidden) el.style.display = 'block';
}

function togglePassById(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.style.color = input.type === 'text' ? 'var(--primary)' : '';
}

// Buka form yang error otomatis
@if(session('error_password') || $errors->has('password_lama') || $errors->has('password_baru'))
    document.getElementById('formPassword').style.display = 'block';
@endif
@if($errors->has('email_baru'))
    document.getElementById('formEmail').style.display = 'block';
@endif
</script>
</body>
</html>