<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoEstanteArchivadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '1',
            'tiesarnombre' => 'Estante uno',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '2',
            'tiesarnombre' => 'Estante dos',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '3',
            'tiesarnombre' => 'Estante tres',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '4',
            'tiesarnombre' => 'Estante cuatro',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '5',
            'tiesarnombre' => 'Estante cinco',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '6',
            'tiesarnombre' => 'Estante seis',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '7',
            'tiesarnombre' => 'Estante siete',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '8',
            'tiesarnombre' => 'Estante ocho',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '9',
            'tiesarnombre' => 'Estante nueve',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipoestantearchivador')->insert([
            'tiesarid'     => '10',
            'tiesarnombre' => 'Estante diez',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}
