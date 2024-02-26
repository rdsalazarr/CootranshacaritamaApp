<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class CarteraVencidaExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de cartera vencida',
            'description'    => 'Contiene todos los asocidados que presenta cartera vencida',
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
        return 'Cartera vencida';
    }

    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return [ 'Documento','Nombre asociado','Línea crédito','Número de crédito','Fecha desembolso','Valor', 'Vehículo', 'Placa', 'Número interno', 'Días en mora'];
    }

    public function collection()
    {
        $request         = $this->request;
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $consulta        = DB::table('colocacion as c')->select('p.persdocumento',
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"),
                                'lc.lincrenombre', DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"), 'c.colofechacolocacion','c.colovalordesembolsado', 
                                DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia) ) as referenciaVehiculo"),'v.vehiplaca', 'v.vehinumerointerno', 
                                DB::raw("DATEDIFF(NOW(), c.colofechacolocacion) as diasMora"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                ->join('vehiculo as v', 'v.vehiid', '=', 'sc.vehiid')
                                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->whereIn('c.coloid', function($query) use ($fechaActual) {
                                    $query->select('coloid')
                                        ->from('colocacionliquidacion')
                                        ->whereDate('colliqfechavencimiento', '<=', $fechaActual)
                                        ->whereNull('colliqfechapago');
                                })
                                ->orderBy('c.colofechacolocacion')->get();
        return $consulta;
    }
}