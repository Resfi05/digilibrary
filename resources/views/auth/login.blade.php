@extends('layouts.auth')

@section('title', 'Masuk - DigiLibrary')

@section('content')
<div class="login-page">
    <div class="login-wrapper">

        {{-- SISI KIRI --}}
        <div class="login-left">
            <div class="login-left-content">
                <a href="{{ route('home') }}" class="login-back">
                    ← Kembali
                </a>
                <div class="login-brand">
                    <div class="login-brand-icon">📚</div>
                    <div>
                        <div class="login-brand-name">DigiLibrary</div>
                        <div class="login-brand-sub">Perpustakaan Digital</div>
                    </div>
                </div>
                <div class="login-left-text">
                    <h2>Akses Ilmu,<br>Kapan Saja,<br>Di Mana Saja</h2>
                    <p>Masuk untuk melanjutkan dan pinjam buku favoritmu.</p>
                </div>

                

                <div class="login-left-stats">
                    <div class="ls-item"><strong>500+</strong><span>Buku</span></div>
                    <div class="ls-divider"></div>
                    <div class="ls-item"><strong>1.2K+</strong><span>Anggota</span></div>
                    <div class="ls-divider"></div>
                    <div class="ls-item"><strong>10+</strong><span>Kategori</span></div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN --}}
        <div class="login-right">
            <div class="login-form-wrap">
                <div class="login-form-header">
                    <h1>Selamat Datang Kembali 👋</h1>
                    <p>Silakan masuk ke akun Anda</p>
                </div>

                @if(session('success'))
                    <div class="login-alert success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="login-alert error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf

                    <div class="lf-group">
                        <label>Email</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">👤</span>
                            <input type="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                class="{{ $errors->has('email') ? 'lf-error' : '' }}"
                                required autofocus>
                        </div>
                    </div>

                    <div class="lf-group">
                        <label>Password</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">🔒</span>
                            <input type="password" id="loginPass" name="password"
                                placeholder="Masukkan password Anda"
                                class="{{ $errors->has('password') ? 'lf-error' : '' }}"
                                required>
                            <button type="button" class="lf-eye" onclick="togglePass('loginPass', this)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="lf-options">
                        <label class="lf-remember">
                            <input type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="lf-forgot">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-masuk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Masuk
                    </button>
                </form>

                <div class="lf-divider"><span>atau masuk cepat sebagai</span></div>

                <div class="lf-demo">
                    <button onclick="fillDemo('admin@digilibrary.id','admin123')" class="btn-demo-login">
                        <span>👨‍💼</span> Admin
                    </button>
                    <button onclick="fillDemo('petugas@digilibrary.id','petugas123')" class="btn-demo-login">
                        <span>👨‍🔧</span> Petugas
                    </button>
                </div>

                <div class="lf-register">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.style.color = input.type === 'text' ? 'var(--primary)' : '';
}
function fillDemo(email, pass) {
    document.querySelector('input[name="email"]').value = email;
    document.getElementById('loginPass').value = pass;
}
</script>
@endpush
@endsection