<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $organizer = auth('organizer')->user();

        if ($organizer && ! $organizer->isActive()) {
            auth('organizer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('organizer.login')
                ->withErrors(['email' => 'Your organizer account has been suspended. Please contact Tiko support.']);
        }

        return $next($request);
    }
}
