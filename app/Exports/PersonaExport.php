<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class PersonaExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de persona',
            'description'    => 'Contiene todas las persona que se encuentra registrados en el sistema',
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
        return 'Personas';
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    { 
        return ['Tipo documento', 'Documento','Nombres','Apellidos','Tipo persona','Fecha nacimiento','Departamento de nacimiento','Municipio de nacimiento',
                'Fecha expedición', 'Departamento de expedición', 'Municipio de expedición', 'Dirección', 'Correo', 'Telefóno fijo','Número de celular', 'Género',
                'Firma electrónica', 'Firma digital', 'Fecha ingreso asociado', 'Fecha ingreso conductor', 'Tipo de conductor', 'Agencia asignada el conductor'];
    }

    public function collection()
    {
        $request    = $this->request;  
        $consulta   = DB::table('persona as p')
                                ->select(DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"), 'p.persdocumento',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre)) as nombrePersona"),
                                        DB::raw("CONCAT(p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as apellidoPersona"),'tp.tippernombre',
                                        'p.persfechanacimiento','dn.depanombre as deptoNacimiento','mn.muninombre as municipioNacimiento',
                                        'p.persfechadexpedicion','de.depanombre as deptoExpedicion','me.muninombre as municipioExpedicion','p.persdireccion','p.perscorreoelectronico',                                     
                                        DB::raw("if(p.persnumerotelefonofijo = null ,'', p.persnumerotelefonofijo) as telefonoFijo"),
                                        DB::raw("if(p.persnumerocelular = null ,'', p.persnumerocelular) as telefonoCelular"), 
                                        DB::raw("if(p.persgenero = 'M' ,'Masculino', 'Femenino') as genero"),
                                        DB::raw("if(p.perstienefirmaelectronica = 1 ,'Sí', 'No') as firmaElectronica"),
                                        DB::raw("if(p.perstienefirmadigital = 1 ,'Sí', 'No') as firmaDigital"),
                                        'a.asocfechaingreso','c.condfechaingreso', 'tc.tipconnombre','ag.agennombre' )
                                ->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->join('tipopersona as tp', 'tp.tipperid', '=', 'p.tipperid')
                                ->join('departamento as dn', 'dn.depaid', '=', 'p.persdepaidnacimiento') 
                                ->join('municipio as mn', function($join)
                                {
                                    $join->on('mn.munidepaid', '=', 'p.persdepaidnacimiento');
                                    $join->on('mn.muniid', '=', 'p.persmuniidnacimiento'); 
                                })
                                ->join('departamento as de', 'de.depaid', '=', 'p.persdepaidexpedicion') 
                                ->join('municipio as me', function($join)
                                {
                                    $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                                    $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                                })
                                ->leftJoin('asociado as a', 'a.persid', '=', 'p.persid')
                                ->leftJoin('conductor as c', 'c.persid', '=', 'p.persid')
                                ->leftJoin('tipoconductor as tc', 'tc.tipconid', '=', 'c.tipconid')
                                ->leftJoin('agencia as ag', 'ag.agenid', '=', 'c.agenid')
                            ->orderBy('nombrePersona')->orderBy('apellidoPersona')->get();
        return $consulta;
    }
}