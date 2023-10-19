<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoAsociadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadoasociado')->insert([
            'tiesasid'     => 'A',
            'tiesasnombre' => 'Activo'
        ]);

        DB::table('tipoestadoasociado')->insert([
            'tiesasid'     => 'S',
            'tiesasnombre' => 'Sancionado'
        ]);

        DB::table('tipoestadoasociado')->insert([
            'tiesasid'     => 'I',
            'tiesasnombre' => 'Inactivo'
        ]);

        DB::table('tipoestadoasociado')->insert([
            'tiesasid'     => 'E',
            'tiesasnombre' => 'Excluido'
        ]);

        DB::table('tipoestadoasociado')->insert([
            'tiesasid'     => 'R',
            'tiesasnombre' => 'Retirado'
        ]);       
    }
}
