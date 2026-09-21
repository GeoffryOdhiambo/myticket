<?php

use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\Payments\MpesaCallbackController;
use App\Http\Controllers\Public\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/checkout/{event:slug}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/{order:order_number}/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');
Route::get('/checkout/{order:order_number}/status', [CheckoutController::class, 'status'])->name('checkout.status');
Route::post('/checkout/{order:order_number}/simulate-payment', [CheckoutController::class, 'simulate'])->name('checkout.simulate');

Route::post('/payments/mpesa/callback', MpesaCallbackController::class)
    ->name('payments.mpesa.callback')
    ->withoutMiddleware(['web']);

Route::get('/ticket/{ticket:ticket_number}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/ticket/{ticket:ticket_number}/download', [TicketController::class, 'download'])->name('ticket.download');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
