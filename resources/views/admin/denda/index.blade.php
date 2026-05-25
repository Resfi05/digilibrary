@extends('layouts.admin')

@section('title', 'Kelola Denda')
@section('page-title', 'Kelola Denda')
@section('page-sub', 'Pantau dan kelola denda keterlambatan pengembalian buku')

@push('styles')
<style>
    .denda-stats { display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px; }
    .denda-stat { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);transition:all .25s; }
    .denda-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .ds-top { display:flex;align-items:center;gap:12px;margin-bottom:12px; }
    .ds-icon { width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
    .ds-title { font-size:.82rem;color:#64748b;font-weight:500; }
    .ds-num { font-size:1.5rem;font-weight:800;color:#111827;margin-bottom:4px; }
    .ds-sub { font-size:.75rem;color:#94a3b8; }
    .ds-progress { height:6px;border-radius:99px;background:#f1f5f9;overflow:hidden;margin-top:12px; }
    .ds-progress-fill { height:100%;border-radius:99px;transition:width .5s; }
    .adm-card { background:white;border-radius:14px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .filter-bar { display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid #f8fafc;flex-wrap:wrap; }
    .filter-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 14px;flex:1;min-width:200px; }
    .filter-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%; }
    .filter-btn { display:flex;align-items:center;gap:6px;padding:9px 16px;border-radius:10px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#374151;text-decoration:none;transition:all .2s; }
    .filter-btn:hover { border-color:#1a56db;color:#1a56db; }
    .status-tabs { display:flex;gap:6px;padding:12px 20px;border-bottom:1px solid #f8fafc; }
    .status-tab { padding:6px 16px;border-radius:99px;font-size:.82rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:white;color:#64748b;text-decoration:none;transition:all .2s; }
    .status-tab:hover { border-color:#1a56db;color:#1a56db; }
    .status-tab.active { background:#1a56db;color:white;border-color:#1a56db; }
    .status-tab.tab-lunas.active { background:#15803d;border-color:#15803d; }
    .status-tab.tab-belum.active { background:#b91c1c;border-color:#b91c1c; }
    .adm-table-wrap { overflow-x:auto; }
    .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .adm-table th { text-align:left;padding:12px 16px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
    .adm-table td { padding:12px 16px;border-bottom:1px solid #f8fafc;vertical-align:middle; }
    .adm-table tr:last-child td { border-bottom:none; }
    .adm-table tr:hover td { background:#fafbff; }
    .user-avatar { width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0; }
    .denda-amount { font-size:.95rem;font-weight:800;color:#ef4444; }
    .lunas-badge { background:#dcfce7;color:#15803d;padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .belum-badge { background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
    .tbl-btn-bayar { display:inline-flex;align-items:center;gap:4px;padding:7px 14px;border-radius:8px;font-family:inherit;font-size:.78rem;font-weight:700;cursor:pointer;border:none;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;transition:all .2s; }
    .tbl-btn-bayar:hover { opacity:.9;transform:translateY(-1px); }
    .pagination-wrap { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-top:1px solid #f8fafc;flex-wrap:wrap;gap:10px; }
    .pagination-info { font-size:.82rem;color:#64748b; }
    .pg-btns { display:flex;gap:6px; }
    .pg-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e2e8f0;background:white;display:flex;align-items:center;justify-content:center;font-size:.82rem;font-weight:600;color:#374151;text-decoration:none;transition:all .2s; }
    .pg-btn:hover { border-color:#1a56db;color:#1a56db; }
    .pg-btn.active { background:#1a56db;color:white;border-color:#1a56db; }
    .pg-btn.disabled { opacity:.4;pointer-events:none; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }
    @media(max-width:900px) { .denda-stats{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Kelola Denda</span>
</div>

{{-- STATS --}}
<div class="denda-stats">
    {{-- Total Denda --}}
    <div class="denda-stat">
        <div class="ds-top">
            <div class="ds-icon" style="background:#fee2e2">💰</div>
            <div class="ds-title">Total Akumulasi Denda</div>
        </div>
        <div class="ds-num" style="color:#ef4444">Rp {{ number_format($stats['total_denda'],0,',','.') }}</div>
        <div class="ds-sub">{{ $stats['jumlah_kasus'] }} kasus denda</div>
        <div class="ds-progress">
            <div class="ds-progress-fill" style="width:100%;background:#ef4444"></div>
        </div>
    </div>

    {{-- Belum Bayar --}}
    <div class="denda-stat">
        <div class="ds-top">
            <div class="ds-icon" style="background:#fef9c3">⏳</div>
            <div class="ds-title">Belum Dibayar</div>
        </div>
        <div class="ds-num" style="color:#a16207">Rp {{ number_format($stats['belum_bayar'],0,',','.') }}</div>
        <div class="ds-sub">{{ $stats['belum_kasus'] }} peminjam</div>
        <div class="ds-progress">
            <div class="ds-progress-fill"
                style="width:{{ $stats['total_denda'] > 0 ? round($stats['belum_bayar']/$stats['total_denda']*100) : 0 }}%;background:#f59e0b">
            </div>
        </div>
    </div>

    {{-- Sudah Bayar --}}
    <div class="denda-stat">
        <div class="ds-top">
            <div class="ds-icon" style="background:#dcfce7">✅</div>
            <div class="ds-title">Sudah Dilunasi</div>
        </div>
        <div class="ds-num" style="color:#15803d">Rp {{ number_format($stats['sudah_bayar'],0,',','.') }}</div>
        <div class="ds-sub">{{ $stats['lunas_kasus'] }} peminjam</div>
        <div class="ds-progress">
            <div class="ds-progress-fill"
                style="width:{{ $stats['total_denda'] > 0 ? round($stats['sudah_bayar']/$stats['total_denda']*100) : 0 }}%;background:#22c55e">
            </div>
        </div>
    </div>
</div>

<div class="adm-card">

    {{-- STATUS TABS --}}
    <div class="status-tabs">
        <a href="{{ route('admin.denda.index') }}"
            class="status-tab {{ !request('status') ? 'active':'' }}">
            Semua ({{ $stats['jumlah_kasus'] }})
        </a>
        <a href="{{ route('admin.denda.index', ['status'=>'belum']) }}"
            class="status-tab tab-belum {{ request('status')==='belum' ? 'active':'' }}">
            ⏳ Belum Bayar ({{ $stats['belum_kasus'] }})
        </a>
        <a href="{{ route('admin.denda.index', ['status'=>'lunas']) }}"
            class="status-tab tab-lunas {{ request('status')==='lunas' ? 'active':'' }}">
            ✅ Lunas ({{ $stats['lunas_kasus'] }})
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-bar">
        <form action="{{ route('admin.denda.index') }}" method="GET"
            style="display:flex;gap:10px;flex:1;flex-wrap:wrap;align-items:center">
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="filter-search" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari nama atau judul buku..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="filter-btn">🔍 Cari</button>
            @if(request('search'))
            <a href="{{ route('admin.denda.index', request('status') ? ['status'=>request('status')] : []) }}"
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
                    <th>Batas Kembali</th>
                    <th>Dikembalikan</th>
                    <th>Keterlambatan</th>
                    <th>Jumlah Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($denda as $i => $p)
                @php
                    $hariTelat = 0;
                    if ($p->tanggal_kembali && $p->tanggal_dikembalikan) {
                        $kembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                        $dikembalikan = \Carbon\Carbon::parse($p->tanggal_dikembalikan);
                        if ($dikembalikan->gt($kembali)) {
                            $hariTelat = $kembali->diffInDays($dikembalikan);
                        }
                    }
                @endphp
                <tr @if(!$p->bayar_denda) style="background:#fffbf0" @endif>
                    <td style="color:#94a3b8">{{ $denda->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="user-avatar">{{ strtoupper(substr($p->user->name ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:600;color:#111827">{{ $p->user->name ?? '-' }}</div>
                                <div style="font-size:.72rem;color:#94a3b8">{{ $p->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#111827">{{ Str::limit($p->book->judul ?? '-', 22) }}</div>
                    </td>
                    <td style="font-size:.78rem;color:#ef4444;font-weight:600">
                        {{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}
                    </td>
                    <td style="font-size:.78rem;color:#64748b">
                        {{ $p->tanggal_dikembalikan ? \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        @if($hariTelat > 0)
                        <span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700">
                            {{ $hariTelat }} hari
                        </span>
                        @else
                        <span style="color:#94a3b8;font-size:.78rem">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="denda-amount">Rp {{ number_format($p->denda,0,',','.') }}</div>
                        @if($hariTelat > 0)
                        <div style="font-size:.68rem;color:#94a3b8">{{ $hariTelat }} hari × Rp 2.000</div>
                        @endif
                    </td>
                    <td>
                        @if($p->bayar_denda)
                        <span class="lunas-badge">✓ Lunas</span>
                        @else
                        <span class="belum-badge">⏳ Belum Bayar</span>
                        @endif
                    </td>
                    <td>
                        @if(!$p->bayar_denda)
                        <form action="{{ route('admin.denda.bayar', $p->id) }}" method="POST" style="display:inline">
                            @csrf @method('PUT')
                            <button type="submit" class="tbl-btn-bayar"
                                onclick="return confirm('Tandai denda Rp {{ number_format($p->denda,0,',','.') }} dari {{ addslashes($p->user->name ?? '') }} sebagai lunas?')">
                                ✓ Tandai Lunas
                            </button>
                        </form>
                        @else
                        <span style="color:#15803d;font-size:.78rem;font-weight:600">✓ Sudah Lunas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:48px;color:#94a3b8">
                        <div style="font-size:2.5rem;margin-bottom:10px">💰</div>
                        <div style="font-weight:600;margin-bottom:4px">
                            {{ request('status')==='lunas' ? 'Belum ada denda yang lunas' : (request('status')==='belum' ? 'Semua denda sudah lunas!' : 'Tidak ada data denda') }}
                        </div>
                        <div style="font-size:.82rem">
                            {{ !request('status') ? 'Tidak ada peminjaman yang terkena denda' : '' }}
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $denda->firstItem() ?? 0 }}-{{ $denda->lastItem() ?? 0 }}
            dari {{ $denda->total() }} data denda
        </div>
        <div class="pg-btns">
            @if($denda->onFirstPage())
                <span class="pg-btn disabled">‹</span>
            @else
                <a href="{{ $denda->previousPageUrl() }}" class="pg-btn">‹</a>
            @endif
            @foreach($denda->getUrlRange(1, $denda->lastPage()) as $page => $url)
                @if($page == $denda->currentPage())
                    <span class="pg-btn active">{{ $page }}</span>
                @elseif($page==1 || $page==$denda->lastPage() || abs($page-$denda->currentPage())<=1)
                    <a href="{{ $url }}" class="pg-btn">{{ $page }}</a>
                @elseif(abs($page-$denda->currentPage())==2)
                    <span class="pg-btn disabled">...</span>
                @endif
            @endforeach
            @if($denda->hasMorePages())
                <a href="{{ $denda->nextPageUrl() }}" class="pg-btn">›</a>
            @else
                <span class="pg-btn disabled">›</span>
            @endif
        </div>
    </div>
</div>

@endsection