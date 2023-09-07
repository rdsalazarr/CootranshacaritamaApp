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
            'persnumerocelular'  => '3204018506',
            'persgenero'    => 'M',
            'persrutafoto'  => null,
            'persrutafirma' => null,
            'persactiva'    => '1',
            'created_at'    => $fechaHoraActual,
            'updated_at'    => $fechaHoraActual,
        ]); 

    }
}
