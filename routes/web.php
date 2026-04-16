<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KioskController::class, 'scan'])->name('kiosk.scan');

// Kiosk
Route::prefix('kiosk')->name('kiosk.')->group(function() {
    Route::get('/api/visitors', [KioskController::class, 'getVisitors'])->name('api.visitors');
    Route::post('/verify', [KioskController::class, 'verifyFace'])->name('verify'); // AJAX post
    Route::get('/register', [KioskController::class, 'register'])->name('register');
    Route::post('/register', [KioskController::class, 'storeVisitor'])->name('register.store');
    Route::get('/ticket/{visitor}', [KioskController::class, 'ticket'])->name('ticket');
    Route::post('/queue', [KioskController::class, 'generateQueue'])->name('queue.generate');
    Route::get('/success/{queue}', [KioskController::class, 'success'])->name('success'); // print & feedback
});

// Tracking
Route::get('/queue/{token}', [TrackingController::class, 'show'])->name('queue.track');
Route::post('/queue/{token}/rate', [TrackingController::class, 'rate'])->name('queue.rate');


// Admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/queue/{queue}/call', [AdminController::class, 'callQueue'])->name('queue.call');
    Route::post('/queue/{queue}/done', [AdminController::class, 'doneQueue'])->name('queue.done');
    Route::get('/queue/pending', [AdminController::class, 'getPending'])->name('queue.pending');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
