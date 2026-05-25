@extends('layouts.admin')

@section('title', 'Manajemen Peminjaman')
@section('page-title', 'Manajemen Peminjaman')
@section('page-sub', 'Kelola semua peminjaman dan pengembalian buku')

@push('styles')
<style>
    .pjm-stats { display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px; }
    .pjm-stat { background:white;border-radius:14px;padding:16px 18px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);text-align:center;transition:all .25s; }
    .pjm-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .pjm-stat-icon { font-size:1.4rem;margin-bottom:6px; }
    .pjm-stat-num { font-size:1.4rem;font-weight:800;color:#111827;line-height:1;margin-bottom:4px; }
    .pjm-stat-label { font-size:.72rem;color:#64748b; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:200px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-select { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none;cursor:pointer; }
    .filter-input { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .filter-btn-primary { background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none; }
    .filter-btn-primary:hover { opacity:.9;color:white; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:12px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .user-avatar { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0; }
    .s-badge { padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .s-pending    { background:#fef9c3;color:#a16207; }
    .s-dipinjam   { background:#dbeafe;color:#1d4ed8; }
    .s-kembali    { background:#dcfce7;color:#15803d; }
    .s-terlambat  { background:#fee2e2;color:#b91c1c; }
    .s-ditolak    { background:#f3f4f6;color:#6b7280; }
    .tbl-actions { display:flex;gap:6px;align-items:center;flex-wrap:wrap; }
    .tbl-btn { display:inline-flex;align-items:center;gap:4px;padding:6px 11px;border-radius:8px;font-family:inherit;font-size:.75rem;font-weight:700;cursor:pointer;border:none;transition:all .2s;white-space:nowrap; }
    .tbl-btn-approve { background:#dcfce7;color:#15803d; }
    .tbl-btn-approve:hover { background:#bbf7d0; }
    .tbl-btn-reject  { background:#fee2e2;color:#b91c1c; }
    .tbl-btn-reject:hover  { background:#fca5a5; }
    .tbl-btn-return  { background:#dbeafe;color:#1d4ed8; }
    .tbl-btn-return:hover  { background:#bfdbfe; }
    .denda-badge { background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:99px;font-size:.68rem;font-weight:700; }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }

    /* Tab filter */
    .status-tabs { display:flex;gap:6px;padding:12px 20px;border-bottom:1px solid #f8fafc;overflow-x:auto; }
    .status-tab { padding:6px 16px;border-radius:99px;font-size:.82rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#64748b;text-decoration:none;white-space:nowrap;transition:all .2s; }
    .status-tab:hover { border-color:#1a56db;color:#1a56db; }
    .status-tab.active { background:#1a56db;color:white;border-color:#1a56db; }
    .status-tab.tab-pending.active { background:#a16207;border-color:#a16207; }
    .status-tab.tab-dipinjam.active { background:#1d4ed8;border-color:#1d4ed8; }
    .status-tab.tab-terlambat.active { background:#b91c1c;border-color:#b91c1c; }
    .status-tab.tab-kembali.active { background:#15803d;border-color:#15803d; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Manajemen Peminjaman</span>
</div>

{{-- STATS --}}
<div class="pjm-stats">
    <div class="pjm-stat">
        <div class="pjm-stat-icon">📋</div>
        <div class="pjm-stat-num">{{ $stats['total'] }}</div>
        <div class="pjm-stat-label">Total Peminjaman</div>
    </div>
    <div class="pjm-stat" style="border-top:3px solid #a16207">
        <div class="pjm-stat-icon">⏳</div>
        <div class="pjm-stat-num" style="color:#a16207">{{ $stats['pending'] }}</div>
        <div class="pjm-stat-label">Menunggu Konfirmasi</div>
    </div>
    <div class="pjm-stat" style="border-top:3px solid #1d4ed8">
        <div class="pjm-stat-icon">📖</div>
        <div class="pjm-stat-num" style="color:#1d4ed8">{{ $stats['dipinjam'] }}</div>
        <div class="pjm-stat-label">Sedang Dipinjam</div>
    </div>
    <div class="pjm-stat" style="border-top:3px solid #b91c1c">
        <div class="pjm-stat-icon">⚠️</div>
        <div class="pjm-stat-num" style="color:#b91c1c">{{ $stats['terlambat'] }}</div>
        <div class="pjm-stat-label">Terlambat</div>
    </div>
    <div class="pjm-stat" style="border-top:3px solid #15803d">
        <div class="pjm-stat-icon">✅</div>
        <div class="pjm-stat-num" style="color:#15803d">{{ $stats['dikembalikan'] }}</div>
        <div class="pjm-stat-label">Dikembalikan</div>
    </div>
</div>

<div class="adm-card">

    {{-- STATUS TABS --}}
    <div class="status-tabs">
        <a href="{{ route('admin.peminjaman.index') }}"
            class="status-tab {{ !request('status') ? 'active' : '' }}">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.peminjaman.index', ['status'=>'pending']) }}"
            class="status-tab tab-pending {{ request('status')==='pending' ? 'active' : '' }}">
            ⏳ Pending ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.peminjaman.index', ['status'=>'dipinjam']) }}"
            class="status-tab tab-dipinjam {{ request('status')==='dipinjam' ? 'active' : '' }}">
            📖 Dipinjam ({{ $stats['dipinjam'] }})
        </a>
        <a href="{{ route('admin.peminjaman.index', ['status'=>'terlambat']) }}"
            class="status-tab tab-terlambat {{ request('status')==='terlambat' ? 'active' : '' }}">
            ⚠️ Terlambat ({{ $stats['terlambat'] }})
        </a>
        <a href="{{ route('admin.peminjaman.index', ['status'=>'dikembalikan']) }}"
            class="status-tab tab-kembali {{ request('status')==='dikembalikan' ? 'active' : '' }}">
            ✅ Dikembalikan ({{ $stats['dikembalikan'] }})
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form action="{{ route('admin.peminjaman.index') }}" method="GET"
            style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="filter-search" style="flex:1;min-width:180px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari nama atau judul buku..."
                    value="{{ request('search') }}">
            </div>
            <input type="date" name="date_from" class="filter-input"
                value="{{ request('date_from') }}" title="Dari tanggal">
            <input type="date" name="date_to" class="filter-input"
                value="{{ request('date_to') }}" title="Sampai tanggal">
            <button type="submit" class="filter-btn">🔍 Filter</button>
            @if(request('search') || request('date_from') || request('date_to'))
            <a href="{{ route('admin.peminjaman.index', request('status') ? ['status'=>request('status')] : []) }}"
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
                    <th>Peminjam</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Dikembalikan</th>
                    <th>Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $p)
                @php
                    $terlambat = $p->status === 'dipinjam' && $p->tanggal_kembali && now()->gt($p->tanggal_kembali);
                    $hariTelat = $terlambat ? now()->diffInDays($p->tanggal_kembali) : 0;
                @endphp
                <tr @if($terlambat) style="background:#fff8f8" @endif>
                    <td style="color:#94a3b8">{{ $peminjaman->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="user-avatar">
                                {{ strtoupper(substr($p->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#111827">{{ $p->user->name ?? '-' }}</div>
                                <div style="font-size:.72rem;color:#94a3b8">{{ $p->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#111827">{{ Str::limit($p->book->judul ?? '-', 25) }}</div>
                        <div style="font-size:.72rem;color:#94a3b8">{{ $p->book->category->nama_kategori ?? '-' }}</div>
                    </td>
                    <td style="font-size:.78rem">
                        {{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d M Y') : '-' }}
                    </td>
                    <td style="font-size:.78rem" class="{{ $terlambat ? 'color:#ef4444;font-weight:700' : '' }}">
                        @if($p->tanggal_kembali)
                            <span style="{{ $terlambat ? 'color:#ef4444;font-weight:700' : '' }}">
                                {{ $p->tanggal_kembali->format('d M Y') }}
                            </span>
                            @if($terlambat)
                            <div style="font-size:.68rem;color:#ef4444">+{{ $hariTelat }} hari</div>
                            @endif
                        @else - @endif
                    </td>
                    <td style="font-size:.78rem;color:#15803d">
                        {{ $p->tanggal_dikembalikan ? \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        @if(($p->denda ?? 0) > 0)
                        <span class="denda-badge">Rp {{ number_format($p->denda,0,',','.') }}</span>
                        @elseif($terlambat)
                        <span class="denda-badge">~Rp {{ number_format($hariTelat*2000,0,',','.') }}</span>
                        @else
                        <span style="color:#d1d5db;font-size:.75rem">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="s-badge
                            @if($terlambat) s-terlambat
                            @elseif($p->status=='pending') s-pending
                            @elseif($p->status=='dipinjam') s-dipinjam
                            @elseif($p->status=='dikembalikan') s-kembali
                            @else s-ditolak @endif">
                            @if($terlambat) Terlambat
                            @elseif($p->status=='pending') Menunggu
                            @elseif($p->status=='dipinjam') Dipinjam
                            @elseif($p->status=='dikembalikan') Dikembalikan
                            @else Ditolak @endif
                        </span>
                    </td>
                    <td>
                        <div class="tbl-actions">
                            @if($p->status === 'pending')
                                <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="tbl-btn tbl-btn-approve"
                                        onclick="return confirm('Setujui peminjaman {{ addslashes($p->user->name ?? '') }}?')">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="tbl-btn tbl-btn-reject"
                                        onclick="return confirm('Tolak peminjaman ini?')">
                                        ✗ Tolak
                                    </button>
                                </form>
                            @elseif(in_array($p->status, ['dipinjam', 'terlambat']) || $terlambat)
                                <form action="{{ route('admin.peminjaman.return', $p->id) }}" method="POST" style="display:inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="tbl-btn tbl-btn-return"
                                        onclick="return confirm('Proses pengembalian buku ini?{{ $terlambat ? ' Terlambat '.$hariTelat.' hari, denda Rp '.number_format($hariTelat*2000,0,',','.').'.' : '' }}')">
                                        📥 Kembalikan
                                    </button>
                                </form>
                            @else
                                <span style="color:#d1d5db;font-size:.75rem">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:2.5rem;margin-bottom:10px">🔄</div>
                        <div style="font-weight:600;margin-bottom:4px">Belum ada peminjaman</div>
                        <div style="font-size:.82rem">{{ request('status') ? 'Tidak ada peminjaman dengan status ini' : 'Belum ada data peminjaman' }}</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $peminjaman->firstItem() ?? 0 }}-{{ $peminjaman->lastItem() ?? 0 }}
            dari {{ $peminjaman->total() }} peminjaman
        </div>
        <div class="pg-btns">
            @if($peminjaman->onFirstPage())
                <span class="pg-btn disabled">‹</span>
            @else
                <a href="{{ $peminjaman->previousPageUrl() }}" class="pg-btn">‹</a>
            @endif
            @foreach($peminjaman->getUrlRange(1, $peminjaman->lastPage()) as $page => $url)
                @if($page == $peminjaman->currentPage())
                    <span class="pg-btn active">{{ $page }}</span>
                @elseif($page==1 || $page==$peminjaman->lastPage() || abs($page-$peminjaman->currentPage())<=1)
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @elseif(abs($page-$peminjaman->currentPage())==2)
                    <span class="pg-btn disabled">...</span>
                @endif
            @endforeach
            @if($peminjaman->hasMorePages())
                <a href="{{ $peminjaman->nextPageUrl() }}" class="pg-btn">›</a>
            @else
                <span class="pg-btn disabled">›</span>
            @endif
        </div>
    </div>
</div>

@endsection