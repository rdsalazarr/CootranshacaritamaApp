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
            'tipvecapacidad'      => '4',
            'tipvenumerofilas'    => '2',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '2',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '24P',
            'tipvecapacidad'      => '24',
            'tipvenumerofilas'    => '7',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '3',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '25P',
            'tipvecapacidad'      => '25',
            'tipvenumerofilas'    => '7',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '4',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '26P',
            'tipvecapacidad'      => '26',
            'tipvenumerofilas'    => '8',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '5',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '28P',
            'tipvecapacidad'      => '28',
            'tipvenumerofilas'    => '8',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '6',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '30P',
            'tipvecapacidad'      => '30',
            'tipvenumerofilas'    => '8',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '7',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '32P',
            'tipvecapacidad'      => '32',
            'tipvenumerofilas'    => '10',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '8',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '33P',
            'tipvecapacidad'      => '33',
            'tipvenumerofilas'    => '10',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '9',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '34P',
            'tipvecapacidad'      => '34',
            'tipvenumerofilas'    => '9',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '10',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '36P',
            'tipvecapacidad'      => '36',
            'tipvenumerofilas'    => '11',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '11',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '37P',
            'tipvecapacidad'      => '37',
            'tipvenumerofilas'    => '11',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '12',
            'tipvehnombre'        => 'BUS',
            'tipvehreferencia'    => '38P',
            'tipvecapacidad'      => '38',
            'tipvenumerofilas'    => '11',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoGeneral',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '13',
            'tipvehnombre'        => 'BUSETA',
            'tipvehreferencia'    => null,
            'tipvecapacidad'      => '22',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]); 

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '14',
            'tipvehnombre'        => 'CAMION',
            'tipvehreferencia'    => null,
            'tipvecapacidad'      => '8',
            'tipvenumerofilas'    => '2',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '15',
            'tipvehnombre'        => 'CAMIONETA',
            'tipvehreferencia'    => null,
            'tipvecapacidad'      => '7',
            'tipvenumerofilas'    => '3',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '16',
            'tipvehnombre'        => 'JEEP',
            'tipvehreferencia'    => null,
            'tipvecapacidad'      => '5',
            'tipvenumerofilas'    => '3',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '17',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '06P',
            'tipvecapacidad'      => '6',
            'tipvenumerofilas'    => '3',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '18',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '08P',
            'tipvecapacidad'      => '8',
            'tipvenumerofilas'    => '4',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '19',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '09P',
            'tipvecapacidad'      => '9',
            'tipvenumerofilas'    => '4',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '20',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '11P',
            'tipvecapacidad'      => '11',
            'tipvenumerofilas'    => '4',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '21',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '12P',
            'tipvecapacidad'      => '12',
            'tipvenumerofilas'    => '4',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '22',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '14P',
            'tipvecapacidad'      => '14',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);
        
        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '23',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '15P',
            'tipvecapacidad'      => '15',
            'tipvenumerofilas'    => '5',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '24',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '16P',
            'tipvecapacidad'      => '16',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '25',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '17P',
            'tipvecapacidad'      => '17',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '26',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '18P',
            'tipvecapacidad'      => '18',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '27',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '19P',
            'tipvecapacidad'      => '19',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '28',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => '20P',
            'tipvecapacidad'      => '20',
            'tipvenumerofilas'    => '6',
            'tipvenumerocolumnas' => '5',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '29',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => 'CARNIVAL',
            'tipvecapacidad'      => '7',
            'tipvenumerofilas'    => '3',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '30',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => 'SPRINTER',
            'tipvecapacidad'      => '15',
            'tipvenumerofilas'    => '5',
            'tipvenumerocolumnas' => '4',
            'tipveclasecss'       => 'distribucionPuestoMicroBusDos',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '31',
            'tipvehnombre'        => 'MICROBUS',
            'tipvehreferencia'    => 'URVAN',
            'tipvecapacidad'      => '9',
            'tipvenumerofilas'    => '4',
            'tipvenumerocolumnas' => '3',
            'tipveclasecss'       => 'distribucionPuestoMicroBus',
            'tipvehactivo'        => '0',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]); 

        DB::table('tipovehiculo')->insert([
            'tipvehid'            => '32',
            'tipvehnombre'        => 'MOTO',
            'tipvehreferencia'    =>  null,
            'tipvecapacidad'      => '1',
            'tipvenumerofilas'    => '1',
            'tipvenumerocolumnas' => '1',
            'tipveclasecss'       => 'distribucionPuestoTaxi',
            'tipvehactivo'        => '1',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);
    }
}