<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, Auth, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class TiqueteExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de tiquete',
            'description'    => 'Contiene todos los tiquetes vendidos',
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
        return 'Tiquetes';
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return ['Fecha registro', 'Fecha salida','Municipio origen','Municipio destino','Vehículo','Número tiquete','Nombre cliente','Valor tiquete',
                'Descuento', 'Valor seguro','Valor estampilla','Fondo reposición', 'Valor total', 'Contabilizado', 'Agencia venta'];
    }

    public function collection()
    {
        $request         = $this->request;
        $fechaInicial    = $request->fechaInicial;
        $fechaFinal      = $request->fechaFinal;

        $consulta =   DB::table('tiquete as t')
                            ->select('t.tiqufechahoraregistro','pr.plarutfechahorasalida',
                            'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino',
                            DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                            DB::raw("CONCAT(t.agenid, t.tiquanio, t.tiquconsecutivo) as numeroTiquete"),
                            DB::raw("CONCAT(ps.perserprimernombre,' ',IFNULL(ps.persersegundonombre,''),' ',ps.perserprimerapellido,' ',IFNULL(ps.persersegundoapellido,'')) as nombreCliente"),
                            't.tiquvalortiquete','t.tiquvalordescuento','t.tiquvalorseguro','t.tiquvalorestampilla','t.tiquvalorfondoreposicion','t.tiquvalortotal',
                            DB::raw("if(t.tiqucontabilizado = 1 ,'Sí', 'No') as contabilizado"),'ag.agennombre')
                        ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                        ->join('municipio as mo', function($join)
                        {
                            $join->on('mo.munidepaid', '=', 't.depaidorigen');
                            $join->on('mo.muniid', '=', 't.muniidorigen');
                        })
                        ->join('municipio as md', function($join)
                        {
                            $join->on('md.munidepaid', '=', 't.depaiddestino');
                            $join->on('md.muniid', '=', 't.muniiddestino');
                        })
                        ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                        ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->join('agencia as ag', 'ag.agenid', '=', 't.agenid')
                        ->whereDate('t.tiqufechahoraregistro', '>=', $fechaInicial)
                        ->whereDate('t.tiqufechahoraregistro', '<=', $fechaFinal)
                        ->orderBy('t.tiquid', 'Desc')->orderBy('pr.plarutid', 'Desc')->get();

        return $consulta;
    }
}