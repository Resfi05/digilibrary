<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengguna - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f5f6fa; padding:24px; }
        .action-bar { display:flex;gap:10px;margin-bottom:20px;max-width:900px;margin-left:auto;margin-right:auto; }
        .btn-back { display:flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;border:1.5px solid #e5e7eb;background:white;color:#374151;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;text-decoration:none; }
        .btn-back:hover { border-color:#1a56db;color:#1a56db; }
        .btn-print { display:flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;border:none;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;font-family:inherit;font-size:.875rem;font-weight:700;cursor:pointer; }
        .laporan-wrap { max-width:900px;margin:0 auto;background:white;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.08);overflow:hidden; }
        .lap-header { background:linear-gradient(135deg,#0e9f6e,#1a56db);padding:28px 36px;color:white;position:relative;overflow:hidden; }
        .lap-header::before { content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.06);top:-60px;right:-40px; }
        .lh-top { display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;position:relative; }
        .lh-brand { display:flex;align-items:center;gap:12px; }
        .lh-brand-icon { font-size:2.5rem; }
        .lh-brand-name { font-size:1.3rem;font-weight:800;color:white; }
        .lh-brand-sub { font-size:.8rem;color:rgba(255,255,255,.7); }
        .lh-badge { background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);padding:6px 14px;border-radius:99px;font-size:.78rem;font-weight:700; }
        .lh-title { font-size:1.1rem;font-weight:700;color:rgba(255,255,255,.9);position:relative; }
        .lh-sub { font-size:.85rem;color:rgba(255,255,255,.7);margin-top:4px;position:relative; }
        .lap-body { padding:24px 36px; }
        .section-title { font-size:.72rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;padding-bottom:8px;border-bottom:1px solid #f3f4f6; }
        .lap-table { width:100%;border-collapse:collapse;font-size:.8rem;margin-bottom:24px; }
        .lap-table th { text-align:left;padding:10px 12px;background:#f8fafc;color:#64748b;font-size:.7rem;font-weight:700;text-transform:uppercase;border-bottom:2px solid #e5e7eb; }
        .lap-table td { padding:10px 12px;border-bottom:1px solid #f8fafc;color:#374151; }
        .lap-table tr:nth-child(even) td { background:#fafafa; }
        .status-a { background:#dcfce7;color:#15803d;padding:3px 8px;border-radius:99px;font-size:.68rem;font-weight:700; }
        .status-n { background:#fee2e2;color:#b91c1c;padding:3px 8px;border-radius:99px;font-size:.68rem;font-weight:700; }
        .ttd-section { display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-top:32px;padding-top:20px;border-top:1px solid #f3f4f6; }
        .ttd-box { text-align:center; }
        .ttd-title { font-size:.8rem;font-weight:600;color:#374151;margin-bottom:60px; }
        .ttd-line { border-bottom:1.5px solid #374151;margin-bottom:6px; }
        .ttd-name { font-size:.8rem;font-weight:700;color:#111827; }
        .ttd-role { font-size:.72rem;color:#64748b; }
        .lap-footer { padding:14px 36px;background:#f9fafb;border-top:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between; }
        .lf-note { font-size:.72rem;color:#9ca3af; }
        @media print { body{background:white;padding:0;} .action-bar{display:none;} .laporan-wrap{box-shadow:none;border-radius:0;max-width:100%;} .lap-header{-webkit-print-color-adjust:exact;print-color-adjust:exact;} }
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
        <div class="lh-title">LAPORAN DATA ANGGOTA PERPUSTAKAAN</div>
        <div class="lh-sub">
            Total Anggota: {{ $users->count() }} orang
            &nbsp;|&nbsp; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            &nbsp;|&nbsp; Oleh: {{ $admin->name }}
        </div>
    </div>

    <div class="lap-body">
        <div class="section-title">Daftar Anggota</div>
        <table class="lap-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Total Pinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="font-weight:600;color:#111827">{{ $u->name }}</td>
                    <td style="font-size:.72rem">{{ $u->email }}</td>
                    <td>{{ $u->phone ?? '-' }}</td>
                    <td style="font-size:.72rem">{{ $u->address ?? '-' }}</td>
                    <td>
                        <span class="{{ $u->is_active ? 'status-a':'status-n' }}">
                            {{ $u->is_active ? 'Aktif':'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ $u->created_at?->format('d M Y') }}</td>
                    <td style="font-weight:700;color:#1a56db">{{ $u->peminjaman_count }}x</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

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
        <div class="lf-note">Dicetak otomatis pada {{ now()->format('d M Y, H:i') }} WIB</div>
    </div>
</div>
</body>
</html>