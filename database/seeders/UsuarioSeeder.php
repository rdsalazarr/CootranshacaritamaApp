<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
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
            'tipideid'      => '1',
            'usuadocumento' => '1978917',
        	'usuanombre'    => 'Ramón David',
            'usuaapellidos' => 'Salazar Rincón',
            'usuaemail'     => 'radasa10@hotmail.com',
            'usuanick'      => 'RSALAZAR',         	
        	'usuapassword'  => bcrypt('123456'),
            'usuaactivo'    => '1'
        ]); 
    }
}