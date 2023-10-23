<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class CargoLaboralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('cargolaboral')->insert([
            'carlabid'     => '1',
            'carlabnombre' => 'Desarrollador',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '2',
            'carlabnombre' => 'Asociado',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '3',
            'carlabnombre' => 'Conductor',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '4',
            'carlabnombre' => 'Gerente',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '5',
            'carlabnombre' => 'Jefe de área',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '6',
            'carlabnombre' => 'Secretaria',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);
    }
}
