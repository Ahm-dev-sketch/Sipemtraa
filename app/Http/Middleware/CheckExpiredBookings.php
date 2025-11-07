<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Services\FonnteService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa dan membatalkan booking yang sudah expired
 * Otomatis membatalkan booking pending yang melewati batas waktu tertentu
 */
class CheckExpiredBookings
{
    /**
     * Handle an incoming request.
     * Mengecek booking pending yang sudah expired dan membatalkannya otomatis
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil konfigurasi waktu expiry dari config
        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $threshold = Carbon::now()->subMinutes($expiryMinutes);

        // Cari booking pending yang sudah expired
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->get();

        // Jika ada booking expired, batalkan dan kirim notifikasi
        if ($expiredBookings->count() > 0) {
            $fonnte = app(FonnteService::class);
            foreach ($expiredBookings as $booking) {
                $booking->status = 'batal';
                $booking->save();
                $fonnte->notifyAdminAutoCancellation($booking);
            }
        }

        return $next($request);
    }
}
