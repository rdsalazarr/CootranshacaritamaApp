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
            'tirelanombre' => 'Empleado'
        ]);

        DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '2',
            'tirelanombre' => 'Asociado'
        ]);

        DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '3',
            'tirelanombre' => 'Conductor'
        ]);

        DB::table('tiporelacionlaboral')->insert([
            'tirelaid'     => '4',
            'tirelanombre' => 'Invitado'
        ]);
    }
}
