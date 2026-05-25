@extends('layouts.petugas')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div style="max-width: 600px;">

    <!-- Profile Header -->
    <div class="section-card">
        <div class="section-body" style="padding: 24px; text-align: center;">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#06b6d4,#059669);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;color:white;margin:0 auto 14px;">
                {{ substr($petugas->name, 0, 1) }}
            </div>
            <h3 style="font-size:18px;font-weight:700;">{{ $petugas->name }}</h3>
            <p style="font-size:13px;color:#6b7280;">Petugas Perpustakaan</p>
            <span class="badge badge-success" style="margin-top:8px;">Aktif</span>
        </div>
    </div>

    <!-- Edit Profile -->
    <div class="section-card">
        <div class="section-head">
            <h3>✏️ Edit Profil</h3>
        </div>
        <div class="section-body" style="padding: 20px;">
            <form method="POST" action="{{ route('petugas.profil.update') }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Lengkap <span class="req">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $petugas->name) }}" required>
                    @error('name')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $petugas->email) }}" required>
                    @error('email')<span class="form-hint" style="color:#dc2626;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone', $petugas->phone) }}">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address" class="form-input" rows="2">{{ old('address', $petugas->address) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">💾 Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="section-card">
        <div class="section-head">
            <h3>🔒 Ubah Password</h3>
        </div>
        <div class="section-body" style="padding: 20px;">
            <form method="POST" action="{{ route('petugas.profil.password') }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Password Lama <span class="req">*</span></label>
                    <input type="password" name="password_lama" class="form-input" placeholder="Masukkan password lama" required>
                </div>
                <div class="form-group">
                    <label>Password Baru <span class="req">*</span></label>
                    <input type="password" name="password_baru" class="form-input" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru <span class="req">*</span></label>
                    <input type="password" name="password_baru_confirmation" class="form-input" placeholder="Ulangi password baru" required>
                </div>
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">🔒 Ubah Password</button>
            </form>
        </div>
    </div>

    <!-- Info -->
    <div class="section-card">
        <div class="section-body" style="padding: 16px;">
            <div class="detail-row"><span class="dl">Email</span><span class="dv">{{ $petugas->email }}</span></div>
            <div class="detail-row"><span class="dl">Terdaftar Sejak</span><span class="dv">{{ date('d/m/Y', strtotime($petugas->created_at)) }}</span></div>
            <div class="detail-row"><span class="dl">Terakhir Update</span><span class="dv">{{ date('d/m/Y H:i', strtotime($petugas->updated_at)) }}</span></div>
        </div>
    </div>

</div>
@endsection

@section('detail-panel')
<div class="detail-empty">
    <div class="de-icon">⚙️</div>
    <p>Edit profil di halaman utama</p>
</div>
@endsection