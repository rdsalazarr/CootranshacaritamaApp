<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon; 

class Kernel extends ConsoleKernel
{

     /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ProcesoDia',
        'App\Console\Commands\ProcesoNoche',
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $schedule->command('procesos:dia')->dailyAt('06:00')->withoutOverlapping()->appendOutputTo('storage/logs/pr_'.$fechaActual.'.log');
        $schedule->command('procesos:noche')->dailyAt('20:00')->withoutOverlapping()->appendOutputTo('storage/logs/pr_'.$fechaActual.'.log');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
