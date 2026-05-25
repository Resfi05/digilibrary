@extends('layouts.petugas')

@section('title', 'Validasi Pengembalian')
@section('page-title', 'Validasi Pengembalian')

@section('content')
<!-- Search -->
<div class="search-bar no-print">
    <form method="GET" action="{{ route('petugas.peminjaman.pengembalian') }}" style="display:flex;gap:10px;flex:1;">
        <div class="search-input">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama peminjam atau judul buku...">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if($search)
            <a href="{{ route('petugas.peminjaman.pengembalian') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

<!-- Tabs -->
<div class="tabs no-print">
    <a href="{{ route('petugas.peminjaman.pengembalian', ['tab' => 'aktif']) }}" class="tab-link {{ $tab == 'aktif' ? 'active' : '' }}">Menunggu Validasi</a>
    <a href="{{ route('petugas.peminjaman.pengembalian', ['tab' => 'terlambat']) }}" class="tab-link {{ $tab == 'terlambat' ? 'active' : '' }}">Terlambat</a>
    <a href="{{ route('petugas.peminjaman.pengembalian', ['tab' => 'selesai']) }}" class="tab-link {{ $tab == 'selesai' ? 'active' : '' }}">Selesai</a>
</div>

<!-- Tabel -->
<div class="section-card">
    <div class="section-head">
        <h3>{{ $tab == 'aktif' ? 'Menunggu Validasi' : ($tab == 'terlambat' ? 'Terlambat' : 'Selesai') }} ({{ $peminjaman->total() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @if($peminjaman->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl. Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $i => $p)
                        @php
                            $isTerlambat = $p->status == 'dipinjam' 
                                && $p->tanggal_kembali 
                                && $p->tanggal_kembali < now()->format('Y-m-d');
                            $denda = $p->hitungDenda();
                            $statusText = $isTerlambat ? 'Terlambat' : ($p->status == 'dikembalikan' ? 'Tepat Waktu' : 'Menunggu');
                            $statusClass = $isTerlambat ? 'danger' : ($p->status == 'dikembalikan' ? 'success' : 'pending');
                        @endphp
                        <tr onclick="showDetail({{ $i }})" id="row-{{ $i }}">
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:{{ $isTerlambat ? '#fee2e2' : ($p->status == 'dikembalikan' ? '#d1fae5' : '#e0f2fe') }};color:{{ $isTerlambat ? '#dc2626' : ($p->status == 'dikembalikan' ? '#065f46' : '#0369a1') }};display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                        {{ $p->user ? substr($p->user->name, 0, 1) : '-' }}
                                    </div>
                                    <div style="font-weight:600;font-size:13px;">{{ $p->user ? $p->user->name : '-' }}</div>
                                </div>
                            </td>
                            <td>{{ $p->book ? $p->book->title : '-' }}</td>
                            <td style="font-size:12px;color:#6b7280;">
                                {{ $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-' }}
                            </td>
                            <td>
                                <span style="font-weight:700;font-size:12.5px;color:{{ $denda > 0 ? '#dc2626' : '#059669' }};">
                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>
                        @php
                            $details[$i] = [
                                'id' => $p->id,
                                'user_name' => $p->user ? $p->user->name : '-',
                                'user_email' => $p->user ? ($p->user->email ?? '-') : '-',
                                'user_phone' => $p->user ? ($p->user->phone ?? '-') : '-',
                                'book_title' => $p->book ? $p->book->title : '-',
                                'book_author' => $p->book ? $p->book->author : '-',
                                'book_publisher' => $p->book ? ($p->book->publisher ?? '-') : '-',
                                'book_cover' => $p->book ? $p->book->cover : null,
                                'tanggal_pinjam' => $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-',
                                'tanggal_kembali' => $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-',
                                'tanggal_dikembalikan' => $p->tanggal_dikembalikan ? date('d/m/Y', strtotime($p->tanggal_dikembalikan)) : '-',
                                'denda' => 'Rp ' . number_format($denda, 0, ',', '.'),
                                'denda_num' => $denda,
                                'isTerlambat' => $isTerlambat,
                                'statusText' => $statusText,
                                'statusClass' => $statusClass,
                                'canReturn' => $p->status !== 'dikembalikan',
                                'isDikembalikan' => $p->status === 'dikembalikan',
                            ];
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <div style="padding:12px 18px;">
                {{ $peminjaman->links() }}
            </div>
        @else
            <div class="empty-state" style="padding:50px 20px;">
                <div class="es-icon">✅</div>
                <h3>Tidak Ada Data</h3>
                <p>{{ $tab == 'aktif' ? 'Tidak ada pengembalian yang menunggu validasi' : ($tab == 'terlambat' ? 'Tidak ada pengembalian terlambat' : 'Belum ada data pengembalian selesai') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('detail-panel')
<div class="detail-close">
    <h4>Detail Pengembalian</h4>
    <button class="detail-close-btn" onclick="closeDetail()">✕</button>
</div>

<div id="detailContent">
    <div class="detail-empty">
        <div class="de-icon">📋</div>
        <p style="font-size:12px;color:#9ca3af;">Klik baris data untuk melihat detail pengembalian</p>
    </div>
</div>

<script>
    const details = @json($details ?? []);
    const csrfToken = '{{ csrf_token() }}';

    function showDetail(idx) {
        const d = details[idx];
        if (!d) return;

        selectRow(document.getElementById('row-' + idx));
        openDetail();

        const dendaBg = d.denda_num > 0 ? '#fef2f2' : '#f0fdf4';
        const dendaBorder = d.denda_num > 0 ? '#fca5a5' : '#bbf7d0';
        const dendaTextColor = d.denda_num > 0 ? '#dc2626' : '#065f46';
        const statusBg = d.isTerlambat ? '#fef2f2' : (d.isDikembalikan ? '#f0fdf4' : '#fffbeb');
        const statusBorder = d.isTerlambat ? '#fca5a5' : (d.isDikembalikan ? '#bbf7d0' : '#fde68a');
        const statusText = d.isTerlambat ? '#dc2626' : (d.isDikembalikan ? '#065f46' : '#92400e');

        document.getElementById('detailContent').innerHTML = `
            <!-- Cover Buku -->
            <div style="width:100%;height:160px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;overflow:hidden;border:1px solid #e5e7eb;">
                ${d.book_cover
                    ? `<img src="/uploads/covers/${d.book_cover}" style="width:100%;height:100%;object-fit:cover;">`
                    : `<span style="font-size:48px;color:#d1d5db;">📖</span>`
                }
            </div>

            <!-- Info Peminjam -->
            <div style="margin-bottom:18px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#9ca3af;margin-bottom:8px;">Informasi Peminjam</div>
                <div style="background:white;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Nama</span>
                        <span style="font-weight:600;color:#111827;">${d.user_name}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Email</span>
                        <span style="font-weight:500;color:#374151;">${d.user_email}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;font-size:12.5px;">
                        <span style="color:#6b7280;">Telepon</span>
                        <span style="font-weight:500;color:#374151;">${d.user_phone}</span>
                    </div>
                </div>
            </div>

            <!-- Info Buku -->
            <div style="margin-bottom:18px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#9ca3af;margin-bottom:8px;">Informasi Buku</div>
                <div style="background:white;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Judul</span>
                        <span style="font-weight:600;color:#111827;text-align:right;max-width:60%;">${d.book_title}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Penulis</span>
                        <span style="font-weight:500;color:#374151;">${d.book_author}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;font-size:12.5px;">
                        <span style="color:#6b7280;">Penerbit</span>
                        <span style="font-weight:500;color:#374151;">${d.book_publisher}</span>
                    </div>
                </div>
            </div>

            <!-- Status Peminjaman -->
            <div style="margin-bottom:18px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#9ca3af;margin-bottom:8px;">Status Peminjaman</div>
                <div style="background:white;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Tgl. Pinjam</span>
                        <span style="font-weight:500;color:#374151;">${d.tanggal_pinjam}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Jatuh Tempo</span>
                        <span style="font-weight:500;color:#374151;">${d.tanggal_kembali}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;font-size:12.5px;">
                        <span style="color:#6b7280;">Tgl. Dikembalikan</span>
                        <span style="font-weight:500;color:#374151;">${d.tanggal_dikembalikan}</span>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div style="background:${statusBg};border:1px solid ${statusBorder};border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:12.5px;color:${statusText};font-weight:600;">
                    ${d.isTerlambat ? '⚠️' : (d.isDikembalikan ? '✅' : '⏳')} Status: ${d.statusText}
                </span>
            </div>

            <!-- Denda -->
            <div style="background:${dendaBg};border:1px solid ${dendaBorder};border-radius:8px;overflow:hidden;margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;padding:10px 14px;border-bottom:1px dashed ${dendaBorder};font-size:12.5px;">
                    <span style="color:${dendaTextColor};font-weight:500;">Jumlah Denda</span>
                    <span style="color:${dendaTextColor};font-weight:800;font-size:16px;">${d.denda}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 14px;font-size:12.5px;">
                    <span style="color:${dendaTextColor};font-weight:500;">Status Denda</span>
                    <span style="font-weight:700;">
                        ${d.denda_num > 0
                            ? '<span style="background:#fee2e2;color:#dc2626;padding:2px 10px;border-radius:12px;font-size:11px;">Belum Dibayar</span>'
                            : '<span style="background:#d1fae5;color:#065f46;padding:2px 10px;border-radius:12px;font-size:11px;">Tidak Ada Denda</span>'
                        }
                    </span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            ${d.canReturn ? `
            <form method="POST" action="/petugas/peminjaman/${d.id}/return">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PUT">
                <button type="submit"
                    onclick="return confirm('Proses pengembalian buku ini dari ${d.user_name}?')"
                    style="width:100%;padding:12px;background:#0891b2;color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:background 0.15s;"
                    onmouseover="this.style.background='#0e7490'"
                    onmouseout="this.style.background='#0891b2'">
                    📥 Proses Pengembalian
                </button>
            </form>
            ` : `
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 14px;text-align:center;">
                <span style="color:#065f46;font-weight:600;font-size:13px;">✅ Pengembalian sudah diproses</span>
            </div>
            `}
        `;
    }
</script>
@endsection