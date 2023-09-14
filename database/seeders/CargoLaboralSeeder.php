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
            'carlabnombre' => 'Jefe',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '2',
            'carlabnombre' => 'Jefe encargado',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);

        DB::table('cargolaboral')->insert([
            'carlabid'     => '3',
            'carlabnombre' => 'Secretaria',
            'carlabactivo' => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]);
    }
}
