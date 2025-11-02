<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        // Get expiry minutes from config
        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $threshold = Carbon::now()->subMinutes($expiryMinutes);

        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->get();

        $count = $expiredBookings->count();

        if ($count > 0) {
            foreach ($expiredBookings as $booking) {
                $booking->update(['status' => 'batal']);

                $this->line("Cancelled booking #{$booking->id} - Ticket: {$booking->ticket_number}");
            }

            $this->info("✅ Successfully cancelled {$count} expired pending bookings.");
        } else {
            $this->info("✓ No expired pending bookings found.");
        }

        return Command::SUCCESS;
    }
}
