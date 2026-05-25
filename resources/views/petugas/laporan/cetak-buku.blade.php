<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Data Buku – DIGILIBRARY</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Segoe UI',Arial,sans-serif; font-size:12px; color:#1a1a2e; background:#fff; }
  .page { max-width:900px; margin:0 auto; padding:32px 36px; }

  .header { display:flex; align-items:center; gap:18px; padding-bottom:16px; border-bottom:3px solid #16a34a; margin-bottom:6px; }
  .header-logo { width:60px; height:60px; background:linear-gradient(135deg,#16a34a,#4ade80); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:28px; flex-shrink:0; }
  .header-text h1 { font-size:20px; font-weight:800; color:#16a34a; letter-spacing:.5px; }
  .header-text p  { font-size:11px; color:#6b7280; margin-top:2px; }
  .header-right { margin-left:auto; text-align:right; }

  .title-block { text-align:center; margin:18px 0 16px; }
  .title-block h2 { font-size:16px; font-weight:800; text-transform:uppercase; letter-spacing:1px; }
  .title-block .subtitle { font-size:11.5px; color:#6b7280; margin-top:4px; }
  .periode-badge { display:inline-block; margin-top:8px; background:#f0fdf4; color:#16a34a; padding:4px 16px; border-radius:20px; font-size:11px; font-weight:600; border:1px solid #bbf7d0; }

  .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:20px; }
  .stat-box { border:1.5px solid #e5e7eb; border-radius:10px; padding:12px; text-align:center; }
  .stat-box .val { font-size:20px; font-weight:800; }
  .stat-box .lbl { font-size:10px; color:#6b7280; margin-top:2px; }
  .stat-box.green  { border-color:#bbf7d0; background:#dcfce7; } .stat-box.green .val  { color:#16a34a; }
  .stat-box.blue   { border-color:#bfdbfe; background:#eff6ff; } .stat-box.blue .val   { color:#2563eb; }
  .stat-box.purple { border-color:#ddd6fe; background:#f5f3ff; } .stat-box.purple .val { color:#7c3aed; }
  .stat-box.amber  { border-color:#fde68a; background:#fffbeb; } .stat-box.amber .val  { color:#d97706; }

  table { width:100%; border-collapse:collapse; margin-bottom:24px; font-size:11px; }
  thead tr { background:linear-gradient(135deg,#16a34a,#22c55e); }
  thead th { padding:10px; text-align:left; font-size:10.5px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:.5px; }
  tbody tr:nth-child(even) { background:#f0fdf4; }
  tbody td { padding:9px 10px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
  tfoot td { padding:8px 10px; font-weight:700; border-top:2px solid #e5e7eb; background:#f3f4f6; }

  .pop-bar { display:flex; align-items:center; gap:6px; }
  .pop-track { flex:1; height:6px; background:#e5e7eb; border-radius:99px; overflow:hidden; min-width:40px; }
  .pop-fill  { height:100%; background:linear-gradient(90deg,#16a34a,#4ade80); border-radius:99px; }

  .ttd-section { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-top:32px; padding-top:20px; border-top:1.5px solid #e5e7eb; }
  .ttd-box { text-align:center; }
  .ttd-box .ttd-title { font-size:11px; font-weight:600; color:#374151; margin-bottom:60px; }
  .ttd-box .ttd-line  { border-top:1.5px solid #374151; padding-top:6px; font-size:11px; font-weight:700; }
  .ttd-box .ttd-sub   { font-size:10px; color:#6b7280; margin-top:2px; }
  .foot { margin-top:20px; text-align:center; font-size:10px; color:#9ca3af; border-top:1px solid #f3f4f6; padding-top:12px; }

  @media print {
    .no-print { display:none !important; }
    .page { padding:16px 20px; }
    thead tr, .pop-fill { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .stat-box { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  }
</style>
</head>
<body>

<div class="no-print" style="position:fixed;top:16px;right:16px;display:flex;gap:8px;z-index:999;">
    <button onclick="window.print()" style="background:#16a34a;color:#fff;border:none;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(22,163,74,.4);">🖨️ Cetak / Simpan PDF</button>
    <button onclick="window.close()" style="background:#f3f4f6;color:#374151;border:none;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;">✕ Tutup</button>
</div>

<div class="page">

    <div class="header">
        <div class="header-logo">📚</div>
        <div class="header-text">
            <h1>DIGILIBRARY</h1>
            <p>Sistem Perpustakaan Digital</p>
            <p style="font-size:10.5px;color:#9ca3af;">Jl. Perpustakaan No. 1 | digilibrary@mail.com</p>
        </div>
        <div class="header-right">
            <div style="font-size:11px;color:#6b7280;">No. Dok: LAP-BKU-{{ now()->format('Ymd') }}-{{ str_pad(rand(1,999),3,'0',STR_PAD_LEFT) }}</div>
            <div style="font-size:12px;font-weight:600;color:#374151;">{{ now()->translatedFormat('d F Y') }}</div>
            <div style="font-size:10px;color:#9ca3af;margin-top:2px;">Dicetak pukul {{ now()->format('H:i') }} WIB</div>
        </div>
    </div>

    <div class="title-block">
        <h2>Laporan Data Koleksi Buku</h2>
        <div class="subtitle">Daftar seluruh buku yang tersedia di perpustakaan</div>
        <div class="periode-badge">📅 Dicetak: {{ now()->translatedFormat('d F Y') }}</div>
    </div>

    <div class="stats-grid">
        <div class="stat-box green">
            <div class="val">{{ $stats['total'] }}</div>
            <div class="lbl">Total Judul Buku</div>
        </div>
        <div class="stat-box blue">
            <div class="val">{{ $stats['total_stok'] }}</div>
            <div class="lbl">Total Stok Fisik</div>
        </div>
        <div class="stat-box purple">
            <div class="val">{{ $stats['total_dipinjam'] }}</div>
            <div class="lbl">Total Dipinjam</div>
        </div>
        <div class="stat-box amber">
            <div class="val">{{ $stats['paling_populer'] ? Str::limit($stats['paling_populer']->judul, 12) : '-' }}</div>
            <div class="lbl">Buku Terpopuler</div>
        </div>
    </div>

    @php $maxPinjam = $buku->max('peminjaman_count') ?: 1; @endphp

    <table>
        <thead>
            <tr>
                <th style="width:30px;">No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Kategori</th>
                <th>ISBN</th>
                <th style="text-align:center;">Stok</th>
                <th style="text-align:center;min-width:100px;">Popularitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $i => $b)
            <tr>
                <td style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td>
                    <div style="font-weight:600;color:#111827;">{{ $b->judul }}</div>
                    @if($b->tahun)
                    <div style="font-size:10px;color:#9ca3af;">Tahun: {{ $b->tahun }}</div>
                    @endif
                </td>
                <td style="color:#374151;">{{ $b->penulis ?? '-' }}</td>
                <td style="color:#374151;">{{ $b->penerbit ?? '-' }}</td>
                <td>
                    @if($b->category)
                        <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:12px;font-size:10px;font-weight:600;">{{ $b->category->name }}</span>
                    @else
                        <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
                <td style="font-size:10px;color:#6b7280;">{{ $b->isbn ?? '-' }}</td>
                <td style="text-align:center;">
                    <span style="font-weight:700;color:{{ $b->stok > 0 ? '#16a34a' : '#dc2626' }};">{{ $b->stok }}</span>
                </td>
                <td>
                    <div class="pop-bar">
                        <div class="pop-track">
                            <div class="pop-fill" style="width:{{ ($b->peminjaman_count / $maxPinjam) * 100 }}%"></div>
                        </div>
                        <span style="font-size:10px;font-weight:700;color:#16a34a;flex-shrink:0;">{{ $b->peminjaman_count }}x</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:30px;color:#9ca3af;">Tidak ada data buku.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;">Total:</td>
                <td style="text-align:center;">{{ $stats['total_stok'] }}</td>
                <td style="text-align:center;">{{ $stats['total_dipinjam'] }}x</td>
            </tr>
        </tfoot>
    </table>

    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-title">Mengetahui,<br>Kepala Perpustakaan</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">NIP. ____________________</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title" style="margin-bottom:0;">Dicetak oleh,<br>Petugas Perpustakaan</div>
            <div style="margin-bottom:10px;font-size:10.5px;font-weight:700;color:#16a34a;">{{ auth()->user()->name ?? '-' }}</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">Petugas Perpustakaan</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-title">{{ now()->translatedFormat('d F Y') }}<br>Pukul {{ now()->format('H:i') }} WIB</div>
            <div class="ttd-line">( _________________________ )</div>
            <div class="ttd-sub">Penerima Laporan</div>
        </div>
    </div>

    <div class="foot">
        <p>Dokumen ini dicetak secara otomatis oleh sistem DIGILIBRARY • {{ now()->format('d/m/Y H:i') }} WIB</p>
        <p style="margin-top:3px;">Laporan ini sah tanpa stempel apabila ditandatangani oleh pejabat yang berwenang</p>
    </div>

</div>
</body>
</html>