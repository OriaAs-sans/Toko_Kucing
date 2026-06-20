<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new \App\Jobs\FetchOrdersJob)->everyMinute();
    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
