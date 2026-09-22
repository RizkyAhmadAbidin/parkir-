<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/login', [AuthController::class, 'create'])->name('login');           // Tampilkan form
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('loginn');     

Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/kendaraan-masuk', [DashboardController::class, 'halamanMasuk'])->name('kendaraan.masuk.form');
Route::post('/kendaraan-masuk', [DashboardController::class, 'storeMasuk'])->name('kendaraan.masuk.store');

Route::get('/kendaraan-keluar', [DashboardController::class, 'halamanKeluar'])->name('kendaraan.keluar.form');
Route::post('/kendaraan-keluar/proses{id}', [DashboardController::class, 'storeKeluar'])->name('kendaraan.keluar.store');

Route::get('/riwayat', [DashboardController::class, 'riwayat'])->name('riwayat');

Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');

// Route::get('/password/reset', [PasswordResetController::class, 'show'])->name('password.request');
// Route::post('/password/email', [PasswordResetController::class, 'store'])->name('password.email');