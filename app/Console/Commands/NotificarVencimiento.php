<?php

namespace App\Console\Commands;
use App\Console\Commands\Vencimiento;
use Illuminate\Console\Command;

class NotificarVencimiento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificar:vencimiento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite enviar notificacion de vencimientos';

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
        $mensaje  = Vencimiento::iniciar();
        $mensaje .= Vencimiento::licencias();
        $mensaje .= Vencimiento::soat();
        $mensaje .= Vencimiento::CRT();
        $mensaje .= Vencimiento::Polizas();
        $mensaje .= Vencimiento::tarjetaOperacion();
        $mensaje .= Vencimiento::cuotasCreditos();
                    Vencimiento::finalizar($mensaje);
    }
}