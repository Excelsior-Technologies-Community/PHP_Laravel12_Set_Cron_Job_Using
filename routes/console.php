<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Default inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===============================
// ✅ Category Cron Job Schedule
// ===============================
if (config('cron.enabled', false)) {
    $interval = config('cron.interval', 'everyMinute');

    Schedule::command('category:cron')
        ->{$interval}()
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/category-cron.log'));
}
