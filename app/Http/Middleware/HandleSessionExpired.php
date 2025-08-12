<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

class HandleSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            // Hanya set session_expired jika sebelumnya sudah login
            if ($request->session()->has('was_logged_in')) {
                return redirect()->route('login')->with('session_expired', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }

            // Jika belum pernah login, redirect biasa tanpa session_expired
            return redirect()->route('login');
        }
    }
}
