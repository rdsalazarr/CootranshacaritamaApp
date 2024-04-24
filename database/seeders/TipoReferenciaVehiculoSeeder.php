<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoReferenciaVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '1',
            'tirevenombre' => 'URVAN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '2',
            'tirevenombre' => 'NKR-55',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '3',
            'tirevenombre' => 'NKR',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '4',
            'tirevenombre' => 'DELTA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '5',
            'tirevenombre' => 'NKR-4',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '6',
            'tirevenombre' => 'TRAFIC',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '7',
            'tirevenombre' => 'ATOS PRIME GL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '8',
            'tirevenombre' => 'CIELO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '9',
            'tirevenombre' => 'ATOS PRIME',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '10',
            'tirevenombre' => 'TAXI 7:24',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '11',
            'tirevenombre' => 'SYMBOL CITIUS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '12',
            'tirevenombre' => 'R-9',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '13',
            'tirevenombre' => 'TAXI DIESEL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '14',
            'tirevenombre' => 'SUPER TAXI',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '15',
            'tirevenombre' => 'CLIO EXPRESS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '16',
            'tirevenombre' => 'ATOS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '17',
            'tirevenombre' => 'SPARK',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '18',
            'tirevenombre' => 'R-9 INYECCIÓN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '19',
            'tirevenombre' => 'MATIZ',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '20',
            'tirevenombre' => 'CIELO BX',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '21',
            'tirevenombre' => 'SYMBOL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '22',
            'tirevenombre' => 'LANOS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '23',
            'tirevenombre' => 'TAXI LANOS S',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '24',
            'tirevenombre' => 'CBX 1047',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '25',
            'tirevenombre' => 'LOGAN DYNAMIQUE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
        
        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '26',
            'tirevenombre' => 'BT-50',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiporeferenciavehiculo')->insert([
            'tireveid'     => '27',
            'tirevenombre' => '4700',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        



        
    }
}