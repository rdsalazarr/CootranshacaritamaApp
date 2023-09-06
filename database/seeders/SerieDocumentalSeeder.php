<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SerieDocumentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('serdocedocumental')->insert([
            'serdocid'     => '1',
            'serdoccodigo' => '001',
            'serdocnombre' => 'Acta',
            'serdoctiempoarchivogestion'   => '360',
            'serdoctiempoarchivocentral'   => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar'        => '0',
            'serdocactiva' => '1'
        ]);   

        DB::table('serdocedocumental')->insert([
            'serdocid'     => '2',
            'serdoccodigo' => '002',
            'serdocnombre' => 'Certificado',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]); 

        DB::table('serdocedocumental')->insert([
            'serdocid' => '3',
            'serdoccodigo' => '003',
            'serdocnombre' => 'Circular',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '4',
            'serdoccodigo' => '004',
            'serdocnombre' => 'Citación',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '5',
            'serdoccodigo' => '005',
            'serdocnombre' => 'Constancia',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '6',
            'serdoccodigo' => '006',
            'serdocnombre' => 'Memorando',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '7',
            'serdoccodigo' => '007',
            'serdocnombre' => 'Oficio',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '8',
            'serdoccodigo' => '008',
            'serdocnombre' => 'Resolucion',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);

        DB::table('serdocedocumental')->insert([
            'serdocid' => '9',
            'serdoccodigo' => '009',
            'serdocnombre' => 'Nota Interna',
            'serdoctiempoarchivogestion' => '360',
            'serdoctiempoarchivocentral' => '720',
            'serdoctiempoarchivohistorico' => '1440',
            'serdocpermiteeliminar' => '0',
            'serdocactiva' => '1'
        ]);
    }
}
