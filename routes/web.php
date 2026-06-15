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
    Route::get('/register', [KioskController::class, 'register'])->name('register');
    Route::post('/register', [KioskController::class, 'storeVisitor'])->name('register.store');
    
    // Options & Profile Updates
    Route::get('/options/{visitor}', [KioskController::class, 'options'])->name('options');
    Route::get('/options/confirm-match/{visitor}', [KioskController::class, 'confirmMatch'])->name('options.confirm-match');
    Route::get('/options/verify/{visitor}', [KioskController::class, 'verifyIdentityForm'])->name('options.verify');
    Route::post('/options/verify/{visitor}', [KioskController::class, 'verifyIdentitySubmit'])->name('options.verify.submit');
    Route::get('/options/edit-profile/{visitor}', [KioskController::class, 'editProfile'])->name('options.edit-profile');
    Route::post('/options/edit-profile/{visitor}', [KioskController::class, 'updateProfile'])->name('options.update-profile');

    // Walk-In Queue Generation
    Route::get('/ticket/{visitor}', [KioskController::class, 'ticket'])->name('ticket');
    Route::post('/queue', [KioskController::class, 'generateQueue'])->name('queue.generate');
    Route::get('/success/{queue}', [KioskController::class, 'success'])->name('success');

    // Appointment Booking
    Route::get('/appointment/{visitor}', [KioskController::class, 'createAppointment'])->name('appointment.create');
    Route::post('/appointment/{visitor}', [KioskController::class, 'storeAppointment'])->name('appointment.store');
    Route::post('/appointment/{appointment}/checkin', [KioskController::class, 'checkIn'])->name('appointment.checkin');
    Route::get('/appointment/success/{appointment}', [KioskController::class, 'appointmentSuccess'])->name('appointment.success');

    // Resend Email actions via Kiosk namespace
    Route::post('/queue/{token}/resend-email', [TrackingController::class, 'resendQueueEmail'])->name('queue.resend-email');
    Route::post('/appointment/{token}/resend-email', [TrackingController::class, 'resendAppointmentEmail'])->name('appointment.resend-email');
});

// Tracking Recovery Flow & Live Tracks
Route::get('/tracking/recovery', [TrackingController::class, 'recovery'])->name('tracking.recovery');
Route::post('/tracking/recovery', [TrackingController::class, 'recoverySubmit'])->name('tracking.recovery.submit');
Route::get('/queue/{token}', [TrackingController::class, 'show'])->name('queue.track');
Route::post('/queue/{token}/rate', [TrackingController::class, 'rate'])->name('queue.rate');
Route::get('/appointment/{token}', [TrackingController::class, 'showAppointment'])->name('appointment.track');

// Admin Operator Panel
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/queue/{queue}/call', [AdminController::class, 'callQueue'])->name('queue.call');
    Route::post('/queue/{queue}/done', [AdminController::class, 'doneQueue'])->name('queue.done');

    // Admin Appointment Operations
    Route::post('/appointment/{appointment}/checkin', [AdminController::class, 'adminCheckIn'])->name('appointment.checkin');
    Route::post('/appointment/{appointment}/cancel', [AdminController::class, 'adminCancel'])->name('appointment.cancel');
    Route::post('/appointment/{appointment}/reschedule', [AdminController::class, 'adminReschedule'])->name('appointment.reschedule');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
