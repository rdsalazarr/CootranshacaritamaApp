<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class VehiculosExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de vehículos',
            'description'    => 'Contiene todos los vehículos registrados en el sistema',
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
        return 'Vehículos';
    }

    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return ['Tipo de vehículo','Tipo de referencia', 'Tipo de marca', 'Tipo de color','Tipo de modalidad', 'Tipo de carroceria','Tipo de combustible',
                'Agencia','Observación', 'Fecha de ingreso', 'Número interno', 'Placa', 'Modelo','Cilindraje', 'Número del motor','Número de chasis',
                'Número de serie','Número de ejes','Estado actual','Motor regrabado', 'Chasis regrabado','Serie regrabado', 'Nombre del asociado'];
    }

    public function collection()
    {
        $request  = $this->request;
        $consulta = DB::table('vehiculo as v')
        ->select('tv.tipvehnombre as tipoVehiculo', 'trv.tirevenombre as tipoReferencia','tmv.timavenombre as tipoMarca',
                'tcv.ticovenombre as tipoColor','tmvh.timovenombre as tipoModalidad','tcrh.ticavenombre as tipoCarroceria',
                'tcvh.ticovhnombre as tipoCombustible','a.agennombre as agencia','v.vehiobservacion',
                'v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje',
                'v.vehinumeromotor','v.vehinumerochasis','v.vehinumeroserie','v.vehinumeroejes', 'tev.tiesvenombre as estadoActual',
                DB::raw("if(v.vehiesmotorregrabado = 1 ,'Sí', 'No') as motorRegrabado"),
                DB::raw("if(v.vehieschasisregrabado = 1 ,'Sí', 'No') as chasisRegrabado"),
                DB::raw("if(v.vehiesserieregrabado = 1 ,'Sí', 'No') as serieRegrabado"),
                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                ->join('asociado as aso', 'aso.asocid', '=', 'v.asocid')
                ->join('persona as p', 'p.persid', '=', 'aso.persid')
                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                ->join('tiporeferenciavehiculo as trv', 'trv.tireveid', '=', 'v.tireveid')
                ->join('tipomarcavehiculo as tmv', 'tmv.timaveid', '=', 'v.timaveid')
                ->join('tipocolorvehiculo as tcv', 'tcv.ticoveid', '=', 'v.ticoveid')
                ->join('tipomodalidadvehiculo as tmvh', 'tmvh.timoveid', '=', 'v.timoveid')
                ->join('tipocarroceriavehiculo as tcrh', 'tcrh.ticaveid', '=', 'v.ticaveid')
                ->join('tipocombustiblevehiculo as tcvh', 'tcvh.ticovhid', '=', 'v.ticovhid')
                ->join('tipoestadovehiculo as tev', 'tev.tiesveid', '=', 'v.tiesveid')
                ->join('agencia as a', 'a.agenid', '=', 'v.agenid')
                ->orderBy('v.vehiplaca')->get();
        return $consulta;
    }
}