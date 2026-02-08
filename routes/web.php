<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// ** Member route ** //
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

// booking route
Route::get('/booking', [BookingsController::class, 'memberIndex'])->name('booking.index');

Route::middleware('auth')->group(function () {

    // History routes (member)
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/bookings', [HistoryController::class, 'bookings'])->name('bookings');
        Route::get('/ulasans', [HistoryController::class, 'ulasans'])->name('ulasans');
        Route::get('/booking/{booking}', [HistoryController::class, 'showBooking'])->name('booking.show');
        Route::post('/booking/{booking}/cancel', [HistoryController::class, 'cancelBooking'])->name('booking.cancel');
        Route::post('/booking/{booking}/complete', [HistoryController::class, 'completeBooking'])->name('booking.complete');
        Route::put('/ulasan/{ulasan}', [HistoryController::class, 'updateUlasan'])->name('ulasan.update');
        Route::delete('/ulasan/{ulasan}', [HistoryController::class, 'deleteUlasan'])->name('ulasan.delete');
    });

    // Ulasan routes (comment and delete form artikel and wisata)
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{ulasan}', [HistoryController::class, 'deleteUlasan'])->name('ulasan.destroy');

    // booking routes
    Route::post('/booking', [BookingsController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/invoice', [BookingsController::class, 'invoice'])->name('booking.invoice');
    Route::get('/booking/{booking}/continue', [BookingsController::class, 'continuePayment'])->name('booking.continue');

    // payment routes
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
});

// ** Member route ** //

// ** Admin route ** //
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// CRUD Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Artikel Management
    Route::resource('artikel', ArtikelController::class);

    // Wisata Management - resource route with model binding (ID-based)
    Route::resource('wisata', WisataController::class)->parameters([
        'wisata' => 'wisata'
    ]);

    // Kota Management
    Route::resource('kota', KotaController::class);

    // Ulasan Management
    Route::get('ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::get('ulasan/{ulasan}', [UlasanController::class, 'show'])->name('ulasan.show');
    Route::delete('ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

    // booking management
    Route::resource('booking', BookingsController::class);

    // payment management
    Route::resource('payment', PaymentController::class);
});

// ** Admin route ** //

//  profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
