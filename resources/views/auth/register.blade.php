@extends('layouts.auth')

@section('title', 'Daftar - DigiLibrary')

@section('content')
<div class="register-page">
    <div class="register-wrapper">

        {{-- SISI KIRI --}}
        <div class="register-left">
            <div class="register-left-content">
                <a href="{{ route('home') }}" class="login-back">← Kembali</a>

                <div class="login-brand">
                    <div class="login-brand-icon">📚</div>
                    <div>
                        <div class="login-brand-name">DigiLibrary</div>
                        <div class="login-brand-sub">Perpustakaan Digital</div>
                    </div>
                </div>

                <div class="login-left-text">
                    <h2>Bergabung<br>Bersama Kami!</h2>
                    <p>Daftar sekarang dan nikmati akses ke ratusan koleksi buku digital pilihan secara gratis.</p>
                </div>

                

                <!-- Benefit list -->
                <div class="reg-benefits">
                    <div class="reg-benefit"><span class="rb-icon">✓</span><span>Gratis selamanya</span></div>
                    <div class="reg-benefit"><span class="rb-icon">✓</span><span>Akses 500+ koleksi buku</span></div>
                    <div class="reg-benefit"><span class="rb-icon">✓</span><span>Pinjam & kembalikan online</span></div>
                    <div class="reg-benefit"><span class="rb-icon">✓</span><span>Notifikasi status real-time</span></div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN --}}
        <div class="register-right">
            <div class="register-form-wrap">
                <div class="login-form-header">
                    <h1>Buat Akun Baru 🚀</h1>
                    <p>Isi data di bawah untuk mendaftar</p>
                </div>

                @if($errors->any())
                    <div class="login-alert error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="register-form">
                    @csrf

                    <div class="rf-row">
                        <div class="lf-group">
                            <label>Nama Lengkap</label>
                            <div class="lf-input-wrap">
                                <span class="lf-icon">👤</span>
                                <input type="text" name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap kamu"
                                    required autofocus>
                            </div>
                        </div>
                        <div class="lf-group">
                            <label>No. Telepon</label>
                            <div class="lf-input-wrap">
                                <span class="lf-icon">📱</span>
                                <input type="text" name="no_telp"
                                    value="{{ old('no_telp') }}"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="lf-group">
                        <label>Email</label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon">✉️</span>
                            <input type="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                required>
                        </div>
                    </div>

                    <div class="lf-group">
                        <label>Alamat <span style="color:var(--gray-400);font-weight:400">(opsional)</span></label>
                        <div class="lf-input-wrap">
                            <span class="lf-icon" style="top:14px">📍</span>
                            <textarea name="alamat" placeholder="Alamat lengkap kamu" rows="2"
                                style="padding:12px 14px 12px 44px;border:2px solid #e5e7eb;border-radius:10px;font-family:inherit;font-size:.9rem;width:100%;resize:none;background:#fafafa;outline:none;transition:all .25s"
                                onfocus="this.style.borderColor='var(--primary)';this.style.background='white'"
                                onblur="this.style.borderColor='#e5e7eb';this.style.background='#fafafa'"
                                >{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <div class="rf-row">
                        <div class="lf-group">
                            <label>Password</label>
                            <div class="lf-input-wrap">
                                <span class="lf-icon">🔒</span>
                                <input type="password" id="regPass" name="password"
                                    placeholder="Min. 6 karakter"
                                    required>
                                <button type="button" class="lf-eye" onclick="togglePass('regPass',this)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="lf-group">
                            <label>Konfirmasi Password</label>
                            <div class="lf-input-wrap">
                                <span class="lf-icon">🔑</span>
                                <input type="password" id="regPassConf" name="password_confirmation"
                                    placeholder="Ulangi password"
                                    required>
                                <button type="button" class="lf-eye" onclick="togglePass('regPassConf',this)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password strength -->
                    <div class="pass-strength" id="passStrength" style="display:none">
                        <div class="ps-bar">
                            <div class="ps-fill" id="psFill"></div>
                        </div>
                        <span class="ps-label" id="psLabel"></span>
                    </div>

                    <button type="submit" class="btn-masuk" style="margin-top:8px">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        Daftar Sekarang
                    </button>

                    <div class="lf-register" style="margin-top:16px">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Masuk di sini</a>
                    </div>
                </form>
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

// Password strength indicator
document.getElementById('regPass').addEventListener('input', function() {
    const val = this.value;
    const strength = document.getElementById('passStrength');
    const fill = document.getElementById('psFill');
    const label = document.getElementById('psLabel');

    if (val.length === 0) { strength.style.display = 'none'; return; }
    strength.style.display = 'flex';

    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w: '20%', color: '#ef4444', text: 'Sangat lemah' },
        { w: '40%', color: '#f97316', text: 'Lemah' },
        { w: '60%', color: '#eab308', text: 'Cukup' },
        { w: '80%', color: '#22c55e', text: 'Kuat' },
        { w: '100%', color: '#10b981', text: 'Sangat kuat' },
    ];

    const lvl = levels[Math.min(score - 1, 4)] || levels[0];
    fill.style.width = lvl.w;
    fill.style.background = lvl.color;
    label.textContent = lvl.text;
    label.style.color = lvl.color;
});
</script>
@endpush
@endsection