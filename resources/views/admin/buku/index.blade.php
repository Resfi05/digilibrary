@extends('layouts.admin')

@section('title', 'Daftar Buku')
@section('page-title', 'Manajemen Buku')
@section('page-sub', 'Kelola semua koleksi buku perpustakaan digital')

@push('styles')
<style>
    .buku-stats { display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px; }
    .buku-stat { background:white;border-radius:14px;padding:18px 20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);display:flex;align-items:center;gap:14px;transition:all .25s; }
    .buku-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .buku-stat-icon { width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
    .buku-stat-num { font-size:1.5rem;font-weight:800;color:#111827;line-height:1; }
    .buku-stat-label { font-size:.75rem;color:#64748b;margin-top:4px; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:200px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-select { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none;cursor:pointer; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .filter-btn-primary { background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;box-shadow:0 2px 8px rgba(26,86,219,.25); }
    .filter-btn-primary:hover { opacity:.9;color:white; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:14px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .buku-cover { width:44px;height:56px;border-radius:6px;object-fit:cover;box-shadow:0 2px 6px rgba(0,0,0,.1); }
    .buku-cover-placeholder { width:44px;height:56px;border-radius:6px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#94a3b8; }
    .stok-badge { padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .stok-ok { background:#dcfce7;color:#15803d; }
    .stok-low { background:#fef9c3;color:#a16207; }
    .stok-empty { background:#fee2e2;color:#b91c1c; }
    .kat-badge { padding:3px 8px;border-radius:99px;background:#eff6ff;color:#1d4ed8;font-size:.68rem;font-weight:700; }
    .tbl-actions { display:flex;gap:6px;align-items:center; }
    .tbl-btn { display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border-radius:8px;font-family:inherit;font-size:.78rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s; }
    .tbl-btn-edit { background:#fef9c3;color:#a16207; }
    .tbl-btn-edit:hover { background:#fde68a; }
    .tbl-btn-del { background:#fee2e2;color:#b91c1c; }
    .tbl-btn-del:hover { background:#fca5a5; }
    .tbl-btn-add { background:#dcfce7;color:#15803d; }
    .tbl-btn-add:hover { background:#bbf7d0; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Daftar Buku</span>
</div>

{{-- STATS --}}
<div class="buku-stats">
    <div class="buku-stat">
        <div class="buku-stat-icon" style="background:#dbeafe">📚</div>
        <div>
            <div class="buku-stat-num">{{ number_format($stats['total']) }}</div>
            <div class="buku-stat-label">Total Koleksi</div>
        </div>
    </div>
    <div class="buku-stat">
        <div class="buku-stat-icon" style="background:#dcfce7">✅</div>
        <div>
            <div class="buku-stat-num">{{ number_format($stats['tersedia']) }}</div>
            <div class="buku-stat-label">Buku Tersedia</div>
        </div>
    </div>
    <div class="buku-stat">
        <div class="buku-stat-icon" style="background:#fee2e2">❌</div>
        <div>
            <div class="buku-stat-num">{{ number_format($stats['habis']) }}</div>
            <div class="buku-stat-label">Stok Habis</div>
        </div>
    </div>
    <div class="buku-stat">
        <div class="buku-stat-icon" style="background:#ede9fe">📂</div>
        <div>
            <div class="buku-stat-num">{{ number_format($stats['kategori']) }}</div>
            <div class="buku-stat-label">Total Kategori</div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="adm-card">
    {{-- FILTER --}}
    <div class="filter-bar">
        <form action="{{ route('admin.buku.index') }}" method="GET"
            style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
            <div class="filter-search" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari judul, penulis, ISBN..."
                    value="{{ request('search') }}">
            </div>
            <select name="kategori" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected':'' }}>
                    {{ $cat->nama_kategori }}
                </option>
                @endforeach
            </select>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status')==='tersedia' ? 'selected':'' }}>Tersedia</option>
                <option value="habis"    {{ request('status')==='habis'    ? 'selected':'' }}>Stok Habis</option>
            </select>
            <button type="submit" class="filter-btn">🔍 Filter</button>
            @if(request('search') || request('kategori') || request('status'))
            <a href="{{ route('admin.buku.index') }}" class="filter-btn">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.buku.create') }}" class="filter-btn filter-btn-primary">
            ＋ Tambah Buku
        </a>
    </div>

    {{-- TABLE --}}
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Dipinjam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buku as $i => $b)
                @php
                    $bcolors  = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                    $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
                    $idx = $b->id % 6;
                @endphp
                <tr>
                    <td style="color:#94a3b8">{{ $buku->firstItem() + $i }}</td>
                    <td>
                        @if($b->gambar)
                            <img src="{{ asset('storage/'.$b->gambar) }}" class="buku-cover" alt="{{ $b->judul }}">
                        @else
                            <div class="buku-cover-placeholder"
                                style="background:{{ $bcolors[$idx] }};color:{{ $btcolors[$idx] }};font-size:.75rem;font-weight:900">
                                {{ strtoupper(substr($b->judul,0,2)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700;color:#111827">{{ $b->judul }}</div>
                        <div style="font-size:.72rem;color:#94a3b8">
                            {{ $b->penerbit ?? '-' }} · {{ $b->tahun_terbit ?? '-' }}
                        </div>
                        @if($b->isbn)
                        <div style="font-size:.68rem;color:#d1d5db">ISBN: {{ $b->isbn }}</div>
                        @endif
                    </td>
                    <td style="color:#374151">{{ $b->penulis }}</td>
                    <td>
                        @if($b->category)
                        <span class="kat-badge">{{ $b->category->nama_kategori }}</span>
                        @else
                        <span style="color:#d1d5db;font-size:.78rem">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="stok-badge
                            @if($b->stok > 3) stok-ok
                            @elseif($b->stok > 0) stok-low
                            @else stok-empty @endif">
                            {{ $b->stok }}
                            @if($b->stok > 3) ✓
                            @elseif($b->stok > 0) ⚠
                            @else ✗ @endif
                        </span>
                    </td>
                    <td style="font-weight:600;color:#1a56db">
                        {{ $b->peminjaman()->whereIn('status',['dipinjam','terlambat'])->count() }}x
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('admin.buku.edit', $b->id) }}" class="tbl-btn tbl-btn-edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.buku.destroy', $b->id) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="tbl-btn tbl-btn-del"
                                    onclick="return confirm('Hapus buku {{ addslashes($b->judul) }}?')">
                                    🗑 Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:2.5rem;margin-bottom:10px">📚</div>
                        <div style="font-weight:600;margin-bottom:6px">Belum ada buku</div>
                        <div style="font-size:.82rem">Tambahkan buku pertama ke koleksi perpustakaan</div>
                        <a href="{{ route('admin.buku.create') }}"
                            style="display:inline-block;margin-top:14px;padding:10px 20px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;text-decoration:none;font-size:.875rem;font-weight:700">
                            ＋ Tambah Buku
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $buku->firstItem() ?? 0 }}-{{ $buku->lastItem() ?? 0 }} dari {{ $buku->total() }} buku
        </div>
        <div class="pg-btns">
            @if($buku->onFirstPage())
                <span class="pg-btn disabled">‹</span>
            @else
                <a href="{{ $buku->previousPageUrl() }}" class="pg-btn">‹</a>
            @endif
            @foreach($buku->getUrlRange(1, $buku->lastPage()) as $page => $url)
                @if($page == $buku->currentPage())
                    <span class="pg-btn active">{{ $page }}</span>
                @elseif($page==1 || $page==$buku->lastPage() || abs($page-$buku->currentPage())<=1)
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @elseif(abs($page-$buku->currentPage())==2)
                    <span class="pg-btn disabled">...</span>
                @endif
            @endforeach
            @if($buku->hasMorePages())
                <a href="{{ $buku->nextPageUrl() }}" class="pg-btn">›</a>
            @else
                <span class="pg-btn disabled">›</span>
            @endif
        </div>
    </div>
</div>

@endsection