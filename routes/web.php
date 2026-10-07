<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\HarianController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PinLoginController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Admin\PinController;

/*
|--------------------------------------------------------------------------
| Web Routes — Presensi 11 RPL B
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/beranda');
    }
    return view('welcome');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// PIN login (siswa)
Route::get('/pin', [PinLoginController::class, 'showPinForm'])->name('pin.form');
Route::post('/pin', [PinLoginController::class, 'verifyPin'])->name('pin.verify')->middleware('throttle:10,1');
Route::post('/pin/logout', [PinLoginController::class, 'logout'])->name('pin.logout');

// Routes accessible by both authenticated users and PIN-verified students
Route::middleware('siswa')->group(function () {

    // Beranda / Dashboard
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{tanggal}', [RiwayatController::class, 'detail'])->name('riwayat.detail')->where('tanggal', '[0-9]{4}-[0-9]{2}-[0-9]{2}');

    // Rekap — view only
    Route::get('/rekap', [ExportController::class, 'rekap'])->name('rekap');

    // Kelas — read only
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
    Route::get('/kelas/{slug}', [KelasController::class, 'show'])->name('kelas.detail');
    Route::get('/kelas/{id}/riwayat', [KelasController::class, 'riwayat'])->name('kelas.riwayat');
});

// Routes accessible only by authenticated users
Route::middleware('auth')->group(function () {

    // Presensi Harian — admin & pengurus_kelas
    Route::middleware('role:admin,pengurus_kelas')->group(function () {
        Route::get('/harian', [HarianController::class, 'index'])->name('harian');
        Route::post('/harian', [HarianController::class, 'store'])->name('harian.store');
    });

    // Export download — hanya admin & guru
    Route::middleware('role:admin,guru')->group(function () {
        Route::get('/rekap/excel', [ExportController::class, 'rekapExcel'])->name('rekap.excel');
        Route::get('/rekap/pdf', [ExportController::class, 'rekapPdf'])->name('rekap.pdf');
    });

    // Kelas — write only (admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/kelas/tambah', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    });

    // Admin — Kelola User & PIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // PIN management
        Route::put('/pin', [PinController::class, 'update'])->name('pin.update');
    });
});