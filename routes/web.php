<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Panitia\DashboardController;
use App\Http\Controllers\Panitia\KelolaWorkshopController;
use App\Http\Controllers\Panitia\PendaftarController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use App\Http\Controllers\Panitia\KategoriController;


Route::get('/', [WorkshopController::class, 'index'])->name('home');
Route::get('/workshop/{workshop}', [WorkshopController::class, 'show'])->name('workshops.show');

Route::middleware('guest')->group(function () {
    Route::get('/daftar-akun', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar-akun', [AuthController::class, 'register']);
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::post('/workshop/{workshop}/daftar', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/tiket-saya', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::post('/pendaftaran/{registration}/cek-status', [RegistrationController::class, 'cekStatus'])->name('registrations.check');
    Route::get('/pendaftaran/{registration}/tiket', [RegistrationController::class, 'ticket'])->name('registrations.ticket');
});

Route::middleware(['auth', 'role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('workshops', KelolaWorkshopController::class)->except('show');
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('registrations.index');
});

Route::post('/midtrans/notifikasi', MidtransNotificationController::class)
    ->withoutMiddleware(PreventRequestForgery::class)
    ->name('midtrans.notifikasi');

Route::resource('kategori', KategoriController::class)
    ->except(['show', 'create'])
    ->parameters(['kategori' => 'kategori']);

    Route::middleware(['auth', 'role:panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('workshops', KelolaWorkshopController::class)->except('show');
    Route::resource('kategori', KategoriController::class)
        ->except(['show', 'create'])
        ->parameters(['kategori' => 'kategori']);
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('registrations.index');
});