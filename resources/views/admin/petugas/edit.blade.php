@extends('layouts.admin')

@section('title', 'Edit Petugas')
@section('page-title', 'Edit Data Petugas')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h2>Form Edit Petugas</h2>
        <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.petugas.update', $petugas->id) }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $petugas->name) }}" required>
                @error('name')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $petugas->email) }}" required>
                @error('email')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                @error('password')<span class="form-text" style="color: red;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $petugas->phone) }}">
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $petugas->address) }}</textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $petugas->is_active) ? 'checked' : '' }}>
                    Petugas Aktif
                </label>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">💾 Update Petugas</button>
                <a href="{{ route('admin.petugas.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection