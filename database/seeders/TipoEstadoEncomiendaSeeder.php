<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoEncomiendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadoencomienda')->insert([
            'tiesenid'     => 'R',
            'tiesennombre' => 'Recibido'
        ]);

        DB::table('tipoestadoencomienda')->insert([
            'tiesenid'     => 'T',
            'tiesennombre' => 'En transporte'
        ]);

        DB::table('tipoestadoencomienda')->insert([
            'tiesenid'     => 'D',
            'tiesennombre' => 'Terminal destino'
        ]);

        DB::table('tipoestadoencomienda')->insert([
            'tiesenid'     => 'E',
            'tiesennombre' => 'Entregado'
        ]);
    }
}