<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Peminjaman – DIGILIBRARY</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Segoe UI',Arial,sans-serif; font-size:12px; color:#1a1a2e; background:#fff; }

  /* ── LAYOUT ── */
  .page { max-width:900px; margin:0 auto; padding:32px 36px; }

  /* ── HEADER ── */
  .header { display:flex; align-items:center; gap:18px; padding-bottom:16px; border-bottom:3px solid #6366f1; margin-bottom:6px; }
  .header-logo { width:60px; height:60px; background:linear-gradient(135deg,#6366f1,#8b5cf6); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:28px; flex-shrink:0; }
  .header-text h1 { font-size:20px; font-weight:800; color:#6366f1; letter-spacing:.5px; }
  .header-text p  { font-size:11px; color:#6b7280; margin-top:2px; }
  .header-right { margin-left:auto; text-align:right; }
  .header-right .doc-no { font-size:11px; color:#6b7280; }
  .header-right .doc-date { font-size:12px; font-weight:600; color:#374151; }

  /* ── TITLE BLOCK ── */
  .title-block { text-align:center; margin:18px 0 16px; }
  .title-block h2 { font-size:16px; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#111827; }
  .title-block .subtitle { font-size:11.5px; color:#6b7280; margin-top:4px; }
  .title-block .periode-badge { display:inline-block; margin-top:8px; background:#eff6ff; color:#6366f1; padding:4px 16px; border-radius:20px; font-size:11px; font-weight:600; border:1px solid #c7d2fe; }

  /* ── INFO FILTER ── */
  .info-bar { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px; }
  .info-chip { background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:5px 12px; font-size:11px; color:#374151; }
  .info-chip strong { color:#6366f1; }

  /* ── STATISTIK ── */
  .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:20px; }
  .stat-box { border:1.5px solid #e5e7eb; border-radius:10px; padding:12px; text-align:center; }
  .stat-box .val { font-size:20px; font-weight:800; }
  .stat-box .lbl { font-size:10px; color:#6b7280; margin-top:2px; }
  .stat-box.blue   { border-color:#bfdbfe; background:#eff6ff; } .stat-box.blue .val   { color:#2563eb; }
  .stat-box.green  { border-color:#bbf7d0; background:#dcfce7; } .stat-box.green .val  { color:#16a34a; }
  .stat-box.red    { border-color:#fecaca; background:#fef2f2; } .stat-box.red .val    { color:#dc2626; }
  .stat-box.purple { border-color:#ddd6fe; background:#f5f3ff; } .stat-box.purple .val { color:#7c3aed; }

  /* ── TABEL ── */
  table { width:100%; border-collapse:collapse; margin-bottom:24px; }
  thead tr { background:linear-gradient(135deg,#6366f1,#8b5cf6); }
  thead th { padding:10px 10px; text-align:left; font-size:10.5px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:.5px; }
  tbody tr:nth-child(even) { background:#f9fafb; }
  tbody tr:hover { background:#eff6ff; }
  tbody td { padding:9px 10px; font-size:11px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
  tfoot tr { background:#f3f4f6; }
  tfoot td { padding:8px 10px; font-size:11px; font-weight:700; border-top:2px solid #e5e7eb; }

  /* ── STATUS BADGE ── */
  .badge { display:inline-block; padding:2px 10px; border-radius:20px; font-size:10px; font-weight:700; }
  .badge-dipinjam     { background:#dbeafe; color:#1d4ed8; }
  .badge-dikembalikan { background:#dcfce7; color:#15803d; }
  .badge-terlambat    { background:#fef9c3; color:#92400e; }
  .badge-pending      { background:#fef3c7; color:#b45309; }
  .badge-ditolak      { background:#fee2e2; color:#b91c1c; }

  /* ── TTD ── */
  .ttd-section { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-top:32px; padding-top:20px; border-top:1.5px solid #e5e7eb; }
  .ttd-box { text-align:center; }
  .ttd-box .ttd-title { font-size:11px; font-weight:600; color:#374151; margin-bottom:60px; }
  .ttd-box .ttd-line  { border-top:1.5px solid #374151; padding-top:6px; font-size:11px; font-weight:700; color:#111827; }
  .ttd-box .ttd-sub   { font-size:10px; color:#6b7280; margin-top:2px; }

  /* ── FOOTER ── */
  .foot { margin-top:20px; text-align:center; font-size:10px; color:#9ca3af; border-top:1px solid #f3f4f6; padding-top:12px; }

  /* ── PRINT ── */
  @media print {
    body { font-size:11px; }
    .no-print { display:none !important; }
    .page { padding:16px 20px; }
    thead tr { -webkit-print-color-adjust:exact; print-color-adjust:exact; background:linear-gradient(135deg,#6366f1,#8b5cf6) !important; }
    .stat-box { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .badge    { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  }
</style>
</head>
<body>

{{-- TOMBOL CETAK --}}
<div class="no-print" style="position:fixed;top:16px;right:16px;display:flex;gap:8px;z-index:999;">
    <button onclick="window.print()" style="background:#6366f1;color:#fff;border:none;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(99,102,241,.4);">🖨️ Cetak / Simpan PDF</button>
    <button onclick="window.close()" style="background:#f3f4f6;color:#374151;border:none;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">✕ Tutup</button>
</div>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-logo">📚</div>
        <div class="header-text">
            <h1>DIGILIBRARY</h1>
            <p>Sistem Perpustakaan Digital</p>
            <p style="font-size:10.5px;color:#9ca3af;">Jl. Perpustakaan No. 1 | digilibrary@mail.com</p>
        </div>
        <div class="header-right">
            <div class="doc-no">No. Dok: LAP-PMJ-{{ now()->format('Ymd') }}-{{ str_pad(rand(1,999),3,'0',STR_PAD_LEFT) }}</div>
            <div class="doc-date">{{ now()->translatedFormat('d F Y') }}</div>
            <div style="font-size:10px;color:#9ca3af;margin-top:2px;">Dicetak pukul {{ now()->format('H:i') }} WIB</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="title-block">
        <h2>Laporan Peminjaman & Pengembalian Buku</h2>
        <div class="subtitle">Rekap seluruh transaksi peminjaman perpustakaan</div>
        <div class="periode-badge">📅 Periode: {{ $periodeLabel }}</div>
    </div>

    {{-- INFO FILTER --}}
    <div class="info-bar">
        <div class="info-chip">Dari: <strong>{{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d F Y') }}</strong></div>
        <div class="info-chip">Sampai: <strong>{{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d F Y') }}</strong></div>
        <div class="info-chip">Status: <strong>{{ $status ? ucfirst($status) : 'Semua' }}</strong></div>
        <div class="info-chip">Total Data: <strong>{{ $stats['total'] }} transaksi</strong></div>
    </div>

    {{-- STATISTIK --}}
    <div class="stats-grid">
        <div class="stat-box blue">
            <div class="val">{{ $stats['total'] }}</div>
            <div class="lbl">Total Transaksi</div>
        </div>
        <div class="stat-box green">
            <div class="val">{{ $stats['dikembalikan'] }}</div>
            <div class="lbl">Dikembalikan</div>
        </div>
        <div class="stat-box red">
            <div class="val">{{ $stats['terlambat'] }}</div>
            <div class="lbl">Terlambat</div>
        </div>
        <div class="stat-box purple">
            <div class="val">Rp {{ number_format($stats['total_denda'],0,',','.') }}</div>
            <div class="lbl">Total Denda</div>
        </div>
    </div>

    {{-- TABEL --}}
    <table>
        <thead>
            <tr>
                <th style="width:32px;">No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Harus Kembali</th>
                <th>Tgl Dikembalikan</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:right;">Denda</th>
                <th style="text-align:center;">Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $i => $p)
            <tr>
                <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td>
                    <div style="font-weight:600;color:#111827;">{{ $p->user->name ?? '-' }}</div>
                    <div style="font-size:10px;color:#9ca3af;">{{ $p->user->email ?? '' }}</div>
                </td>
                <td>
                    <div style="font-weight:600;color:#111827;">{{ $p->book->judul ?? 'Buku Dihapus' }}</div>
                    <div style="font-size:10px;color:#9ca3af;">{{ $p->book->penulis ?? '' }}</div>
                </td>
                <td>{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                <td>{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($p->tanggal_dikembalikan)
                        {{ \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') }}
                        @if($p->tanggal_kembali && \Carbon\Carbon::parse($p->tanggal_dikembalikan)->gt($p->tanggal_kembali))
                            <div style="font-size:9px;color:#dc2626;">+{{ $p->tanggal_kembali->diffInDays(\Carbon\Carbon::parse($p->tanggal_dikembalikan)) }} hari</div>
                        @endif
                    @else
                        <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    @php
                        $badgeClass = match($p->status) {
                            'dipinjam'     => 'badge-dipinjam',
                            'dikembalikan' => 'badge-dikembalikan',
                            'terlambat'    => 'badge-terlambat',
                            'pending'      => 'badge-pending',
                            'ditolak'      => 'badge-ditolak',
                            default        => 'badge-pending',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td style="text-align:right;font-weight:600;color:{{ $p->denda > 0 ? '#dc2626' : '#9ca3af' }};">
                    {{ $p->denda > 0 ? 'Rp '.number_format($p->denda,0,',','.') : '-' }}
                </td>
                <td style="text-align:center;">
                    @if($p->denda > 0)
                        <span class="badge {{ $p->bayar_denda ? 'badge-dikembalikan' : 'badge-terlambat' }}">
                            {{ $p->bayar_denda ? 'Lunas' : 'Belum' }}
                        </span>
                    @else
                        <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:30px;color:#9ca3af;">Tidak ada data peminjaman pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align:right;">Total Denda Keseluruhan:</td>
                <td style="text-align:right;color:#dc2626;">Rp {{ number_format($stats['total_denda'],0,',','.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="7" style="text-align:right;">Denda Lunas:</td>
                <td style="text-align:right;color:#16a34a;">Rp {{ number_format($stats['denda_lunas'],0,',','.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="7" style="text-align:right;">Denda Belum Dibayar:</td>
                <td style="text-align:right;color:#dc2626;">Rp {{ number_format($stats['denda_belum'],0,',','.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    {{-- TTD --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-title">Mengetahui,<br>Kepala Perpustakaan</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">NIP. ____________________</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title" style="margin-bottom:0;">Dicetak oleh,<br>Petugas Perpustakaan</div>
            <div style="margin-bottom:10px;font-size:10.5px;font-weight:700;color:#6366f1;">{{ auth()->user()->name ?? '-' }}</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">Petugas Perpustakaan</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title">{{ now()->translatedFormat('d F Y') }}<br>Pukul {{ now()->format('H:i') }} WIB</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">Penerima Laporan</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="foot">
        <p>Dokumen ini dicetak secara otomatis oleh sistem DIGILIBRARY • {{ now()->format('d/m/Y H:i') }} WIB</p>
        <p style="margin-top:3px;">Laporan ini sah tanpa stempel apabila ditandatangani oleh pejabat yang berwenang</p>
    </div>

</div>
</body>
</html>