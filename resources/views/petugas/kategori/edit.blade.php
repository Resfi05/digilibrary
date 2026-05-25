@extends('layouts.petugas')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div style="display:flex;gap:24px;align-items:flex-start;">

    <!-- Kiri: Form -->
    <div style="flex:1;min-width:0;">
        <div class="section-card">
            <div class="section-head">
                <h3>📁 Edit Data Kategori</h3>
            </div>
            <div class="section-body" style="padding:24px;">
                
                {{-- Menggunakan $kategori langsung (bukan $kategori->id) untuk menghindari error --}}
                <form method="POST" action="{{ url('/petugas/kategori/' . request()->segment(3)) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group" style="margin-bottom:20px;">
                        <label>Nama Kategori <span class="req">*</span></label>
                        <input type="text" name="nama_kategori" class="form-input" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" placeholder="Masukkan nama kategori" required autofocus>
                        @error('nama_kategori')
                            <span class="form-hint" style="color:#dc2626;">{{ $message }}</span>
                        @enderror
                        <span class="form-hint">Pastikan nama kategori bersifat umum (contoh: Fiksi, Sejarah, Teknologi)</span>
                    </div>

                    <div style="display:flex;gap:10px; margin-top:30px;">
                        <button type="submit" class="btn btn-primary" style="padding:10px 24px;font-size:13px;">
                            💾 Simpan Perubahan
                        </button>
                        <a href="{{ route('petugas.kategori.index') }}" class="btn btn-outline" style="padding:10px 24px;font-size:13px;">
                            Batal
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Kanan: Info Tambahan -->
    <div style="width:280px;flex-shrink:0;">
        <div class="section-card">
            <div class="section-head">
                <h3>💡 Informasi</h3>
            </div>
            <div class="section-body" style="padding:20px;">
                <div style="background:#f9fafb;border-radius:8px;padding:16px;margin-bottom:16px;">
                    <p style="font-size:12px;color:#6b7280;line-height:1.7;margin:0;">
                        <span style="font-weight:600;color:#111827;display:block;margin-bottom:6px;">ID Kategori:</span>
                        <span style="background:#e5e7eb;color:#374151;padding:2px 8px;border-radius:4px;font-family:monospace;">{{ $kategori->id }}</span>
                    </p>
                </div>
                
                <div style="background:#f9fafb;border-radius:8px;padding:16px;margin-bottom:16px;">
                    <p style="font-size:12px;color:#6b7280;line-height:1.7;margin:0;">
                        <span style="font-weight:600;color:#111827;display:block;margin-bottom:6px;">Total Buku:</span>
                        <span style="font-size:18px;font-weight:700;color:#1d4ed8;">{{ $kategori->books()->count() }}</span> buku
                    </p>
                </div>

                <div class="tips-box">
                    <h4 style="margin-top:0;">Tips Mengedit</h4>
                    <ul>
                        <li>Hindari nama kategori yang duplikat</li>
                        <li>Gunakan huruf kapital di awal kata</li>
                        <li>Perubahan nama akan langsung terupdate di data buku</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('detail-panel')
@endsection