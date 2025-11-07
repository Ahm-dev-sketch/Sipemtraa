<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule removed: jadwal:update-dates command no longer exists (switched to day-based system)

// Schedule to cancel expired pending bookings every 30 seconds
Schedule::command('bookings:cancel-expired')
    ->everyThirtySeconds()
    ->withoutOverlapping();
