<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:check-stalled-tickets --days=14')
    ->dailyAt('08:00')
    ->description('Cek berkala tiket PBI yang tertahan di kementerian > 14 hari');
