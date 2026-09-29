<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanMasukController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    // ... route dashboard, masuk, keluar, riwayat yang sudah ada ...

    // Menu User Management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
     Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.upadate');
    Route::delete('/user/delete{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.process');

});

// Route untuk Authenticated User (Sudah Login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kendaraan Masuk
    Route::get('/kendaraan-masuk', [DashboardController::class, 'halamanMasuk'])->name('kendaraan.masuk.form');
    Route::post('/kendaraan-masuk', [DashboardController::class, 'storeMasuk'])->name('kendaraan.masuk.store');
    Route::post('/kendaraan-masuk/otomatis', [DashboardController::class, 'storeOtomatis'])->name('kendaraan-masuk.store-otomatis');
    // Kendaraan Keluar
    // Kendaraan Keluar
Route::match(['get', 'post'], '/kendaraan-keluar', [DashboardController::class, 'halamanKeluar'])->name('kendaraan.keluar.form');
Route::post('/kendaraan-keluar/proses/{id}', [DashboardController::class, 'storeKeluar'])->name('kendaraan.keluar.store');

    // Riwayat
    Route::get('/riwayat', [DashboardController::class, 'riwayat'])->name('riwayat');
    
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
});