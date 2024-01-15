<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '1',
            'tipvehnombre'        => 'AUTOMÓVIL',
            'tipvehreferencia'    => NULL,
            'tipvehcapacidad'      => '4',
            'tipvehnumerofilas'    => '2',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '2',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '24P',
            'tipvehcapacidad'      => '24',
            'tipvehnumerofilas'    => '7',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '3',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '25P',
            'tipvehcapacidad'      => '25',
            'tipvehnumerofilas'    => '7',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '4',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '26P',
            'tipvehcapacidad'      => '26',
            'tipvehnumerofilas'    => '8',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '5',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '28P',
            'tipvehcapacidad'      => '28',
            'tipvehnumerofilas'    => '8',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '6',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '30P',
            'tipvehcapacidad'      => '30',
            'tipvehnumerofilas'    => '8',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '7',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '32P',
            'tipvehcapacidad'      => '32',
            'tipvehnumerofilas'    => '10',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '8',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '33P',
            'tipvehcapacidad'      => '33',
            'tipvehnumerofilas'    => '10',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '9',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '34P',
            'tipvehcapacidad'      => '34',
            'tipvehnumerofilas'    => '9',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '10',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '36P',
            'tipvehcapacidad'      => '36',
            'tipvehnumerofilas'    => '11',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '11',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '37P',
            'tipvehcapacidad'      => '37',
            'tipvehnumerofilas'    => '11',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '12',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '38P',
            'tipvehcapacidad'      => '38',
            'tipvehnumerofilas'    => '11',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '13',
            'tipvehnombre'        => 'BUSETA',
            'tipvehreferencia'    => null,
            'tipvehcapacidad'      => '22',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]); 

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '14',
            'tipvehnombre'        => 'CAMION',
            'tipvehreferencia'    => null,
            'tipvehcapacidad'      => '8',
            'tipvehnumerofilas'    => '2',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '15',
            'tipvehnombre'        => 'CAMIONETA',
            'tipvehreferencia'    => null,
            'tipvehcapacidad'      => '7',
            'tipvehnumerofilas'    => '3',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '16',
            'tipvehnombre'        => 'JEEP',
            'tipvehreferencia'    => null,
            'tipvehcapacidad'      => '5',
            'tipvehnumerofilas'    => '3',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '17',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '06P',
            'tipvehcapacidad'      => '6',
            'tipvehnumerofilas'    => '3',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '18',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '08P',
            'tipvehcapacidad'      => '8',
            'tipvehnumerofilas'    => '4',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '19',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '09P',
            'tipvehcapacidad'      => '9',
            'tipvehnumerofilas'    => '4',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '20',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '11P',
            'tipvehcapacidad'      => '11',
            'tipvehnumerofilas'    => '4',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '21',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '12P',
            'tipvehcapacidad'      => '12',
            'tipvehnumerofilas'    => '4',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '22',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '14P',
            'tipvehcapacidad'      => '14',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);
        
        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '23',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '15P',
            'tipvehcapacidad'      => '15',
            'tipvehnumerofilas'    => '5',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '24',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '16P',
            'tipvehcapacidad'      => '16',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '25',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '17P',
            'tipvehcapacidad'      => '17',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '26',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '18P',
            'tipvehcapacidad'      => '18',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '27',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '19P',
            'tipvehcapacidad'      => '19',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'             => '28',
            'tipvehnombre'         => 'MICROBUS',
            'tipvehreferencia'     => '20P',
            'tipvehcapacidad'      => '20',
            'tipvehnumerofilas'    => '6',
            'tipvehnumerocolumnas' => '5',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'         => '1',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'             => '29',
            'tipvehnombre'         => 'MICROBUS',
            'tipvehreferencia'     => 'CARNIVAL',
            'tipvehcapacidad'      => '7',
            'tipvehnumerofilas'    => '3',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'         => '1',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'             => '30',
            'tipvehnombre'         => 'MICROBUS',
            'tipvehreferencia'     => 'SPRINTER',
            'tipvehcapacidad'      => '15',
            'tipvehnumerofilas'    => '5',
            'tipvehnumerocolumnas' => '4',
            'tipvehclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'         => '1',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'             => '31',
            'tipvehnombre'         => 'MICROBUS',
            'tipvehreferencia'     => 'URVAN',
            'tipvehcapacidad'      => '9',
            'tipvehnumerofilas'    => '4',
            'tipvehnumerocolumnas' => '3',
            'tipvehclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'         => '0',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]); 

        DB::table('tipovehiculo')->insert([
            'tipvehid'             => '32',
            'tipvehnombre'         => 'MOTO',
            'tipvehreferencia'     =>  null,
            'tipvehcapacidad'      => '1',
            'tipvehnumerofilas'    => '1',
            'tipvehnumerocolumnas' => '1',
            'tipvehclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'         => '1',
            'created_at'           => $fechaHoraActual,
            'updated_at'           => $fechaHoraActual,
        ]);
    }
}