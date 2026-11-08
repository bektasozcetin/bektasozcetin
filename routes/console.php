<?php

use App\Support\GitHubSync;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Açık kaynak repolarını her gece GitHub'dan tazele (kullanıcı adı ayarlıysa)
Schedule::call(function () {
    if (GitHubSync::username()) {
        GitHubSync::run();
    }
})->dailyAt('03:30')->name('github-sync')->withoutOverlapping();
