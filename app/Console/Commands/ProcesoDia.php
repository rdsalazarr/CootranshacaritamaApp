<?php

namespace App\Console\Commands;
use App\Console\Commands\Notificacion;
use Illuminate\Console\Command;
use App\Console\Commands\Dia;

class ProcesoDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesos:dia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite realizar proceso automaticos que se ejecutan en el dia';

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
        $mensaje  = Dia::iniciar();
        $mensaje .= Dia::suspenderConductor();
        $mensaje .= Dia::suspenderVehiculosSoat();
        $mensaje .= Dia::suspenderVehiculosCRT();
        $mensaje .= Dia::suspenderVehiculosPolizas();
        $mensaje .= Dia::suspenderVehiculosTarjetaOperacion();
        $mensaje .= Dia::suspenderVehiculosProgramados();
        $mensaje .= Dia::levantarSancionVehiculo();
        $mensaje .= Notificacion::iniciar();
        $mensaje .= Notificacion::vencimientoLicencias();
        $mensaje .= Notificacion::vencimientoSoat();
        $mensaje .= Notificacion::vencimientoCRT();
        $mensaje .= Notificacion::vencimientoPolizas();
        $mensaje .= Notificacion::vencimientoTarjetaOperacion(); 
        $mensaje .= Notificacion::cuotasCreditos();
                    Dia::finalizar($mensaje);
    }
}