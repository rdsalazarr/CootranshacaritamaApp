<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class SubSerieDocumentalSeeder extends Seeder
{    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('subseriedocumental')->insert([
            'susedoid'     => '1',
            'serdocid'     => '1',
            'tipdocid'     => '1',
            'susedocodigo' => '01',
            'susedonombre' => 'Acta universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '2',
            'serdocid' => '2',
            'tipdocid' => '2',
            'susedocodigo' => '01',
            'susedonombre' => 'Certificado universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '3',
            'serdocid' => '3',
            'tipdocid' => '3',
            'susedocodigo' => '01',
            'susedonombre' => 'Circular universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '4',
            'serdocid' => '4',
            'tipdocid' => '4',
            'susedocodigo' => '01',
            'susedonombre' => 'Citación universal',
            'susedopermiteeliminar' => '0',
            'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '5',
            'serdocid' => '5',
            'tipdocid' => '5',
            'susedocodigo' => '01',
            'susedonombre' => 'Constancia universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '6',
            'serdocid' => '6',
            'tipdocid' => '6',
            'susedocodigo' => '01',
            'susedonombre' => 'Oficio universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]); 

        DB::table('subseriedocumental')->insert([
            'susedoid' => '7',
            'serdocid' => '7',
            'tipdocid' => '7',
            'susedocodigo' => '01',
            'susedonombre' => 'Resolución universal',
            'susedopermiteeliminar' => '0',
             'susedoactiva' => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);        
    }
}
