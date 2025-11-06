<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule to auto-update jadwal dates every day at midnight (00:00)
Schedule::command('jadwal:update-dates')
    ->dailyAt('00:00') // Run at 12:00 AM (midnight) every day
    ->withoutOverlapping()
    ->timezone('Asia/Jakarta');

// Schedule to cancel expired pending bookings every minute
Schedule::command('bookings:cancel-expired')
    ->everyMinute()
    ->withoutOverlapping();
