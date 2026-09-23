<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stops browsers and any intermediary (CDN, reverse proxy) from caching
 * a response. Applied to public pages whose content changes as soon as
 * an organizer publishes/unpublishes/deletes an event, so visitors
 * never see a stale snapshot after that changes.
 */
class PreventCaching
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}
