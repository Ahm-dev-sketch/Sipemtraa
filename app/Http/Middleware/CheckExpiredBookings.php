<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Services\FonnteService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExpiredBookings
{
    public function handle(Request $request, Closure $next): Response
    {
        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $threshold = Carbon::now()->subMinutes($expiryMinutes);

        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->get();

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
