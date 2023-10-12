<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class InformacionConfiguracionCorreoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();
          
        DB::table('informacionconfiguracioncorreo')->insert([
            'incocoid'       => '1',
            'incocohost'     => 'smtp.gmail.com',
            'incocousuario'  => 'notificacioncootranshacaritama@gmail.com',
            'incococlave'    => 'Notific@2023.',
            'incococlaveapi' => 'grgsmqtlmijxaapj',
            'incocopuerto'   => '587', 
            'created_at'     => $fechaHoraActual,
            'updated_at'     => $fechaHoraActual,
        ]); 
    }
}