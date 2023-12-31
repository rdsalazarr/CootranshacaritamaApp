<?php

namespace App\Http\Controllers\Admin\Exportar;

use App\Exports\CarteraVencidaExport;
use App\Exports\ArchivoHistoricoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class RegistrosController extends Controller
{
    public function exportarConsultaAH(Request $request){
        $this->validate(request(),['fechaInicial'     => 'nullable|date|date_format:Y-m-d',
                                    'fechaFinal'      => 'nullable|date|date_format:Y-m-d',
                                    'asuntoDocumento' => 'nullable|string|min:4|max:100'
                                ]);

        try {
            $nombreReporte         = 'Reporte_consulta_archivo_historico_'.Carbon::now().'.xls'; 
            $archivoHisoricoExport = new ArchivoHistoricoExport($request);
            return Excel::download($archivoHisoricoExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
	}

    public function exportarCarteraVencida(Request $request){
        try {
            $nombreReporte        = 'Reporte_cartera_vencida_'.Carbon::now().'.xls';
            $carteraVencidaExport = new CarteraVencidaExport($request);
            return Excel::download($carteraVencidaExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }
}