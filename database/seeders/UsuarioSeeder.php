<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        DB::table('usuario')->insert([
            'persid'        => '1',
        	'usuanombre'    => 'RAMÓN DAVID',
            'usuaapellidos' => 'SALAZAR RINCÓN',
            'usuaemail'     => 'radasa10@hotmail.com',
            'usuanick'      => 'RSALAZAR',
            'usuaalias'     => 'Salazar R.',
        	'password'      => bcrypt('123456'),
            'usuaactivo'    => '1',
            'usuacambiarpassword' => '0',
        ]); 
    }
}