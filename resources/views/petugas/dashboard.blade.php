@extends('layouts.petugas')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-ic ic-blue">📚</div>
        <div>
            <div class="stat-val">{{ number_format($totalBuku, 0, ',', '.') }}</div>
            <div class="stat-lbl">Total Buku</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-ic ic-green">🔄</div>
        <div>
            <div class="stat-val">{{ number_format($totalPeminjaman, 0, ',', '.') }}</div>
            <div class="stat-lbl">Total Peminjaman</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-ic ic-yellow">✅</div>
        <div>
            <div class="stat-val">{{ number_format($peminjamanAktif, 0, ',', '.') }}</div>
            <div class="stat-lbl">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-ic ic-red">⏳</div>
        <div>
            <div class="stat-val">{{ number_format($peminjamanPending, 0, ',', '.') }}</div>
            <div class="stat-lbl">Menunggu Persetujuan</div>
        </div>
    </div>
</div>

<!-- Peminjaman Perlu Diproses -->
<div class="section-card">
    <div class="section-head">
        <h3>📥 Peminjaman Perlu Diproses</h3>
        <a href="{{ route('petugas.peminjaman.validasi') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
    </div>
    <div class="section-body">
        @if($peminjamanTerbaru->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjamanTerbaru as $i => $p)
                        @php
                            $judulBuku = $p->book ? $p->book->title : '<span style="color:#ef4444;">Buku tidak ditemukan</span>';
                            $coverBuku = $p->book ? $p->book->cover : null;
                        @endphp
                        <tr onclick="showDashboardDetail({{ $i }})" id="row-{{ $i }}">
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $p->user ? $p->user->name : '-' }}</strong></td>
                            <td>{!! $judulBuku !!}</td>
                            <td>{{ $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $p->status == 'pending' ? 'pending' : ($p->status == 'dipinjam' ? 'success' : ($p->status == 'dikembalikan' ? 'info' : 'danger')) }}">
                                    {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td>
                                @if($p->status == 'pending')
                                    <div class="action-btns" onclick="event.stopPropagation()">
                                        <form action="{{ route('petugas.peminjaman.approve', $p->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui?')">✓ Setujui</button>
                                        </form>
                                        <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak?')">✗ Tolak</button>
                                        </form>
                                    </div>
                                @elseif($p->status == 'dipinjam' || $p->status == 'terlambat')
                                    <form action="{{ route('petugas.peminjaman.return', $p->id) }}" method="POST" style="display:inline;" onclick="event.stopPropagation()">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Kembalikan?')">📥 Kembalikan</button>
                                    </form>
                                @else
                                    <span style="color:#9ca3af; font-size:11px;">-</span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $details[$i] = [
                                'user_name' => $p->user ? $p->user->name : '-',
                                'user_email' => $p->user ? ($p->user->email ?? '-') : '-',
                                'user_phone' => $p->user ? ($p->user->phone ?? '-') : '-',
                                'book_title' => $p->book ? $p->book->title : 'Tidak ditemukan',
                                'book_author' => $p->book ? $p->book->author : '-',
                                'book_publisher' => $p->book ? ($p->book->publisher ?? '-') : '-',
                                'book_year' => $p->book ? ($p->book->year ?? '-') : '-',
                                'book_cover' => $coverBuku,
                                'tanggal_pinjam' => $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-',
                                'tanggal_kembali' => $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-',
                                'status' => strtoupper($p->status),
                                'created_at' => date('d/m/Y H:i', strtotime($p->created_at)),
                            ];
                        @endphp
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="es-icon">📭</div>
                <h3>Tidak Ada Peminjaman</h3>
                <p>Semua peminjaman sudah diproses</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('detail-panel')
<div class="detail-close">
    <h4>Detail Peminjaman</h4>
    <button class="detail-close-btn" onclick="closeDetail()">✕</button>
</div>

<div id="detailContent">
    <div class="detail-empty">
        <div class="de-icon">📋</div>
        <p>Klik baris data untuk melihat detail</p>
    </div>
</div>

<script>
    const details = @json($details ?? []);

    function showDashboardDetail(idx) {
        const d = details[idx];
        if (!d) return;

        selectRow(document.getElementById('row-' + idx));
        openDetail();

        let statusBadge = '';
        if (d.status === 'PENDING') statusBadge = '<span class="badge badge-pending">Menunggu Konfirmasi</span>';
        else if (d.status === 'DIPINJAM') statusBadge = '<span class="badge badge-success">Sedang Dipinjam</span>';
        else if (d.status === 'DIKEMBALIKAN') statusBadge = '<span class="badge badge-info">Dikembalikan</span>';
        else statusBadge = '<span class="badge badge-danger">' + d.status + '</span>';

        document.getElementById('detailContent').innerHTML = `
            <div class="detail-cover">
                ${d.book_cover ? `<img src="/uploads/covers/${d.book_cover}">` : '📖'}
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Informasi Peminjam</div>
                <div class="detail-row"><span class="dl">Nama</span><span class="dv">${d.user_name}</span></div>
                <div class="detail-row"><span class="dl">Email</span><span class="dv">${d.user_email}</span></div>
                <div class="detail-row"><span class="dl">Telepon</span><span class="dv">${d.user_phone}</span></div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Informasi Buku</div>
                <div class="detail-row"><span class="dl">Judul</span><span class="dv">${d.book_title}</span></div>
                <div class="detail-row"><span class="dl">Penulis</span><span class="dv">${d.book_author}</span></div>
                <div class="detail-row"><span class="dl">Penerbit</span><span class="dv">${d.book_publisher}</span></div>
                <div class="detail-row"><span class="dl">Tahun</span><span class="dv">${d.book_year}</span></div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Status Peminjaman</div>
                <div class="detail-row"><span class="dl">Tgl. Ajuan</span><span class="dv">${d.created_at}</span></div>
                <div class="detail-row"><span class="dl">Tgl. Pinjam</span><span class="dv">${d.tanggal_pinjam}</span></div>
                <div class="detail-row"><span class="dl">Jatuh Tempo</span><span class="dv">${d.tanggal_kembali}</span></div>
                <div class="detail-row"><span class="dl">Status</span><span class="dv">${statusBadge}</span></div>
            </div>
        `;
    }
</script>
@endsection