<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f5f6fa; padding:24px; }
        .action-bar { display:flex;gap:10px;margin-bottom:20px;max-width:900px;margin-left:auto;margin-right:auto; }
        .btn-back { display:flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;border:1.5px solid #e5e7eb;background:white;color:#374151;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s; }
        .btn-back:hover { border-color:#1a56db;color:#1a56db; }
        .btn-print { display:flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;border:none;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer;transition:all .25s; }
        .btn-print:hover { opacity:.9; }
        .laporan-wrap { max-width:900px;margin:0 auto;background:white;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);overflow:hidden; }

        /* HEADER */
        .lap-header { background:linear-gradient(135deg,#1a56db,#0e9f6e);padding:28px 36px;color:white;position:relative;overflow:hidden; }
        .lap-header::before { content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.06);top:-60px;right:-40px; }
        .lh-top { display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;position:relative; }
        .lh-brand { display:flex;align-items:center;gap:12px; }
        .lh-brand-icon { font-size:2.5rem; }
        .lh-brand-name { font-size:1.3rem;font-weight:800;color:white; }
        .lh-brand-sub { font-size:.8rem;color:rgba(255,255,255,.7); }
        .lh-badge { background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);padding:6px 14px;border-radius:99px;font-size:.78rem;font-weight:700;backdrop-filter:blur(4px); }
        .lh-title { font-size:1.1rem;font-weight:700;color:rgba(255,255,255,.85);position:relative; }
        .lh-sub { font-size:.85rem;color:rgba(255,255,255,.7);margin-top:4px;position:relative; }

        /* STATS BAR */
        .stats-bar { display:grid;grid-template-columns:repeat(5,1fr);background:#f8fafc;border-bottom:1px solid #f1f5f9; }
        .sb-item { padding:14px 16px;text-align:center;border-right:1px solid #f1f5f9; }
        .sb-item:last-child { border-right:none; }
        .sb-num { font-size:1.2rem;font-weight:800;color:#111827; }
        .sb-label { font-size:.72rem;color:#64748b;margin-top:3px; }

        /* BODY */
        .lap-body { padding:24px 36px; }
        .section-title { font-size:.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f3f4f6; }

        /* TABLE */
        .lap-table { width:100%;border-collapse:collapse;font-size:.8rem;margin-bottom:24px; }
        .lap-table th { text-align:left;padding:10px 12px;background:#f8fafc;color:#64748b;font-size:.7rem;font-weight:700;text-transform:uppercase;border-bottom:2px solid #e5e7eb; }
        .lap-table td { padding:10px 12px;border-bottom:1px solid #f8fafc;color:#374151;vertical-align:middle; }
        .lap-table tr:nth-child(even) td { background:#fafafa; }
        .s-badge { padding:3px 8px;border-radius:99px;font-size:.68rem;font-weight:700; }
        .s-dipinjam { background:#dbeafe;color:#1d4ed8; }
        .s-kembali  { background:#dcfce7;color:#15803d; }
        .s-terlambat{ background:#fee2e2;color:#b91c1c; }
        .s-pending  { background:#fef9c3;color:#a16207; }

        /* TTD */
        .ttd-section { display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-top:32px;padding-top:20px;border-top:1px solid #f3f4f6; }
        .ttd-box { text-align:center; }
        .ttd-title { font-size:.8rem;font-weight:600;color:#374151;margin-bottom:60px; }
        .ttd-line { border-bottom:1.5px solid #374151;margin-bottom:6px; }
        .ttd-name { font-size:.8rem;font-weight:700;color:#111827; }
        .ttd-role { font-size:.72rem;color:#64748b; }

        /* FOOTER */
        .lap-footer { padding:14px 36px;background:#f9fafb;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between; }
        .lf-note { font-size:.72rem;color:#9ca3af; }

        @media print {
            body { background:white;padding:0; }
            .action-bar { display:none; }
            .laporan-wrap { box-shadow:none;border-radius:0;max-width:100%; }
            .lap-header { -webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .stats-bar { -webkit-print-color-adjust:exact;print-color-adjust:exact; }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="{{ route('admin.laporan.index') }}" class="btn-back">← Kembali</a>
    <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
</div>

<div class="laporan-wrap">
    <div class="lap-header">
        <div class="lh-top">
            <div class="lh-brand">
                <div class="lh-brand-icon">📚</div>
                <div>
                    <div class="lh-brand-name">DigiLibrary</div>
                    <div class="lh-brand-sub">Perpustakaan Digital — SMK RPL</div>
                </div>
            </div>
            <div class="lh-badge">LAPORAN RESMI</div>
        </div>
        <div class="lh-title">LAPORAN DATA PEMINJAMAN BUKU</div>
        <div class="lh-sub">
            Periode: {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}
            &nbsp;|&nbsp; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            &nbsp;|&nbsp; Oleh: {{ $admin->name }}
        </div>
    </div>

    <div class="stats-bar">
        <div class="sb-item">
            <div class="sb-num">{{ $stats['total'] }}</div>
            <div class="sb-label">Total Peminjaman</div>
        </div>
        <div class="sb-item">
            <div class="sb-num" style="color:#1d4ed8">{{ $stats['dipinjam'] }}</div>
            <div class="sb-label">Sedang Dipinjam</div>
        </div>
        <div class="sb-item">
            <div class="sb-num" style="color:#15803d">{{ $stats['dikembalikan'] }}</div>
            <div class="sb-label">Dikembalikan</div>
        </div>
        <div class="sb-item">
            <div class="sb-num" style="color:#b91c1c">{{ $stats['terlambat'] }}</div>
            <div class="sb-label">Terlambat</div>
        </div>
        <div class="sb-item">
            <div class="sb-num" style="font-size:.95rem;color:#b91c1c">Rp {{ number_format($stats['total_denda'],0,',','.') }}</div>
            <div class="sb-label">Total Denda</div>
        </div>
    </div>

    <div class="lap-body">
        <div class="section-title">Data Peminjaman</div>
        <table class="lap-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Email</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Tgl Dikembalikan</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="font-weight:600">{{ $p->user->name ?? '-' }}</td>
                    <td style="font-size:.72rem;color:#64748b">{{ $p->user->email ?? '-' }}</td>
                    <td style="font-weight:600">{{ $p->book->judul ?? '-' }}</td>
                    <td style="font-size:.72rem">{{ $p->book->category->nama_kategori ?? '-' }}</td>
                    <td>{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                    <td class="{{ $p->status=='terlambat' ? 'color:#ef4444':'' }}">
                        {{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $p->tanggal_dikembalikan ? \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="s-badge
                            @if($p->status=='dipinjam') s-dipinjam
                            @elseif($p->status=='dikembalikan') s-kembali
                            @elseif($p->status=='terlambat') s-terlambat
                            @else s-pending @endif">
                            @if($p->status=='dipinjam') Dipinjam
                            @elseif($p->status=='dikembalikan') Dikembalikan
                            @elseif($p->status=='terlambat') Terlambat
                            @else Menunggu @endif
                        </span>
                    </td>
                    <td style="font-weight:600;color:{{ $p->denda > 0 ? '#ef4444':'#94a3b8' }}">
                        {{ $p->denda > 0 ? 'Rp '.number_format($p->denda,0,',','.') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- TTD --}}
        <div class="ttd-section">
            <div class="ttd-box">
                <div class="ttd-title">Mengetahui,<br>Kepala Sekolah</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">________________________</div>
                <div class="ttd-role">NIP. ________________________</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-title">Menyetujui,<br>Kepala Perpustakaan</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">________________________</div>
                <div class="ttd-role">NIP. ________________________</div>
            </div>
            <div class="ttd-box">
                <div class="ttd-title">Dibuat oleh,<br>Administrator Sistem</div>
                <div class="ttd-line"></div>
                <div class="ttd-name">{{ $admin->name }}</div>
                <div class="ttd-role">Administrator DigiLibrary</div>
            </div>
        </div>
    </div>

    <div class="lap-footer">
        <div class="lf-note">DigiLibrary — Perpustakaan Digital SMK RPL</div>
        <div class="lf-note">Dicetak otomatis pada {{ now()->format('d M Y, H:i') }} WIB &nbsp;|&nbsp; Halaman 1</div>
    </div>
</div>
</body>
</html>