<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('persona')->insert([
            'persid'    => '1',
            'carlabid'  => '1',
            'tipideid'  => '1',
            'tirelaid'  => '1',
            'persdepaidnacimiento' => 18, 
            'persmuniidnacimiento' => 789,
            'persdepaidexpedicion' => 18, 
            'persmuniidexpedicion' => 804,
            'persdocumento'        => '1978917',
            'persprimernombre'     => 'RAMÓN',
            'perssegundonombre'    => 'DAVID',
            'persprimerapellido'   => 'SALAZAR',
            'perssegundoapellido'  => 'RINCÓN',
            'persfechanacimiento'  => '1979-08-29',
            'persdireccion'        => 'Calle 4 36 49',
            'perscorreoelectronico'  => 'radasa10@hotmail.com',
            'persfechadexpedicion'   => '1998-04-16',
            'persnumerotelefonofijo' => '3204018506',
            'persnumerocelular'     => '3204018506',
            'persgenero'            => 'M',
            'persrutafoto'          => null,
            'persrutafirma'         => 'Firma_1978917.png',
            'persactiva'            => '1',
            'perstienefirmadigital' => '1',
            //'persrutacrt'           => '1978917.crt',
            //'persrutapem'           => '1978917.pem',
            'persclavecertificado'  => '123456',
            'created_at'            => $fechaHoraActual,
            'updated_at'            => $fechaHoraActual,
        ]); 

        DB::table('persona')->insert([
            'persid'    => '2',
            'carlabid'  => '1',
            'tipideid'  => '1',
            'tirelaid'  => '1',
            'persdepaidnacimiento' => 9, 
            'persmuniidnacimiento' => 416,
            'persdepaidexpedicion' => 9, 
            'persmuniidexpedicion' => 416,
            'persdocumento'        => '5036123',
            'persprimernombre'     => 'LUIS',    
            'perssegundonombre'    => 'MANUEL',
            'persprimerapellido'   => 'ASCANIO',
            'perssegundoapellido'  => 'CLARO',
            'persfechanacimiento'  => '1979-08-29',
            'persdireccion'        => 'Calle 4 36 49',
            'perscorreoelectronico'  => 'luisangel330@hotmail.com',
            'persfechadexpedicion'   => '1998-04-16',
            'persnumerotelefonofijo' => '3163374329',
            'persnumerocelular'  => '3163374329',
            'persgenero'    => 'M',
            'persrutafoto'  => null,
            'persrutafirma' => null,
            'persactiva'    => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]); 

    }
}
