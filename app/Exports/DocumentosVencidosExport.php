<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, Auth, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class DocumentosVencidosExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'description'    => 'Contiene todos documentos de los vehículos vencidos o por vencer',
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
        $request     = $this->request;
        $tipoReporte = $request->tipoReporte;

        return ($tipoReporte === 'SOAT') ? 'Soat vencidos' : (($tipoReporte === 'CRT') ? 'CRT vencidos' :'Tarjeta de operación' );

    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return ['Tipo de vehículo','Número interno', 'Placa','Modalidad','Número','Fecha vencimiento', 'Estado'];
    }

    public function collection()
    {
        $request         = $this->request;
        $tipoReporte     = $request->tipoReporte;
        $fechaInicial    = $request->fechaInicial;
        $fechaFinal      = $request->fechaFinal;
        /*$fechaSuperiror  = Carbon::now()->addDays(30)->toDateString();
        $fechaSuperiror  = Carbon::parse($fechaSuperiror);
        $fechaFinal      = $fechaSuperiror->format('Y-m-d');*/
        return ($tipoReporte === 'SOAT') ? $this->soat($fechaInicial, $fechaFinal) : (($tipoReporte === 'CRT') ? $this->CRT($fechaInicial, $fechaFinal) : $this->tarjetaOperacion($fechaInicial, $fechaFinal));
    }

    public function soat($fechaInicial, $fechaFinal){
        return DB::table('vehiculosoat as vs')
                ->select('tv.tipvehnombre','v.vehinumerointerno','v.vehiplaca','tmv.timovenombre','vs.vehsoanumero',
                    'vs.vehsoafechafinal',DB::raw('(CASE WHEN vs.vehsoafechafinal < CURDATE() THEN "Vencido" ELSE "Vigente" END) AS estado'))
                ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                ->whereIn('vs.vehsoaid', function ($query){
                    $query->select(DB::raw('MAX(vehsoaid)'))
                        ->from('vehiculosoat')
                        ->groupBy('vehiid');
                })            
                ->whereDate('vs.vehsoafechainicial', '>=', $fechaInicial)
                ->whereDate('vs.vehsoafechafinal', '<=', $fechaFinal)
                ->orderBy('v.vehinumerointerno')
                ->get();
    }

    public function CRT($fechaInicial, $fechaFinal){
        return DB::table('vehiculocrt as vcrt')
                ->select('tv.tipvehnombre','v.vehinumerointerno','v.vehiplaca','tmv.timovenombre','vcrt.vehcrtnumero',
                    'vcrt.vehcrtfechafinal',DB::raw('(CASE WHEN vcrt.vehcrtfechafinal < CURDATE() THEN "Vencido" ELSE "Vigente" END) AS estado'))
                ->join('vehiculo as v', 'v.vehiid', '=', 'vcrt.vehiid')
                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                ->whereIn('vcrt.vehcrtid', function ($query){
                    $query->select(DB::raw('MAX(vehcrtid)'))
                        ->from('vehiculocrt')
                        ->groupBy('vehiid');
                })    
                ->whereDate('vcrt.vehcrtfechainicial', '>=', $fechaInicial)
                ->whereDate('vcrt.vehcrtfechafinal', '<=', $fechaFinal)
                ->orderBy('v.vehinumerointerno')
                ->get();
    }

    public function tarjetaOperacion($fechaInicial, $fechaFinal){
        return DB::table('vehiculotarjetaoperacion as vto')
                ->select('tv.tipvehnombre','v.vehinumerointerno','v.vehiplaca','tmv.timovenombre','vto.vetaopnumero',
                    'vto.vetaopfechafinal',DB::raw('(CASE WHEN vto.vetaopfechafinal < CURDATE() THEN "Vencida" ELSE "Vigente" END) AS estado'))
                ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                ->whereIn('vto.vetaopid', function ($query){
                    $query->select(DB::raw('MAX(vetaopid)'))
                        ->from('vehiculocrt')
                        ->groupBy('vehiid');
                })
                ->whereDate('vto.vetaopfechainicial', '>=', $fechaInicial)
                ->whereDate('vto.vetaopfechafinal', '<=', $fechaFinal)
                ->orderBy('v.vehinumerointerno')
                ->get();
    }
}