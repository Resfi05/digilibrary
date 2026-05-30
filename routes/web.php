<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminBukuController;
use App\Http\Controllers\Admin\AdminPetugasController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPeminjamanController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminDendaController;
use App\Http\Controllers\Admin\AdminNotifikasiController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\Petugas\PetugasBukuController;
use App\Http\Controllers\Petugas\PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PetugasLaporanController;
use App\Http\Controllers\Petugas\PetugasUserController;
use App\Http\Controllers\UserPeminjamanController;
use App\Http\Controllers\Petugas\PetugasKategoriController;
use App\Http\Controllers\Petugas\PetugasDendaController;
use App\Http\Controllers\Petugas\PetugasNotifikasiController;
use App\Http\Controllers\Petugas\PetugasProfileController;

// ==========================================
// LANDING PAGE (Publik)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==========================================
// AUTH
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// USER ROUTES
// ==========================================
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/katalog', [UserDashboard::class, 'katalog'])->name('katalog');
    Route::get('/riwayat', [UserDashboard::class, 'riwayat'])->name('riwayat');
    Route::get('/favorit', [UserDashboard::class, 'favorit'])->name('favorit');
    Route::get('/ulasan', [UserDashboard::class, 'ulasan'])->name('ulasan');
    Route::get('/notifikasi', [UserDashboard::class, 'notifikasi'])->name('notifikasi');
    Route::post('/notifikasi/baca-semua', [UserDashboard::class, 'bacaSemua'])->name('notifikasi.baca');
    Route::get('/profil', [UserDashboard::class, 'profil'])->name('profil');
    Route::put('/profil', [UserDashboard::class, 'updateProfil'])->name('profil.update');
    Route::get('/buku/{id}', [UserDashboard::class, 'detailBuku'])->name('buku.detail');
    Route::post('/buku/{id}/pinjam', [UserDashboard::class, 'pinjamBuku'])->name('buku.pinjam');
    Route::post('/buku/{id}/favorit', [UserDashboard::class, 'toggleFavorit'])->name('buku.favorit');
    Route::post('/profil/password', [UserDashboard::class, 'ubahPassword'])->name('profil.password');
    Route::post('/profil/email', [UserDashboard::class, 'ubahEmail'])->name('profil.email');
    Route::post('/profil/telpon', [UserDashboard::class, 'ubahTelpon'])->name('profil.telpon');
    Route::post('/profil/preferensi', [UserDashboard::class, 'ubahPreferensi'])->name('profil.preferensi');
    Route::post('/ulasan/{book_id}', [UserDashboard::class, 'simpanUlasan'])->name('ulasan.simpan');
    Route::put('/ulasan/{id}', [UserDashboard::class, 'editUlasanSimpan'])->name('ulasan.edit');
    Route::delete('/ulasan/{id}', [UserDashboard::class, 'hapusUlasan'])->name('ulasan.hapus');
    Route::get('/peminjaman/{id}/bukti', [UserDashboard::class, 'cetakBukti'])->name('peminjaman.bukti');
    Route::post('/profil/avatar', [UserDashboard::class, 'uploadAvatar'])->name('profil.avatar');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/peminjaman/riwayat', [UserPeminjamanController::class, 'index'])->name('user.peminjaman.riwayat');
    Route::get('/peminjaman/cetak/{id}', [UserPeminjamanController::class, 'cetakBukti'])->name('user.peminjaman.cetak');
});

Route::get('/redirect', function () {
    $role = auth()->user()->role;
    switch ($role) {
        case 'admin':   return redirect()->route('admin.dashboard');
        case 'petugas': return redirect()->route('petugas.dashboard');
        default:        return redirect()->route('user.dashboard');
    }
})->name('redirect')->middleware('auth');

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', AdminKategoriController::class);
    Route::resource('buku', AdminBukuController::class);
    Route::post('buku/validate/{id}', [AdminBukuController::class, 'validateBook'])->name('buku.validate');
    Route::resource('petugas', AdminPetugasController::class);

    Route::get('peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::put('peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::put('peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::put('peminjaman/{id}/return', [AdminPeminjamanController::class, 'returnBook'])->name('peminjaman.return');

    Route::get('review', [AdminReviewController::class, 'index'])->name('review.index');
    Route::delete('review/{id}', [AdminReviewController::class, 'destroy'])->name('review.destroy');

    Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak-buku', [AdminLaporanController::class, 'cetakBuku'])->name('laporan.cetak-buku');
    Route::get('laporan/cetak-user', [AdminLaporanController::class, 'cetakUser'])->name('laporan.cetak-user');
    Route::get('laporan/cetak-peminjaman', [AdminLaporanController::class, 'cetakPeminjaman'])->name('laporan.cetak-peminjaman');
    Route::get('laporan/cetak-petugas', [AdminLaporanController::class, 'cetakPetugas'])->name('laporan.cetak-petugas');

    // Manajemen User
    Route::get('user', [AdminUserController::class, 'index'])->name('user.index');
    Route::put('user/{id}', [AdminUserController::class, 'update'])->name('user.update');
    Route::put('user/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('user.toggle');
    Route::delete('user/{id}', [AdminUserController::class, 'destroy'])->name('user.destroy');

    // Manajemen Petugas
    Route::get('petugas-list', [AdminUserController::class, 'petugasList'])->name('petugas.list');
    Route::put('petugas-list/{id}', [AdminUserController::class, 'petugasUpdate'])->name('petugas.list.update');
    Route::put('petugas-list/{id}/toggle', [AdminUserController::class, 'petugasToggle'])->name('petugas.list.toggle');
    Route::delete('petugas-list/{id}', [AdminUserController::class, 'petugasDestroy'])->name('petugas.list.destroy');

    Route::get('denda', [AdminDendaController::class, 'index'])->name('denda.index');
    Route::put('denda/{id}/bayar', [AdminDendaController::class, 'bayar'])->name('denda.bayar');

    // Notifikasi
    Route::get('notifikasi', [AdminNotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('notifikasi/kirim', [AdminNotifikasiController::class, 'kirim'])->name('notifikasi.kirim');
    Route::delete('notifikasi/{id}', [AdminNotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::post('notifikasi/hapus-semua', [AdminNotifikasiController::class, 'hapusSemua'])->name('notifikasi.hapus-semua');
});

// ==========================================
// PETUGAS ROUTES
// ==========================================
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    // Beranda
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

    // Validasi Peminjaman & Pengembalian
    Route::get('peminjaman/validasi', [PetugasPeminjamanController::class, 'validasi'])->name('peminjaman.validasi');
    Route::get('peminjaman/pengembalian', [PetugasPeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
    Route::put('peminjaman/{id}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::put('peminjaman/{id}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::put('peminjaman/{id}/return', [PetugasPeminjamanController::class, 'returnBook'])->name('peminjaman.return');

    // Kelola Data Buku
    Route::resource('buku', PetugasBukuController::class)->except(['show']);

    // Kelola Kategori
    Route::get('kategori', [PetugasKategoriController::class, 'index'])->name('kategori.index');
    Route::post('kategori', [PetugasKategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/{id}/edit', [PetugasKategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('kategori/{id}', [PetugasKategoriController::class, 'update'])->name('kategori.update');
    Route::delete('kategori/{id}', [PetugasKategoriController::class, 'destroy'])->name('kategori.destroy');

    // Kelola Data User
    Route::get('user', [PetugasUserController::class, 'index'])->name('user.index');
    Route::get('user/detail/{id}', [PetugasUserController::class, 'detail'])->name('user.detail');

    // Kelola Denda
    Route::get('denda', [PetugasDendaController::class, 'index'])->name('denda.index');
    Route::put('denda/{id}/bayar', [PetugasDendaController::class, 'bayar'])->name('denda.bayar');

    // Laporan
    Route::get('laporan', [PetugasLaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak-peminjaman', [PetugasLaporanController::class, 'cetakPeminjaman'])->name('laporan.cetak-peminjaman');
    Route::get('laporan/cetak-buku', [PetugasLaporanController::class, 'cetakBuku'])->name('laporan.cetak-buku');
    Route::get('laporan/cetak-user', [PetugasLaporanController::class, 'cetakUser'])->name('laporan.cetak-user');

    // Notifikasi
    Route::get('notifikasi', [PetugasNotifikasiController::class, 'index'])->name('notifikasi.index');

    // Profile
    Route::get('profil', [PetugasProfileController::class, 'index'])->name('profil.index');
    Route::put('profil', [PetugasProfileController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [PetugasProfileController::class, 'ubahPassword'])->name('profil.password');
});