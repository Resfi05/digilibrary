@extends('layouts.petugas')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div class="section-card">
    <div class="section-head">
        <h3>📁 Daftar Kategori</h3>
        <span style="font-size:12px;color:#6b7280;">{{ $categories->count() }} kategori</span>
    </div>
    <div class="section-body" style="padding:0;overflow-x:auto;">
        <table class="data-table" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">No</th>
                    <th style="padding:14px 20px;text-align:left;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Nama Kategori</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Jumlah Buku</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Status</th>
                    <th style="padding:14px 20px;text-align:center;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;border-bottom:2px solid #e5e7eb;background:#f9fafb;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $kategori)
                <tr class="table-row" style="cursor:pointer;transition:background 0.15s;">
                    <td style="padding:14px 20px;font-size:13px;color:#6b7280;border-bottom:1px solid #f3f4f6;">{{ $index + 1 }}</td>
                    <td style="padding:14px 20px;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-size:14px;font-weight:700;">
                                {{ substr($kategori->nama_kategori, 0, 1) }}
                            </div>
                            <span style="font-size:13.5px;font-weight:600;color:#111827;">{{ $kategori->nama_kategori }}</span>
                        </div>
                    </td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        <span style="background:#eff6ff;color:#2563eb;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                            {{ $kategori->books->count() }} buku
                        </span>
                    </td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        <span style="background:#dcfce7;color:#16a34a;padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">Aktif</span>
                    </td>
                    <td style="padding:14px 20px;text-align:center;border-bottom:1px solid #f3f4f6;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button onclick="window.location.href='{{ url('petugas/kategori') }}/{{ $kategori->id }}/edit'" title="Edit" style="width:32px;height:32px;border:1px solid #e5e7eb;border-radius:6px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.15s;" onmouseover="this.style.background='#eff6ff';this.style.borderColor='#93c5fd'" onmouseout="this.style.background='white';this.style.borderColor='#e5e7eb'">✏️</button>
                            
                            <form action="{{ url('petugas/kategori/'. $kategori->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" style="width:32px;height:32px;border:1px solid #e5e7eb;border-radius:6px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.15s;" onmouseover="this.style.background='#fef2f2';this.style.borderColor='#fca5a5'" onmouseout="this.style.background='white';this.style.borderColor='#e5e7eb'">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:60px 20px;text-align:center;">
                        <div style="font-size:40px;margin-bottom:12px;">📂</div>
                        <p style="font-size:14px;color:#6b7280;margin:0;">Belum ada kategori</p>
                        <p style="font-size:12px;color:#9ca3af;margin:4px 0 0;">Gunakan form di samping untuk menambahkan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('detail-panel')
<div class="section-card" style="margin-bottom:0;">
    <div class="section-head">
        <h3>➕ Tambah Kategori</h3>
    </div>
    <div class="section-body" style="padding:24px;">
        <form method="POST" action="{{ route('petugas.kategori.store') }}">
            @csrf
            <div class="form-group" style="margin-bottom:24px;">
                <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">
                    Nama Kategori <span style="color:#dc2626;">*</span>
                </label>
                <input type="text" name="nama_kategori" class="form-input" value="{{ old('nama_kategori') }}" placeholder="Contoh: Fiksi, Non-Fiksi..." required style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;transition:border 0.15s;outline:none;" onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'" onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">
                @error('nama_kategori')
                <span style="font-size:12px;color:#dc2626;margin-top:4px;display:block;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;font-size:14px;font-weight:600;border:none;border-radius:8px;cursor:pointer;transition:all 0.15s;background:#6366f1;color:white;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                💾 Simpan Kategori
            </button>
        </form>
    </div>
</div>

<div style="margin-top:20px;background:#f9fafb;border-radius:10px;padding:16px;">
    <h4 style="font-size:13px;font-weight:600;color:#374151;margin:0 0 10px;">💡 Tips</h4>
    <ul style="margin:0;padding-left:16px;font-size:12px;color:#6b7280;line-height:1.8;">
        <li>Buat nama kategori yang singkat dan jelas</li>
        <li>Kategori yang memiliki buku tidak bisa dihapus</li>
    </ul>
</div>
@endsection

<script>
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('mouseover', function() { this.style.background = '#f9fafb'; });
        row.addEventListener('mouseout', function() { this.style.background = ''; });
    });
</script>