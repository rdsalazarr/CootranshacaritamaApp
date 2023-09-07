<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoRelacionLaboralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '1',
            'tirelanombre' => 'Jefe'
        ]);

        DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '2',
            'tirelanombre' => 'Secretaria'
        ]);

         DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '3',
            'tirelanombre' => 'Usuario Invitado'
        ]);
    }
}
