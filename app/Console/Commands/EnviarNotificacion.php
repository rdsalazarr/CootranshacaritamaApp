<?php

namespace App\Console\Commands;
use App\Console\Commands\Notificar;
use Illuminate\Console\Command;

class EnviarNotificacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:notificacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite enviar notificaciones en el sistema';

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
        $notificar = new Notificar();

        $notificar->verificarSalidaCorreo();
        $notificar->notificarSolicitudesEstadoInicial();
        $notificar->notificarSolicitudesPendientePorResponder();       
    }
}