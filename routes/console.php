<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the daily schedule generation
Schedule::command('schedules:generate-daily --days=7')
    ->dailyAt('02:00') // Run every day at 2 AM
    ->withoutOverlapping(); // Prevent overlapping executions
