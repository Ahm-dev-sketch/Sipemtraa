<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateJadwalDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:update-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-update tanggal jadwal setiap hari berdasarkan day_offset';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting jadwal date update...');

        // Get all active jadwals
        $jadwals = Jadwal::where('is_active', true)->get();

        $updated = 0;
        foreach ($jadwals as $jadwal) {
            try {
                // Calculate new date based on day_offset
                // day_offset: 0 = hari ini, 1 = besok, 2 = lusa, dst
                $newDate = Carbon::today()->addDays($jadwal->day_offset);

                // Update tanggal jadwal
                $jadwal->tanggal = $newDate->format('Y-m-d');
                $jadwal->save();

                $updated++;

                Log::info("Jadwal ID {$jadwal->id} updated to {$newDate->format('Y-m-d')}");
            } catch (\Exception $e) {
                Log::error("Failed to update Jadwal ID {$jadwal->id}: " . $e->getMessage());
                $this->error("Failed to update Jadwal ID {$jadwal->id}");
            }
        }

        $this->info("Successfully updated {$updated} jadwal(s)");
        Log::info("Jadwal auto-update completed: {$updated} jadwal(s) updated");

        return 0;
    }
}
