<?php

namespace App\Http\Controllers\Admin\Exportar;

use App\Exports\TablaLiquidacionCreditoExport;
use App\Exports\DocumentosVencidosExport;
use App\Exports\MovimientosDiariosExport;
use App\Exports\LicenciasVencidasExport;
use App\Exports\ArchivoHistoricoExport;
use App\Exports\CarteraVencidaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\TiqueteExport;
use App\Exports\PersonaExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class RegistrosController extends Controller
{
    public function exportarPersona(Request $request){
        try {
            $nombreReporte = 'Reporte_persona_'.Carbon::now().'.xls'; 
            $personaExport = new PersonaExport($request);
            return Excel::download($personaExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
	}

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
            $movimientosDiariosExport = new MovimientosDiariosExport($request);
            return Excel::download($movimientosDiariosExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }

    public function exportarTiquete(Request $request){
        try {
            $nombreReporte = 'Reporte_tiquete_'.Carbon::now().'.xls';
            $tiqueteExport = new TiqueteExport($request);
            return Excel::download($tiqueteExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }

    public function documentosVencidos(Request $request){
        try {
            $nombreReporte            = 'Reporte_documento_vencidos_'.Carbon::now().'.xls';
            $documentosVencidosExport = new DocumentosVencidosExport($request);
            return Excel::download($documentosVencidosExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }

    public function exportarLicencias(Request $request){
        try {
            $nombreReporte            = 'Reporte_licencias_vencidas_'.Carbon::now().'.xls';
            $licenciasVencidasExport = new LicenciasVencidasExport($request);
            return Excel::download($licenciasVencidasExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
    }

    public function tablaLiquidacionCreditos(Request $request){
        $this->validate(request(),['colocacionId'=> 'required|numeric' ]);

        try {
            $nombreReporte                 = 'Reporte_tabla_liquidacion_credito_'.Carbon::now().'.xls';
            $tablaLiquidacionCreditoExport = new TablaLiquidacionCreditoExport($request);
            return Excel::download($tablaLiquidacionCreditoExport, $nombreReporte);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al generar el reporte => '.$error->getMessage()]);
        }
	}
}