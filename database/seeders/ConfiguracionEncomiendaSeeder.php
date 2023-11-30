<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ConfiguracionEncomiendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('configuracionencomienda')->insert([
            'conencid'                      => '1',
            'conencvalorminimoenvio'        => '3900',
            'conencvalorminimodeclarado'    => '10000',
            'conencporcentajeseguro'        => '1.0',
            'conencporcencomisionempresa'   => '10.0',
            'conencporcencomisionagencia'   => '20.0',
            'conencporcencomisionvehiculo'  => '70.0',
            'created_at'                    => $fechaHoraActual,
            'updated_at'                    => $fechaHoraActual,
        ]);

        DB::table('mensajeimpresion')->insert([
            'menimpid'     => '1',
            'menimpnombre' => 'TIQUETES',
            'menimpvalor'  => '*** FELIZ NAVIDAD Y PROSPERO AÑO 2024 ***',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);   

        DB::table('mensajeimpresion')->insert([
            'menimpid'     => '2',
            'menimpnombre' => 'PLANILLA',
            'menimpvalor'  => '*** FELIZ VIAJE Y PRONTO REGRESO ***',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('mensajeimpresion')->insert([
            'menimpid'     => '3',
            'menimpnombre' => 'ENCOMIENDAS',
            'menimpvalor'  => 'Su encomienda será tratada con la máxima gentileza posible',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('mensajeimpresion')->insert([
            'menimpid'     => '4',
            'menimpnombre' => 'RECAUDO',
            'menimpvalor'  => 'Agradecemos su contribución y compromiso con lo pactado. ¡Gracias!',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}