<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// artikel route
Route::get('/artikel', [ArtikelController::class, 'memberIndex'])->name('artikel.index');
Route::get('/artikel/{slug}', [ArtikelController::class, 'detail'])->name('artikel.detail');
Route::get('/ulasan/load-more', [UlasanController::class, 'loadMore'])->name('ulasan.loadMore');

// Load more artikel route
Route::get('/artikel-load-more/terbaru/{offset}', [ArtikelController::class, 'loadMoreTerbaru'])->name('artikel.loadMoreTerbaru');
Route::get('/artikel-load-more/populer/{offset}', [ArtikelController::class, 'loadMorePopuler'])->name('artikel.loadMorePopuler');
Route::get('/artikel-load-more/top-wisata/{offset}', [ArtikelController::class, 'loadMoreTopWisata'])->name('artikel.loadMoreTopWisata');

// kota route
Route::get('/kota', [KotaController::class, 'memberIndex'])->name('kota.index');
Route::get('/kota/{slug}', [KotaController::class, 'detail'])->name('kota.detail');

// wisata route
Route::get('/wisata', [WisataController::class, 'memberIndex'])->name('wisata.index');
Route::get('/wisata/{slug}', [WisataController::class, 'detail'])->name('wisata.detail');

// ** Member route ** //
Route::middleware('auth')->group(function () {

    // History routes (member)
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/bookings', [HistoryController::class, 'bookings'])->name('bookings');
        Route::get('/ulasans', [HistoryController::class, 'ulasans'])->name('ulasans');
        Route::get('/booking/{booking}', [HistoryController::class, 'showBooking'])->name('booking.show');
        Route::post('/booking/{booking}/cancel', [HistoryController::class, 'cancelBooking'])->name('booking.cancel');
        Route::put('/ulasan/{ulasan}', [HistoryController::class, 'updateUlasan'])->name('ulasan.update');
        Route::delete('/ulasan/{ulasan}', [HistoryController::class, 'deleteUlasan'])->name('ulasan.delete');
    });

    // Ulasan routes (for creating and deleting reviews from wisata/artikel pages)
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [HistoryController::class, 'deleteUlasan'])->name('ulasan.destroy');
});

// Dashboard (admin only)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Admin CRUD Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Artikel Management - resource route with model binding (ID-based)
    Route::resource('artikel', ArtikelController::class);

    // Wisata Management - resource route with model binding (ID-based)
    Route::resource('wisata', WisataController::class);

    // Kota Management - resource route with model binding (ID-based)
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
