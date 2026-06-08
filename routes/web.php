<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengaturanSuratController;

/*
|--------------------------------------------------------------------------
| HOME / ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    return match($user->role) {
        'admin', 'pegawai' => redirect()->route('admin.dashboard'),
        'pimpinan'         => redirect()->route('pimpinan.dashboard'),
        default            => redirect()->route('user.dashboard'),
    };
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD USER (PENDUDUK)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:penduduk'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'penduduk'])->name('user.dashboard');
    Route::get('/faq', fn() => view('layouts.faq', ['title' => 'Bantuan / FAQ']))->name('faq');

    // Profil
    Route::get('/profil-saya', [ProfileController::class, 'show'])->name('profil.saya');
    Route::get('/profil-saya/edit', fn() => view('components.Pengguna.Profil.edit_profil', ['title' => 'Edit Profil']))->name('profil.edit');
    Route::post('/profil-saya/update', [ProfileController::class, 'update'])->name('profil.update');

    // FITUR UTAMA: SURAT DINAMIS
    Route::get('/ajukan-surat', [SuratController::class, 'pilihSurat'])->name('pengajuan.surat');
    Route::get('/ajukan-surat/form/{id}', [SuratController::class, 'formAjukan'])->name('ajukan-surat.form');
    Route::match(['get', 'post'], '/ajukan-surat/preview', [SuratController::class, 'previewAjukan'])->name('ajukan-surat.preview');
    Route::post('/ajukan-surat/submit', [SuratController::class, 'submitAjukan'])->name('ajukan-surat.submit');
  
    Route::get('/riwayat-pengajuan', [SuratController::class, 'riwayatSurat'])->name('riwayat.pengajuan');
    Route::get('/riwayat-pengajuan/detail/{id}', [SuratController::class, 'detailSurat'])->name('riwayat-pengajuan.detail');
    Route::get('/riwayat-pengajuan/download/{id}', [SuratController::class, 'downloadSurat'])->name('downloadSurat');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN / PEGAWAI
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:pegawai,admin'])
    ->name('admin.') 
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [SuratController::class, 'indexAdminDashboard'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | TEMPLATE SURAT (CRUD)
        |--------------------------------------------------------------------------
        */
        Route::get('/surat', [SuratController::class, 'showAllSurat'])->name('surat');
        Route::get('/surat/create', [SuratController::class, 'showCreateForm'])->name('surat.create');
        Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
        Route::get('/surat/{id}/edit', [SuratController::class, 'edit'])->name('surat.edit');
        Route::put('/surat/{id}', [SuratController::class, 'update'])->name('surat.update');
        Route::delete('/surat/{id}', [SuratController::class, 'hapus'])->name('surat.hapus');

        /*
        |--------------------------------------------------------------------------
        | WORKFLOW PENGAJUAN SURAT
        |--------------------------------------------------------------------------
        */
        Route::get('/permohonan', [SuratController::class, 'permohonanSuratPengguna'])->name('permohonan');
        Route::get('/riwayat-pengajuan/detail/{id}', [SuratController::class, 'detailSurat'])->name('riwayat-pengajuan.detail');
        
        // Verifikasi ACC / Tolak berkas oleh admin
        Route::post('/surat/verifikasi/{id}', [SuratController::class, 'verifikasi'])->name('surat.verifikasi');

        Route::get('/surat/download/{id}', [SuratController::class, 'downloadPdf'])->name('surat.download');
    // Cukup tulis seperti ini di dalam group admin
        Route::post('/surat/update-file/{id}', [SuratController::class, 'updateFileSurat'])->name('surat.update_file');
        // PERBAIKAN UTAMA: Menggunakan method 'selesaiSurat' sesuai dengan isi Controller kamu
        Route::post('/surat/selesai/{id}', [SuratController::class, 'selesaiSurat'])->name('surat.selesai');

        /*
        |--------------------------------------------------------------------------
        | REKAP & PENGATURAN MASTER KELURAHAN
        |--------------------------------------------------------------------------
        */
        Route::get('/rekap', [SuratController::class, 'rekapSurat'])->name('rekap');

        Route::get('/pengaturan', [PengaturanSuratController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan/update', [PengaturanSuratController::class, 'update'])->name('pengaturan.update');
    });

/*
|--------------------------------------------------------------------------
| DASHBOARD PIMPINAN
|--------------------------------------------------------------------------
*/
Route::prefix('pimpinan')
    ->middleware(['auth', 'role:pimpinan'])
    ->name('pimpinan.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');
        Route::get('/permohonan', [SuratController::class, 'permohonanSuratPimpinan'])->name('permohonan');
        Route::get('/riwayat', [SuratController::class, 'riwayatSuratPimpinan'])->name('riwayat');
        Route::get('/rekap', [SuratController::class, 'rekapSurat'])->name('rekap');
        Route::get('/permohonan/detail/{id}', [SuratController::class, 'detailPermohonanPimpinan'])->name('surat.detail');
        Route::post('/permohonan/approve/{id}', [SuratController::class, 'approvePimpinan'])->name('surat.approve');
    });