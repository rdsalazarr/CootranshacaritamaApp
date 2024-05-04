<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;
use Carbon\Carbon;

class RutasExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte de rutas',
            'description'    => 'Contiene todas las rutas que se encuentra registrados en el sistema',
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
        return 'Rutas';
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    { 
        return ['Departamento origen', 'Municipio origen','Departamento destino','Municipio destino','Ruta activa','Tiene nodos','Municipio origen del nodo',
                'Municipio destino del nodo', 'Departamento origen del tiquete', 'Municipio origen del tiquete','Departamento destino del tiquete',
                'Municipio destinodel tiquete','Valor tiquete', 'Valor seguro', 'Valor estampilla', 'Fondo de reposición', 'Fondo de recaudo'];
    }

    public function collection()
    {
        $request   = $this->request;  
        $dataRutas  = DB::table('ruta as r')
                                ->select('r.rutaid', 'do.depanombre as nombreDeptoOrigen', 'mo.muninombre as nombreMunicipioOrigen',
                                'de.depanombre as nombreDeptoDestino', 'md.muninombre as nombreMunicipioDestino', 
                                DB::raw("if(r.rutaactiva = 1 ,'Sí', 'No') as estado"),
                                DB::raw("if(r.rutatienenodos = 1 ,'Sí', 'No') as tieneNodos"))
                                ->join('departamento as do', 'do.depaid', '=', 'r.rutadepaidorigen')
                                ->join('municipio as mo', function($join)
                                {
                                    $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                                    $join->on('mo.muniid', '=', 'r.rutamuniidorigen'); 
                                })
                                ->join('departamento as de', 'de.depaid', '=', 'r.rutadepaiddestino') 
                                ->join('municipio as md', function($join)
                                {
                                    $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                    $join->on('md.muniid', '=', 'r.rutamuniiddestino'); 
                                })
                                ->orderBy('do.depanombre')->orderBy('mo.muninombre')
                                ->orderBy('de.depanombre')->orderBy('md.muninombre')->get();
        
        $rutaNodos   = DB::table('rutanodo as rn')
                                ->select('rn.rutaid','d.depaid','d.depanombre','m.muniid', 'm.muninombre')
                                ->join('departamento as d', 'd.depaid', '=', 'rn.rutnoddepaid')
                                ->join('municipio as m', function($join)
                                {
                                    $join->on('m.munidepaid', '=', 'rn.rutnoddepaid');
                                    $join->on('m.muniid', '=', 'rn.rutnodmuniid');
                                })                              
                                ->orderBy('m.muninombre')->get();
        $rutaNodosArray = $rutaNodos->toArray();

        $tarifaTiquetes = DB::table('tarifatiquete as tt')
                                ->select('tt.tartiqid', 
                                'do.depanombre as nombreDeptoOrigen', 'mo.muninombre as nombreMunicipioOrigen',
                                'de.depanombre as nombreDeptoDestino', 'md.muninombre as nombreMunicipioDestino',
                                DB::raw("CONCAT(FORMAT(tt.tartiqvalor, 0)) as valorTiquete"),
                                DB::raw("CONCAT(FORMAT(tt.tartiqfondoreposicion, 0)) as valorFondoReposicion"),
                                DB::raw("CONCAT(FORMAT(tt.tartiqvalorestampilla, 0)) as valorEstampilla"),
                                DB::raw("CONCAT(FORMAT(tt.tartiqvalorseguro, 0)) as valorSeguro"),
                                DB::raw("CONCAT(FORMAT(tt.tartiqvalorfondorecaudo, 0)) as valorFondoRecaudo"))
                                ->join('departamento as do', 'do.depaid', '=', 'tt.tartiqdepaidorigen')
                                ->join('municipio as mo', function($join)
                                {
                                    $join->on('mo.munidepaid', '=', 'tt.tartiqdepaidorigen');
                                    $join->on('mo.muniid', '=', 'tt.tartiqmuniidorigen'); 
                                })
                                ->join('departamento as de', 'de.depaid', '=', 'tt.tartiqdepaiddestino') 
                                ->join('municipio as md', function($join)
                                {
                                    $join->on('md.munidepaid', '=', 'tt.tartiqdepaiddestino');
                                    $join->on('md.muniid', '=', 'tt.tartiqmuniiddestino'); 
                                })->get();
        $tarifaTiquetesArray = $tarifaTiquetes->toArray();

        $consulta = [];
        foreach($dataRutas as $dataRuta){
            $rutaid           = $dataRuta->rutaid;
            $deptoOrigen      = $dataRuta->nombreDeptoOrigen;
            $municipioOrigen  = $dataRuta->nombreMunicipioOrigen;
            $deptoDestino     = $dataRuta->nombreDeptoDestino;
            $municipioDestino = $dataRuta->nombreMunicipioDestino;
            $tieneNodos       = $dataRuta->tieneNodos;
            $estado           = $dataRuta->estado;

            $array = [
                "deptoOrigen"              => $deptoOrigen,
                "municipioOrigen"          => $municipioOrigen,
                "deptoDestino"             => $deptoDestino,
                "municipioDestino"         => $municipioDestino,
                "estado"                   => $estado,
                "tieneNodos"               => $tieneNodos,                
                "municipioOrigenNodo"      => '',
                "municipioDestinoNodo"     => '',
                "deptoOrigenTiquete"       => '',
                "municipioOrigenTiquete"   => '',
                "deptoDestinoTiquete"      => '',
                "municipioDestiinoTiquete" => '',
                "valorTiquete"             => '',
                "valorSeguro"              => '',
                "valorEstampilla"          => '',
                "valorFondoReposicion"     => '',
                "valorFonfoRecaudo"        => ''
            ];
            array_push($consulta, $array);

            $rutaNodosFiltrados = array_filter($rutaNodosArray, function($item) use ($rutaid) { 
                return $item->rutaid === $rutaid;
            });

            foreach($rutaNodosFiltrados as $rutaNodo){
                $array = [
                    "deptoOrigen"              => '',
                    "municipioOrigen"          => '',
                    "deptoDestino"             => '',
                    "municipioDestino"         => '',
                    "tieneNodos"               => '',
                    "estado"                   => '',
                    "municipioOrigenNodo"      => $rutaNodo->depanombre,
                    "municipioDestinoNodo"     => $rutaNodo->muninombre,
                    "deptoOrigenTiquete"       => '',
                    "municipioOrigenTiquete"   => '',
                    "deptoDestinoTiquete"      => '',
                    "municipioDestiinoTiquete" => '',
                    "valorTiquete"             => '',
                    "valorSeguro"              => '',
                    "valorEstampilla"          => '',
                    "valorFondoReposicion"     => '',
                    "valorFonfoRecaudo"        => ''
                ];
                array_push($consulta, $array);
            }

            foreach($tarifaTiquetesArray as $tarifaTiquete){
                $array = [
                    "deptoOrigen"              => '',
                    "municipioOrigen"          => '',
                    "deptoDestino"             => '',
                    "municipioDestino"         => '',
                    "tieneNodos"               => '',
                    "estado"                   => '',
                    "municipioOrigenNodo"      => '',
                    "municipioDestinoNodo"     => '',
                    "deptoOrigenTiquete"       => $tarifaTiquete->nombreDeptoOrigen,
                    "municipioOrigenTiquete"   => $tarifaTiquete->nombreMunicipioOrigen,
                    "deptoDestinoTiquete"      => $tarifaTiquete->nombreDeptoDestino,
                    "municipioDestiinoTiquete" => $tarifaTiquete->nombreMunicipioDestino,
                    "valorTiquete"             => $tarifaTiquete->valorTiquete,
                    "valorSeguro"              => $tarifaTiquete->valorSeguro,
                    "valorEstampilla"          => $tarifaTiquete->nombreMunicipioOrigen,
                    "valorFondoReposicion"     => $tarifaTiquete->valorFondoReposicion,
                    "valorFonfoRecaudo"        => $tarifaTiquete->valorFondoRecaudo
                ];
                array_push($consulta, $array);
            }
        }

        return collect($consulta);
    }
}