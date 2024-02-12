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
            'proautclasephp'       => 'suspenderConductor',
            'proautmetodo'         => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '2',
            'proautnombre'         => 'VencimientoSoat',
            'proautclasephp'       => 'suspenderVehiculosSoat',
            'proautmetodo'         => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '3',
            'proautnombre'         => 'VencimientoCRT',
            'proautclasephp'       => 'suspenderVehiculosCRT',
            'proautmetodo'         => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '4',
            'proautnombre'         => 'VencimientoPolizas',
            'proautclasephp'       => 'suspenderVehiculosPolizas',
            'proautmetodo'        => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '5',
            'proautnombre'         => 'VencimientoTarjetaOperacion',
            'proautclasephp'       => 'suspenderVehiculosTarjetaOperacion',
            'proautmetodo'         => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '6',
            'proautnombre'         => 'LevantarSancionVehiculo',
            'proautclasephp'       => 'levantarSancionVehiculo',
            'proautmetodo'         => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]); 

        DB::table('procesoautomatico')->insert([
            'proautid'             => '7',
            'proautnombre'         => 'NotificarVencimientoLicencia',
            'proautclasephp'       => 'vencimientoLicencias',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '8',
            'proautnombre'         => 'NotificarVencimientoVehiculosSoat',
            'proautclasephp'       => 'vencimientoSoat',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '9',
            'proautnombre'         => 'NotificarVencimientoVehiculosCRT',
            'proautclasephp'       => 'vencimientoCRT',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '10',
            'proautnombre'         => 'NotificarVencimientoVehiculosPolizas',
            'proautclasephp'       => 'vencimientoPolizas',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '11',
            'proautnombre'         => 'NotificarVencimientoVehiculosTarjetaOperacion',
            'proautclasephp'       => 'suspenderConductor',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '12',
            'proautnombre'         => 'VencimientoCuotasCreditos',
            'proautclasephp'       => 'cuotasCreditos',
            'proautmetodo'         => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);
      
    }
}
