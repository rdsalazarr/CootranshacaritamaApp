<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB, AfterSheet, BeforeExport, Exportable, WithEvents;

class ArchivoHistoricoExport implements FromCollection, WithHeadings,WithProperties,WithTitle,ShouldAutoSize
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
            'title'          => 'Reporte del archivo historico',
            'description'    => 'Contiene todos los documento del archivo historico generados en la consulta',
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
        return 'Archivo historico';
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */  

    public function headings(): array
    {
        $request   = $this->request;  
        
        return [ 'Tipo documental', 'Estante', 'Caja', 'Carpeta', 'Asunto', 'Número de folio', 'Fecha del documento', 'Tomo', 
                'Código documental', 'Entidad remitente', 'Entidad productora','Resumen del documento', 'Observación'];
    }

    public function collection()
    {
        $request        = $this->request; 
        $fechaInicial   = $request->fechaInicial;
        $fechaFinal     = $request->fechaFinal;
        $tipoDocumental = $request->tipoDocumental;
        $estante        = $request->estante;
        $caja           = $request->caja;
        $carpeta        = $request->carpeta;
        $asunto         = $request->asuntoDocumento;

        $consulta = DB::table('archivohistorico as ah')
                        ->select('td.tipdocnombre','tea.tiesarnombre','tcu.ticaubnombre','tcb.ticrubnombre',
                        'ah.archisasuntodocumento','ah.archisnumerofolio','ah.archisfechadocumento',
                        'ah.archistomodocumento','ah.archiscodigodocumental','ah.archisentidadremitente',
                        'ah.archisentidadproductora','ah.archisresumendocumento','ah.archisobservacion')
                        ->join('tipodocumental as td', 'td.tipdocid', '=', 'ah.tipdocid')
                        ->join('tipoestantearchivador as tea', 'tea.tiesarid', '=', 'ah.tiesarid')
                        ->join('tipocajaubicacion as tcu', 'tcu.ticaubid', '=', 'ah.ticaubid')
                        ->join('tipocarpetaubicacion as tcb', 'tcb.ticrubid', '=', 'ah.ticrubid');

                    if($fechaInicial != '')
                        $consulta = $consulta->whereDate('ah.archisfechadocumento', '>=', $fechaInicial);
                    
                    if($fechaFinal != '')
                        $consulta = $consulta->whereDate('ah.archisfechadocumento', '<=', $fechaFinal);

                    if($tipoDocumental != '000')
                        $consulta = $consulta->where('ah.tipdocid', $tipoDocumental);

                    if($estante != '000')
                        $consulta = $consulta->where('ah.tiesarid', $estante);

                    if($caja != '000')
                        $consulta = $consulta->where('ah.ticaubid', $caja);

                    if($carpeta !='000')
                        $consulta = $consulta->where('ah.ticrubid', $carpeta);

                    if($asunto != '')
                        $consulta = $consulta->where('ah.archisasuntodocumento', 'LIKE', "%$asunto%");

        return $consulta->get();
    }
}