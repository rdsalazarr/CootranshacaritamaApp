<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, Auth, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class MovimientosDiariosExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de movimiento diarios',
            'description'    => 'Contiene todos los movimiento diarios por usuario y caja',
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
        return 'Movimientos diarios';
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        $request   = $this->request;  
        
        return ['Fecha movimiento', 'Código contable','Cuenta','Débito','Crédito'];
    }

    public function collection()
    {
        $request         = $this->request; 
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $idUsuario       = Auth::id();
        $agenciaId       = auth()->user()->agenid;
        $cajaId          = auth()->user()->cajaid;

        $consulta = DB::table('comprobantecontabledetalle as ccd')
                        ->select('ccd.cocodefechahora', 'cc.cueconcodigo', 'cc.cueconnombre',
                            DB::raw("(CASE WHEN cc.cueconnaturaleza = 'D' THEN COALESCE(ccd.cocodemonto, 0) ELSE 0 END) AS valorDebito"),
                            DB::raw("(CASE WHEN cc.cueconnaturaleza = 'C' THEN COALESCE(ccd.cocodemonto, 0) ELSE 0 END) AS valorCredito")
                        )
                        ->join('comprobantecontable as cct', 'cct.comconid', '=', 'ccd.comconid')
                        ->join('cuentacontable as cc', 'cc.cueconid', '=', 'ccd.cueconid')
                        ->join('movimientocaja as mc', function($join)
                        {
                            $join->on('mc.movcajid', '=', 'cct.movcajid');
                            $join->on('mc.usuaid', '=', 'cct.usuaid');
                        })
                        ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                        ->where('mc.usuaid', $idUsuario)
                        ->where('cct.agenid', $agenciaId)
                        ->where('mc.cajaid', $cajaId)
                        ->orderBy('ccd.cocodefechahora')
                        ->get();

        return $consulta;
    }
}