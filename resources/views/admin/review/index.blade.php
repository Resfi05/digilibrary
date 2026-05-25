@extends('layouts.admin')

@section('title', 'Kelola Ulasan')
@section('page-title', 'Kelola Ulasan')
@section('page-sub', 'Moderasi semua ulasan pengguna')

@push('styles')
<style>
    .rev-stats { display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:24px; }
    .rev-stat { background:white;border-radius:14px;padding:16px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);text-align:center;transition:all .25s; }
    .rev-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .rev-stat-icon { font-size:1.3rem;margin-bottom:6px; }
    .rev-stat-num { font-size:1.3rem;font-weight:800;color:#111827;line-height:1;margin-bottom:3px; }
    .rev-stat-label { font-size:.7rem;color:#64748b; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:200px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-select { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none;cursor:pointer; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:12px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .user-avatar { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0; }
    .star-on { color:#f59e0b; }
    .star-off { color:#e5e7eb; }
    .rating-badge { display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:.75rem;font-weight:700; }
    .r5 { background:#dcfce7;color:#15803d; }
    .r4 { background:#d1fae5;color:#065f46; }
    .r3 { background:#fef9c3;color:#a16207; }
    .r2 { background:#fee2e2;color:#b91c1c; }
    .r1 { background:#fee2e2;color:#991b1b; }
    .komentar-text { color:#374151;font-size:.82rem;line-height:1.5;max-width:280px; }
    .tbl-btn-del { display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border-radius:8px;font-family:inherit;font-size:.78rem;font-weight:700;cursor:pointer;border:none;background:#fee2e2;color:#b91c1c;transition:all .2s; }
    .tbl-btn-del:hover { background:#fca5a5; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }

    /* Rating tabs */
    .rating-tabs { display:flex;gap:6px;padding:12px 20px;border-bottom:1px solid #f8fafc;overflow-x:auto; }
    .rating-tab { padding:6px 14px;border-radius:99px;font-size:.82rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#64748b;text-decoration:none;white-space:nowrap;transition:all .2s; }
    .rating-tab:hover { border-color:#f59e0b;color:#d97706; }
    .rating-tab.active { background:#f59e0b;color:white;border-color:#f59e0b; }

    @media(max-width:1200px) { .rev-stats{grid-template-columns:repeat(3,1fr);} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Kelola Ulasan</span>
</div>

{{-- STATS --}}
<div class="rev-stats">
    <div class="rev-stat">
        <div class="rev-stat-icon">💬</div>
        <div class="rev-stat-num">{{ $stats['total'] }}</div>
        <div class="rev-stat-label">Total Ulasan</div>
    </div>
    <div class="rev-stat" style="border-top:3px solid #f59e0b">
        <div class="rev-stat-icon">⭐</div>
        <div class="rev-stat-num" style="color:#d97706">{{ $stats['avg'] ?: '-' }}</div>
        <div class="rev-stat-label">Rata-rata Rating</div>
    </div>
    <div class="rev-stat">
        <div class="rev-stat-icon">⭐⭐⭐⭐⭐</div>
        <div class="rev-stat-num" style="color:#15803d">{{ $stats['bintang5'] }}</div>
        <div class="rev-stat-label">Rating 5 Bintang</div>
    </div>
    <div class="rev-stat">
        <div class="rev-stat-icon">⭐⭐⭐⭐</div>
        <div class="rev-stat-num" style="color:#065f46">{{ $stats['bintang4'] }}</div>
        <div class="rev-stat-label">Rating 4 Bintang</div>
    </div>
    <div class="rev-stat">
        <div class="rev-stat-icon">⭐⭐⭐</div>
        <div class="rev-stat-num" style="color:#a16207">{{ $stats['bintang3'] }}</div>
        <div class="rev-stat-label">Rating 3 Bintang</div>
    </div>
    <div class="rev-stat" style="border-top:3px solid #ef4444">
        <div class="rev-stat-icon">⭐⭐</div>
        <div class="rev-stat-num" style="color:#b91c1c">{{ $stats['rendah'] }}</div>
        <div class="rev-stat-label">Rating ≤ 2 Bintang</div>
    </div>
</div>

<div class="adm-card">

    {{-- RATING TABS --}}
    <div class="rating-tabs">
        <a href="{{ route('admin.review.index') }}"
            class="rating-tab {{ !request('rating') ? 'active' : '' }}">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.review.index', ['rating'=>5]) }}"
            class="rating-tab {{ request('rating')==5 ? 'active' : '' }}">
            ⭐⭐⭐⭐⭐ ({{ $stats['bintang5'] }})
        </a>
        <a href="{{ route('admin.review.index', ['rating'=>4]) }}"
            class="rating-tab {{ request('rating')==4 ? 'active' : '' }}">
            ⭐⭐⭐⭐ ({{ $stats['bintang4'] }})
        </a>
        <a href="{{ route('admin.review.index', ['rating'=>3]) }}"
            class="rating-tab {{ request('rating')==3 ? 'active' : '' }}">
            ⭐⭐⭐ ({{ $stats['bintang3'] }})
        </a>
        <a href="{{ route('admin.review.index', ['rating'=>'rendah']) }}"
            class="rating-tab {{ request('rating')==='rendah' ? 'active' : '' }}"
            style="{{ request('rating')==='rendah' ? 'background:#ef4444;border-color:#ef4444' : '' }}">
            ⭐⭐ ke bawah ({{ $stats['rendah'] }})
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form action="{{ route('admin.review.index') }}" method="GET"
            style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
            @if(request('rating'))
            <input type="hidden" name="rating" value="{{ request('rating') }}">
            @endif
            <div class="filter-search" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari nama user atau judul buku..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="filter-btn">🔍 Cari</button>
            @if(request('search'))
            <a href="{{ route('admin.review.index', request('rating') ? ['rating'=>request('rating')] : []) }}"
                class="filter-btn">Reset</a>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Buku</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $i => $r)
                <tr>
                    <td style="color:#94a3b8">{{ $reviews->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="user-avatar">
                                {{ strtoupper(substr($r->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#111827">{{ $r->user->name ?? '-' }}</div>
                                <div style="font-size:.72rem;color:#94a3b8">{{ $r->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#111827">{{ Str::limit($r->book->judul ?? '-', 25) }}</div>
                        <div style="font-size:.72rem;color:#94a3b8">{{ $r->book->category->nama_kategori ?? '-' }}</div>
                    </td>
                    <td>
                        <div>
                            @for($s=1;$s<=5;$s++)
                            <span class="{{ $s<=$r->rating ? 'star-on':'star-off' }}">★</span>
                            @endfor
                        </div>
                        <span class="rating-badge r{{ $r->rating }}">
                            {{ $r->rating }}/5 —
                            @if($r->rating==5) Sangat Bagus
                            @elseif($r->rating==4) Bagus
                            @elseif($r->rating==3) Cukup
                            @elseif($r->rating==2) Buruk
                            @else Sangat Buruk @endif
                        </span>
                    </td>
                    <td>
                        <div class="komentar-text">
                            {{ $r->komentar ? Str::limit($r->komentar, 80) : '-' }}
                        </div>
                    </td>
                    <td style="font-size:.78rem;color:#64748b;white-space:nowrap">
                        {{ $r->created_at ? $r->created_at->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <form action="{{ route('admin.review.destroy', $r->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="tbl-btn-del"
                                onclick="return confirm('Hapus ulasan dari {{ addslashes($r->user->name ?? '') }}?')">
                                🗑 Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:2.5rem;margin-bottom:10px">💬</div>
                        <div style="font-weight:600;margin-bottom:4px">Belum ada ulasan</div>
                        <div style="font-size:.82rem">{{ request('search') || request('rating') ? 'Tidak ada ulasan yang cocok' : 'Belum ada ulasan dari pengguna' }}</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $reviews->firstItem() ?? 0 }}-{{ $reviews->lastItem() ?? 0 }}
            dari {{ $reviews->total() }} ulasan
        </div>
        <div class="pg-btns">
            @if($reviews->onFirstPage())
                <span class="pg-btn disabled">‹</span>
            @else
                <a href="{{ $reviews->previousPageUrl() }}" class="pg-btn">‹</a>
            @endif
            @foreach($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                @if($page == $reviews->currentPage())
                    <span class="pg-btn active">{{ $page }}</span>
                @elseif($page==1 || $page==$reviews->lastPage() || abs($page-$reviews->currentPage())<=1)
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @elseif(abs($page-$reviews->currentPage())==2)
                    <span class="pg-btn disabled">...</span>
                @endif
            @endforeach
            @if($reviews->hasMorePages())
                <a href="{{ $reviews->nextPageUrl() }}" class="pg-btn">›</a>
            @else
                <span class="pg-btn disabled">›</span>
            @endif
        </div>
    </div>
</div>

@endsection