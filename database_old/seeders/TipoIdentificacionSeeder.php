<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoIdentificacion;
use DB;

class TipoIdentificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipoidentificacion')->insert([
            'tipideid' => '1',
            'tipidesigla' => 'CC',
            'tipidenombre' => 'Cédula de ciudadanía'
        ]);

        DB::table('tipoidentificacion')->insert([
            'tipideid' => '2',
            'tipidesigla' => 'TI',
            'tipidenombre' => 'Tarjeta de identidad'
        ]);

        DB::table('tipoidentificacion')->insert([
            'tipideid' => '3',
            'tipidesigla' => 'RC',
            'tipidenombre' => 'Registro civil'
        ]);

        DB::table('tipoidentificacion')->insert([
            'tipideid' => '4',
            'tipidesigla' => 'CE',
            'tipidenombre' => 'Cédula de extranjería'
        ]);
    }
}
