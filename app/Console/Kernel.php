<?php

namespace App\Console;

use App\Console\Commands\Export\TransformPatientsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * @var array
     */
    protected $commands = [
        TransformPatientsCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        //
    }
}
