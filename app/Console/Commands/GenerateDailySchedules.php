<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Mobil;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDailySchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:generate-daily {--days=30 : Number of days to generate ahead}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily schedules automatically based on existing templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Generating daily schedules for the next {$days} days...");

        // Get all active routes and vehicles
        $rutes = Rute::where('status_rute', 'aktif')->get();
        $mobils = Mobil::where('status', 'aktif')->get();

        if ($rutes->isEmpty()) {
            $this->error('No active routes found. Please add routes first.');
            return;
        }

        if ($mobils->isEmpty()) {
            $this->error('No active vehicles found. Please add vehicles first.');
            return;
        }

        $generatedCount = 0;
        $skippedCount = 0;

        // Generate schedules for each day
        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::today()->addDays($i);

            // Skip if date is in the past
            if ($date->isPast() && !$date->isToday()) {
                continue;
            }

            $this->info("Processing date: {$date->format('Y-m-d')}");

            // For each route and vehicle combination, create schedule if not exists
            foreach ($rutes as $rute) {
                foreach ($mobils as $mobil) {
                    // Check if schedule already exists for this date, route, and vehicle
                    $existingSchedule = Jadwal::where('tanggal', $date->format('Y-m-d'))
                        ->where('rute_id', $rute->id)
                        ->where('mobil_id', $mobil->id)
                        ->first();

                    if ($existingSchedule) {
                        $this->line("  - Schedule already exists for route {$rute->kota_asal}-{$rute->kota_tujuan} with vehicle {$mobil->merk} on {$date->format('Y-m-d')}");
                        $skippedCount++;
                        continue;
                    }

                    // Create new schedule
                    // Use default time (can be made configurable later)
                    $defaultTime = '08:00:00'; // Default departure time

                    Jadwal::create([
                        'rute_id' => $rute->id,
                        'mobil_id' => $mobil->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'jam' => $defaultTime,
                        'harga' => $rute->harga_tiket, // Use route's ticket price
                    ]);

                    $this->line("  + Created schedule for route {$rute->kota_asal}-{$rute->kota_tujuan} with vehicle {$mobil->merk} on {$date->format('Y-m-d')} at {$defaultTime}");
                    $generatedCount++;
                }
            }
        }

        $this->info("Schedule generation completed!");
        $this->info("Generated: {$generatedCount} schedules");
        $this->info("Skipped: {$skippedCount} existing schedules");
    }
}
