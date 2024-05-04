<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoDocumentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '1',
            'tipdoccodigo' => 'A',
            'tipdocnombre' => 'Acta',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '2',
            'tipdoccodigo' => 'B',
            'tipdocnombre' => 'Certificado',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '3',
            'tipdoccodigo' => 'C',
            'tipdocnombre' => 'Circular',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '4',
            'tipdoccodigo' => 'H',
            'tipdocnombre' => 'Citación',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '5',
            'tipdoccodigo' => 'T',
            'tipdocnombre' => 'Constancia',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '6',
            'tipdoccodigo' => 'O',
            'tipdocnombre' => 'Oficio',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '7',
            'tipdoccodigo' => 'R',
            'tipdocnombre' => 'Resolución',
            'tipdocactivo' => '0',
            'tipdocproducedocumento' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 
    }
}
