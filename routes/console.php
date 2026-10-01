<?php

use App\Services\CarSensor\CarSensorImageService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('soro:import-blogs')->hourly();

// Safety net: remove listing-import temp images left behind by interrupted imports
Schedule::call(fn () => app(CarSensorImageService::class)->cleanupOldImports(24))
    ->daily()
    ->name('carsensor-temp-cleanup');

