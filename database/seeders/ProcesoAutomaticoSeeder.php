<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ProcesoAutomaticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');

        DB::table('procesoautomatico')->insert([
            'proautid'             => '1',
            'proautnombre'         => 'VencimientoLicencias',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '2',
            'proautnombre'         => 'VencimientoSoat',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '3',
            'proautnombre'         => 'VencimientoCRT',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '4',
            'proautnombre'         => 'VencimientoPolizas',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '5',
            'proautnombre'         => 'VencimientoTarjetaOperacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '6',
            'proautnombre'         => 'VencimientoCuotasCreditos',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);


        DB::table('procesoautomatico')->insert([
            'proautid'             => '7',
            'proautnombre'         => 'SuspenderConductor',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '8',
            'proautnombre'         => 'SuspenderVehiculosSoat',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);


        DB::table('procesoautomatico')->insert([
            'proautid'             => '9',
            'proautnombre'         => 'SuspenderVehiculosCRT',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '10',
            'proautnombre'         => 'SuspenderVehiculosPolizas',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '11',
            'proautnombre'         => 'SuspenderVehiculosTarjetaOperacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '12',
            'proautnombre'         => 'LevantarSancionVehiculo',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);
    }
}
