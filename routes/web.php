<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherSearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Edukasi Relawan & Siswa Daerah Terpencil
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC & DISCOVERY ROUTES
// ==========================================
// Beranda menampilkan halaman katalog pencarian guru relawan
Route::get('/', [TeacherSearchController::class, 'index'])->name('home');
Route::get('/teachers', [TeacherSearchController::class, 'index'])->name('teachers.index');
Route::get('/teachers/{teacher}', [TeacherSearchController::class, 'show'])->name('teachers.show');

// ==========================================
// 2. AUTHENTICATION ROUTES (GUEST)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (Hanya User Terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================================
// 3. STUDENT JOURNEY (MURID DAERAH TERPENCIL)
// ==========================================
Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
    // Dashboard Siswa: Menampilkan sesi mendatang, link video call, dan catatan materi
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    // Aksi Pemesanan Jadwal Belajar
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Memberi Ulasan (Rating & Feedback) setelah sesi selesai
    Route::post('/bookings/{booking}/review', [BookingController::class, 'review'])->name('bookings.review');
});

// ==========================================
// 4. TEACHER JOURNEY (GURU RELAWAN)
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('teacher.')->group(function () {
    // Dashboard Guru: Mengelola ketersediaan waktu dan daftar murid yang memesan
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Slot Jadwal Ketersediaan Mengajar
    Route::post('/schedules', [TeacherDashboardController::class, 'storeSchedule'])->name('schedules.store');
    Route::delete('/schedules/{schedule}', [TeacherDashboardController::class, 'destroySchedule'])->name('schedules.destroy');

    // Pengelolaan Status Transaksi Booking Murid
    Route::patch('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
});
