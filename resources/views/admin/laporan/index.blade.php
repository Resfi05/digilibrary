@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Cetak')
@section('page-sub', 'Rekap data dan cetak laporan perpustakaan')

@push('styles')
<style>
    .lap-stats { display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:24px; }
    .lap-stat { background:white;border-radius:14px;padding:16px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);text-align:center;transition:all .25s; }
    .lap-stat:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
    .lap-stat-icon { font-size:1.4rem;margin-bottom:8px; }
    .lap-stat-num { font-size:1.3rem;font-weight:800;color:#111827;line-height:1;margin-bottom:4px; }
    .lap-stat-label { font-size:.72rem;color:#64748b; }
    .cetak-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px; }
    .cetak-card { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);text-align:center;transition:all .25s; }
    .cetak-card:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.1);border-color:#bfdbfe; }
    .cetak-icon { font-size:2rem;margin-bottom:12px; }
    .cetak-title { font-size:.875rem;font-weight:700;color:#111827;margin-bottom:6px; }
    .cetak-desc { font-size:.75rem;color:#94a3b8;margin-bottom:14px;line-height:1.5; }
    .cetak-btn { display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;font-family:inherit;font-size:.82rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .2s; }
    .cetak-btn:hover { opacity:.9;transform:translateY(-1px); }
    .filter-card { background:white;border-radius:14px;padding:16px 20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);margin-bottom:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap; }
    .filter-select { padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:inherit;font-size:.875rem;color:#374151;background:white;outline:none;cursor:pointer; }
    .filter-btn { padding:9px 20px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;border:none;transition:all .2s; }
    .filter-btn:hover { opacity:.9; }
    .lap-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px; }
    .adm-card { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
    .adm-card-title { font-size:.95rem;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px; }
    .chart-wrap { position:relative;height:220px; }
    .lap-table { width:100%;border-collapse:collapse;font-size:.82rem; }
    .lap-table th { text-align:left;padding:8px 12px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;border-bottom:1px solid #f1f5f9; }
    .lap-table td { padding:10px 12px;border-bottom:1px solid #f8fafc;color:#374151;vertical-align:middle; }
    .lap-table tr:last-child td { border-bottom:none; }
    .lap-table tr:hover td { background:#fafbff; }
    .s-badge { padding:3px 8px;border-radius:99px;font-size:.68rem;font-weight:700; }
    .s-dipinjam { background:#dbeafe;color:#1d4ed8; }
    .s-kembali  { background:#dcfce7;color:#15803d; }
    .s-terlambat{ background:#fee2e2;color:#b91c1c; }
    .s-pending  { background:#fef9c3;color:#a16207; }
    .rank-num { width:24px;height:24px;border-radius:6px;background:#f1f5f9;color:#64748b;font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .rank-num.top { background:#fef3c7;color:#d97706; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }
    @media(max-width:1200px) { .lap-stats{grid-template-columns:repeat(3,1fr);} .cetak-grid{grid-template-columns:repeat(2,1fr);} }
    @media(max-width:900px) { .lap-grid-2{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span><span>Laporan</span>
</div>

{{-- STATS --}}
<div class="lap-stats">
    <div class="lap-stat">
        <div class="lap-stat-icon">🔄</div>
        <div class="lap-stat-num">{{ number_format($stats['total_peminjaman']) }}</div>
        <div class="lap-stat-label">Peminjaman Bulan Ini</div>
    </div>
    <div class="lap-stat">
        <div class="lap-stat-icon">↩️</div>
        <div class="lap-stat-num">{{ number_format($stats['total_pengembalian']) }}</div>
        <div class="lap-stat-label">Pengembalian Bulan Ini</div>
    </div>
    <div class="lap-stat">
        <div class="lap-stat-icon">⚠️</div>
        <div class="lap-stat-num">{{ number_format($stats['total_terlambat']) }}</div>
        <div class="lap-stat-label">Total Terlambat</div>
    </div>
    <div class="lap-stat">
        <div class="lap-stat-icon">💰</div>
        <div class="lap-stat-num" style="font-size:.9rem">Rp {{ number_format($stats['total_denda'],0,',','.') }}</div>
        <div class="lap-stat-label">Total Denda Bulan Ini</div>
    </div>
    <div class="lap-stat">
        <div class="lap-stat-icon">📚</div>
        <div class="lap-stat-num">{{ number_format($stats['total_buku']) }}</div>
        <div class="lap-stat-label">Total Koleksi Buku</div>
    </div>
    <div class="lap-stat">
        <div class="lap-stat-icon">👥</div>
        <div class="lap-stat-num">{{ number_format($stats['total_user']) }}</div>
        <div class="lap-stat-label">Total Anggota</div>
    </div>
</div>

{{-- FILTER BULAN --}}
<div class="filter-card">
    <span style="font-size:.875rem;font-weight:600;color:#374151">📅 Filter Periode:</span>
    <form action="{{ route('admin.laporan.index') }}" method="GET"
        style="display:flex;gap:10px;align-items:center">
        <select name="bulan" class="filter-select">
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $namaBln)
            <option value="{{ $idx+1 }}" {{ $bulan == $idx+1 ? 'selected':'' }}>{{ $namaBln }}</option>
            @endforeach
        </select>
        <select name="tahun" class="filter-select">
            @for($y = now()->year; $y >= now()->year-3; $y--)
            <option value="{{ $y }}" {{ $tahun == $y ? 'selected':'' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" class="filter-btn">Tampilkan</button>
    </form>
    <span style="font-size:.82rem;color:#94a3b8;margin-left:auto">
        Menampilkan: <strong style="color:#1a56db">
        {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }}
        {{ $tahun }}</strong>
    </span>
</div>

{{-- TOMBOL CETAK --}}
<div class="cetak-grid">
    <div class="cetak-card">
        <div class="cetak-icon">📋</div>
        <div class="cetak-title">Laporan Peminjaman</div>
        <div class="cetak-desc">Rekap semua data peminjaman beserta status dan denda</div>
        <a href="{{ route('admin.laporan.cetak-peminjaman', ['bulan'=>$bulan,'tahun'=>$tahun]) }}"
            target="_blank" class="cetak-btn">🖨️ Cetak PDF</a>
    </div>
    <div class="cetak-card">
        <div class="cetak-icon">📚</div>
        <div class="cetak-title">Laporan Buku</div>
        <div class="cetak-desc">Daftar koleksi buku beserta data peminjaman</div>
        <a href="{{ route('admin.laporan.cetak-buku') }}" target="_blank" class="cetak-btn">🖨️ Cetak PDF</a>
    </div>
    <div class="cetak-card">
        <div class="cetak-icon">👥</div>
        <div class="cetak-title">Laporan Pengguna</div>
        <div class="cetak-desc">Daftar semua anggota dan aktivitas peminjaman</div>
        <a href="{{ route('admin.laporan.cetak-user') }}" target="_blank" class="cetak-btn">🖨️ Cetak PDF</a>
    </div>
    <div class="cetak-card">
        <div class="cetak-icon">👨‍💼</div>
        <div class="cetak-title">Laporan Petugas</div>
        <div class="cetak-desc">Daftar semua petugas perpustakaan aktif</div>
        <a href="{{ route('admin.laporan.cetak-petugas') }}" target="_blank" class="cetak-btn">🖨️ Cetak PDF</a>
    </div>
</div>

{{-- GRAFIK --}}
<div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card-title">📈 Grafik Peminjaman & Pengembalian (12 Bulan Terakhir)</div>
    <div class="chart-wrap">
        <canvas id="grafikLaporan"></canvas>
    </div>
    <div style="display:flex;gap:20px;margin-top:12px;justify-content:center">
        <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:#374151">
            <div style="width:12px;height:12px;border-radius:3px;background:#1a56db"></div> Peminjaman
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:#374151">
            <div style="width:12px;height:12px;border-radius:3px;background:#0e9f6e"></div> Pengembalian
        </div>
    </div>
</div>

{{-- BUKU POPULER + USER AKTIF --}}
<div class="lap-grid-2">
    <div class="adm-card">
        <div class="adm-card-title">🏆 10 Buku Terpopuler</div>
        <table class="lap-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bukuPopuler as $i => $buku)
                <tr>
                    <td><div class="rank-num {{ $i < 3 ? 'top':'' }}">{{ $i+1 }}</div></td>
                    <td>
                        <div style="font-weight:600;color:#111827">{{ Str::limit($buku->judul,30) }}</div>
                        <div style="font-size:.72rem;color:#94a3b8">{{ $buku->penulis }}</div>
                    </td>
                    <td style="font-size:.78rem;color:#64748b">{{ $buku->category->nama_kategori ?? '-' }}</td>
                    <td><span style="font-weight:700;color:#1a56db">{{ $buku->peminjaman_count }}x</span></td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:20px;color:#94a3b8">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="adm-card">
        <div class="adm-card-title">🌟 10 User Teraktif</div>
        <table class="lap-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama User</th>
                    <th>Total Pinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userAktif as $i => $u)
                <tr>
                    <td><div class="rank-num {{ $i < 3 ? 'top':'' }}">{{ $i+1 }}</div></td>
                    <td>
                        <div style="font-weight:600;color:#111827">{{ $u->name }}</div>
                        <div style="font-size:.72rem;color:#94a3b8">{{ $u->email }}</div>
                    </td>
                    <td><span style="font-weight:700;color:#1a56db">{{ $u->peminjaman_count }}x</span></td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:20px;color:#94a3b8">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PEMINJAMAN TERLAMBAT --}}
@if($peminjamanTerlambat->count() > 0)
<div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card-title">⚠️ Peminjaman Terlambat
        <span style="background:#fee2e2;color:#b91c1c;font-size:.72rem;padding:3px 8px;border-radius:99px;font-weight:700">
            {{ $peminjamanTerlambat->count() }} buku
        </span>
    </div>
    <table class="lap-table">
        <thead>
            <tr>
                <th>No</th><th>Peminjam</th><th>Buku</th>
                <th>Batas Kembali</th><th>Keterlambatan</th><th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamanTerlambat as $i => $p)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td>
                    <div style="font-weight:600;color:#111827">{{ $p->user->name ?? '-' }}</div>
                    <div style="font-size:.72rem;color:#94a3b8">{{ $p->user->email ?? '-' }}</div>
                </td>
                <td style="font-weight:600">{{ Str::limit($p->book->judul ?? '-', 25) }}</td>
                <td style="color:#ef4444;font-weight:600">
                    {{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}
                </td>
                <td>
                    @if($p->tanggal_kembali)
                    <span style="background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:99px;font-size:.72rem;font-weight:700">
                        {{ now()->diffInDays($p->tanggal_kembali) }} hari
                    </span>
                    @endif
                </td>
                <td style="font-weight:700;color:#ef4444">
                    Rp {{ number_format($p->denda ?? 0,0,',','.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- PEMINJAMAN TERBARU --}}
<div class="adm-card">
    <div class="adm-card-title">🔄 Peminjaman Terbaru</div>
    <table class="lap-table">
        <thead>
            <tr>
                <th>No</th><th>Peminjam</th><th>Buku</th>
                <th>Tgl Pinjam</th><th>Batas Kembali</th><th>Status</th><th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanTerbaru as $i => $p)
            <tr>
                <td style="color:#94a3b8">{{ $i+1 }}</td>
                <td>
                    <div style="font-weight:600;color:#111827">{{ $p->user->name ?? '-' }}</div>
                    <div style="font-size:.72rem;color:#94a3b8">{{ $p->user->email ?? '-' }}</div>
                </td>
                <td>
                    <div style="font-weight:600">{{ Str::limit($p->book->judul ?? '-', 22) }}</div>
                    <div style="font-size:.72rem;color:#94a3b8">{{ $p->book->category->nama_kategori ?? '-' }}</div>
                </td>
                <td style="font-size:.78rem">{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d M Y') : '-' }}</td>
                <td style="font-size:.78rem">{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}</td>
                <td>
                    <span class="s-badge
                        @if($p->status=='dipinjam') s-dipinjam
                        @elseif($p->status=='dikembalikan') s-kembali
                        @elseif($p->status=='terlambat') s-terlambat
                        @else s-pending @endif">
                        @if($p->status=='dipinjam') Dipinjam
                        @elseif($p->status=='dikembalikan') Dikembalikan
                        @elseif($p->status=='terlambat') Terlambat
                        @elseif($p->status=='pending') Menunggu
                        @else {{ ucfirst($p->status) }} @endif
                    </span>
                </td>
                <td style="font-weight:600;color:{{ ($p->denda ?? 0) > 0 ? '#ef4444':'#94a3b8' }}">
                    {{ ($p->denda ?? 0) > 0 ? 'Rp '.number_format($p->denda,0,',','.') : '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:#94a3b8">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
const grafik = @json($grafikBulanan);
new Chart(document.getElementById('grafikLaporan'), {
    type: 'line',
    data: {
        labels: grafik.map(d => d.label),
        datasets: [
            {
                label: 'Peminjaman',
                data: grafik.map(d => d.peminjaman),
                borderColor: '#1a56db',
                backgroundColor: 'rgba(26,86,219,.08)',
                fill: true, tension: 0.4,
                pointBackgroundColor: '#1a56db', pointRadius: 4,
            },
            {
                label: 'Pengembalian',
                data: grafik.map(d => d.pengembalian),
                borderColor: '#0e9f6e',
                backgroundColor: 'rgba(14,159,110,.08)',
                fill: true, tension: 0.4,
                pointBackgroundColor: '#0e9f6e', pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font:{size:11}, color:'#94a3b8' } },
            x: { grid: { display: false }, ticks: { font:{size:10}, color:'#94a3b8' } }
        }
    }
});
</script>
@endpush