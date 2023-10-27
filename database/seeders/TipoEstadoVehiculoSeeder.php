<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadovehiculo')->insert([
            'tiesveid'     => 'A',
            'tiesvenombre' => 'Activo'
        ]);

        DB::table('tipoestadovehiculo')->insert([
            'tiesveid'     => 'S',
            'tiesvenombre' => 'Suspendido'
        ]);

        DB::table('tipoestadovehiculo')->insert([
            'tiesveid'     => 'D',
            'tiesvenombre' => 'Desvinculado'
        ]);
    }
}