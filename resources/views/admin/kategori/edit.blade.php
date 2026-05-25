@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h2>Form Edit Kategori</h2>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori.update', $kategori->id) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $kategori->name) }}" placeholder="Masukkan nama kategori" required>
                @error('name')
                    <span class="form-text" style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" placeholder="Deskripsi kategori (opsional)">{{ old('description', $kategori->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">💾 Update</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection