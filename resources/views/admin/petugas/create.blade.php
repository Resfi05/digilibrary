@extends('layouts.admin')

@section('title', 'Tambah Petugas')
@section('page-title', 'Tambah Petugas Baru')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h2>Form Tambah Petugas</h2>
        <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.petugas.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                @error('name')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com" required>
                @error('email')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color: red;">*</span></label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                @error('password')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password <span style="color: red;">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                @error('phone')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" rows="3" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">💾 Simpan Petugas</button>
                <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection