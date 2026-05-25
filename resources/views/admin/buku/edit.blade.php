@extends('layouts.admin')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')
@section('page-sub', 'Perbarui informasi buku')

@push('styles')
<style>
    .form-card { background:white;border-radius:14px;padding:28px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);max-width:800px; }
    .form-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
    .form-group { display:flex;flex-direction:column;gap:6px; }
    .form-group.full { grid-column:1/-1; }
    .form-label { font-size:.82rem;font-weight:700;color:#374151; }
    .form-input { padding:11px 14px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.9rem;outline:none;transition:all .25s;background:#fafafa;width:100%; }
    .form-input:focus { border-color:#1a56db;background:white;box-shadow:0 0 0 3px rgba(26,86,219,.08); }
    .form-error { font-size:.75rem;color:#ef4444;margin-top:3px; }
    .cover-upload { border:2px dashed #e5e7eb;border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all .25s;background:#fafafa; }
    .cover-upload:hover { border-color:#1a56db;background:#f0f9ff; }
    .cover-preview { width:100%;max-height:200px;object-fit:contain;border-radius:8px;margin-top:10px; }
    .form-actions { display:flex;gap:10px;margin-top:20px;padding-top:20px;border-top:1px solid #f3f4f6; }
    .btn-save { padding:12px 28px;border-radius:10px;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;border:none;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer; }
    .btn-cancel { padding:12px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:white;font-family:inherit;font-size:.9rem;font-weight:600;cursor:pointer;color:#374151;text-decoration:none;display:flex;align-items:center; }
    .btn-cancel:hover { background:#f3f4f6; }
    .breadcrumb { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#94a3b8;margin-bottom:16px; }
    .breadcrumb a { color:#1a56db;text-decoration:none; }
</style>
@endpush

@section('content')

<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span>›</span>
    <a href="{{ route('admin.buku.index') }}">Daftar Buku</a>
    <span>›</span><span>Edit Buku</span>
</div>

<div class="form-card">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
        {{-- Cover saat ini --}}
        @if($buku->gambar)
        <img src="{{ asset('storage/'.$buku->gambar) }}"
            style="width:52px;height:68px;border-radius:8px;object-fit:cover;box-shadow:0 2px 8px rgba(0,0,0,.1)">
        @endif
        <div>
            <h2 style="font-size:1.1rem;font-weight:800;color:#111827;margin-bottom:4px">✏️ Edit: {{ $buku->judul }}</h2>
            <p style="font-size:.875rem;color:#64748b">Perbarui informasi buku di bawah ini.</p>
        </div>
    </div>

    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c;padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:20px">
        @foreach($errors->all() as $e)<div>✕ {{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">

            <div class="form-group full">
                <label class="form-label">Judul Buku *</label>
                <input type="text" name="judul" class="form-input"
                    value="{{ old('judul', $buku->judul) }}" required>
                @error('judul')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Penulis *</label>
                <input type="text" name="penulis" class="form-input"
                    value="{{ old('penulis', $buku->penulis) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Penerbit *</label>
                <input type="text" name="penerbit" class="form-input"
                    value="{{ old('penerbit', $buku->penerbit) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori *</label>
                <select name="kategori_id" class="form-input" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('kategori_id', $buku->kategori_id) == $cat->id ? 'selected':'' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" class="form-input"
                    value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                    min="1900" max="{{ date('Y') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-input"
                    value="{{ old('isbn', $buku->isbn) }}" placeholder="978-xxx-xxx-xxx-x">
            </div>

            <div class="form-group">
                <label class="form-label">Stok *</label>
                <input type="number" name="stok" class="form-input"
                    value="{{ old('stok', $buku->stok) }}" min="0" required>
            </div>

            <div class="form-group full">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-input" rows="4">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
            </div>

            <div class="form-group full">
                <label class="form-label">Ganti Cover
                    <span style="color:#94a3b8;font-weight:400">(kosongkan jika tidak ingin ganti)</span>
                </label>
                @if($buku->gambar)
                <div style="margin-bottom:10px">
                    <div style="font-size:.75rem;color:#94a3b8;margin-bottom:6px">Cover saat ini:</div>
                    <img src="{{ asset('storage/'.$buku->gambar) }}"
                        style="height:80px;border-radius:8px;object-fit:cover">
                </div>
                @endif
                <div class="cover-upload" onclick="document.getElementById('gambarInput').click()">
                    <div style="font-size:2rem;margin-bottom:8px">🖼️</div>
                    <div style="font-size:.875rem;font-weight:600;color:#374151;margin-bottom:4px">
                        Klik untuk {{ $buku->gambar ? 'ganti' : 'upload' }} cover
                    </div>
                    <div style="font-size:.75rem;color:#94a3b8">JPG, PNG, WEBP · Maks. 2MB</div>
                    <img id="coverPreview" class="cover-preview" style="display:none">
                </div>
                <input type="file" id="gambarInput" name="gambar" accept="image/*" style="display:none"
                    onchange="previewCover(this)">
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.buku.index') }}" class="btn-cancel">← Batal</a>
            <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function previewCover(input) {
    const preview = document.getElementById('coverPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush