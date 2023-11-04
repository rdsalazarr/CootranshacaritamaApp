<?php

namespace App\Http\Controllers\Admin\Radicacion;

use App\Models\Radicacion\DocumentoEntranteCambioEstado;
use App\Models\Radicacion\DocumentoEntranteDependencia;
use App\Models\Radicacion\DocumentoEntranteAnexo;
use App\Models\Radicacion\DocumentoEntrante;
use App\Models\Radicacion\PersonaRadicaDocumento;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Exception, Auth, DB, File;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class DocumentoEntranteController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);
         
        $consulta   = DB::table('radicaciondocumentoentrante as rde')
                    ->select('rde.radoenid as id', 'rde.radoenfechahoraradicado as fechaRadicado','rde.radoenasunto as asunto',
                        DB::raw("CONCAT(rde.radoenanio,' - ', rde.radoenconsecutivo) as consecutivo"),'d.depenombre as dependencia','terde.tierdenombre as estado',
                        DB::raw("CONCAT(prd.peradoprimernombre,' ',if(prd.peradosegundonombre is null ,'', prd.peradosegundonombre),' ', prd.peradoprimerapellido,' ',if(prd.peradosegundoapellido is null ,' ', prd.peradosegundoapellido)) as nombrePersonaRadica"))
                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')                    
                    ->join('radicaciondocentdependencia as rded', function($join)
                        {
                            $join->on('rded.radoenid', '=', 'rde.radoenid');
                            $join->where('rded.radoedescopia', false); 
                        })
                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                    ->join('tipoestadoraddocentrante as terde', 'terde.tierdeid', '=', 'rde.tierdeid');

                    if($request->tipo === 'PRODUCIR')
                        $consulta = $consulta->where('rde.tierdeid', 1);

                    if($request->tipo === 'VERIFICAR')
                        $consulta = $consulta->where('rde.tierdeid', '!=', 1);

                    if($request->tipo === 'HISTORICO')
                        $consulta = $consulta->where('rde.radoenanio', '!=', Carbon::now()->year);

                $data = $consulta->orderBy('rde.radoenid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);	
		$codigo            = $request->codigo;
		$tipo              = $request->tipo;
        $data              = [];
        $copiaDependencias = [];
        $anexosRadicados   = [];
        if($tipo === 'U'){
            $data   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.peradoid','rde.tipmedid','rde.tierdeid','rde.depaid','rde.muniid','rded.depeid','rded.radoedid','rde.radoenfechadocumento',
                                'rde.radoenfechallegada','rde.radoenpersonaentregadocumento','rde.radoenasunto','rde.radoentieneanexo',
                                'rde.radoendescripcionanexo','rde.radoentienecopia','rde.radoenobservacion',
                                'prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre','prd.peradoprimerapellido',
                                'prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo','prd.peradocodigodocumental',
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid AND radoedescopia = true) AS totalCopias'),
                                DB::raw('(SELECT COUNT(radoeaid) AS radoeaid FROM radicaciondocentanexo WHERE radoenid = rde.radoenid AND radoearequiereradicado = false ) AS totalAnexos'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('radicaciondocentdependencia as rded', function($join)
                            {
                                $join->on('rded.radoenid', '=', 'rde.radoenid');
                                $join->where('rded.radoedescopia', false); 
                            })
                        ->where('rde.radoenid', $codigo)->first();
            
            if($data->totalCopias > 0){
                $copiaDependencias  =  DB::table('radicaciondocentdependencia as rded')
                                        ->select('rded.depeid','d.depenombre','d.depesigla')
                                        ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                        ->where('rded.radoenid', $codigo)
                                        ->where('rded.radoedescopia', true)->get();
            }

            $anexosRadicados  =  DB::table('radicaciondocentanexo as rdea')
                                ->select('rdea.radoeaid as id','rdea.radoeanombreanexooriginal as nombreOriginal','rdea.radoeanombreanexoeditado as nombreEditado',
                                'rdea.radoearutaanexo as rutaAnexo',DB::raw("if(rdea.radoearequiereradicado = 1 ,'Sí', 'No') as radicarDocumento"), 'rde.radoenanio as anio',
                                DB::raw("CONCAT('archivos/radicacion/documentoEntrante/',rde.radoenanio,'/', rdea.radoearutaanexo) as rutaDescargar"))
                                ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'rdea.radoenid')
                                ->where('rdea.radoenid', $codigo)->get();
        }

        $fechaActual           = Carbon::now()->format('Y-m-d');
        $tipoMedios            = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
        $dependencias          = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->orderBy('depenombre')->get();	
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();

        return response()->json(["fechaActual"    => $fechaActual,   "tipoMedios"        => $tipoMedios,       "tipoIdentificaciones" => $tipoIdentificaciones,
                                "dependencias"    => $dependencias,  "departamentos"     => $departamentos,    "municipios"           => $municipios,
								"data"            => $data,          "copiaDependencias" => $copiaDependencias, "anexosRadicados"     => $anexosRadicados ]);
	}

    public function salve(Request $request)
	{
	    $this->validate(request(),[
                'codigo'                  => 'required',
                'tipo'                    => 'required',
                'tipoIdentificacion'      => 'required|numeric',
                'numeroIdentificacion'    => 'required|string|max:15',
                'primerNombre'            => 'required|string|min:4|max:70',
                'segundoNombre'           => 'nullable|string|min:4|max:40',
                'primerApellido'          => 'nullable|string|min:4|max:40',
                'segundoApellido'         => 'nullable|string|min:4|max:40',
                'direccionFisica'         => 'required|string|min:4|max:100',
                'correoElectronico'       => 'nullable|email|string|max:80',
                'numeroContacto'          => 'nullable|string|max:20',
                'codigoDocumental'        => 'nullable|string|max:20',

                'fechaLlegadaDocumento'   => 'required|date|date_format:Y-m-d',
                'fechaDocumento'          => 'required|date|date_format:Y-m-d',
                'dependencia'             => 'required|numeric',
                'departamento'            => 'required|numeric',
                'municipio'               => 'required|numeric',
                'asuntoRadicado'          => 'required|string|min:4|max:500',
                'personaEntregaDocumento' => 'required|string|min:4|max:100',
                'tieneAnexos'             => 'nullable|numeric',
                'descripcionAnexos'       => 'nullable|string|min:4|max:300',
                'tieneCopia'              => 'nullable|numeric',
                'observacionGeneral'      => 'nullable|string|min:4|max:300',
                'personaId'               => 'nullable|numeric',

                'pdfRadicar'              => 'nullable|array|max:2000',
                'pdfRadicar.*'            => 'nullable|mimes:pdf|max:2000',
                'archivos'                => 'nullable|array|max:2000',
                'archivos.*'              => 'nullable|mimes:jpg,png,jpeg,doc,docx,pdf,ppt,pptx,xls,xlsx,xlsm,zip,rar|max:2000'  
	        ]);

        DB::beginTransaction();
        try {

            $estado              = '1'; //Recibido
            $fechaHoraActual     = Carbon::now();
            $anioActual          = Carbon::now()->year;
            $funcion 		     = new generales();
            $generarPdf          = new generarPdf();
            $radoenid            = $request->codigo; 
           
            $nombreOriginalPdf    = '';
            $nombreArchivoPdf     = '';
            $debeRadicarDocumento = false;
            if($request->hasFile('pdfRadicar')){
                $numeroAleatorio = rand(100, 1000);
                $rutaCarpeta       = public_path().'/archivos/radicacion/documentoEntrante/'.$anioActual;
                $carpetaServe      = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$file              = $request->file('pdfRadicar')[0];
				$nombreOriginalPdf = $file->getclientOriginalName();
				$filename          = pathinfo($nombreOriginalPdf, PATHINFO_FILENAME);
				$extension         = pathinfo($nombreOriginalPdf, PATHINFO_EXTENSION);
				$nombreArchivoPdf  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $nombreArchivoPdf);
                //Verifico que el archivo lo pueda radicar
                $verificarPdf         = $generarPdf->validarPuedeAbrirPdf($rutaCarpeta.'/'.$nombreArchivoPdf);
                $debeRadicarDocumento = true;
                if(!$verificarPdf){
                    unlink($rutaCarpeta.'/'.$nombreArchivoPdf);//Elimina el archivo de la carpeta
                    DB::rollback();
                    return response()->json(['success' => false, 'message'=> 'Este documento PDF está encriptado y no puede ser procesado']);
                }
            }

            $personaRadicado   = DB::table('personaradicadocumento')->select('peradoid')
                                        ->where('tipideid', $request->tipoIdentificacion)
                                        ->where('peradodocumento', $request->numeroIdentificacion)->first();
        
            $peradoid          = ($personaRadicado !== null)? $personaRadicado->peradoid : '000';

            $personaradicadocumento                         = ($peradoid != '000') ? PersonaRadicaDocumento::findOrFail($peradoid) : new PersonaRadicaDocumento();
            $personaradicadocumento->tipideid               = $request->tipoIdentificacion;
            $personaradicadocumento->peradodocumento        = $request->numeroIdentificacion;
            $personaradicadocumento->peradoprimernombre     = mb_strtoupper($request->primerNombre,'UTF-8');
            $personaradicadocumento->peradosegundonombre    = mb_strtoupper($request->segundoNombre,'UTF-8');
            $personaradicadocumento->peradoprimerapellido   = mb_strtoupper($request->primerApellido,'UTF-8');
            $personaradicadocumento->peradosegundoapellido  = mb_strtoupper($request->segundoApellido,'UTF-8');
            $personaradicadocumento->peradodireccion        = $request->direccionFisica; 
            $personaradicadocumento->peradotelefono         = $request->numeroContacto; 
            $personaradicadocumento->peradocorreo           = $request->correoElectronico;
            $personaradicadocumento->peradocodigodocumental = $request->codigoDocumental;
            $personaradicadocumento->save();

            if($request->tipo === 'I'){
                //Consulto el ultimo identificador de la persona 
                $perRadDocumentoMaxConsecutio  = PersonaRadicaDocumento::latest('peradoid')->first();
                $peradoid                      = $perRadDocumentoMaxConsecutio->peradoid;
            }
      
            $radicaciondocumentoentrante       = ($request->tipo === 'U') ? DocumentoEntrante::findOrFail($radoenid) : new DocumentoEntrante();
            if($request->tipo === 'I'){
                $radicaciondocumentoentrante->tierdeid                   = $estado;
                $radicaciondocumentoentrante->usuaid                     = Auth::id();
                $radicaciondocumentoentrante->radoenconsecutivo          = $this->obtenerConsecutivo($anioActual);
                $radicaciondocumentoentrante->radoenanio                 = $anioActual;
                $radicaciondocumentoentrante->radoenfechahoraradicado    = $fechaHoraActual;
                $radicaciondocumentoentrante->radoenfechamaximarespuesta = $funcion->obtenerFechaMaxima(15, Carbon::now()->format('Y-m-d'));
            }

            $radicaciondocumentoentrante->peradoid                      = $peradoid;
            $radicaciondocumentoentrante->tipmedid                      = $request->tipoMedio;
            $radicaciondocumentoentrante->depaid                        = $request->departamento;
            $radicaciondocumentoentrante->muniid                        = $request->municipio;
            $radicaciondocumentoentrante->radoenfechadocumento          = $request->fechaDocumento;
            $radicaciondocumentoentrante->radoenfechallegada            = $request->fechaLlegadaDocumento;
            $radicaciondocumentoentrante->radoenpersonaentregadocumento = $request->personaEntregaDocumento;
            $radicaciondocumentoentrante->radoenasunto                  = $request->asuntoRadicado;
            $radicaciondocumentoentrante->radoentieneanexo              = $request->tieneAnexos;
            $radicaciondocumentoentrante->radoendescripcionanexo        = $request->descripcionAnexos;
            $radicaciondocumentoentrante->radoentienecopia              = $request->tieneCopia;
            $radicaciondocumentoentrante->radoenobservacion             = $request->observacionGeneral;
            $radicaciondocumentoentrante->save();

            if($request->tipo === 'I'){
                //Consulto el ultimo identificador de la persona 
                $radDocumentoMaxConsecutio      = DocumentoEntrante::latest('radoenid')->first();
                $radoenid                       = $radDocumentoMaxConsecutio->radoenid;

                $radicaciondocentdependencia           = new DocumentoEntranteDependencia();
                $radicaciondocentdependencia->radoenid = $radoenid;
                $radicaciondocentdependencia->depeid   = $request->dependencia;
                $radicaciondocentdependencia->save();
            }

            if($request->tipo === 'U' and $request->dependencia !== $request->dependenciaRadicado){
                $radicaciondocentdependencia         = DocumentoEntranteDependencia::findOrFail($request->radoedid);
                $radicaciondocentdependencia->depeid = $request->dependencia;
                $radicaciondocentdependencia->save();
            }

            if($nombreOriginalPdf  !== ''){
                $radicaciondocentanexo                            = new DocumentoEntranteAnexo();
                $radicaciondocentanexo->radoenid                  = $radoenid;
                $radicaciondocentanexo->radoeanombreanexooriginal = $nombreOriginalPdf;
                $radicaciondocentanexo->radoeanombreanexoeditado  = $nombreArchivoPdf;
                $radicaciondocentanexo->radoearutaanexo           = Crypt::encrypt($nombreArchivoPdf);
                $radicaciondocentanexo->radoearequiereradicado    = true;
                $radicaciondocentanexo->save();
            }

            //Registramos los adjuntos
			if($request->hasFile('archivos')){
				$numeroAleatorio = rand(100, 1000);
				$funcion         = new generales();
                $rutaCarpeta     = public_path().'/archivos/radicacion/documentoEntrante/'.$anioActual;
                $carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files           = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo = Crypt::encrypt($nombreArchivo);

					$radicaciondocentanexo                            = new RadicacionDocumentoEntranteAnexo();
					$radicaciondocentanexo->radoenid                  = $radoenid;
					$radicaciondocentanexo->radoeanombreanexooriginal = $nombreOriginal;
					$radicaciondocentanexo->radoeanombreanexoeditado  = $nombreArchivo;
					$radicaciondocentanexo->radoearutaanexo           = $rutaArchivo;
                    $radicaciondocentanexo->radoearequiereradicado    = false;
					$radicaciondocentanexo->save();
				}
			}

			if($request->tipo === 'U' and $request->copiasDependencia !== null){
				//Elimino las dependencia que esten en el documento
				$radicaciondocentdependenciaConsultas = DB::table('radicaciondocentdependencia')->select('radoedid')->where('radoenid', $radoenid)->where('radoedescopia', true)->get();
				foreach($radicaciondocentdependenciaConsultas as $radicaciondocentdepen){
					$radicaciondocentdependenciaDelete = RadicacionDocumentoEntranteDependencia::findOrFail($radicaciondocentdepen->radoedid);
					$radicaciondocentdependenciaDelete->delete();
				}
			}

			if($request->copiasDependencia !== null){
				foreach($request->copiasDependencia as $copiaDependencia){                  
					$radicaciondocentdependencia                = new RadicacionDocumentoEntranteDependencia();
					$radicaciondocentdependencia->radoenid      = $radoenid;
					$radicaciondocentdependencia->depeid        = $copiaDependencia;
                    $radicaciondocentdependencia->radoedescopia = true;
					$radicaciondocentdependencia->save();
				}
			}

            if($request->tipo === 'I'){
                //Almaceno la trazabilidad del radicado
                $radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
                $radicaciondocentcambioestado->radoenid          = $radoenid;
                $radicaciondocentcambioestado->tierdeid          = $estado;
                $radicaciondocentcambioestado->radeceusuaid      = Auth::id();
                $radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
                $radicaciondocentcambioestado->radeceobservacion = 'Documento radicado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $radicaciondocentcambioestado->save();
            }

            if($debeRadicarDocumento){//Radico el documento
                $rutaPdf      = $rutaCarpeta.'/'.$nombreArchivoPdf;
                $dataCopias   = [];
                $dataRadicado = DB::table('radicaciondocumentoentrante as rde')
                                    ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                            'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo',    'rde.radoenasunto as asunto',
                                            DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid AND radoedescopia = true) AS totalCopias'))
                                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                    ->join('radicaciondocentdependencia as rded', function($join)
                                        {
                                            $join->on('rded.radoenid', '=', 'rde.radoenid');
                                            $join->where('rded.radoedescopia', false);
                                        })
                                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                    ->where('rde.radoenid', $radoenid)->first();

                if($dataRadicado->totalCopias > 0){
                    $dataCopias    =  DB::table('radicaciondocentdependencia as rded')
                                        ->select('d.depenombre as dependencia','d.depecorreo')
                                        ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                        ->where('rded.radoenid', $radoenid)
                                        ->where('rded.radoedescopia', true)->get();
                }

                $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivoPdf, $dataRadicado, $dataCopias, true);
                //$funcion->reducirPesoPDF($rutaPdf, $rutaPdf);
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'idRadicado' => $radoenid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'numeroIdentificacion' => 'required|string|max:15']);

        $data     = DB::table('personaradicadocumento as prd')
                            ->select('prd.peradoid','prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre',
                            'prd.peradoprimerapellido','prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo',
                            'prd.peradocodigodocumental','rde.radoenpersonaentregadocumento')
                            ->join('radicaciondocumentoentrante as rde', 'rde.peradoid', '=', 'prd.peradoid')
                            ->where('prd.tipideid', $request->tipoIdentificacion)
                            ->where('prd.peradodocumento', $request->numeroIdentificacion)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function enviar(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric']);

        $estado          = '2';
        $codigo          = $request->codigo;
        $fechaHoraActual = Carbon::now();
        $dataCopias      = [];
        DB::beginTransaction();
        try {

            $dataRadicado   =   DB::table('radicaciondocumentoentrante as rde')
                                ->select('rde.radoenfechahoraradicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                        'd.depenombre', 'd.depecorreo','u.usuaalias', 'prd.peradocorreo',
                                        DB::raw("(SELECT emprnombre FROM empresa WHERE emprid = 1) as empresa"),
                                        DB::raw("CONCAT(prd.peradoprimernombre,' ',if(prd.peradosegundonombre is null ,'', prd.peradosegundonombre),' ', prd.peradoprimerapellido,' ',if(prd.peradosegundoapellido is null ,' ', prd.peradosegundoapellido)) as nombrePersonaRadica"),
                                        DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid AND radoedescopia = true) AS totalCopias'))
                                ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                ->join('radicaciondocentdependencia as rded', function($join)
                                {
                                    $join->on('rded.radoenid', '=', 'rde.radoenid');
                                    $join->where('rded.radoedescopia', false);
                                })
                                ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                ->where('rde.radoenid', $codigo)->first();
            
            if($dataRadicado->totalCopias > 0){
                $dataCopias    =  DB::table('radicaciondocentdependencia as rded')
                                        ->select('d.depenombre','d.depecorreo')
                                        ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                        ->where('rded.radoenid', $codigo)
                                        ->where('rded.radoedescopia', true)->get();
            }

            $arrayfiles = [];
            $anexos     =  DB::table('radicaciondocentanexo as rdea')
                                    ->select('rdea.radoeaid as id','rdea.radoeanombreanexooriginal','rdea.radoeanombreanexoeditado',
                                    'rdea.radoearutaanexo', 'rde.radoenanio as anio',
                                    DB::raw("CONCAT('/archivos/radicacion/documentoEntrante/',rde.radoenanio) as rutaDescargar"))
                                    ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'rdea.radoenid')
                                    ->where('rdea.radoenid', $codigo)->get();
            foreach($anexos as $anexo){
                $rutaFile = public_path().$anexo->rutaDescargar.'/'.Crypt::decrypt($anexo->radoearutaanexo);
                array_push($arrayfiles,  $rutaFile);
            }

            $nombreUsuario     = $dataRadicado->nombrePersonaRadica;
            $correoPersona     = $dataRadicado->peradocorreo;
            $numeroRadicado    = $dataRadicado->consecutivo;
            $nombreEmpresa     = $dataRadicado->empresa;
            $fechaRadicado     = $dataRadicado->radoenfechahoraradicado;
            $nombreFuncionario = $dataRadicado->usuaalias;
            $nombreDependencia = $dataRadicado->depenombre;
            $correoDependencia = $dataRadicado->depecorreo;

            $radicaciondocumentoentrante           = RadicacionDocumentoEntrante::findOrFail($codigo);
            $radicaciondocumentoentrante->tierdeid = $estado;
            $radicaciondocumentoentrante->save();

            $radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
            $radicaciondocentcambioestado->radoenid          = $codigo;
            $radicaciondocentcambioestado->tierdeid          = $estado;
            $radicaciondocentcambioestado->radeceusuaid      = Auth::id();
            $radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
            $radicaciondocentcambioestado->radeceobservacion = 'Documento enviado a la dependencia, este proceso fue realizado por '.auth()->user()->usuanombre.'  en la fecha '.$fechaHoraActual;
            $radicaciondocentcambioestado->save();

            $notificar          = new notificar();
            $informacioncorreos = DB::table('informacionnotificacioncorreo')->wherein('innoconombre', ['notificarRegistroRadicado','notificarRadicadoDocumento'])->orderBy('innocoid')->get();
            foreach($informacioncorreos as  $informacioncorreo){
                $buscar            = Array('numeroRadicado', 'nombreUsuario', 'nombreEmpresa', 'fechaRadicado','nombreDependencia','nombreFuncionario','nombreDependencia');
                $remplazo          = Array($numeroRadicado, $nombreUsuario, $nombreEmpresa,  $fechaRadicado, $nombreDependencia, $nombreFuncionario, $nombreDependencia); 
                $innocoasunto      = $informacioncorreo->innocoasunto;
                $innococontenido   = $informacioncorreo->innococontenido;
                $enviarcopia       = $informacioncorreo->innocoenviarcopia;
                $enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
                $asunto            = str_replace($buscar, $remplazo, $innocoasunto);
                $msg               = str_replace($buscar, $remplazo, $innococontenido);
                $prueba = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoDependencia, $enviarcopia, $enviarpiepagina);
                $correoPersona     = $correoDependencia;
            }

            if($dataRadicado->totalCopias > 0){
                foreach($dataCopias as $dataCopia){
                    $nombreDependencia = $dataCopia->depenombre;
                    $correo            = $dataCopia->depecorreo;
                    $buscar            = Array('numeroRadicado', 'nombreEmpresa', 'fechaRadicado','nombreDependencia','nombreFuncionario','nombreDependencia');
                    $remplazo          = Array($numeroRadicado, $nombreEmpresa,  $fechaRadicado, $nombreDependencia, $nombreFuncionario, $nombreDependencia); 
                    $asunto            = str_replace($buscar, $remplazo, $innocoasunto);
                    $msg               = str_replace($buscar, $remplazo, $innococontenido);
                    $notificar->correo([$correo], $asunto, $msg, [$arrayfiles], $correoDependencia, $enviarcopia, $enviarpiepagina);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function imprimir(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric']);

        try{
            $dataCopia      = [];
            $dataRadicado   =   DB::table('radicaciondocumentoentrante as rde')
                                ->select('rde.radoenfechahoraradicado as fechaRadicado','rde.radoenasunto as asunto',
                                        DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),'d.depenombre as dependencia',
                                        'prd.peradocorreo as correo', 'u.usuaalias as usuario',
                                        DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid AND radoedescopia = true) AS totalCopias'))
                                ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                ->join('radicaciondocentdependencia as rded', function($join)
                                {
                                    $join->on('rded.radoenid', '=', 'rde.radoenid');
                                    $join->where('rded.radoedescopia', false);
                                })
                                ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                ->where('rde.radoenid', $request->codigo)->first();

            if($dataRadicado->totalCopias > 0){
                $dataCopia    =  DB::table('radicaciondocentdependencia as rded')
                                        ->select('d.depenombre as dependencia')
                                        ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                        ->where('rded.radoenid', $request->codigo)
                                        ->where('rded.radoedescopia', true)->get();
            }

            $generarPdf   = new generarPdf();
            return response()->json(["data" => $generarPdf->stickersRadicado($dataRadicado, $dataCopia)]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
    }

    public function obtenerConsecutivo($anioActual)
	{
		$consecutivoRadicado = DB::table('radicaciondocumentoentrante')->select('radoenconsecutivo as consecutivo')
								->where('radoenanio', $anioActual)->orderBy('radoenid', 'desc')->first();
        $consecutivo = ($consecutivoRadicado === null) ? 1 : $consecutivoRadicado->consecutivo + 1;
		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}