<?php

use App\Http\Controllers\Organizer\Auth\LoginController;
use App\Http\Controllers\Organizer\Auth\PasswordResetController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\Organizer\ScannerController;
use App\Http\Controllers\Organizer\TicketController;
use App\Http\Controllers\Organizer\TicketTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('organizer')->name('organizer.')->group(function () {
    Route::middleware('guest:organizer')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
        Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
    });

    Route::middleware(['auth:organizer', 'organizer.active'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventController::class);
        Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
        Route::post('events/{event}/unpublish', [EventController::class, 'unpublish'])->name('events.unpublish');
        Route::get('events/{event}/attendees', [EventController::class, 'downloadAttendees'])->name('events.attendees');

        Route::resource('events.ticket-types', TicketTypeController::class)
            ->shallow()
            ->except(['show', 'index']);

        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');

        Route::get('scanner', [ScannerController::class, 'index'])->name('scanner');
        Route::post('scanner/verify', [ScannerController::class, 'verify'])->middleware('throttle:60,1')->name('scanner.verify');
        Route::post('scanner/check-in/{ticket:ticket_number}', [ScannerController::class, 'checkIn'])->middleware('throttle:60,1')->name('scanner.checkin');
    });
});
