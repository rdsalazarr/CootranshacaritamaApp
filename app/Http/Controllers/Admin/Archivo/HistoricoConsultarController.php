<?php

namespace App\Http\Controllers\Admin\Archivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use Exception, DB;

class HistoricoConsultarController extends Controller
{
    public function index()
	{
        $tipoDocumentales        = DB::table('tipodocumental')->select('tipdocid','tipdocnombre')->orderBy('tipdocnombre')->get();
		$tipoEstanteArchivadores = DB::table('tipoestantearchivador')->select('tiesarid','tiesarnombre')->where('tiesaractivo', true)->get();
        $tipoCajaUbicaciones     = DB::table('tipocajaubicacion')->select('ticaubid','ticaubnombre')->get();
        $tipoCarpetaUbicaciones  = DB::table('tipocarpetaubicacion')->select('ticrubid','ticrubnombre')->get();

        return response()->json(["tipoDocumentales"   => $tipoDocumentales,    "tipoEstanteArchivadores" => $tipoEstanteArchivadores, 
                                "tipoCajaUbicaciones" => $tipoCajaUbicaciones, "tipoCarpetaUbicaciones" => $tipoCarpetaUbicaciones]);
	}

    public function consultar(Request $request)
	{
        $this->validate(request(),['fechaInicial'    => 'nullable|date|date_format:Y-m-d',
                                   'fechaFinal'      => 'nullable|date|date_format:Y-m-d',
                                   'asuntoDocumento' => 'nullable|string|min:4|max:100'
                                ]);

        $fechaInicial   = $request->fechaInicial;
        $fechaFinal     = $request->fechaFinal;
        $tipoDocumental = $request->tipoDocumental;
        $estante        = $request->estante;
        $caja           = $request->caja;
        $carpeta        = $request->carpeta;
        $asunto         = $request->asuntoDocumento;

        $consulta = DB::table('archivohistorico as ah')
                    ->select('ah.archisid','td.tipdocnombre as tipoDocumental','tea.tiesarnombre as estante','tcu.ticaubnombre as caja','tcb.ticrubnombre as carpeta',
                    'ah.archisnumerofolio as numeroFolio','ah.archisasuntodocumento as asunto')
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

                    $data = $consulta->get();

            $array = ($data !== null) ? ['success' => true, "data" => $data] : ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda seleccionados'];

		return response()->json($array);
	}

    public function expediente(Request $request)
	{
        $this->validate(request(),[ 'tipoDocumental' => 'nullable|numeric',
                                    'estante'        => 'required|numeric',
                                    'caja'           => 'required|numeric',
                                    'carpeta'        => 'required|numeric'
                                ]);

        $consulta = DB::table('archivohistorico')->select('archisid') 
                    ->where('tiesarid', $request->estante)->where('ticrubid', $request->carpeta)
                    ->where('ticaubid', $request->caja);

                    if($request->tipoDocumental != '000')
                        $consulta = $consulta->where('tipdocid', $request->tipoDocumental);

                    $data = $consulta->first();

            $array = ($data !== null) ? ['success' => true, "data" => $data] : ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda seleccionados'];

		return response()->json($array);
	}

    public function expedientePdf(Request $request)
	{
        $this->validate(request(),[ 'tipoDocumental' => 'nullable|numeric',
                                    'estante'        => 'required|numeric',
                                    'caja'           => 'required|numeric',
                                    'carpeta'        => 'required|numeric'
                                ]);
        
        $consulta = DB::table('archivohistoricodigitalizado as ahd')
                        ->select(DB::raw("CONCAT('archivos/digitalizados/',YEAR(ah.archisfechadocumento)) as rutaDigitalizacion"), 'ahd.arhidirutaarchivo  as rutaPdf')
                        ->join('archivohistorico as ah', 'ah.archisid', '=', 'ahd.archisid')
                        ->where('ah.tiesarid', $request->estante)
                        ->where('ah.ticrubid', $request->carpeta)
                        ->where('ah.ticaubid', $request->caja);

                        if($request->tipoDocumental != '000')
                            $consulta = $consulta->where('tipdocid', $request->tipoDocumental);

                    $digitalizados = $consulta->get();

        try {
            $generarPdf    = new generarPdf();
            $dataDocumento = $generarPdf->expedienteArchivoHistorico($digitalizados, 'S');
            return response()->json(["data" => $dataDocumento]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}
}