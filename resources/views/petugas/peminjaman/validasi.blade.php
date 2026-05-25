@extends('layouts.petugas')

@section('title', 'Validasi Peminjaman')
@section('page-title', 'Validasi Peminjaman')

@section('content')
<!-- Search -->
<div class="search-bar no-print">
    <form method="GET" action="{{ route('petugas.peminjaman.validasi') }}" style="display:flex;gap:10px;flex:1;">
        <div class="search-input">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama peminjam atau judul buku...">
        </div>
        <button type="submit" class="btn btn-primary">Cari</button>
        @if($search)
            <a href="{{ route('petugas.peminjaman.validasi') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel -->
<div class="section-card">
    <div class="section-head">
        <h3>Menunggu Konfirmasi ({{ $peminjaman->total() }})</h3>
    </div>
    <div class="section-body" style="padding:0;">
        @if($peminjaman->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl. Pengajuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $i => $p)
                        <tr onclick="showDetail({{ $i }})" id="row-{{ $i }}">
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:#e0f2fe;color:#0369a1;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                                        {{ $p->user ? substr($p->user->name, 0, 1) : '-' }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;">{{ $p->user ? $p->user->name : '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->book ? $p->book->title : '<span style="color:#ef4444;">Buku tidak ditemukan</span>' }}</td>
                            <td style="font-size:12px;color:#6b7280;">
                                {{ date('d/m/Y', strtotime($p->created_at)) }}
                            </td>
                            <td>
                                <span class="badge badge-pending">Menunggu Konfirmasi</span>
                            </td>
                        </tr>
                        @php
                            $details[$i] = [
                                'id' => $p->id,
                                'user_name' => $p->user ? $p->user->name : '-',
                                'user_email' => $p->user ? ($p->user->email ?? '-') : '-',
                                'user_phone' => $p->user ? ($p->user->phone ?? '-') : '-',
                                'book_title' => $p->book ? $p->book->title : 'Tidak ditemukan',
                                'book_author' => $p->book ? $p->book->author : '-',
                                'book_publisher' => $p->book ? ($p->book->publisher ?? '-') : '-',
                                'book_year' => $p->book ? ($p->book->year ?? '-') : '-',
                                'book_isbn' => $p->book ? ($p->book->isbn ?? '-') : '-',
                                'book_category' => $p->book && $p->book->category ? $p->book->category->name : '-',
                                'book_cover' => $p->book ? $p->book->cover : null,
                                'book_stock' => $p->book ? $p->book->stock : 0,
                                'created_at' => date('d F Y, H:i', strtotime($p->created_at)),
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
                <h3>Semua Peminjaman Sudah Diproses</h3>
                <p>Tidak ada peminjaman yang menunggu konfirmasi</p>
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
        <p style="font-size:12px;color:#9ca3af;">Klik baris data untuk melihat detail peminjaman</p>
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
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Penerbit</span>
                        <span style="font-weight:500;color:#374151;">${d.book_publisher}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Tahun</span>
                        <span style="font-weight:500;color:#374151;">${d.book_year}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">ISBN</span>
                        <span style="font-weight:500;color:#374151;">${d.book_isbn}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;border-bottom:1px solid #f3f4f6;font-size:12.5px;">
                        <span style="color:#6b7280;">Kategori</span>
                        <span style="font-weight:500;color:#374151;">${d.book_category}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:9px 14px;font-size:12.5px;">
                        <span style="color:#6b7280;">Stok Tersedia</span>
                        <span style="font-weight:700;color:${d.book_stock > 0 ? '#059669' : '#dc2626'};">${d.book_stock}</span>
                    </div>
                </div>
            </div>

            <!-- Tgl Ajuan -->
            <div style="margin-bottom:20px;">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#9ca3af;margin-bottom:8px;">Waktu Pengajuan</div>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;font-size:12.5px;color:#065f46;">
                    📅 ${d.created_at}
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div style="display:flex;flex-direction:column;gap:8px;">
                <form method="POST" action="/petugas/peminjaman/${d.id}/approve">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit"
                        onclick="return confirm('Setujui peminjaman buku ini untuk ${d.user_name}?')"
                        style="width:100%;padding:11px;background:#059669;color:white;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:background 0.15s;"
                        onmouseover="this.style.background='#047857'"
                        onmouseout="this.style.background='#059669'">
                        ✓ Setujui Peminjaman
                    </button>
                </form>
                <form method="POST" action="/petugas/peminjaman/${d.id}/reject">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit"
                        onclick="return confirm('Tolak peminjaman buku ini untuk ${d.user_name}?')"
                        style="width:100%;padding:11px;background:white;color:#dc2626;border:1px solid #fca5a5;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.15s;"
                        onmouseover="this.style.background='#fef2f2'"
                        onmouseout="this.style.background='white'">
                        ✗ Tolak Peminjaman
                    </button>
                </form>
            </div>
        `;
    }
</script>
@endsection