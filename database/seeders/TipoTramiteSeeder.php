<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoTramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipotramite')->insert([
            'tiptraid' => '1',
            'tiptranombre' => 'Archivar'
        ]);

	    DB::table('tipotramite')->insert([
	        'tiptraid' => '2',
	        'tiptranombre' => 'Socializar'
	    ]);

	    DB::table('tipotramite')->insert([
	        'tiptraid' => '3',
	        'tiptranombre' => 'Enviar a otra dependencia'
	    ]);

	    DB::table('tipotramite')->insert([
	        'tiptraid' => '4',
	        'tiptranombre' => 'Dar respuesta'
	    ]);

	    DB::table('tipotramite')->insert([
	        'tiptraid' => '5',
	        'tiptranombre' => 'Otro'
	    ]);
    }
}
