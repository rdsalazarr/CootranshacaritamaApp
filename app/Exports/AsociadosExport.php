<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class AsociadosExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de asociados',
            'description'    => 'Contiene todos los asociados registrados en el sistema',
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
        return 'Asociados';
    }

    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        return ['Tipo documento', 'Documento','Nombres','Apellidos','Tipo persona','Fecha nacimiento','Departamento de nacimiento','Municipio de nacimiento',
                'Fecha expedición', 'Departamento de expedición', 'Municipio de expedición', 'Dirección', 'Correo', 'Telefóno fijo','Número de celular', 'Género',
                'Firma electrónica',  'Fecha ingreso asociado','Activo'];
    }

    public function collection()
    {
        $request  = $this->request;
        $consulta   = DB::table('persona as p')
                                ->select(DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"), 'p.persdocumento',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre)) as nombrePersona"),
                                        DB::raw("CONCAT(p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as apellidoPersona"),'tp.tippernombre',
                                        'p.persfechanacimiento','dn.depanombre as deptoNacimiento','mn.muninombre as municipioNacimiento',
                                        'p.persfechadexpedicion','de.depanombre as deptoExpedicion','me.muninombre as municipioExpedicion','p.persdireccion','p.perscorreoelectronico',                                     
                                        DB::raw("if(p.persnumerotelefonofijo = null ,'', p.persnumerotelefonofijo) as telefonoFijo"),
                                        DB::raw("if(p.persnumerocelular = null ,'', p.persnumerocelular) as telefonoCelular"), 
                                        DB::raw("if(p.persgenero = 'M' ,'Masculino', 'Femenino') as genero"),
                                        DB::raw("if(p.perstienefirmaelectronica = 1 ,'Si', 'No') as firmaElectronica"),'a.asocfechaingreso',
                                        DB::raw("if(a.tiesasid = 'A' ,'Si', 'No') as estado"))                               
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
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                            ->orderBy('nombrePersona')->orderBy('apellidoPersona')->get();
        return $consulta;
    }
}