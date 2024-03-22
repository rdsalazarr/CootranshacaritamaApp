<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class IngresoUsuarioExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de ingreso de usuario',
            'description'    => 'Contiene todos los ingreso realizados por el usario al sistema',
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
        return 'Ingreso usuario';
    }

    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return ['Documento','Nombre completo', 'Usuario', 'IP acceso','Fecha ingreso', 'Fecha salida'];
    }

    public function collection()
    {
        $request  = $this->request;
        $consulta = DB::table('usuario as u')
                            ->select(DB::raw("CONCAT(ti.tipidesigla,'-', p.persdocumento ) as tipoDocumento"),
                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),'u.usuanick',
                                "is.ingsisipacceso","is.ingsisfechahoraingreso","is.ingsisfechahorasalida")
                                ->join('persona as p', 'p.persid', '=', 'u.persid')
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->join('ingresosistema as is', 'is.usuaid', '=', 'u.usuaid')
                            ->where('u.usuaid', $request->codigo)->get();
        return $consulta;
    }
}