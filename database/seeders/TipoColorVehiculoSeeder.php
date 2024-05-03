<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoColorVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '1',
            'ticovenombre' => 'BLANCO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '2',
            'ticovenombre' => 'BLANCO VERDE AMARILLO ROJO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '3',
            'ticovenombre' => 'BLANCO VERDE AMARILLO AZUL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '4',
            'ticovenombre' => 'VERDE BLANCO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '5',
            'ticovenombre' => 'BLANCO NIEVE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '6',
            'ticovenombre' => 'BLANCO VERDE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '7',
            'ticovenombre' => 'AMARILLO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '8',
            'ticovenombre' => 'AMARILLO URBANO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '9',
            'ticovenombre' => 'AMARILLO LIMA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '10',
            'ticovenombre' => 'BLANCO NIEBLA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '11',
            'ticovenombre' => 'BLANCO GALAXIA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '12',
            'ticovenombre' => 'BLANCO VERDE AMARILLO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '13',
            'ticovenombre' => 'AMARILLO BLANCO VERDE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '14',
            'ticovenombre' => 'BLANCO GLACIAL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '15',
            'ticovenombre' => 'BLANCO AZUL ROJO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '16',
            'ticovenombre' => 'BLANCO ÁRTICO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '17',
            'ticovenombre' => 'AZUL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '18',
            'ticovenombre' => 'BLANCO POLAR',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '19',
            'ticovenombre' => 'VERDE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '20',
            'ticovenombre' => 'AZUL AMARILLO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '21',
            'ticovenombre' => 'VERDE AMARILLO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '22',
            'ticovenombre' => 'NARANJA-CREMA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '23',
            'ticovenombre' => 'VERDE AMARILLO ROJO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '24',
            'ticovenombre' => 'ROJO LADRILLO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipocolorvehiculo')->insert([
            'ticoveid'     => '25',
            'ticovenombre' => 'ROJO VERDE BLANCO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);       
    }
}