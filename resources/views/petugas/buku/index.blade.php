@extends('layouts.petugas')

@section('title', 'Kelola Data Buku')
@section('page-title', 'Kelola Data Buku')

@section('content')
<!-- Statistik -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Total Buku</div>
            <div style="font-size:24px;font-weight:700;color:#111827;">{{ $totalBooks }}</div>
        </div>
    </div>
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Tersedia</div>
            <div style="font-size:24px;font-weight:700;color:#16a34a;">{{ $availableBooks }}</div>
        </div>
    </div>
    <div class="section-card" style="margin:0;">
        <div class="section-body" style="padding:20px;">
            <div style="font-size:13px;color:#6b7280;margin-bottom:4px;">Dipinjam</div>
            <div style="font-size:24px;font-weight:700;color:#f59e0b;">{{ $borrowedBooks }}</div>
        </div>
    </div>
</div>

<!-- Filter & Aksi -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;gap:16px;flex-wrap:wrap;">
    <div style="display:flex;gap:10px;align-items:center;">
        <select id="filterKategori" style="padding:9px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;outline:none;min-width:180px;cursor:pointer;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request()->get('kategori') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nama_kategori }}
                </option>
            @endforeach
        </select>
        <button onclick="applyFilter()" class="btn btn-outline" style="padding:9px 20px;font-size:13px;">Filter</button>
    </div>
    <a href="{{ route('petugas.buku.create') }}" class="btn btn-primary" style="padding:9px 20px;font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        ➕ Tambah Buku
    </a>
</div>

<!-- Tabel Buku -->
<div class="section-card">
    <div class="section-head">
        <h3>📖 Daftar Buku</h3>
    </div>
    <div class="section-body" style="padding:0;overflow-x:auto;">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">No</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Judul Buku</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Penulis</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Kategori</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Stok</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $index => $buku)
                <tr class="table-row" onclick="openDetail()" style="cursor:pointer;transition:background 0.15s;">
                    <td style="padding:14px 20px;font-size:13px;color:#6b7280;border-bottom:1px solid #f3f4f6;">{{ $index + 1 }}</td>
                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if($buku->cover)
                                <img src="{{ asset('storage/' . $buku->cover) }}" style="width:36px;height:50px;object-fit:cover;border-radius:4px;box-shadow:0 1px 2px rgba(0,0,0,0.1);">
                            @else
                                <div style="width:36px;height:50px;background:#e5e7eb;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:12px;">📖</div>
                            @endif
                            <div>
                                <div style="font-size:13.5px;font-weight:600;color:#111827;">{{ $buku->title }}</div>
                                <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $buku->isbn ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 20px;font-size:13px;color:#374151;border-bottom:1px solid #f3f4f6;">{{ $buku->author }}</td>
                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
                        <span style="background:#f3f4f6;color:#374151;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500;">
                            {{ $buku->category->nama_kategori ?? '-' }}
                        </span>
                    </td>
                    <td style="padding:14px 20px;text-align:center;font-size:13px;font-weight:600;color:#111827;border-bottom:1px solid #f3f4f6;">{{ $buku->stock }}</td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        @if($buku->stock > 0)
                            <span style="background:#dcfce7;color:#16a34a;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">Tersedia</span>
                        @else
                            <span style="background:#fef2f2;color:#dc2626;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">Habis</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;gap:6px;justify-content:center;" onclick="event.stopPropagation();">
                            <a href="{{ route('petugas.buku.edit', $buku->id) }}" title="Edit" style="width:32px;height:32px;border:1px solid #e5e7eb;border-radius:6px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.15s;text-decoration:none;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#93c5fd'" onmouseout="this.style.background='white';this.style.borderColor='#e5e7eb'">
                                ✏️
                            </a>
                            <button onclick="confirmDelete('{{ route('petugas.buku.destroy', $buku->id) }}')" title="Hapus" style="width:32px;height:32px;border:1px solid #e5e7eb;border-radius:6px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.15s;" onmouseover="this.style.background='#fef2f2';this.style.borderColor='#fca5a5'" onmouseout="this.style.background='white';this.style.borderColor='#e5e7eb'">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:60px 20px;text-align:center;">
                        <div style="font-size:40px;margin-bottom:12px;">📚</div>
                        <p style="font-size:14px;color:#6b7280;margin:0;">Belum ada data buku</p>
                        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0;">Klik tombol "Tambah Buku" untuk menambahkan data baru</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('detail-panel')
<!-- Panel detail bisa dikosongkan atau diisi info buku saat diklik nanti -->
@endsection

<script>
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('mouseover', function() { this.style.background = '#f9fafb'; });
        row.addEventListener('mouseout', function() { this.style.background = ''; });
    });

    function applyFilter() {
        let kategoriId = document.getElementById('filterKategori').value;
        let url = '{{ route("petugas.buku.index") }}?kategori=' + kategoriId;
        window.location.href = url;
    }
</script>