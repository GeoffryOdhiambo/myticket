<?php

use App\Http\Controllers\Organizer\Auth\LoginController;
use App\Http\Controllers\Organizer\Auth\PasswordResetController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\Organizer\ScannerController;
use App\Http\Controllers\Organizer\TicketTypeController;
use App\Http\Controllers\Organizer\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('organizer')->name('organizer.')->group(function () {
    Route::middleware('guest:organizer')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.store');
        Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });

    Route::middleware(['auth:organizer', 'organizer.active'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventController::class);
        Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
        Route::post('events/{event}/unpublish', [EventController::class, 'unpublish'])->name('events.unpublish');

        Route::resource('events.ticket-types', TicketTypeController::class)
            ->shallow()
            ->except(['show']);

        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');

        Route::get('scanner', [ScannerController::class, 'index'])->name('scanner');
        Route::post('scanner/verify', [ScannerController::class, 'verify'])->name('scanner.verify');
        Route::post('scanner/check-in/{ticket:ticket_number}', [ScannerController::class, 'checkIn'])->name('scanner.checkin');
    });
});
