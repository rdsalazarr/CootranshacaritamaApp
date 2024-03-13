<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class CuentaContableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('cuentacontable')->insert([
            'cueconid'          => '1',
            'cueconnombre'      => 'caja',
            'cuecondescripcion' => 'CAJA',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '110001',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '2',
            'cueconnombre'      => 'banco',
            'cuecondescripcion' => 'BANCO',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '110002',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '3',
            'cueconnombre'      => 'desembolso',
            'cuecondescripcion' => 'VALOR DESEMBOLSO',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '120003',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '4',
            'cueconnombre'      => 'pagoMensualidad',
            'cuecondescripcion' => 'PAGO MENSUALIDAD',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120004',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '5',
            'cueconnombre'      => 'pagoMensualidadTotal',
            'cuecondescripcion' => 'PAGO MENSUALIDAD TOTAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120005',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '6',
            'cueconnombre'      => 'pagoMensualidadParcial',
            'cuecondescripcion' => 'PAGO MENSUALIDAD PARCIAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120006',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '7',
            'cueconnombre'      => 'pagoCuotaCredito',
            'cuecondescripcion' => 'PAGO CUOTA CRÉDITO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120007',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '8',
            'cueconnombre'      => 'pagoCreditoTotal',
            'cuecondescripcion' => 'PAGO CRÉDITO TOTAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120008',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '9',
            'cueconnombre'      => 'pagoSancion',
            'cuecondescripcion' => 'PAGO SANCIÓN',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120009',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);     

        DB::table('cuentacontable')->insert([
            'cueconid'          => '10',
            'cueconnombre'      => 'pagoTiquete',
            'cuecondescripcion' => 'PAGO DE TIQUETE',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120010',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '11',
            'cueconnombre'      => 'fondoReposicion',
            'cuecondescripcion' => 'FONDO REPOSICIÓN',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120011',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '12',
            'cueconnombre'      => 'pagoEstampilla',
            'cuecondescripcion' => 'PAGO ESTAMIPILLA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120012',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '13',
            'cueconnombre'      => 'pagoSeguro',
            'cuecondescripcion' => 'PAGO SEGURO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120013',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '14',
            'cueconnombre'      => 'descuentoTiquete',
            'cuecondescripcion' => 'DESCUENTO TIQUETE',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120014',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '15',
            'cueconnombre'      => 'comisionEncomiendaEmpresa',
            'cuecondescripcion' => 'COMISIÓN ENCOMIENDA EMPRESA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120015',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '16',
            'cueconnombre'      => 'comisionEncomiendaAgencia',
            'cuecondescripcion' => 'COMISIÓN ENCOMIENDA AGENCIA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120016',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '17',
            'cueconnombre'      => 'comisionEncomiendaVehiculo',
            'cuecondescripcion' => 'COMISIÓN ENCOMIENDA VEHÍCULO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120017',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '18',
            'cueconnombre'      => 'pagoEncomiendaDomicilio',
            'cuecondescripcion' => 'PAGO ENCOMIENDA A DOMICILIO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '1200018',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '19',
            'cueconnombre'      => 'pagoEncomiendaContraentrega',
            'cuecondescripcion' => 'PAGO ENCOMIENDA CONTRAENTREGA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '1200019',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '20',
            'cueconnombre'      => 'valorFondoRecaudo',
            'cuecondescripcion' => 'VALOR FONDO DE RECAUDO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '1200020',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '21',
            'cueconnombre'      => 'valorPuntoRedimir',
            'cuecondescripcion' => 'VALOR PUNTOS A REDIMIR',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '1200021',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);
    }
}