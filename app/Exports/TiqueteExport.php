<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, Auth, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class TiqueteExportExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
        $request   = $this->request;

        return ['Fecha movimiento', 'Código contable','Cuenta','Agencia','Usuario','Débito','Crédito'];
    }

    public function collection()
    {
        $request         = $this->request;
        $fechaInicial   = $request->fechaInicial;
        $fechaFinal     = $request->fechaFinal;
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $idUsuario       = Auth::id();
        $agenciaId       = auth()->user()->agenid;
        $cajaId          = auth()->user()->cajaid;

        $consulta = DB::table('comprobantecontabledetalle as ccd')
                        ->select('ccd.cocodefechahora', 'cc.cueconcodigo', 'cc.cueconnombre','a.agennombre',
                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 
                            DB::raw("(CASE WHEN cc.cueconnaturaleza = 'D' THEN COALESCE(ccd.cocodemonto, 0) ELSE 0 END) AS valorDebito"),
                            DB::raw("(CASE WHEN cc.cueconnaturaleza = 'C' THEN COALESCE(ccd.cocodemonto, 0) ELSE 0 END) AS valorCredito")
                        )
                        ->join('comprobantecontable as cct', 'cct.comconid', '=', 'ccd.comconid')
                        ->join('cuentacontable as cc', 'cc.cueconid', '=', 'ccd.cueconid')
                        ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                        ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                        ->join('usuario as u', 'u.usuaid', '=', 'cc.usuaid')
                        ->join('movimientocaja as mc', function($join)
                        {
                            $join->on('mc.movcajid', '=', 'cct.movcajid');
                            $join->on('mc.usuaid', '=', 'cct.usuaid');
                        });

                    if($fechaInicial !==''){
                        $consulta = $consulta->whereDate('mc.movcajfechahoraapertura', '>=', $fechaInicial)
                                            ->whereDate('mc.movcajfechahoraapertura', '<=', $fechaFinal);
                    }else{
                        $consulta = $consulta->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                                            ->where('mc.usuaid', $idUsuario)
                                            ->where('cct.agenid', $agenciaId)
                                            ->where('mc.cajaid', $cajaId);
                    }

                $consulta = $consulta->orderBy('ccd.cocodefechahora')->get();

        return $consulta;
    }
}