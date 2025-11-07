<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk menambahkan header keamanan HTTP
 * Melindungi aplikasi dari berbagai serangan web seperti clickjacking, XSS, dll
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * Menambahkan berbagai header keamanan ke response HTTP
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah clickjacking dengan membatasi iframe dari domain yang sama
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengaktifkan proteksi XSS di browser lama
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Mengatur kebijakan referrer untuk privasi
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Membatasi akses ke fitur browser seperti geolocation, microphone, camera
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
