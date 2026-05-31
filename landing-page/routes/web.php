<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepairController;

// FRONTEND (Landing Page)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/fitur', [PageController::class, 'fitur'])->name('fitur');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/lapor', [PageController::class, 'lapor'])->name('lapor');
Route::get('/laporan-saya', [PageController::class, 'laporanSaya'])->name('laporan-saya');
Route::get('/peta', [PageController::class, 'peta'])->name('peta');
Route::get('/profil', [PageController::class, 'profil'])->name('profil');

// AUTH (Login/Logout)
//Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
//Route::post('/login', [AuthController::class, 'login']);
//Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// BACKEND CRUD (Wajib Login)
//Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
//   Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//    Route::resource('reports', ReportController::class);
//    Route::resource('repairs', RepairController::class);
//});