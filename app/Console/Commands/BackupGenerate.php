<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BackupGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando copia de seguridad...');

        // Generar copia de seguridad de la base de datos
        Artisan::call('backup:run');

        // Copiar la carpeta public/archivos a una ubicación de respaldo
        // Por ejemplo, puedes usar Laravel Filesystem para copiar los archivos

        $this->info('Copia de seguridad completa.');
    }
}
