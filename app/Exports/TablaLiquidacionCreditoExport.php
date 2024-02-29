<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;

class TablaLiquidacionCreditoExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
{
    protected $request;

    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'IMPLESOFT',
            'lastModifiedBy' => 'IMPLESOFT',
            'title'          => 'Reporte tabla de liquidación de crédito',
            'description'    => 'Contiene todos los moviemiento de la tabla de liquidación del crédito',
            'subject'        => 'HACARITAMA',
            'keywords'       => 'Reporte',
            'category'       => 'reporte',
            'manager'        => 'IMPLESOFT',
            'company'        => 'www.implesoft.com',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Tabla liquidacion';
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [ 'Cuota','Valor cuota','Fecha vencimiento','Comprobante','Fecha pago','Valor pagado','Saldo capital',
                'Capital pagado','Intereses pagado','Intereses devuelto', 'Intereses mora'];
    }

    public function collection()
    {
        $request   = $this->request; 
        $consulta  = DB::table('colocacionliquidacion as cl')
                        ->select('cl.colliqnumerocuota','cl.colliqvalorcuota','cl.colliqfechavencimiento',
                            DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),'cl.colliqfechapago',
                            'cl.colliqvalorpagado','cl.colliqsaldocapital','cl.colliqvalorcapitalpagado','cl.colliqvalorinterespagado',
                            'cl.colliqvalorinteresdevuelto','cl.colliqvalorinteresmora')
                        ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                        ->join('comprobantecontable as cc', 'cc.comconid', '=', 'cl.comconid') 
                        ->where('c.coloid', $request->colocacionId)
                        ->orderBy('cl.colliqfechapago')->get();

        return $consulta;
    }
}