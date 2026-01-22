<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Add this import

class CheckTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Teacher access required.');
    }
}