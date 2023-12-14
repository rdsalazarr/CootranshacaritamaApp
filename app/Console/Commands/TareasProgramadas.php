<?php

namespace App\Console\Commands;
use App\Console\Commands\Tareas;
use Illuminate\Console\Command;

class TareasProgramadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tareas:programadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite programar todas las tareas que utiliza el cron';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Tareas::verificarSalidaCorreo();
        Tareas::notificarVencimientoLicencias();

        //$notificar->notificarSolicitudesEstadoInicial();
       // $notificar->notificarSolicitudesPendientePorResponder();       
    }
}