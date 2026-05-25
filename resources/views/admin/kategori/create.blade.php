@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori Baru')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h2>Form Tambah Kategori</h2>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama kategori" required>
                @error('name')
                    <span class="form-text" style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection