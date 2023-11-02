<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoColocacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadocolocacion')->insert([
            'tiesclid'     => 'V',
            'tiesclnombre' => 'Vigente'
        ]);

        DB::table('tipoestadocolocacion')->insert([
            'tiesclid'     => 'S',
            'tiesclnombre' => 'Saldada'
        ]);

        DB::table('tipoestadocolocacion')->insert([
            'tiesclid'     => 'C',
            'tiesclnombre' => 'Cancelado anticipadamente'
        ]);

        DB::table('tipoestadocolocacion')->insert([
            'tiesclid'     => 'J',
            'tiesclnombre' => 'Júridica'
        ]);

        DB::table('tipoestadocolocacion')->insert([
            'tiesclid'     => 'R',
            'tiesclnombre' => 'Recuperacón'
        ]);
    }
}
