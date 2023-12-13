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
        'App\Console\Commands\EnviarNotificacion',
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $cronLog         = storage_path('logs/cron.log');
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $schedule->command('enviar:notificacion')->dailyAt('06:00')->withoutOverlapping()->appendOutputTo('logs/pr_'.$fechaActual.'.log');
        //$schedule->command('verificar:notificacion')->everyMinute('06:00')->withoutOverlapping()->appendOutputTo('logs/pr_'.$fechaActual.'.log');
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
