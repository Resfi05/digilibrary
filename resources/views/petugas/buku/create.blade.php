@extends('layouts.petugas')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')

@section('content')
{{-- FORM DIPINDAH KE SINI SUPAYA MEMBUNGKUS SEMUA INPUT (KIRI DAN KANAN) --}}
<form method="POST" action="{{ route('petugas.buku.store') }}" enctype="multipart/form-data" id="formBuku" style="display:flex;gap:24px;align-items:flex-start;width:100%;">
    @csrf

    <!-- Kiri: Form -->
    <div style="flex:1;min-width:0;">

        <!-- Informasi Buku -->
        <div class="section-card" style="margin-bottom:20px;">
            <div class="section-head">
                <h3>Informasi Buku</h3>
            </div>
            <div class="section-body" style="padding:20px;">
                <div class="form-row">
                    <div class="form-group">
                        <label>Judul Buku <span class="req">*</span></label>
                        <input type="text" name="judul" class="form-input" value="{{ old('judul') }}" placeholder="Masukkan judul buku" required>
                        @error('judul')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Penulis <span class="req">*</span></label>
                        <input type="text" name="penulis" class="form-input" value="{{ old('penulis') }}" placeholder="Nama penulis" required>
                        @error('penulis')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Penerbit <span class="req">*</span></label>
                        <input type="text" name="penerbit" class="form-input" value="{{ old('penerbit') }}" placeholder="Nama penerbit" required>
                        @error('penerbit')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Tahun Terbit <span class="req">*</span></label>
                        <input type="number" name="tahun_terbit" class="form-input" value="{{ old('tahun_terbit', date('Y')) }}" min="1900" max="{{ date('Y') }}" required>
                        @error('tahun_terbit')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="text" name="isbn" class="form-input" value="{{ old('isbn') }}" placeholder="ISBN (opsional)">
                        @error('isbn')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="req">*</span></label>
                        <select name="kategori_id" class="form-input" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Stok -->
        <div class="section-card" style="margin-bottom:20px;">
            <div class="section-head">
                <h3>Informasi Stok</h3>
            </div>
            <div class="section-body" style="padding:20px;">
                <div class="form-group">
                    <label>Jumlah Stok <span class="req">*</span></label>
                    <input type="number" name="stok" class="form-input" value="{{ old('stok', 1) }}" min="0" style="max-width:200px;" required>
                    @error('stok')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                    <span class="form-hint">Jumlah buku yang tersedia untuk dipinjam</span>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="padding:11px 28px;font-size:14px;">
                💾 Simpan Buku
            </button>
            <a href="{{ route('petugas.buku.index') }}" class="btn btn-outline" style="padding:11px 28px;font-size:14px;">
                Batal
            </a>
        </div>

    </div>

    <!-- Kanan: Upload Cover -->
    <div style="width:280px;flex-shrink:0;">
        <div class="section-card">
            <div class="section-head">
                <h3>Sampul Buku</h3>
            </div>
            <div class="section-body" style="padding:20px;text-align:center;">

                <!-- Preview Area -->
                <div id="coverPreview" style="width:100%;height:320px;background:#f9fafb;border:2px dashed #d1d5db;border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;overflow:hidden;position:relative;margin-bottom:16px;">
                    <input type="file" name="gambar" accept="image/*" onchange="previewCover(this)" style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                    <div id="coverPlaceholder">
                        <div style="width:64px;height:64px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:12px;">
                            <span style="font-size:28px;color:#9ca3af;">📷</span>
                        </div>
                        <p style="font-size:13px;font-weight:600;color:#374151;margin-bottom:4px;">Upload Sampul</p>
                        <p style="font-size:11px;color:#9ca3af;">Klik untuk memilih gambar</p>
                    </div>
                    <img id="coverImage" src="" style="width:100%;height:100%;object-fit:cover;display:none;position:absolute;inset:0;">
                </div>

                <!-- Info -->
                <div style="background:#f9fafb;border-radius:8px;padding:12px;margin-bottom:16px;text-align:left;">
                    <p style="font-size:11px;color:#6b7280;line-height:1.6;margin:0;">
                        <span style="font-weight:600;color:#374151;">Format:</span> JPG, JPEG, PNG<br>
                        <span style="font-weight:600;color:#374151;">Ukuran Maks:</span> 2MB<br>
                        <span style="font-weight:600;color:#374151;">Rasio:</span> 2:3 (disarankan)
                    </p>
                </div>

                <!-- Tombol Hapus -->
                <button type="button" id="hapusCover" onclick="hapusCoverPreview()" style="display:none;width:100%;padding:9px;background:white;color:#dc2626;border:1px solid #fca5a5;border-radius:8px;font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.15px;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                    🗑️ Hapus Sampul
                </button>

            </div>
        </div>
    </div>


@endsection

@section('detail-panel')
<div class="tips-box">
    <h4>💡 Tips Menambah Buku</h4>
    <ul>
        <li>Isi data buku dengan lengkap dan akurat</li>
        <li>ISBN bersifat opsional tapi membantu identifikasi</li>
        <li>Upload cover buku dengan ukuran yang proporsional</li>
        <li>Buku yang ditambahkan akan langsung tampil di katalog</li>
        <li>Pastikan stok buku sesuai ketersediaan fisik</li>
        <li>Gambar cover akan ditampilkan di katalog buku</li>
    </ul>
</div>
@endsection

<script>
    function previewCover(input) {
        var preview = document.getElementById('coverPreview');
        var image = document.getElementById('coverImage');
        var placeholder = document.getElementById('coverPlaceholder');
        var hapusBtn = document.getElementById('hapusCover');

        if (input.files && input.files[0]) {
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                image.style.display = 'block';
                placeholder.style.display = 'none';
                preview.style.border = '2px solid #059669';
                preview.style.background = '#f0fdf4';
                hapusBtn.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function hapusCoverPreview() {
        var preview = document.getElementById('coverPreview');
        var image = document.getElementById('coverImage');
        var placeholder = document.getElementById('coverPlaceholder');
        var hapusBtn = document.getElementById('hapusCover');

        image.src = '';
        image.style.display = 'none';
        placeholder.style.display = 'block';
        preview.style.border = '2px dashed #d1d5db';
        preview.style.background = '#f9fafb';
        hapusBtn.style.display = 'none';

        var fileInput = preview.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';
    }
</script>