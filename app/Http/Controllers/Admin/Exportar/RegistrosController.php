<?php

namespace App\Http\Controllers\Admin\Exportar;

use App\Exports\MovimientosDiariosExport;
use App\Exports\ArchivoHistoricoExport;
use App\Exports\CarteraVencidaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\TiqueteExport;
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

    public function exportarMovimientoDiarios(Request $request){
        try {
            $nombreReporte            = 'Reporte_movimientos_diarios_'.Carbon::now().'.xls';
            $MovimientosDiariosExport = new MovimientosDiariosExport($request);
            return Excel::download($MovimientosDiariosExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }

    public function exportarTiquete(Request $request){
        try {
            $nombreReporte            = 'Reporte_tiquete_'.Carbon::now().'.xls';
            $TiqueteExport = new TiqueteExport($request);
            return Excel::download($TiqueteExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }
}