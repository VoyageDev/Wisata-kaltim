<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Member route
Route::middleware('auth')->group(function () {
    // artikel route
    Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/{slug}', [ArtikelController::class, 'show'])->name('artikel.detail');

    // Load more artikel route
    Route::get('/artikel-load-more/terbaru/{offset}', [ArtikelController::class, 'loadMoreTerbaru'])->name('artikel.loadMoreTerbaru');
    Route::get('/artikel-load-more/populer/{offset}', [ArtikelController::class, 'loadMorePopuler'])->name('artikel.loadMorePopuler');
    Route::get('/artikel-load-more/top-wisata/{offset}', [ArtikelController::class, 'loadMoreTopWisata'])->name('artikel.loadMoreTopWisata');

    // kota route
    Route::get('/kota', [KotaController::class, 'index'])->name('kota.index');
    Route::get('/kota/{slug}', [KotaController::class, 'show'])->name('kota.detail');

    // wisata route
    Route::get('/wisata/{slug}', [WisataController::class, 'show'])->name('wisata.detail');

    // history route
    Route::get('/history', [HomeController::class, 'showHistory'])->name('history.index');

    // Ulasan route
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
    Route::get('/ulasan/load-more', [UlasanController::class, 'loadMore'])->name('ulasan.loadMore');
});

// Dashboard (admin only)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Admin CRUD Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Artikel Management
    Route::resource('artikel', ArtikelController::class);

    // Wisata Management
    Route::resource('wisata', WisataController::class);

    // Kota Management
    Route::resource('kota', KotaController::class);

    // Ulasan Management
    Route::get('ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::get('ulasan/{ulasan}', [UlasanController::class, 'show'])->name('ulasan.show');
    Route::delete('ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
});

//  profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
