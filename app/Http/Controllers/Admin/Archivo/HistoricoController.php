<?php

namespace App\Http\Controllers\Admin\Archivo;

use App\Models\Archivo\HistoricoDigitalizado;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Models\Archivo\Historico;
use Exception, Auth, DB, File;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;

class HistoricoController extends Controller
{
    public function index()
	{
        $data = DB::table('archivohistorico as ah')
                    ->select('ah.archisid','td.tipdocnombre as tipoDocumental','tea.tiesarnombre as estante','tcu.ticaubnombre as caja','tcb.ticrubnombre as carpeta',
                    'ah.archisnumerofolio as numeroFolio','ah.archisasuntodocumento as asunto')
                    ->join('tipodocumental as td', 'td.tipdocid', '=', 'ah.tipdocid')
                    ->join('tipoestantearchivador as tea', 'tea.tiesarid', '=', 'ah.tiesarid')
					->join('tipocajaubicacion as tcu', 'tcu.ticaubid', '=', 'ah.ticaubid')
                    ->join('tipocarpetaubicacion as tcb', 'tcb.ticrubid', '=', 'ah.ticrubid')
                    ->get();

		return response()->json(['success' => true, "data" => $data]);
	}

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);	
		$codigo            = $request->codigo;
		$tipo              = $request->tipo;
        $data              = [];
        $digitalizados     = [];
        if($tipo === 'U'){
            $data   = DB::table('archivohistorico as ah')
                        ->select('ah.archisid','ah.tipdocid','ah.tiesarid', 'ah.ticaubid','ah.ticrubid', 'ah.archisfechadocumento',
                                'ah.archisnumerofolio','ah.archisasuntodocumento','ah.archistomodocumento', 'ah.archiscodigodocumental',
                                'ah.archisentidadremitente','ah.archisentidadproductora','ah.archisresumendocumento','ah.archisobservacion',
                                DB::raw('(SELECT COUNT(arhidiid) AS arhidiid FROM archivohistoricodigitalizado WHERE archisid = ah.archisid ) AS totalAnexos'))
                        ->where('ah.archisid', $codigo)->first();

            $digitalizados  =  DB::table('archivohistoricodigitalizado as ahd')
                                ->select('ahd.arhidiid as id','ahd.arhidinombrearchivooriginal as nombreOriginal','ahd.arhidinombrearchivoeditado as nombreEditado',
                                'ahd.arhidirutaarchivo as rutaArchivo', DB::raw("YEAR(ah.archisfechadocumento) as anio"),
                                DB::raw("CONCAT('archivos/digitalizados/',YEAR(ah.archisfechadocumento),'/', ahd.arhidirutaarchivo) as rutaDescargar"))
                                ->join('archivohistorico as ah', 'ah.archisid', '=', 'ahd.archisid')
                                ->where('ahd.archisid', $codigo)->get();
        }
 
        $tipoDocumentales        = DB::table('tipodocumental')->select('tipdocid','tipdocnombre')->orderBy('tipdocnombre')->get();
		$tipoEstanteArchivadores = DB::table('tipoestantearchivador')->select('tiesarid','tiesarnombre')->where('tiesaractivo', true)->get();
        $tipoCajaUbicaciones     = DB::table('tipocajaubicacion')->select('ticaubid','ticaubnombre')->get();
        $tipoCarpetaUbicaciones  = DB::table('tipocarpetaubicacion')->select('ticrubid','ticrubnombre')->get();

        return response()->json(["tipoDocumentales"      => $tipoDocumentales,       "tipoEstanteArchivadores" => $tipoEstanteArchivadores, "tipoCajaUbicaciones" => $tipoCajaUbicaciones, 
								"tipoCarpetaUbicaciones" => $tipoCarpetaUbicaciones, "data"                     => $data,                    "digitalizados"      => $digitalizados]);
	}

    public function salve(Request $request)
	{
	    $this->validate(request(),[
                'codigo'            => 'required',
                'tipo'              => 'required',
                'tipoDocumental'    => 'required|numeric',
                'estante'           => 'required|numeric',
                'caja'              => 'required|numeric',
                'carpeta'           => 'required|numeric',
                'fechaDocumento'    => 'required|date|date_format:Y-m-d',
                'numeroFolio'       => 'required|numeric|between:0,99',   
                'asuntoDocumento'   => 'required|string|min:4|max:500',
                'tomoDocumento'     => 'nullable|numeric|between:0,99',
                'codigoDocumental'  => 'nullable|string|min:2|max:20',
                'entidadRemitente'  => 'nullable|string|min:4|max:200',
                'entidadProductora' => 'nullable|string|min:4|max:200',
                'resumenDocumento'  => 'nullable|string|min:4|max:500',
                'observacion'       => 'nullable|string|min:4|max:500',

                'archivos'          => 'nullable|array|max:2000',
                'archivos.*'        => 'nullable|mimes:pdf,PDF|max:2000'
	        ]);

        DB::beginTransaction();
        try {
            $fechaHoraActual     = Carbon::now();
            $funcion 		     = new generales();
            $archisid            = $request->codigo;
            $archivohistorico    = ($request->tipo === 'U') ? Historico::findOrFail($archisid) : new Historico();
            if($request->tipo === 'I'){
                $archivohistorico->usuaid              = Auth::id();
                $archivohistorico->archisfechahora     = $fechaHoraActual;
            }

            $archivohistorico->tipdocid                = $request->tipoDocumental;
            $archivohistorico->tiesarid                = $request->estante;
            $archivohistorico->ticaubid                = $request->caja;
            $archivohistorico->ticrubid                = $request->carpeta;
            $archivohistorico->archisfechadocumento    = $request->fechaDocumento;
            $archivohistorico->archisnumerofolio       = $request->numeroFolio;
            $archivohistorico->archisasuntodocumento   = $request->asuntoDocumento;
            $archivohistorico->archistomodocumento     = $request->tomoDocumento;
            $archivohistorico->archiscodigodocumental  = $request->codigoDocumental;
            $archivohistorico->archisentidadremitente  = $request->entidadRemitente;
            $archivohistorico->archisentidadproductora = $request->entidadProductora;
            $archivohistorico->archisresumendocumento  = $request->resumenDocumento;
            $archivohistorico->archisobservacion       = $request->observacion;
            $archivohistorico->save();

            if($request->tipo === 'I'){
                //Consulto el ultimo identificador de la persona 
                $archHistoricoMaxConsecutio = Historico::latest('archisid')->first();
                $archisid                   = $archHistoricoMaxConsecutio->archisid;
            }

            //Registramos los adjuntos
			if($request->hasFile('archivos')){
                $fechaDocumento = Carbon::parse($request->fechaDocumento);
                $anioDocumento  = $fechaDocumento->year;
				$funcion        = new generales();
                $generarPdf     = new generarPdf();
                $rutaCarpeta    = public_path().'/archivos/digitalizados/'.$anioDocumento;
                $carpetaServe   = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files          = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $anioDocumento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo     = Crypt::encrypt($nombreArchivo);
                    $verificarPdf    = $generarPdf->validarPuedeAbrirPdf($rutaCarpeta.'/'.$nombreArchivo);
                    if(!$verificarPdf){
                        DB::rollback();
                        return response()->json(['success' => false, 'message'=> 'Este documento PDF está encriptado y no puede ser procesado']);
                    }

					$archivohistoricodigitalizado                              = new HistoricoDigitalizado();
					$archivohistoricodigitalizado->archisid                    = $archisid;
					$archivohistoricodigitalizado->arhidinombrearchivooriginal = $nombreOriginal;
					$archivohistoricodigitalizado->arhidinombrearchivoeditado  = $nombreArchivo;
					$archivohistoricodigitalizado->arhidirutaarchivo           = $rutaArchivo;
					$archivohistoricodigitalizado->save();
				}
			} 

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'idRadicado' => $archisid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}   

}