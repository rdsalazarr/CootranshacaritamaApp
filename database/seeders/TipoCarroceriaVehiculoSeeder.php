<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoCarroceriaVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();
      
        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '1',
            'ticavenombre' => 'CERRADO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '2',
            'ticavenombre' => 'SEDÁN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '3',
            'ticavenombre' => 'HATCH-BACK',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '4',
            'ticavenombre' => 'MIXTA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '5',
            'ticavenombre' => 'CABINADO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '6',
            'ticavenombre' => 'VAN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '7',
            'ticavenombre' => 'STAT-WAGON',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '8',
            'ticavenombre' => 'VANS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '9',
            'ticavenombre' => 'CARPADO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocarroceriavehiculo')->insert([
            'ticaveid'     => '10',
            'ticavenombre' => 'ESTACAS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}