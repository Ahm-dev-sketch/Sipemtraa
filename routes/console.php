<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule to auto-update jadwal dates every day at midnight
Schedule::command('jadwal:update-dates')
    ->dailyAt('00:01') // Run at 12:01 AM every day
    ->withoutOverlapping()
    ->timezone('Asia/Jakarta');

// Schedule to cancel expired pending bookings every 5 minutes
Schedule::command('bookings:cancel-expired')
    ->everyFiveMinutes()
    ->withoutOverlapping();
