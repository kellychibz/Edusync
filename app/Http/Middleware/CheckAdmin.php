<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Add this import

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Use Auth facade instead of auth() helper
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Admin access required.');
    }
}