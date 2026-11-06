<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Kasir;
use App\Http\Controllers\Siswa;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/* ---------------------------------------------------------------- siswa */
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/', [Siswa\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/pesanan', [Siswa\PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/status', [Siswa\PesananController::class, 'status'])->name('pesanan.status');
    });

/* ---------------------------------------------------------------- admin */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/menu', [Admin\MenuController::class, 'index'])->name('menu.index');
        Route::post('/menu', [Admin\MenuController::class, 'store'])->name('menu.store');
        Route::patch('/menu/{menu}', [Admin\MenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{menu}', [Admin\MenuController::class, 'destroy'])->name('menu.destroy');
        Route::patch('/menu/{menu}/tersedia', [Admin\MenuController::class, 'toggle'])->name('menu.toggle');

        Route::get('/pesanan', [Admin\PesananController::class, 'index'])->name('pesanan.index');
        Route::patch('/pesanan/{pesanan}/status', [Admin\PesananController::class, 'updateStatus'])->name('pesanan.status');
    });

/* ---------------------------------------------------------------- kasir */
Route::middleware(['auth', 'role:kasir,admin'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        Route::get('/', [Kasir\DashboardController::class, 'index'])->name('dashboard');
        Route::patch('/pesanan/{pesanan}/lunas', [Kasir\PembayaranController::class, 'lunas'])->name('pesanan.lunas');
        Route::get('/pesanan/{pesanan}/struk', [Kasir\PembayaranController::class, 'struk'])->name('pesanan.struk');
    });
