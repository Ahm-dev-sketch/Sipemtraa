<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa akses admin
 * Memastikan hanya user dengan role 'admin' yang dapat mengakses route tertentu
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Memeriksa apakah user sudah login dan memiliki role admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Cek apakah user sudah login dan memiliki role admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }
        // Jika tidak, tolak akses dengan error 403
        abort(403, 'Akses ditolak');
    }
}
