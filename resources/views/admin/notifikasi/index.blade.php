@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('page-sub', 'Kirim dan kelola notifikasi untuk pengguna')

@push('styles')
<style>
    .notif-layout { display:grid;grid-template-columns:1fr 340px;gap:20px; }
    .notif-stats { display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px; }
    .notif-stat { background:white;border-radius:14px;padding:16px 18px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);text-align:center;transition:all .25s; }
    .notif-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .notif-stat-icon { font-size:1.3rem;margin-bottom:6px; }
    .notif-stat-num { font-size:1.4rem;font-weight:800;color:#111827;line-height:1;margin-bottom:3px; }
    .notif-stat-label { font-size:.7rem;color:#64748b; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:180px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .filter-btn-danger { border-color:#fca5a5;color:#b91c1c;background:#fee2e2; }
    .filter-btn-danger:hover { background:#fca5a5; }
    .status-tabs { display:flex;gap:6px;padding:12px 20px;border-bottom:1px solid #f8fafc; }
    .status-tab { padding:6px 14px;border-radius:99px;font-size:.82rem;font-weight:600;border:1.5px solid #e2e8f0;background:white;color:#64748b;text-decoration:none;transition:all .2s; }
    .status-tab:hover { border-color:#1a56db;color:#1a56db; }
    .status-tab.active { background:#1a56db;color:white;border-color:#1a56db; }
    .status-tab.tab-belum.active { background:#ef4444;border-color:#ef4444; }
    .status-tab.tab-dibaca.active { background:#15803d;border-color:#15803d; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:12px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .user-avatar { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0; }
    .read-badge { padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .read-yes { background:#dcfce7;color:#15803d; }
    .read-no  { background:#fee2e2;color:#b91c1c; }
    .tbl-btn-del { display:inline-flex;align-items:center;gap:4px;padding:6px 10px;border-radius:8px;font-family:inherit;font-size:.75rem;font-weight:700;cursor:pointer;border:none;background:#fee2e2;color:#b91c1c;transition:all .2s; }
    .tbl-btn-del:hover { background:#fca5a5; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }

    /* PANEL KIRIM */
    .kirim-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);position:sticky;top:80px; }
    .kirim-header { padding:18px 20px;border-bottom:1px solid #f1f5f9; }
    .kirim-title { font-size:.95rem;font-weight:700;color:#111827; }
    .kirim-body { padding:20px; }
    .kf-group { display:flex;flex-direction:column;gap:6px;margin-bottom:14px; }
    .kf-label { font-size:.82rem;font-weight:600;color:#374151; }
    .kf-input { padding:10px 14px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;transition:all .25s;background:#fafafa;width:100%; }
    .kf-input:focus { border-color:#1a56db;background:white;box-shadow:0 0 0 3px rgba(26,86,219,.08); }
    .kf-radio-group { display:flex;gap:10px; }
    .kf-radio { display:flex;align-items:center;gap:6px;padding:10px 14px;border:2px solid #e5e7eb;border-radius:10px;cursor:pointer;flex:1;transition:all .2s;background:#fafafa; }
    .kf-radio:has(input:checked) { border-color:#1a56db;background:#eff6ff; }
    .kf-radio input { accent-color:#1a56db; }
    .kf-radio span { font-size:.82rem;font-weight:600; }
    .btn-kirim { width:100%;padding:12px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer;transition:all .25s;box-shadow:0 4px 12px rgba(26,86,219,.25); }
    .btn-kirim:hover { opacity:.9;transform:translateY(-1px); }
    .char-count { font-size:.72rem;color:#94a3b8;text-align:right;margin-top:3px; }

    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }

    @media(max-width:1100px) { .notif-layout{grid-template-columns:1fr;} .kirim-card{position:static;} }
    @media(max-width:700px)  { .notif-stats{grid-template-columns:repeat(2,1fr);} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Notifikasi</span>
</div>

{{-- STATS --}}
<div class="notif-stats">
    <div class="notif-stat">
        <div class="notif-stat-icon">🔔</div>
        <div class="notif-stat-num">{{ $stats['total'] }}</div>
        <div class="notif-stat-label">Total Notifikasi</div>
    </div>
    <div class="notif-stat" style="border-top:3px solid #ef4444">
        <div class="notif-stat-icon">🔴</div>
        <div class="notif-stat-num" style="color:#ef4444">{{ $stats['belum'] }}</div>
        <div class="notif-stat-label">Belum Dibaca</div>
    </div>
    <div class="notif-stat" style="border-top:3px solid #15803d">
        <div class="notif-stat-icon">✅</div>
        <div class="notif-stat-num" style="color:#15803d">{{ $stats['dibaca'] }}</div>
        <div class="notif-stat-label">Sudah Dibaca</div>
    </div>
    <div class="notif-stat" style="border-top:3px solid #1a56db">
        <div class="notif-stat-icon">📅</div>
        <div class="notif-stat-num" style="color:#1a56db">{{ $stats['hari_ini'] }}</div>
        <div class="notif-stat-label">Dikirim Hari Ini</div>
    </div>
</div>

<div class="notif-layout">

    {{-- KIRI: DAFTAR NOTIFIKASI --}}
    <div>
        <div class="adm-card">

            {{-- STATUS TABS --}}
            <div class="status-tabs">
                <a href="{{ route('admin.notifikasi.index') }}"
                    class="status-tab {{ !request('status') ? 'active':'' }}">
                    Semua ({{ $stats['total'] }})
                </a>
                <a href="{{ route('admin.notifikasi.index', ['status'=>'belum']) }}"
                    class="status-tab tab-belum {{ request('status')==='belum' ? 'active':'' }}">
                    🔴 Belum Dibaca ({{ $stats['belum'] }})
                </a>
                <a href="{{ route('admin.notifikasi.index', ['status'=>'dibaca']) }}"
                    class="status-tab tab-dibaca {{ request('status')==='dibaca' ? 'active':'' }}">
                    ✅ Dibaca ({{ $stats['dibaca'] }})
                </a>
            </div>

            {{-- FILTER + HAPUS --}}
            <div class="filter-bar">
                <form action="{{ route('admin.notifikasi.index') }}" method="GET"
                    style="display:flex;gap:10px;flex:1;align-items:center">
                    @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="filter-search" style="flex:1">
                        <span>🔍</span>
                        <input type="text" name="search" placeholder="Cari pesan atau nama user..."
                            value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="filter-btn">🔍 Cari</button>
                    @if(request('search'))
                    <a href="{{ route('admin.notifikasi.index') }}" class="filter-btn">Reset</a>
                    @endif
                </form>

                {{-- Hapus massal --}}
                <form action="{{ route('admin.notifikasi.hapus-semua') }}" method="POST"
                    onsubmit="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                    @csrf
                    <input type="hidden" name="target" value="dibaca">
                    <button type="submit" class="filter-btn filter-btn-danger">🗑 Hapus Dibaca</button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="adm-table-wrap">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penerima</th>
                            <th>Pesan</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifikasi as $i => $n)
                        <tr @if(!$n->is_read) style="background:#fafbff;font-weight:500" @endif>
                            <td style="color:#94a3b8">{{ $notifikasi->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="user-avatar">{{ strtoupper(substr($n->user->name ?? 'U', 0, 1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;color:#111827">{{ $n->user->name ?? '-' }}</div>
                                        <div style="font-size:.7rem;color:#94a3b8">{{ $n->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="max-width:280px">
                                <div style="font-size:.82rem;color:#374151;line-height:1.5">
                                    {{ Str::limit($n->pesan, 80) }}
                                </div>
                            </td>
                            <td>
                                <span class="read-badge {{ $n->is_read ? 'read-yes':'read-no' }}">
                                    {{ $n->is_read ? '✓ Dibaca':'● Belum' }}
                                </span>
                            </td>
                            <td style="font-size:.78rem;color:#64748b;white-space:nowrap">
                                {{ $n->created_at ? $n->created_at->diffForHumans() : '-' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.notifikasi.destroy', $n->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="tbl-btn-del"
                                        onclick="return confirm('Hapus notifikasi ini?')">
                                        🗑
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8">
                                <div style="font-size:2.5rem;margin-bottom:10px">🔔</div>
                                <div style="font-weight:600;margin-bottom:4px">Tidak ada notifikasi</div>
                                <div style="font-size:.82rem">{{ request('search') ? 'Tidak ada yang cocok dengan pencarian' : 'Belum ada notifikasi dikirim' }}</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Menampilkan {{ $notifikasi->firstItem() ?? 0 }}-{{ $notifikasi->lastItem() ?? 0 }}
                    dari {{ $notifikasi->total() }} notifikasi
                </div>
                <div class="pg-btns">
                    @if($notifikasi->onFirstPage())
                        <span class="pg-btn disabled">‹</span>
                    @else
                        <a href="{{ $notifikasi->previousPageUrl() }}" class="pg-btn">‹</a>
                    @endif
                    @foreach($notifikasi->getUrlRange(1, $notifikasi->lastPage()) as $page => $url)
                        @if($page == $notifikasi->currentPage())
                            <span class="pg-btn active">{{ $page }}</span>
                        @elseif($page==1 || $page==$notifikasi->lastPage() || abs($page-$notifikasi->currentPage())<=1)
                            <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                        @elseif(abs($page-$notifikasi->currentPage())==2)
                            <span class="pg-btn disabled">...</span>
                        @endif
                    @endforeach
                    @if($notifikasi->hasMorePages())
                        <a href="{{ $notifikasi->nextPageUrl() }}" class="pg-btn">›</a>
                    @else
                        <span class="pg-btn disabled">›</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KANAN: FORM KIRIM --}}
    <div>
        <div class="kirim-card">
            <div class="kirim-header">
                <div class="kirim-title">📤 Kirim Notifikasi</div>
                <div style="font-size:.78rem;color:#94a3b8;margin-top:3px">Kirim pesan ke pengguna</div>
            </div>
            <div class="kirim-body">

                @if(session('success'))
                <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:10px 14px;border-radius:10px;font-size:.82rem;margin-bottom:14px">
                    ✓ {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:10px 14px;border-radius:10px;font-size:.82rem;margin-bottom:14px">
                    @foreach($errors->all() as $e)<div>✕ {{ $e }}</div>@endforeach
                </div>
                @endif

                <form action="{{ route('admin.notifikasi.kirim') }}" method="POST">
                    @csrf

                    {{-- Target --}}
                    <div class="kf-group">
                        <label class="kf-label">Kirim Ke *</label>
                        <div class="kf-radio-group">
                            <label class="kf-radio">
                                <input type="radio" name="target" value="semua"
                                    {{ old('target','semua')==='semua' ? 'checked':'' }}
                                    onchange="toggleUserSelect(this.value)">
                                <span>👥 Semua User</span>
                            </label>
                            <label class="kf-radio">
                                <input type="radio" name="target" value="tertentu"
                                    {{ old('target')==='tertentu' ? 'checked':'' }}
                                    onchange="toggleUserSelect(this.value)">
                                <span>👤 User Tertentu</span>
                            </label>
                        </div>
                    </div>

                    {{-- Pilih User --}}
                    <div class="kf-group" id="userSelect"
                        style="{{ old('target')==='tertentu' ? '' : 'display:none' }}">
                        <label class="kf-label">Pilih User *</label>
                        <select name="user_id" class="kf-input">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected':'' }}>
                                {{ $u->name }} — {{ $u->email }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pesan --}}
                    <div class="kf-group">
                        <label class="kf-label">Isi Pesan *</label>
                        <textarea name="pesan" class="kf-input" rows="5"
                            maxlength="500"
                            placeholder="Tulis pesan notifikasi di sini..."
                            oninput="document.getElementById('charCount').textContent=this.value.length"
                            >{{ old('pesan') }}</textarea>
                        <div class="char-count">
                            <span id="charCount">{{ strlen(old('pesan','')) }}</span>/500 karakter
                        </div>
                    </div>

                    {{-- Template cepat --}}
                    <div class="kf-group">
                        <label class="kf-label">Template Cepat</label>
                        <div style="display:flex;flex-direction:column;gap:6px">
                            <button type="button" onclick="isiTemplate('Perpustakaan DigiLibrary akan melakukan maintenance pada hari ini. Mohon maaf atas ketidaknyamanannya.')"
                                style="padding:8px 12px;border-radius:8px;border:1.5px solid #e5e7eb;background:#f8fafc;font-family:inherit;font-size:.75rem;text-align:left;cursor:pointer;transition:all .2s;color:#374151"
                                onmouseover="this.style.borderColor='#1a56db'" onmouseout="this.style.borderColor='#e5e7eb'">
                                🔧 Notifikasi Maintenance
                            </button>
                            <button type="button" onclick="isiTemplate('Selamat! Ada koleksi buku baru telah ditambahkan ke perpustakaan DigiLibrary. Yuk cek katalog terbaru!')"
                                style="padding:8px 12px;border-radius:8px;border:1.5px solid #e5e7eb;background:#f8fafc;font-family:inherit;font-size:.75rem;text-align:left;cursor:pointer;transition:all .2s;color:#374151"
                                onmouseover="this.style.borderColor='#1a56db'" onmouseout="this.style.borderColor='#e5e7eb'">
                                📚 Buku Baru Tersedia
                            </button>
                            <button type="button" onclick="isiTemplate('Pengingat: Jangan lupa kembalikan buku yang sedang dipinjam sebelum batas waktu untuk menghindari denda keterlambatan.')"
                                style="padding:8px 12px;border-radius:8px;border:1.5px solid #e5e7eb;background:#f8fafc;font-family:inherit;font-size:.75rem;text-align:left;cursor:pointer;transition:all .2s;color:#374151"
                                onmouseover="this.style.borderColor='#1a56db'" onmouseout="this.style.borderColor='#e5e7eb'">
                                ⏰ Pengingat Pengembalian
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-kirim">📤 Kirim Notifikasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleUserSelect(val) {
    document.getElementById('userSelect').style.display = val === 'tertentu' ? 'flex' : 'none';
    document.getElementById('userSelect').style.flexDirection = 'column';
}

function isiTemplate(teks) {
    const ta = document.querySelector('textarea[name="pesan"]');
    ta.value = teks;
    document.getElementById('charCount').textContent = teks.length;
    ta.focus();
}
</script>
@endpush