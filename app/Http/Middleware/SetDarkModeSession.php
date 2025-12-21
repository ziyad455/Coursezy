<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetDarkModeSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated and dark_mode session is not set, set it from user preference
        if (auth()->check() && !session()->has('dark_mode')) {
            session(['dark_mode' => auth()->user()->dark_mode ?? false]);
        }

        return $next($request);
    }
}
