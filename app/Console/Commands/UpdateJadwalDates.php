<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk mengupdate tanggal jadwal secara otomatis setiap hari
 * Command ini akan menyesuaikan tanggal jadwal berdasarkan day_offset yang telah ditentukan
 */
class UpdateJadwalDates extends Command
{
    /**
     * The name and signature of the console command.
     * Nama dan parameter command yang akan digunakan di terminal
     *
     * @var string
     */
    protected $signature = 'jadwal:update-dates';

    /**
     * The console command description.
     * Deskripsi singkat tentang fungsi command ini
     *
     * @var string
     */
    protected $description = 'Auto-update tanggal jadwal setiap hari berdasarkan day_offset';

    /**
     * Execute the console command.
     * Menjalankan logika utama command untuk mengupdate tanggal jadwal
     */
    public function handle()
    {
        // Tampilkan pesan mulai proses
        $this->info('Starting jadwal date update...');

        // Ambil semua jadwal yang aktif
        $jadwals = Jadwal::where('is_active', true)->get();

        $updated = 0;
        foreach ($jadwals as $jadwal) {
            try {
                // Hitung tanggal baru berdasarkan day_offset dari hari ini
                $newDate = Carbon::today()->addDays($jadwal->day_offset);

                // Update tanggal jadwal
                $jadwal->tanggal = $newDate->format('Y-m-d');
                $jadwal->save();

                $updated++;

                // Log aktivitas update
                Log::info("Jadwal ID {$jadwal->id} updated to {$newDate->format('Y-m-d')}");
            } catch (\Exception $e) {
                // Log error jika gagal update
                Log::error("Failed to update Jadwal ID {$jadwal->id}: " . $e->getMessage());
                $this->error("Failed to update Jadwal ID {$jadwal->id}");
            }
        }

        // Tampilkan hasil akhir
        $this->info("Successfully updated {$updated} jadwal(s)");
        Log::info("Jadwal auto-update completed: {$updated} jadwal(s) updated");

        return 0;
    }
}
