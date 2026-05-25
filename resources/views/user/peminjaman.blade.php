<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman | DIGILIBRARY</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ===== Sesuaikan dengan layout user kamu ===== */
        /* Kalau kamu punya layout user sendiri, ganti bagian ini */
        .user-page { max-width: 1000px; margin: 30px auto; padding: 0 20px; }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 6px;
            color: #111827;
        }

        .page-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .tab-link {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .tab-link:hover { color: #111827; }

        .tab-link.active {
            color: #059669;
            border-bottom-color: #059669;
        }

        .tab-count {
            display: inline-block;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
            margin-left: 6px;
        }

        .tab-link.active .tab-count {
            background: #d1fae5;
            color: #059669;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #f3f4f6;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .peminjaman-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            gap: 16px;
            transition: background 0.15s;
        }

        .peminjaman-card:hover { background: #f9fafb; }

        .pc-cover {
            width: 50px;
            height: 68px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            border: 1px solid #e5e7eb;
        }

        .pc-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pc-info {
            flex: 1;
            min-width: 0;
        }

        .pc-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pc-author {
            font-size: 12px;
            color: #6b7280;
            margin-top: 1px;
        }

        .pc-dates {
            display: flex;
            gap: 16px;
            margin-top: 6px;
            font-size: 12px;
            color: #9ca3af;
        }

        .pc-dates span { display: flex; align-items: center; gap: 4px; }

        .pc-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            flex-shrink: 0;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-dipinjam { background: #d1fae5; color: #065f46; }
        .badge-dikembalikan { background: #e0f2fe; color: #075985; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-terlambat { background: #fee2e2; color: #991b1b; }

        .btn-cetak {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-cetak:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Status info box */
        .status-info {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 6px;
        }

        .status-info.si-pending { background: #fffbeb; color: #92400e; }
        .status-info.si-dipinjam { background: #f0fdf4; color: #065f46; }
        .status-info.si-dikembalikan { background: #f0f9ff; color: #075985; }
        .status-info.si-ditolak { background: #fef2f2; color: #991b1b; }
        .status-info.si-terlambat { background: #fef2f2; color: #991b1b; }

        /* Empty */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
        }

        .empty-state .es-icon { font-size: 40px; margin-bottom: 10px; }
        .empty-state h3 { font-size: 15px; color: #6b7280; margin-bottom: 4px; }
        .empty-state p { font-size: 13px; }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
            padding: 16px 0;
        }

        .pagination a, .pagination span {
            padding: 6px 11px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 12px;
            color: #6b7280;
            text-decoration: none;
        }

        .pagination a:hover { background: #f3f4f6; }
        .pagination .active { background: #059669; color: white; border-color: #059669; }

        @media (max-width: 640px) {
            .peminjaman-card { flex-direction: column; align-items: flex-start; }
            .pc-right { flex-direction: row; align-items: center; width: 100%; justify-content: space-between; }
        }
    </style>
</head>
<body>

<div class="user-page">
    <h1 class="page-title">Riwayat Peminjaman</h1>
    <p class="page-subtitle">Daftar peminjaman buku yang pernah Anda ajukan</p>

    <!-- Tabs -->
    <div class="tabs">
        <a href="{{ route('user.peminjaman.riwayat', ['tab' => 'semua']) }}" class="tab-link {{ $tab == 'semua' ? 'active' : '' }}">
            Semua <span class="tab-count">{{ $countSemua }}</span>
        </a>
        <a href="{{ route('user.peminjaman.riwayat', ['tab' => 'aktif']) }}" class="tab-link {{ $tab == 'aktif' ? 'active' : '' }}">
            Aktif <span class="tab-count">{{ $countAktif }}</span>
        </a>
        <a href="{{ route('user.peminjaman.riwayat', ['tab' => 'selesai']) }}" class="tab-link {{ $tab == 'selesai' ? 'active' : '' }}">
            Selesai <span class="tab-count">{{ $countSelesai }}</span>
        </a>
        <a href="{{ route('user.peminjaman.riwayat', ['tab' => 'ditolak']) }}" class="tab-link {{ $tab == 'ditolak' ? 'active' : '' }}">
            Ditolak <span class="tab-count">{{ $countDitolak }}</span>
        </a>
    </div>

    <!-- List -->
    @if($peminjaman->count() > 0)
        @foreach($peminjaman as $p)
            @php
                $statusClass = match($p->status) {
                    'pending' => 'pending',
                    'dipinjam' => 'dipinjam',
                    'dikembalikan' => 'dikembalikan',
                    'ditolak' => 'ditolak',
                    'terlambat' => 'terlambat',
                    default => 'pending',
                };

                $statusText = match($p->status) {
                    'pending' => 'Menunggu Konfirmasi',
                    'dipinjam' => 'Sedang Dipinjam',
                    'dikembalikan' => 'Dikembalikan',
                    'ditolak' => 'Ditolak',
                    'terlambat' => 'Terlambat',
                    default => $p->status,
                };

                $statusIcon = match($p->status) {
                    'pending' => '⏳',
                    'dipinjam' => '📚',
                    'dikembalikan' => '✅',
                    'ditolak' => '❌',
                    'terlambat' => '⚠️',
                    default => '📌',
                };

                $statusDesc = match($p->status) {
                    'pending' => 'Peminjaman Anda sedang menunggu persetujuan petugas.',
                    'dipinjam' => 'Buku sedang Anda pinjam. Kembalikan sebelum ' . ($p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-') . '.',
                    'dikembalikan' => 'Buku sudah dikembalikan pada ' . ($p->tanggal_dikembalikan ? date('d/m/Y', strtotime($p->tanggal_dikembalikan)) : '-') . '.',
                    'ditolak' => 'Maaf, peminjaman Anda ditolak oleh petugas.',
                    'terlambat' => 'Buku melewati batas pengembalian! Segera kembalikan.',
                    default => '',
                };

                $siClass = 'si-' . $statusClass;
            @endphp

            <div class="card">
                <div class="peminjaman-card">
                    <!-- Cover -->
                    <div class="pc-cover">
                        @if($p->book && $p->book->cover)
                            <img src="{{ asset('uploads/covers/' . $p->book->cover) }}">
                        @else
                            📖
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="pc-info">
                        <div class="pc-title">{{ $p->book ? $p->book->title : 'Buku tidak ditemukan' }}</div>
                        <div class="pc-author">{{ $p->book ? $p->book->author : '-' }}</div>

                        <div class="pc-dates">
                            @if($p->tanggal_pinjam)
                                <span>📅 Pinjam: {{ date('d/m/Y', strtotime($p->tanggal_pinjam)) }}</span>
                            @endif
                            @if($p->tanggal_kembali)
                                <span>📆 Tempo: {{ date('d/m/Y', strtotime($p->tanggal_kembali)) }}</span>
                            @endif
                            @if($p->tanggal_dikembalikan)
                                <span>✅ Kembali: {{ date('d/m/Y', strtotime($p->tanggal_dikembalikan)) }}</span>
                            @endif
                        </div>

                        <!-- Status Description -->
                        <div class="status-info {{ $siClass }}">
                            {{ $statusIcon }} {{ $statusDesc }}
                        </div>
                    </div>

                    <!-- Right: Badge + Cetak -->
                    <div class="pc-right">
                        <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                        @if(in_array($p->status, ['dipinjam', 'dikembalikan']))
                            <a href="{{ route('user.peminjaman.cetak', $p->id) }}" target="_blank" class="btn-cetak">🖨️ Cetak Bukti</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        {{ $peminjaman->links() }}
    @else
        <div class="empty-state">
            <div class="es-icon">📭</div>
            <h3>Belum Ada Peminjaman</h3>
            <p>Peminjaman Anda akan muncul di sini</p>
        </div>
    @endif
</div>

</body>
</html>