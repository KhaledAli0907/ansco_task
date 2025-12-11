<?php

use App\Jobs\ExpireSubscriptionsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule subscription expiration job every 10 minutes
Schedule::job(ExpireSubscriptionsJob::class)
    ->everyTenMinutes()
    ->withoutOverlapping();
