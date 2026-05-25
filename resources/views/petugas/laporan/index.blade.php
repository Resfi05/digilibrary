@extends('layouts.petugas')

@section('title', 'Laporan')
@section('page-title', 'Laporan Perpustakaan')

@section('content')
<style>
    .lap-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:28px; }
    .lap-card { background:#fff; border-radius:14px; padding:20px; box-shadow:0 1px 4px rgba(0,0,0,.07); display:flex; align-items:center; gap:14px; }
    .lap-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0; }
    .lap-val  { font-size:24px; font-weight:800; color:#111827; line-height:1; }
    .lap-lbl  { font-size:12px; color:#6b7280; margin-top:3px; }
    .report-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
    .report-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.07); display:flex; flex-direction:column; }
    .report-card-head { padding:20px 24px 16px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:12px; }
    .report-card-body { padding:20px 24px; flex:1; }
    .report-card-foot { padding:16px 24px; background:#f9fafb; border-top:1px solid #f3f4f6; }
    .btn-cetak { display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:11px 20px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .15s; }
    .btn-indigo { background:#6366f1; color:#fff; } .btn-indigo:hover { background:#4f46e5; }
    .btn-green  { background:#16a34a; color:#fff; } .btn-green:hover  { background:#15803d; }
    .btn-purple { background:#7c3aed; color:#fff; } .btn-purple:hover { background:#6d28d9; }
    .filter-group { display:flex; flex-direction:column; gap:4px; margin-bottom:10px; }
    .filter-group label { font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; }
    .filter-group select, .filter-group input { padding:8px 12px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:13px; color:#374151; background:#fff; outline:none; width:100%; }
    .filter-group select:focus, .filter-group input:focus { border-color:#6366f1; }
    .popular-item { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid #f3f4f6; }
    .popular-rank { width:24px; height:24px; border-radius:6px; background:#eff6ff; color:#6366f1; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .bar-wrap { flex:1; min-width:0; }
    .bar-label { font-size:11.5px; font-weight:600; color:#111827; margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .bar-track { height:5px; background:#f3f4f6; border-radius:99px; overflow:hidden; }
    .bar-fill  { height:100%; background:linear-gradient(90deg,#6366f1,#8b5cf6); border-radius:99px; }
    .bar-count { font-size:11px; font-weight:700; color:#6366f1; flex-shrink:0; }
    @media(max-width:1100px){ .lap-grid{grid-template-columns:repeat(2,1fr);} .report-grid{grid-template-columns:1fr 1fr;} }
    @media(max-width:700px) { .lap-grid{grid-template-columns:1fr 1fr;}  .report-grid{grid-template-columns:1fr;} }
</style>

{{-- STATISTIK ATAS --}}
<div class="lap-grid">
    <div class="lap-card">
        <div class="lap-icon" style="background:#eff6ff;">📋</div>
        <div><div class="lap-val">{{ number_format($totalPeminjaman) }}</div><div class="lap-lbl">Total Peminjaman</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#dcfce7;">✅</div>
        <div><div class="lap-val" style="color:#16a34a;">{{ number_format($totalDikembalikan) }}</div><div class="lap-lbl">Sudah Dikembalikan</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#fef3c7;">⚠️</div>
        <div><div class="lap-val" style="color:#d97706;">{{ number_format($totalTerlambat) }}</div><div class="lap-lbl">Pernah Terlambat</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#fce7f3;">💰</div>
        <div><div class="lap-val" style="color:#db2777;font-size:16px;">Rp {{ number_format($totalDenda,0,',','.') }}</div><div class="lap-lbl">Total Denda Terkumpul</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#f0fdf4;">📚</div>
        <div><div class="lap-val">{{ number_format($totalBuku) }}</div><div class="lap-lbl">Total Koleksi Buku</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#faf5ff;">👥</div>
        <div><div class="lap-val">{{ number_format($totalUser) }}</div><div class="lap-lbl">Total Pengguna</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#fff7ed;">👷</div>
        <div><div class="lap-val">{{ number_format($totalPetugas) }}</div><div class="lap-lbl">Total Petugas</div></div>
    </div>
    <div class="lap-card">
        <div class="lap-icon" style="background:#ecfeff;">📅</div>
        <div><div class="lap-val" style="color:#0891b2;">{{ number_format($peminjamanBulanIni) }}</div><div class="lap-lbl">Pinjam Bulan Ini</div></div>
    </div>
</div>

{{-- JUDUL SEKSI --}}
<div style="margin-bottom:16px;">
    <h3 style="font-size:15px;font-weight:700;color:#111827;">🖨️ Cetak Laporan</h3>
    <p style="font-size:12px;color:#6b7280;margin-top:3px;">Pilih jenis laporan yang ingin dicetak atau disimpan sebagai PDF</p>
</div>

{{-- GRID LAPORAN --}}
<div class="report-grid">

    {{-- LAPORAN PEMINJAMAN --}}
    <div class="report-card">
        <div class="report-card-head">
            <div style="width:44px;height:44px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📋</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#111827;">Laporan Peminjaman</div>
                <div style="font-size:11px;color:#6b7280;">Riwayat peminjaman & pengembalian</div>
            </div>
        </div>
        <div class="report-card-body">
            <form id="formPeminjaman" target="_blank" action="{{ route('petugas.laporan.cetak-peminjaman') }}" method="GET">
                <div class="filter-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <label>Filter Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="pending">Pending</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="report-card-foot">
            <button onclick="document.getElementById('formPeminjaman').submit()" class="btn-cetak btn-indigo">🖨️ Cetak Laporan Peminjaman</button>
        </div>
    </div>

    {{-- LAPORAN BUKU --}}
    <div class="report-card">
        <div class="report-card-head">
            <div style="width:44px;height:44px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📚</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#111827;">Laporan Data Buku</div>
                <div style="font-size:11px;color:#6b7280;">Koleksi buku & statistik peminjaman</div>
            </div>
        </div>
        <div class="report-card-body">
            <form id="formBuku" target="_blank" action="{{ route('petugas.laporan.cetak-buku') }}" method="GET">
                <div class="filter-group">
                    <label>Filter Kategori</label>
                    <select name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama ?? $kat->name ?? $kat->nama_kategori ?? 'Kategori' }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div style="margin-top:8px;">
                <div style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;margin-bottom:8px;">📊 Top 5 Buku Terpopuler</div>
                @php $maxPinjam = $bukuPopuler->max('peminjaman_count') ?: 1; @endphp
                @foreach($bukuPopuler as $i => $b)
                <div class="popular-item">
                    <div class="popular-rank">{{ $i+1 }}</div>
                    <div class="bar-wrap">
                        <div class="bar-label">{{ $b->judul }}</div>
                        <div class="bar-track">
                            <div class="bar-fill" style="width:{{ ($b->peminjaman_count/$maxPinjam)*100 }}%"></div>
                        </div>
                    </div>
                    <div class="bar-count">{{ $b->peminjaman_count }}x</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="report-card-foot">
            <button onclick="document.getElementById('formBuku').submit()" class="btn-cetak btn-green">🖨️ Cetak Laporan Buku</button>
        </div>
    </div>

    {{-- LAPORAN PENGGUNA --}}
    <div class="report-card">
        <div class="report-card-head">
            <div style="width:44px;height:44px;background:#faf5ff;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">👥</div>
            <div>
                <div style="font-size:14px;font-weight:700;color:#111827;">Laporan Data Pengguna</div>
                <div style="font-size:11px;color:#6b7280;">Daftar anggota & aktivitas peminjaman</div>
            </div>
        </div>
        <div class="report-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                <div style="background:#faf5ff;border-radius:10px;padding:14px;text-align:center;">
                    <div style="font-size:22px;font-weight:800;color:#7c3aed;">{{ $totalUser }}</div>
                    <div style="font-size:11px;color:#6b7280;">Total Anggota</div>
                </div>
                <div style="background:#f0fdf4;border-radius:10px;padding:14px;text-align:center;">
                    <div style="font-size:22px;font-weight:800;color:#16a34a;">{{ $peminjamanBulanIni }}</div>
                    <div style="font-size:11px;color:#6b7280;">Aktif Bulan Ini</div>
                </div>
            </div>
            <p style="font-size:12px;color:#6b7280;line-height:1.6;">Laporan mencakup daftar seluruh anggota terdaftar, total peminjaman per pengguna, status aktif, dan tanggal bergabung.</p>
        </div>
        <div class="report-card-foot">
            <a href="{{ route('petugas.laporan.cetak-user') }}" target="_blank" class="btn-cetak btn-purple">🖨️ Cetak Laporan Pengguna</a>
        </div>
    </div>

</div>
@endsection