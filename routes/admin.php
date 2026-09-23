<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
        Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
    });

    Route::middleware('auth:web')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('organizers', OrganizerController::class);
        Route::post('organizers/{organizer}/suspend', [OrganizerController::class, 'suspend'])->name('organizers.suspend');
        Route::post('organizers/{organizer}/activate', [OrganizerController::class, 'activate'])->name('organizers.activate');
        Route::post('organizers/{organizer}/approve', [OrganizerController::class, 'approve'])->name('organizers.approve');

        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('events/{event:slug}', [EventController::class, 'show'])->name('events.show');
        Route::get('events/{event:slug}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event:slug}', [EventController::class, 'update'])->name('events.update');
        Route::post('events/{event:slug}/publish', [EventController::class, 'publish'])->name('events.publish');
        Route::post('events/{event:slug}/unpublish', [EventController::class, 'unpublish'])->name('events.unpublish');
        Route::post('events/{event:slug}/suspend', [EventController::class, 'suspend'])->name('events.suspend');
        Route::delete('events/{event:slug}', [EventController::class, 'destroy'])->name('events.destroy');

        Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');

        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
