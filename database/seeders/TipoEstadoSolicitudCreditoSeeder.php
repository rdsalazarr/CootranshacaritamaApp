<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoSolicitudCreditoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        DB::table('tipoestadosolicitudcredito')->insert([
            'tiesscid'     => 'R',
            'tiesscnombre' => 'Registrado'
        ]);

        DB::table('tipoestadosolicitudcredito')->insert([
            'tiesscid'     => 'A',
            'tiesscnombre' => 'Aprobado'
        ]);

        DB::table('tipoestadosolicitudcredito')->insert([
            'tiesscid'     => 'N',
            'tiesscnombre' => 'Negado'
        ]);

        DB::table('tipoestadosolicitudcredito')->insert([
            'tiesscid'     => 'S',
            'tiesscnombre' => 'Asesoria'
        ]);
    }
}