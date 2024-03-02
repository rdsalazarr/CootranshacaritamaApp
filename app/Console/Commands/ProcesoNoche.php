<?php

namespace App\Console\Commands;
use App\Console\Commands\Notificacion;
use Illuminate\Console\Command;
use App\Console\Commands\Noche;

class ProcesoNoche extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesos:noche';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite realizar proceso automaticos que se ejecutan en la noche';

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
        $mensaje  = Noche::iniciar();
        $mensaje .= Noche::procesarPagoMensualidad();
        $mensaje .= Noche::cerrarMovimientoCaja();
        $mensaje .= Noche::marcarRecibidoPlanilla();
        $mensaje .= Noche::crearBackup();
                    Noche::finalizar($mensaje);
    }
}