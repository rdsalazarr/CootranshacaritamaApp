<?php

namespace App\Console\Commands;
use App\Console\Commands\Automaticos;
use Illuminate\Console\Command;

class ProcesosAutomaticos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'procesos:automaticos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite realizar proceso automaticos';

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
        $mensaje  = Automaticos::iniciar();
        $mensaje .= Automaticos::suspenderConductor();
       /* $mensaje .= Automaticos::soat();
        $mensaje .= Automaticos::CRT();
        $mensaje .= Automaticos::Polizas();
        $mensaje .= Automaticos::tarjetaOperacion();
        $mensaje .= Automaticos::cuotasCreditos();*/
                    Automaticos::finalizar($mensaje);
    }
}