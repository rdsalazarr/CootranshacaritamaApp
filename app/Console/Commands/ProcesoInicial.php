<?php

namespace App\Console\Commands;
use App\Console\Commands\Notificacion;
use Illuminate\Console\Command;
use App\Console\Commands\Inicial;

class ProcesoInicial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:inicial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite realizar proceso automaticos que se ejecutan inicialmente';

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
        $mensaje  = Inicial::iniciar();
        $mensaje .= Inicial::procesarAsignarContrato();
                    Inicial::finalizar($mensaje);
    }
}