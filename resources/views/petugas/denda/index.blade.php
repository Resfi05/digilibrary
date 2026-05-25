@extends('layouts.petugas')

@section('title', 'Kelola Denda')
@section('page-title', 'Kelola Denda')

@section('content')
<!-- Stats Denda -->
<div class="stats-row" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-box" style="border-left: 4px solid #2563eb;">
        <div>
            <div class="stat-val" style="font-size:16px;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
            <div class="stat-lbl">Total Denda</div>
        </div>
    </div>
    <div class="stat-box" style="border-left: 4px solid #ef4444;">
        <div>
            <div class="stat-val" style="font-size:16px;color:#dc2626;">Rp {{ number_format($belumBayar, 0, ',', '.') }}</div>
            <div class="stat-lbl">Belum Dibayar ({{ $countBelum }})</div>
        </div>
    </div>
    <div class="stat-box" style="border-left: 4px solid #059669;">
        <div>
            <div class="stat-val" style="font-size:16px;color:#059669;">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</div>
            <div class="stat-lbl">Sudah Dibayar ({{ $countSudah }})</div>
        </div>
    </div>
    <div class="stat-box" style="border-left: 4px solid #f59e0b;">
        <div>
            <div class="stat-val" style="font-size:16px;">Rp {{ number_format($rataRata, 0, ',', '.') }}</div>
            <div class="stat-lbl">Rata-rata / Transaksi</div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs no-print">
    <a href="{{ route('petugas.denda.index', ['tab' => 'belum']) }}" class="tab-link {{ $tab == 'belum' ? 'active' : '' }}">Belum Dibayar</a>
    <a href="{{ route('petugas.denda.index', ['tab' => 'sudah']) }}" class="tab-link {{ $tab == 'sudah' ? 'active' : '' }}">Sudah Dibayar</a>
    <a href="{{ route('petugas.denda.index', ['tab' => 'semua']) }}" class="tab-link {{ $tab == 'semua' ? 'active' : '' }}">Semua</a>
</div>

<div class="section-card">
    <div class="section-head">
        <h3>{{ $tab == 'belum' ? 'Belum Dibayar' : ($tab == 'sudah' ? 'Sudah Dibayar' : 'Semua Denda') }} ({{ $peminjaman->total() }})</h3>
    </div>
    <div class="section-body">
        @if($peminjaman->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $i => $p)
                        @php
                            $denda = $p->hitungDenda();
                            $p->denda = $denda; // override dengan hitungan baru
                        @endphp
                        <tr onclick="showDendaDetail({{ $i }})" id="row-{{ $i }}">
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td><strong>{{ $p->user->name }}</strong></td>
                            <td>{{ $p->book->title }}</td>
                            <td>{{ $p->tanggal_dikembalikan ? date('d/m/Y', strtotime($p->tanggal_dikembalikan)) : '-' }}</td>
                            <td>
                                <span style="color:#dc2626;font-weight:700;font-size:13px;">
                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($p->bayar_denda)
                                    <span class="badge badge-success">Sudah Dibayar</span>
                                @else
                                    <span class="badge badge-danger">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>
                                @if(!$p->bayar_denda)
                                    <div class="action-btns" onclick="event.stopPropagation()">
                                        <form action="{{ route('petugas.denda.bayar', $p->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai sudah dibayar?')">✓ Bayar</button>
                                        </form>
                                    </div>
                                @else
                                    <span style="color:#9ca3af;font-size:11px;">-</span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $details[$i] = [
                                'id' => $p->id,
                                'user_name' => $p->user->name,
                                'user_email' => $p->user->email ?? '-',
                                'book_title' => $p->book->title,
                                'book_author' => $p->book->author,
                                'book_cover' => $p->book->cover,
                                'tanggal_pinjam' => $p->tanggal_pinjam ? date('d/m/Y', strtotime($p->tanggal_pinjam)) : '-',
                                'tanggal_kembali' => $p->tanggal_kembali ? date('d/m/Y', strtotime($p->tanggal_kembali)) : '-',
                                'tanggal_dikembalikan' => $p->tanggal_dikembalikan ? date('d/m/Y', strtotime($p->tanggal_dikembalikan)) : '-',
                                'denda' => 'Rp ' . number_format($denda, 0, ',', '.'),
                                'bayar_denda' => $p->bayar_denda,
                                'canBayar' => !$p->bayar_denda,
                            ];
                        @endphp
                    @endforeach
                </tbody>
            </table>
            {{ $peminjaman->links() }}
        @else
            <div class="empty-state">
                <div class="es-icon">💰</div>
                <h3>Tidak Ada Denda</h3>
                <p>{{ $tab == 'belum' ? 'Tidak ada denda yang belum dibayar' : 'Belum ada data denda' }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('detail-panel')
<div class="detail-close">
    <h4>Detail Denda</h4>
    <button class="detail-close-btn" onclick="closeDetail()">✕</button>
</div>

<div id="detailContent">
    <div class="detail-empty">
        <div class="de-icon">💰</div>
        <p>Klik baris untuk melihat detail</p>
    </div>
</div>

<script>
    const details = @json($details ?? []);

    function showDendaDetail(idx) {
        const d = details[idx];
        if (!d) return;
        selectRow(document.getElementById('row-' + idx));
        openDetail();

        document.getElementById('detailContent').innerHTML = `
            <div class="detail-cover">
                ${d.book_cover ? `<img src="/uploads/covers/${d.book_cover}">` : '📖'}
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Informasi Peminjam</div>
                <div class="detail-row"><span class="dl">Nama</span><span class="dv">${d.user_name}</span></div>
                <div class="detail-row"><span class="dl">Email</span><span class="dv">${d.user_email}</span></div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Informasi Buku</div>
                <div class="detail-row"><span class="dl">Judul</span><span class="dv">${d.book_title}</span></div>
                <div class="detail-row"><span class="dl">Penulis</span><span class="dv">${d.book_author}</span></div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title">Detail Waktu</div>
                <div class="detail-row"><span class="dl">Tgl Pinjam</span><span class="dv">${d.tanggal_pinjam}</span></div>
                <div class="detail-row"><span class="dl">Jatuh Tempo</span><span class="dv">${d.tanggal_kembali}</span></div>
                <div class="detail-row"><span class="dl">Tgl Dikembalikan</span><span class="dv">${d.tanggal_dikembalikan}</span></div>
            </div>

            <div class="detail-section" style="background:${d.bayar_denda ? '#f0fdf4' : '#fef2f2'};padding:12px;border-radius:8px;">
                <div class="detail-row"><span class="dl">Jumlah Denda</span><span class="dv" style="color:#dc2626;font-size:16px;font-weight:800;">${d.denda}</span></div>
                <div class="detail-row"><span class="dl">Status</span><span class="dv">${d.bayar_denda ? '<span class="badge badge-success">Sudah Dibayar</span>' : '<span class="badge badge-danger">Belum Dibayar</span>'}</span></div>
            </div>

            ${d.canBayar ? `
            <div class="detail-actions">
                <form method="POST" action="/petugas/denda/${d.id}/bayar" style="flex:1;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-success" style="width:100%;" onclick="return confirm('Tandai sudah dibayar?')">✓ Tandai Sudah Dibayar</button>
                </form>
            </div>
            ` : ''}
        `;
    }
</script>
@endsection