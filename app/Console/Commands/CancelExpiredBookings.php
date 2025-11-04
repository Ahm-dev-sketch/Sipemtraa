<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending bookings that are older than 30 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $threshold = Carbon::now()->subMinutes($expiryMinutes);

        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            $fonnteService = app(FonnteService::class);

            foreach ($expiredBookings as $booking) {
                $booking->load(['user', 'jadwal.rute', 'jadwal.mobil']);
                $booking->update(['status' => 'batal']);

                try {
                    $fonnteService->notifyAdminAutoCancellation($booking);
                } catch (\Exception $e) {
                    Log::error('Gagal kirim notifikasi auto-cancel ke admin', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                }

                $this->line("Cancelled booking #{$booking->id} - Ticket: {$booking->ticket_number}");
            }

            $this->info("✅ Successfully cancelled {$count} expired pending bookings.");

            Log::info("Auto-cancelled expired bookings", [
                'total' => $count,
                'threshold' => $threshold->format('Y-m-d H:i:s')
            ]);
        } else {
            $this->info("✓ No expired pending bookings found.");
        }

        return Command::SUCCESS;
    }
}
