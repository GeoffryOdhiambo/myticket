<?php

use App\Http\Middleware\EnsureOrganizerIsActive;
use App\Http\Middleware\PreventCaching;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')->group(base_path('routes/organizer.php'));
            Route::middleware('web')->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Traefik terminates TLS in production; trust its X-Forwarded-* headers
        // so generated URLs (ticket links, M-Pesa callbacks) use https.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'organizer.active' => EnsureOrganizerIsActive::class,
            'guest' => RedirectIfAuthenticated::class,
            'no-cache' => PreventCaching::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('organizer') || $request->is('organizer/*')) {
                return route('organizer.login');
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A stale/expired CSRF token (e.g. a form left open past the
        // session lifetime) would otherwise show Laravel's jarring "Page
        // expired" screen. Send the user somewhere sensible instead: a
        // logout attempt just lands them on the public site like a normal
        // logout would; anything else goes back to where they were so they
        // can retry with a fresh token. Laravel converts TokenMismatchException
        // into a generic HttpException(419) before render callbacks run, so
        // that's the type this has to match against, not the original.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if (str_ends_with($request->path(), 'logout')) {
                return redirect()->route('home');
            }

            return redirect()->back()->with('error', 'Your session expired. Please try again.');
        });
    })->create();
