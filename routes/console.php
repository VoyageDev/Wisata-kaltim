<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Command yang sebelumnya (membatalkan pending 1 hari)
Schedule::command('booking:cancel-expired')->hourly();

// Command yang baru (menyelesaikan tiket yang sudah dipakai)
Schedule::command('booking:mark-done')->dailyAt('01:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
