<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstanteArchivadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '1',
            'tiesarnombre' => 'Estante uno'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '2',
            'tiesarnombre' => 'Estante dos'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '3',
            'tiesarnombre' => 'Estante tres'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '4',
            'tiesarnombre' => 'Estante cuatro'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '5',
            'tiesarnombre' => 'Estante cinco'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '6',
            'tiesarnombre' => 'Estante seis'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '7',
            'tiesarnombre' => 'Estante siete'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '8',
            'tiesarnombre' => 'Estante ocho'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '9',
            'tiesarnombre' => 'Estante nueve'
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '10',
            'tiesarnombre' => 'Estante diez'
        ]);
    }
}
