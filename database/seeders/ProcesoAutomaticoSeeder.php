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
        $fechaAnterior   = $fechaHoraActual->subDay()->format('Y-m-d');

        DB::table('procesoautomatico')->insert([
            'proautid'             => '1',
            'proautnombre'         => 'VencimientoLicencias',
            'proautmetodo'         => 'suspenderConductor',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '2',
            'proautnombre'         => 'VencimientoSoat',
            'proautmetodo'         => 'suspenderVehiculosSoat',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '3',
            'proautnombre'         => 'VencimientoCRT',
            'proautmetodo'         => 'suspenderVehiculosCRT',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '4',
            'proautnombre'         => 'VencimientoPolizas',
            'proautmetodo'         => 'suspenderVehiculosPolizas',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '5',
            'proautnombre'         => 'VencimientoTarjetaOperacion',
            'proautmetodo'         => 'suspenderVehiculosTarjetaOperacion',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '6',
            'proautnombre'         => 'LevantarSancionVehiculo',
            'proautmetodo'         => 'levantarSancionVehiculo',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]); 

        DB::table('procesoautomatico')->insert([
            'proautid'             => '7',
            'proautnombre'         => 'NotificarVencimientoLicencia',
            'proautmetodo'         => 'vencimientoLicencias',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '8',
            'proautnombre'         => 'NotificarVencimientoVehiculosSoat',
            'proautmetodo'         => 'vencimientoSoat',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '9',
            'proautnombre'         => 'NotificarVencimientoVehiculosCRT',
            'proautmetodo'         => 'vencimientoCRT',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '10',
            'proautnombre'         => 'NotificarVencimientoVehiculosPolizas',
            'proautmetodo'         => 'vencimientoPolizas',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '11',
            'proautnombre'         => 'NotificarVencimientoVehiculosTarjetaOperacion',
            'proautmetodo'         => 'suspenderConductor',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '12',
            'proautnombre'         => 'VencimientoCuotasCreditos',
            'proautmetodo'         => 'cuotasCreditos',
            'proautclasephp'       => 'Notificacion',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '13',
            'proautnombre'         => 'ProcesarPagoMensualidad',
            'proautmetodo'         => 'procesarPagoMensualidad',
            'proautclasephp'       => 'Noche',
            'proautfechaejecucion' => $fechaAnterior,
            'proauttipo'           => 'N',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '14',
            'proautnombre'         => 'CerrarMovimientoCaja',
            'proautmetodo'         => 'cerrarMovimientoCaja',
            'proautclasephp'       => 'Noche',
            'proautfechaejecucion' => $fechaAnterior,
            'proauttipo'           => 'N',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '15',
            'proautnombre'         => 'MarcarRecibidoPlanilla',
            'proautmetodo'         => 'marcarRecibidoPlanilla',
            'proautclasephp'       => 'Noche',
            'proautfechaejecucion' => $fechaAnterior,
            'proauttipo'           => 'N',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '16',
            'proautnombre'         => 'MarcarRedencionPuntos',
            'proautmetodo'         => 'marcarRedencionPuntos',
            'proautclasephp'       => 'Noche',
            'proautfechaejecucion' => $fechaAnterior,
            'proauttipo'           => 'N',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '17',
            'proautnombre'         => 'CrearBackup',
            'proautmetodo'         => 'crearBackup',
            'proautclasephp'       => 'Noche',
            'proautfechaejecucion' => $fechaAnterior,
            'proauttipo'           => 'N',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('procesoautomatico')->insert([
            'proautid'             => '18',
            'proautnombre'         => 'SuspenderVehiculosProgramado',
            'proautmetodo'         => 'suspenderVehiculosProgramados',
            'proautclasephp'       => 'Dia',
            'proautfechaejecucion' => $fechaActual,
            'proauttipo'           => 'D',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);      
    }
}
