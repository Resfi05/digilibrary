<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* TOMBOL AKSI (tidak ikut cetak) */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 680px;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: white;
            color: #374151;
            font-family: inherit;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-back:hover { border-color: #1a56db; color: #1a56db; }

        .btn-print {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #1a56db, #0e9f6e);
            color: white;
            font-family: inherit;
            font-size: .875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 12px rgba(26,86,219,.3);
        }

        .btn-print:hover { opacity: .9; transform: translateY(-1px); }

        /* BUKTI CARD */
        .bukti-wrap {
            width: 100%;
            max-width: 680px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,.1);
            overflow: hidden;
        }

        /* HEADER BUKTI */
        .bukti-header {
            background: linear-gradient(135deg, #1a56db 0%, #0e9f6e 100%);
            padding: 32px 36px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .bukti-header::before {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
            top: -60px; right: -40px;
        }

        .bukti-header::after {
            content: '';
            position: absolute;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            bottom: -40px; right: 80px;
        }

        .bh-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .bh-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bh-brand-icon { font-size: 2rem; }

        .bh-brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: white;
        }

        .bh-brand-sub {
            font-size: .72rem;
            color: rgba(255,255,255,.7);
            margin-top: 1px;
        }

        .bh-badge {
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.3);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: .78rem;
            font-weight: 700;
            color: white;
            backdrop-filter: blur(4px);
        }

        .bh-title {
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            margin-bottom: 4px;
            position: relative;
        }

        .bh-no {
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffd166;
            position: relative;
        }

        /* STATUS BANNER */
        .status-banner {
            padding: 14px 36px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .sb-pending { background: #fefce8; }
        .sb-dipinjam { background: #eff6ff; }
        .sb-dikembalikan { background: #f0fdf4; }
        .sb-terlambat { background: #fef2f2; }
        .sb-ditolak { background: #f9fafb; }

        .sb-icon { font-size: 1.4rem; flex-shrink: 0; }

        .sb-text-title {
            font-size: .875rem;
            font-weight: 700;
        }

        .sb-text-sub { font-size: .78rem; color: #6b7280; margin-top: 1px; }

        /* BODY BUKTI */
        .bukti-body { padding: 28px 36px; }

        .bb-section { margin-bottom: 24px; }

        .bb-section-title {
            font-size: .72rem;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }

        .bb-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px dashed #f3f4f6;
            gap: 16px;
        }

        .bb-row:last-child { border-bottom: none; }

        .bb-label {
            font-size: .82rem;
            color: #6b7280;
            min-width: 140px;
            flex-shrink: 0;
        }

        .bb-val {
            font-size: .875rem;
            font-weight: 600;
            color: #111827;
            text-align: right;
        }

        .bb-val-red { color: #dc2626; }
        .bb-val-green { color: #15803d; }
        .bb-val-blue { color: #1d4ed8; }

        /* BUKU INFO */
        .buku-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
        }

        .buku-cover {
            width: 60px; height: 76px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 900;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }

        .buku-info-title {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .buku-info-author { font-size: .82rem; color: #6b7280; margin-bottom: 6px; }

        .buku-info-cat {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 99px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: .68rem;
            font-weight: 700;
        }

        /* TIMELINE */
        .timeline {
            display: flex;
            align-items: center;
            gap: 0;
            margin: 20px 0;
        }

        .tl-item {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .tl-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 60%;
            right: -40%;
            height: 2px;
            background: linear-gradient(to right, #1a56db, #0e9f6e);
            z-index: 0;
        }

        .tl-dot {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin: 0 auto 8px;
            position: relative;
            z-index: 1;
        }

        .tl-dot-done { background: linear-gradient(135deg, #1a56db, #0e9f6e); }
        .tl-dot-pending { background: #f3f4f6; border: 2px dashed #d1d5db; }

        .tl-date { font-size: .72rem; font-weight: 700; color: #374151; margin-bottom: 2px; }
        .tl-label { font-size: .68rem; color: #9ca3af; }

        /* DENDA */
        .denda-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .denda-ok {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        /* FOOTER BUKTI */
        .bukti-footer {
            padding: 20px 36px;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .bf-note {
            font-size: .75rem;
            color: #9ca3af;
            line-height: 1.5;
            max-width: 400px;
        }

        .bf-qr {
            width: 70px; height: 70px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            border: 1px solid #e5e7eb;
        }

        /* PRINT STYLES */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none; }
            .bukti-wrap { box-shadow: none; border-radius: 0; max-width: 100%; }
            .bukti-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .status-banner { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media (max-width: 600px) {
            body { padding: 12px; }
            .bukti-header { padding: 24px 20px; }
            .bukti-body { padding: 20px; }
            .bukti-footer { padding: 16px 20px; }
            .bh-top { flex-direction: column; align-items: flex-start; gap: 10px; }
            .timeline { flex-direction: column; gap: 12px; }
            .tl-item::after { display: none; }
        }
    </style>
</head>
<body>

{{-- TOMBOL AKSI --}}
<div class="action-bar">
    <a href="{{ route('user.riwayat') }}" class="btn-back">← Kembali</a>
    <button onclick="window.print()" class="btn-print">🖨️ Cetak Bukti</button>
    <button onclick="downloadPDF()" class="btn-print" style="background:linear-gradient(135deg,#7c3aed,#db2777)">
        📄 Simpan PDF
    </button>
</div>

{{-- BUKTI PEMINJAMAN --}}
<div class="bukti-wrap" id="buktiDoc">

    {{-- HEADER --}}
    <div class="bukti-header">
        <div class="bh-top">
            <div class="bh-brand">
                <div class="bh-brand-icon">📚</div>
                <div>
                    <div class="bh-brand-name">DigiLibrary</div>
                    <div class="bh-brand-sub">Perpustakaan Digital</div>
                </div>
            </div>
            <div class="bh-badge">BUKTI PEMINJAMAN</div>
        </div>
        <div class="bh-title">Nomor Transaksi</div>
        <div class="bh-no">#DL-{{ str_pad($peminjaman->id, 8, '0', STR_PAD_LEFT) }}</div>
    </div>

    {{-- STATUS BANNER --}}
    @php
        $statusConfig = [
            'pending'      => ['icon'=>'⏳','title'=>'Menunggu Konfirmasi','sub'=>'Peminjaman sedang menunggu persetujuan petugas.','class'=>'sb-pending','color'=>'#a16207'],
            'dipinjam'     => ['icon'=>'📖','title'=>'Sedang Dipinjam','sub'=>'Buku sedang dalam peminjaman Anda.','class'=>'sb-dipinjam','color'=>'#1d4ed8'],
            'dikembalikan' => ['icon'=>'✅','title'=>'Dikembalikan','sub'=>'Buku telah berhasil dikembalikan. Terima kasih!','class'=>'sb-dikembalikan','color'=>'#15803d'],
            'terlambat'    => ['icon'=>'⚠️','title'=>'Terlambat','sub'=>'Buku melewati batas waktu pengembalian.','class'=>'sb-terlambat','color'=>'#dc2626'],
            'ditolak'      => ['icon'=>'❌','title'=>'Ditolak','sub'=>'Peminjaman ditolak oleh petugas.','class'=>'sb-ditolak','color'=>'#6b7280'],
        ];
        $sc = $statusConfig[$peminjaman->status] ?? $statusConfig['pending'];
    @endphp
    <div class="status-banner {{ $sc['class'] }}">
        <div class="sb-icon">{{ $sc['icon'] }}</div>
        <div>
            <div class="sb-text-title" style="color:{{ $sc['color'] }}">{{ $sc['title'] }}</div>
            <div class="sb-text-sub">{{ $sc['sub'] }}</div>
        </div>
    </div>

    <div class="bukti-body">

        {{-- INFO PEMINJAM --}}
        <div class="bb-section">
            <div class="bb-section-title">Informasi Peminjam</div>
            <div class="bb-row">
                <span class="bb-label">Nama Lengkap</span>
                <span class="bb-val">{{ $user->name }}</span>
            </div>
            <div class="bb-row">
                <span class="bb-label">Email</span>
                <span class="bb-val">{{ $user->email }}</span>
            </div>
            <div class="bb-row">
                <span class="bb-label">No. Telepon</span>
                <span class="bb-val">{{ $user->phone ?? '-' }}</span>
            </div>
            <div class="bb-row">
                <span class="bb-label">ID Anggota</span>
                <span class="bb-val bb-val-blue">DL-{{ str_pad($user->id, 8, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- INFO BUKU --}}
        <div class="bb-section">
            <div class="bb-section-title">Buku yang Dipinjam</div>
            @php
                $bcolors  = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
                $idx = ($peminjaman->book->id ?? 0) % 6;
            @endphp
            <div class="buku-card">
                <div class="buku-cover"
                    style="background:{{ $bcolors[$idx] }};color:{{ $btcolors[$idx] }};opacity:.7">
                    @if($peminjaman->book && $peminjaman->book->gambar)
                        <img src="{{ asset('storage/'.$peminjaman->book->gambar) }}"
                            style="width:100%;height:100%;object-fit:cover;border-radius:8px;opacity:1">
                    @else
                        {{ strtoupper(substr($peminjaman->book->judul ?? 'B', 0, 2)) }}
                    @endif
                </div>
                <div>
                    <div class="buku-info-title">{{ $peminjaman->book->judul ?? '-' }}</div>
                    <div class="buku-info-author">{{ $peminjaman->book->penulis ?? '-' }}</div>
                    <span class="buku-info-cat">{{ $peminjaman->book->category->nama_kategori ?? '-' }}</span>
                    @if($peminjaman->book && $peminjaman->book->isbn)
                    <div style="font-size:.72rem;color:#9ca3af;margin-top:6px">ISBN: {{ $peminjaman->book->isbn }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="bb-section">
            <div class="bb-section-title">Alur Peminjaman</div>
            <div class="timeline">
                <div class="tl-item">
                    <div class="tl-dot tl-dot-done">📝</div>
                    <div class="tl-date">{{ $peminjaman->created_at ? $peminjaman->created_at->format('d M Y') : '-' }}</div>
                    <div class="tl-label">Pengajuan</div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot {{ in_array($peminjaman->status, ['dipinjam','dikembalikan','terlambat']) ? 'tl-dot-done' : 'tl-dot-pending' }}">
                        {{ in_array($peminjaman->status, ['dipinjam','dikembalikan','terlambat']) ? '✅' : '⏳' }}
                    </div>
                    <div class="tl-date">{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d M Y') : '-' }}</div>
                    <div class="tl-label">Dipinjam</div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot {{ $peminjaman->status === 'dikembalikan' ? 'tl-dot-done' : 'tl-dot-pending' }}">
                        {{ $peminjaman->status === 'dikembalikan' ? '🏁' : '🔵' }}
                    </div>
                    <div class="tl-date">{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d M Y') : '-' }}</div>
                    <div class="tl-label">Batas Kembali</div>
                </div>
            </div>
        </div>

        {{-- DETAIL WAKTU --}}
        <div class="bb-section">
            <div class="bb-section-title">Detail Waktu</div>
            <div class="bb-row">
                <span class="bb-label">Tanggal Pinjam</span>
                <span class="bb-val">{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}</span>
            </div>
            <div class="bb-row">
                <span class="bb-label">Batas Pengembalian</span>
                <span class="bb-val {{ $peminjaman->status === 'terlambat' ? 'bb-val-red' : '' }}">
                    {{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d F Y') : '-' }}
                </span>
            </div>
            @if($peminjaman->tanggal_dikembalikan)
            <div class="bb-row">
                <span class="bb-label">Tanggal Dikembalikan</span>
                <span class="bb-val bb-val-green">
                    {{ $peminjaman->tanggal_dikembalikan->format('d F Y, H:i') }}
                </span>
            </div>
            @endif
            <div class="bb-row">
                <span class="bb-label">Durasi Peminjaman</span>
                <span class="bb-val">
                    @if($peminjaman->tanggal_pinjam && $peminjaman->tanggal_kembali)
                        {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) }} hari
                    @else - @endif
                </span>
            </div>
            <div class="bb-row">
                <span class="bb-label">Status</span>
                <span class="bb-val" style="color:{{ $sc['color'] }}">{{ $sc['title'] }}</span>
            </div>
        </div>

        {{-- DENDA --}}
        @php
            $denda = $peminjaman->hitungDenda();
        @endphp
        <div class="bb-section">
            <div class="bb-section-title">Informasi Denda</div>
            <div class="denda-box {{ $denda == 0 ? 'denda-ok' : '' }}">
                <div style="font-size:1.5rem">{{ $denda == 0 ? '✅' : '⚠️' }}</div>
                <div>
                    @if($denda == 0)
                        <div style="font-size:.875rem;font-weight:700;color:#15803d">Tidak Ada Denda</div>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:2px">Buku dikembalikan tepat waktu.</div>
                    @else
                        <div style="font-size:.875rem;font-weight:700;color:#dc2626">
                            Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                        </div>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:2px">
                            Terlambat × Rp 2.000/hari.
                            {{ $peminjaman->bayar_denda ? '✓ Sudah dibayar.' : 'Harap segera dibayar.' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- FOOTER BUKTI --}}
    <div class="bukti-footer">
        <div class="bf-note">
            <strong style="color:#374151">DigiLibrary</strong> — Perpustakaan Digital<br>
            Dokumen ini digenerate otomatis pada {{ now()->format('d M Y, H:i') }} WIB.<br>
            Nomor transaksi: <strong>#DL-{{ str_pad($peminjaman->id, 8, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div class="bf-qr">📋</div>
    </div>

</div>

<script>
function downloadPDF() {
    window.print();
}
</script>
</body>
</html>