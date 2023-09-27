<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoDocumentoEntranteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadoraddocentrante')->insert([
            'tierdeid'     => '1',
            'tierdenombre' => 'Inicial'
        ]);

        DB::table('tipoestadoraddocentrante')->insert([
            'tierdeid'     => '2',
            'tierdenombre' => 'Tramitado'
        ]);

        DB::table('tipoestadoraddocentrante')->insert([
            'tierdeid'     => '3',
            'tierdenombre' => 'Recibido'
        ]);

        DB::table('tipoestadoraddocentrante')->insert([
            'tierdeid'     => '4',
            'tierdenombre' => 'Respondido'
        ]);

        DB::table('tipoestadoraddocentrante')->insert([
            'tierdeid'     => '5',
            'tierdenombre' => 'Anulado'
        ]);
    }
}