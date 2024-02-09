<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoMedioSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipomediosolicitud')->insert([
            'timesoid'     => 'BU',
            'timesonombre' => 'BUZÓN'
        ]);

        DB::table('tipomediosolicitud')->insert([
            'timesoid'     => 'LL',
            'timesonombre' => 'LLAMADA'
        ]);

        DB::table('tipomediosolicitud')->insert([
            'timesoid'     => 'CP',
            'timesonombre' => 'CARTA PERSONAL'
        ]);

        DB::table('tipomediosolicitud')->insert([
            'timesoid'     => 'EM',
            'timesonombre' => 'E-MAIL'
        ]);

        DB::table('tipomediosolicitud')->insert([
            'timesoid'     => 'OT',
            'timesonombre' => 'OTRO:'
        ]);
    }
}