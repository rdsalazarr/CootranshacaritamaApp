<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fechaHoraActual = Carbon::now();

        DB::table('usuario')->insert([
            'persid'        => '1',
            'agenid'        => '101',
        	'usuanombre'    => 'SISTEMA',
            'usuaapellidos' => 'SISTEMA',
            'usuaemail'     => 'notificacioncootranshacaritama@gmail.com',
            'usuanick'      => 'SISTEMA',
            'usuaalias'     => 'SISTEMA',
        	'password'      => bcrypt('Sistemas2023.'),
            'usuaactivo'          => '0',
            'usuacambiarpassword' => '0',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('usuario')->insert([
            'persid'        => '2',
            'agenid'        => '101',
        	'usuanombre'    => 'RAMÓN DAVID',
            'usuaapellidos' => 'SALAZAR RINCÓN',
            'usuaemail'     => 'radasa10@hotmail.com',
            'usuanick'      => 'RSALAZAR',
            'usuaalias'     => 'Salazar R.',
        	'password'      => bcrypt('123456'),
            'usuaactivo'          => '1',
            'usuacambiarpassword' => '0',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 
    }
}