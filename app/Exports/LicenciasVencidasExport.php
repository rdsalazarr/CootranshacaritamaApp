<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, Auth, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class LicenciasVencidasExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de licencias',
            'description'    => 'Contiene todos las licencias vencidad o por vencer segun el rango de fecha proporcionado',
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
        return 'Licencias';
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {        
        return ['Documento', 'Nombre','Apellidos','Direccion','Celular','Número de licencia','Categoria','Fecha vencimiento'];
    }

    public function collection()
    {
        $request      = $this->request;
        $fechaInicial = $request->fechaInicial;
        $fechaFinal   = $request->fechaFinal;

        $consulta = DB::table('conductorlicencia as cl')
                        ->select('p.persdocumento',
                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre)) as nombrePersona"),
                        DB::raw("CONCAT(p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as apellidoPersona"),
                        'p.persdireccion','p.persnumerocelular','cl.conlicnumero','tcl.ticalinombre','cl.conlicfechavencimiento')
                        ->join('tipocategorialicencia as tcl', 'tcl.ticaliid', '=', 'cl.ticaliid')
                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                        ->join('persona as p', 'p.persid', '=', 'c.persid')
                        ->whereIn('cl.conlicid', function ($query){
                            $query->select(DB::raw('MAX(conlicid)'))
                                ->from('conductorlicencia')
                                ->groupBy('condid');
                        })
                        ->whereDate('cl.conlicfechavencimiento', '>=', $fechaInicial)
                        ->whereDate('cl.conlicfechavencimiento', '<=', $fechaFinal)
                        ->orderBy('nombrePersona')->orderBy('apellidoPersona')->get();

        return $consulta;
    }
}