<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminController;

/**
 * File Route Web Laravel
 * Menentukan semua routing untuk aplikasi web pemesanan tiket travel
 */

// ADMIN ROUTES
// Route untuk panel admin dengan middleware auth dan admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Jadwal - CRUD operations untuk jadwal perjalanan
    Route::get('/jadwals', [AdminController::class, 'jadwals'])->name('jadwals');
    Route::get('/jadwals/create', [AdminController::class, 'createJadwal'])->name('jadwals.create');
    Route::post('/jadwals', [AdminController::class, 'storeJadwal'])->name('jadwals.store');
    Route::get('/jadwals/{jadwal}/edit', [AdminController::class, 'editJadwal'])->name('jadwals.edit');
    Route::put('/jadwals/{jadwal}', [AdminController::class, 'updateJadwal'])->name('jadwals.update');
    Route::patch('/jadwals/{jadwal}/toggle', [AdminController::class, 'toggleJadwalStatus'])->name('jadwals.toggle');
    Route::delete('/jadwals/{jadwal}', [AdminController::class, 'destroyJadwal'])->name('jadwals.destroy');

    // Kelola Booking - Manajemen booking dan status
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');

    // CRITICAL FIX: Route update status booking dipindah ke admin untuk keamanan
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update.status');

    // Kelola Pembayaran - Manajemen status pembayaran booking
    Route::get('/pembayaran', [AdminController::class, 'pembayaran'])->name('pembayaran');
    Route::put('/pembayaran/{booking}', [AdminController::class, 'updatePembayaran'])->name('pembayaran.update');

    // AJAX untuk mendapatkan data kursi real-time
    Route::get('/jadwal/{id}/seats', [BookingController::class, 'getSeats'])->name('jadwal.seats');

    // API untuk data rute dengan rate limiting
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/api/rute/{id}', [AdminController::class, 'getRuteData'])->name('api.rute.data');
        Route::get('/api/rute/{id}/jam', [AdminController::class, 'getJamKeberangkatan'])->name('api.rute.jam');
    });

    // Kelola Pelanggan - CRUD operations untuk data pelanggan
    Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
    Route::get('/pelanggan/create', [AdminController::class, 'createPelanggan'])->name('pelanggan.create');
    Route::post('/pelanggan', [AdminController::class, 'storePelanggan'])->name('pelanggan.store');
    Route::get('/pelanggan/{customer}/edit', [AdminController::class, 'editPelanggan'])->name('pelanggan.edit');
    Route::put('/pelanggan/{customer}', [AdminController::class, 'updatePelanggan'])->name('pelanggan.update');
    Route::delete('/pelanggan/{customer}', [AdminController::class, 'destroyPelanggan'])->name('pelanggan.destroy');

    // Laporan Pendapatan - Halaman laporan statistik pendapatan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    // Data Rute - CRUD operations untuk data rute perjalanan
    Route::get('/rute', [AdminController::class, 'rute'])->name('rute');
    Route::get('/rute/create', [AdminController::class, 'createRute'])->name('rute.create');
    Route::post('/rute', [AdminController::class, 'storeRute'])->name('rute.store');
    Route::get('/rute/{rute}/edit', [AdminController::class, 'editRute'])->name('rute.edit');
    Route::put('/rute/{rute}', [AdminController::class, 'updateRute'])->name('rute.update');
    Route::delete('/rute/{rute}', [AdminController::class, 'destroyRute'])->name('rute.destroy');

    // Data Mobil - CRUD operations untuk data mobil
    Route::get('/mobil', [AdminController::class, 'mobil'])->name('mobil');
    Route::get('/mobil/create', [AdminController::class, 'createMobil'])->name('mobil.create');
    Route::post('/mobil', [AdminController::class, 'storeMobil'])->name('mobil.store');
    Route::get('/mobil/{mobil}/edit', [AdminController::class, 'editMobil'])->name('mobil.edit');
    Route::put('/mobil/{mobil}', [AdminController::class, 'updateMobil'])->name('mobil.update');
    Route::delete('/mobil/{mobil}', [AdminController::class, 'destroyMobil'])->name('mobil.destroy');

    // Data Supir - CRUD operations untuk data supir
    Route::get('/supir', [AdminController::class, 'supir'])->name('supir');
    Route::get('/supir/create', [AdminController::class, 'createSupir'])->name('supir.create');
    Route::post('/supir', [AdminController::class, 'storeSupir'])->name('supir.store');
    Route::get('/supir/{supir}/edit', [AdminController::class, 'editSupir'])->name('supir.edit');
    Route::put('/supir/{supir}', [AdminController::class, 'updateSupir'])->name('supir.update');
    Route::delete('/supir/{supir}', [AdminController::class, 'destroySupir'])->name('supir.destroy');
});

// USER ROUTES
// Import controller untuk user routes
use App\Http\Controllers\HomeController;

// Halaman utama website
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public pages - halaman yang dapat diakses tanpa login
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

// Lightweight API: date-aware seat availability endpoint (canonical handler)
// This is a minimal, reversible fix so the frontend can call a guaranteed
// date-aware endpoint without changing legacy controllers/routes.
Route::get('/api/jadwal/{jadwal}/seats', [BookingController::class, 'getSeats']);

// Authentication routes - login, register, logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,10'); // Max 3x dalam 10 menit (karena kirim OTP)
Route::get('/register/verify', [AuthController::class, 'showRegisterVerify'])->name('register.verify');
Route::post('/register/verify', [AuthController::class, 'verifyRegisterOtp'])->name('register.verify.submit')->middleware('throttle:5,1'); // Max 5x per menit
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot & Reset Password routes
Route::get('password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,10'); // Max 3x dalam 10 menit (karena kirim OTP)
Route::get('password/reset/confirm', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('password/reset/confirm', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1'); // Max 5x per menit

// Protected pages - halaman yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Multi-step booking wizard - proses pemesanan tiket bertahap
    Route::get('/pesan-tiket', [BookingController::class, 'wizardStep1'])->name('pesan');
    Route::post('/pesan-tiket/step1', [BookingController::class, 'processStep1'])->name('booking.step1');
    Route::get('/pesan-tiket/step2', [BookingController::class, 'wizardStep2'])->name('booking.step2');
    Route::post('/pesan-tiket/step2', [BookingController::class, 'processStep2'])->name('booking.step2.process');
    Route::get('/pesan-tiket/step3', [BookingController::class, 'wizardStep3'])->name('booking.step3');
    Route::post('/pesan-tiket/step3', [BookingController::class, 'processStep3'])->name('booking.step3.process');

    // Quick booking - pemesanan cepat langsung ke step3 dengan jadwal_id
    Route::get('/pesan-tiket/quick/{jadwal}', [BookingController::class, 'quickBooking'])->name('booking.quick');

    // Riwayat booking user
    Route::get('/riwayat', [BookingController::class, 'index'])->name('riwayat');

    // Batal booking
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Legacy route untuk backward compatibility
    Route::post('/pesan-tiket', [BookingController::class, 'store'])->name('booking.store');

    // Download e-ticket
    Route::get('/booking/{booking}/download-ticket', [BookingController::class, 'downloadTicket'])->name('booking.download.ticket');

    // View e-ticket
    Route::get('/booking/{ticketNumber}/ticket', [BookingController::class, 'viewTicket'])->name('booking.ticket');

    // Route untuk mendapatkan data kursi yang sudah dipesan secara dinamis
    Route::get('/jadwal/{jadwal}/seats', [JadwalController::class, 'getBookedSeats']);
});
