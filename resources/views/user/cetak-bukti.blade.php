<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman - DIGILIBRARY</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
            font-size: 13px;
            color: #1f2937;
        }

        .bukti-card {
            max-width: 450px;
            margin: 0 auto;
            border: 2px solid #059669;
            border-radius: 12px;
            overflow: hidden;
        }

        .bukti-header {
            background: linear-gradient(135deg, #065f46, #059669);
            color: white;
            padding: 18px;
            text-align: center;
        }

        .bukti-header h2 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .bukti-header p {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 3px;
        }

        .bukti-body {
            padding: 20px;
        }

        .bukti-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e5e7eb;
            font-size: 13px;
        }

        .bukti-row:last-child { border-bottom: none; }
        .bukti-row .bl { color: #6b7280; }
        .bukti-row .bv { font-weight: 600; text-align: right; max-width: 60%; }

        .bukti-status {
            text-align: center;
            margin: 16px 0;
            padding: 10px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .bukti-status.status-dipinjam {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .bukti-status.status-dikembalikan {
            background: #e0f2fe;
            color: #075985;
            border: 1px solid #bae6fd;
        }

        .bukti-footer {
            text-align: center;
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
        }

        .ttd-area {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        .ttd-line {
            margin-top: 60px;
            border-top: 1px solid #1f2937;
            width: 180px;
            display: inline-block;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="bukti-card">
    <div class="bukti-header">
        <h2>DIGILIBRARY</h2>
        <p>Perpustakaan Digital</p>
    </div>

    <div class="bukti-body">
        @php
            $isDikembalikan = $peminjaman->status === 'dikembalikan';
            $statusLabel = $isDikembalikan ? 'Dikembalikan' : 'Dipinjam';
            $statusClass = $isDikembalikan ? 'status-dikembalikan' : 'status-dipinjam';
        @endphp

        <div class="bukti-status {{ $statusClass }}">
            {{ $isDikembalikan ? '✅' : '📚' }} Status: {{ $statusLabel }}
        </div>

        <div class="bukti-row">
            <span class="bl">Nama Peminjam</span>
            <span class="bv">{{ $peminjaman->user->name }}</span>
        </div>
        <div class="bukti-row">
            <span class="bl">Judul Buku</span>
            <span class="bv">{{ $peminjaman->book ? $peminjaman->book->title : '-' }}</span>
        </div>
        <div class="bukti-row">
            <span class="bl">Penulis</span>
            <span class="bv">{{ $peminjaman->book ? $peminjaman->book->author : '-' }}</span>
        </div>
        <div class="bukti-row">
            <span class="bl">Penerbit</span>
            <span class="bv">{{ $peminjaman->book ? ($peminjaman->book->publisher ?? '-') : '-' }}</span>
        </div>
        <div class="bukti-row">
            <span class="bl">Tgl. Pinjam</span>
            <span class="bv">{{ $peminjaman->tanggal_pinjam ? date('d/m/Y', strtotime($peminjaman->tanggal_pinjam)) : '-' }}</span>
        </div>
        <div class="bukti-row">
            <span class="bl">Tgl. Kembali</span>
            <span class="bv">{{ $peminjaman->tanggal_kembali ? date('d/m/Y', strtotime($peminjaman->tanggal_kembali)) : '-' }}</span>
        </div>
        @if($isDikembalikan)
            <div class="bukti-row">
                <span class="bl">Tgl. Dikembalikan</span>
                <span class="bv">{{ $peminjaman->tanggal_dikembalikan ? date('d/m/Y', strtotime($peminjaman->tanggal_dikembalikan)) : '-' }}</span>
            </div>
        @endif
    </div>

    <div class="bukti-footer">
        Bukti ini digunakan sebagai tanda terima peminjaman/pengembalian buku.<br>
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</div>

<!-- Tanda Tangan -->
<div class="ttd-area no-print" style="margin-top: 30px;">
    <p>Kota Ilmu, {{ date('d F Y') }}</p>
    <p>Petugas Perpustakaan</p>
    <div class="ttd-line"></div>
    <p><strong>________________________</strong></p>
</div>

<!-- Tombol Cetak -->
<div style="text-align: center; margin-top: 20px;" class="no-print">
    <button onclick="window.print()" style="padding: 10px 30px; background: #059669; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
        🖨️ Cetak Bukti
    </button>
    <button onclick="window.close()" style="padding: 10px 30px; background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; margin-left: 10px;">
        Tutup
    </button>
</div>

</body>
</html>