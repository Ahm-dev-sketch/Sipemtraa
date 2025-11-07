<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk membatalkan booking pending yang sudah expired
 * Command ini akan dijalankan secara otomatis untuk membersihkan booking yang tidak dibayar tepat waktu
 */
class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     * Nama dan parameter command yang akan digunakan di terminal
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     * Deskripsi singkat tentang fungsi command ini
     *
     * @var string
     */
    protected $description = 'Cancel pending bookings that are older than 30 minutes';

    /**
     * Execute the console command.
     * Menjalankan logika utama command untuk membatalkan booking expired
     */
    public function handle()
    {
        // Ambil konfigurasi waktu expiry dari config file
        $expiryMinutes = config('booking.pending_expiry_minutes', 30);
        $threshold = Carbon::now()->subMinutes($expiryMinutes);

        // Cari semua booking pending yang sudah melewati batas waktu
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<', $threshold)
            ->get();

        $count = $expiredBookings->count();

        // Jika ada booking expired, proses pembatalan
        if ($count > 0) {
            $fonnteService = app(FonnteService::class);

            foreach ($expiredBookings as $booking) {
                // Load relasi yang diperlukan untuk notifikasi
                $booking->load(['user', 'jadwal.rute', 'jadwal.mobil']);
                // Update status booking menjadi batal
                $booking->update(['status' => 'batal']);

                // Kirim notifikasi ke admin via Fonnte
                try {
                    $fonnteService->notifyAdminAutoCancellation($booking);
                } catch (\Exception $e) {
                    // Log error jika gagal kirim notifikasi
                    Log::error('Gagal kirim notifikasi auto-cancel ke admin', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                }

                // Tampilkan progress di console
                $this->line("Cancelled booking #{$booking->id} - Ticket: {$booking->ticket_number}");
            }

            // Tampilkan pesan sukses
            $this->info("✅ Successfully cancelled {$count} expired pending bookings.");

            // Log aktivitas pembatalan
            Log::info("Auto-cancelled expired bookings", [
                'total' => $count,
                'threshold' => $threshold->format('Y-m-d H:i:s')
            ]);
        } else {
            // Jika tidak ada booking expired
            $this->info("✓ No expired pending bookings found.");
        }

        return Command::SUCCESS;
    }
}
