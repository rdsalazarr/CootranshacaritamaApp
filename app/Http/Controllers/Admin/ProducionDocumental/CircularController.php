<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoCircular;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProcesoAnexo;
use App\Models\CodigoDocumentalProcesoCopia;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\CircularRequests;
use App\Models\CodigoDocumental;
use App\Util\showTipoDocumental;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Auth, DB, File;
use Carbon\Carbon;

class CircularController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

		$consulta   = DB::table('coddocumprocesocircular as cdpc')
						->select('cdpc.codoplid as id', 'cdpc.codoprid', DB::raw("CONCAT(cdpc.codoplanio,' - ', cdpc.codoplconsecutivo) as consecutivo"),
								'cdp.codoprfecha as fecha', 'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 
								'd.depenombre as dependencia', 'ted.tiesdonombre as estado')
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->whereIn('cd.depeid', function($query){
							$query->from('dependencia as d')
									->join('dependenciapersona as dp', 'dp.depperdepeid', '=', 'd.depeid')
									->select('d.depeid')
									->where('dp.depperpersid', auth()->user()->persid);
						});

						if($request->tipo === 'PRODUCIR')
							$consulta = $consulta->whereIn('cdp.tiesdoid', [1, 3]);
					
						if($request->tipo === 'VERIFICAR')
							$consulta = $consulta->whereIn('cdp.tiesdoid', [2, 4]);

						if($request->tipo === 'HISTORICOS')
							$consulta = $consulta->whereNotIn('cdp.tiesdoid', [1, 2, 3, 4]);
					
				$data = $consulta->orderBy('cdp.codoprfecha','Desc')->get();

        return response()->json(["data" => $data]);
	}

	public function area()
	{
		$areas = DB::table('dependencia as d')
						->select('d.depeid','d.depenombre','d.depesigla')
						->join('dependenciapersona as dp', 'dp.depperdepeid', '=', 'd.depeid')
						->where('dp.depperpersid', auth()->user()->persid)
						->orderBy('d.depenombre')->get();

		return response()->json(["areas" => $areas]);
	}

	public function datos(Request $request)
	{ 
		$this->validate(request(),['tipo' => 'required']);

		$id                = $request->id;
		$tipo              = $request->tipo;
		$depeid            = $request->dependencia;
		$data              = '';
		$firmasDocumento   = [] ;
		$copiaDependencias = [] ;
		$anexosDocumento   = [] ;
		if($tipo === 'U'){
			$visualizar  = new showTipoDocumental();
			list($data, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->circular($id);
			$depeid      = $data->depeid;
		}

		$fechaActual     = Carbon::now()->format('Y-m-d');
		$tipoDestinos    = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
		$tipoMedios      = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$tipoSaludos     = DB::table('tiposaludo')->select('tipsalid','tipsalnombre')->orderBy('tipsalnombre')->get();
        $tipoDespedidas  = DB::table('tipodespedida')->select('tipdesid','tipdesnombre')->orderBy('tipdesnombre')->get();
        $dependencias    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->where('depeid', '!=', $depeid)->orderBy('depenombre')->get();
 		$personas        = DB::table('persona')->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
														->orderBy('nombrePersona')
														->whereIn('carlabid', [1, 2])->get();
        $cargoLaborales  = DB::table('cargolaboral')->select('carlabid','carlabnombre')->orderBy('carlabnombre')->whereIn('carlabid', [1, 2])->get();

        return response()->json(["fechaActual"      => $fechaActual,       "tipoDestinos" => $tipoDestinos, "tipoMedios"      => $tipoMedios,
                                "tipoDespedidas"    => $tipoDespedidas,    "dependencias" => $dependencias, "personas"        => $personas, 
								"cargoLaborales"    => $cargoLaborales,    "data"         => $data,         "firmasDocumento" => $firmasDocumento,
								"copiaDependencias" => $copiaDependencias, "anexosDocumento" => $anexosDocumento ]);
	}

    public function salve(CircularRequests $request){

        $coddocid      			 = $request->idCD;
	    $codoprid      			 = $request->idCDP;
	    $codoplid      			 = $request->idCDPC;
        $codigodocumental        = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesocircular = ($codoplid != 000) ? CodigoDocumentalProcesoCircular::findOrFail($codoplid) : new CodigoDocumentalProcesoCircular();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;
			
			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '3';//Circular
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}
	    	
	    	$codigodocumentalproceso->codoprfecha               = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido      = $request->destinatarios;
	      	$codigodocumentalproceso->codoprasunto              = $request->asunto;
	    	$codigodocumentalproceso->codoprcorreo              = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido           = $request->contenido;
	    	$codigodocumentalproceso->codoprtieneanexo          = $request->tieneAnexo;
	    	$codigodocumentalproceso->codopranexonombre         = $request->nombreAnexo;
	    	$codigodocumentalproceso->codoprtienecopia          = $request->tieneCopia;
	    	$codigodocumentalproceso->codoprcopianombre         = $request->nombreCopia;
	    	$codigodocumentalproceso->save();

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				= CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				= $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesocircular->codoprid          = $codoprid;
				$coddocumprocesocircular->usuaid            = $usuarioId;
				$coddocumprocesocircular->codoplconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual);
				$coddocumprocesocircular->codoplsigla       = $sigla;
				$coddocumprocesocircular->codoplanio        = $anioActual;
			}

			$coddocumprocesocircular->tipdesid  = $request->despedida;
		   	$coddocumprocesocircular->save();

			//Registramos los adjuntos
			if($request->hasFile('archivos')){
				$numeroAleatorio = rand(100, 1000);
				$funcion         = new generales();
				$rutaCarpeta     = public_path().'/archivos/produccionDocumental/adjuntos/'.$sigla.'/'.$anioActual;
				$carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files           = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo = Crypt::encrypt($nombreArchivo);

					$coddocumprocesoanexo = new CodigoDocumentalProcesoAnexo();
					$coddocumprocesoanexo->codoprid                  = $codoprid;
					$coddocumprocesoanexo->codopxnombreanexooriginal = $nombreOriginal;
					$coddocumprocesoanexo->codopxnombreanexoeditado  = $nombreArchivo;
					$coddocumprocesoanexo->codopxrutaanexo           = $rutaArchivo;
					$coddocumprocesoanexo->save();
				}
			}

			foreach($request->firmaPersonas as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->tipo === 'U'){
				//Elimino las dependencia que esten en el documento
				$coddocumprocesocopiaConsultas = DB::table('coddocumprocesocopia')->select('codoppid')->where('codoprid', $codoprid)->get();
				foreach($coddocumprocesocopiaConsultas as $coddocumprocesocop){
					$coddocumprocesocopiaDelete = CodigoDocumentalProcesoCopia::findOrFail($coddocumprocesocop->codoppid);
					$coddocumprocesocopiaDelete->delete();
				}
			}

			if($request->copiasDependencia !== null){
				foreach($request->copiasDependencia as $copiaDependencia){
					$coddocumprocesocopia                         = new CodigoDocumentalProcesoCopia();
					$coddocumprocesocopia->codoprid               = $codoprid;
					$coddocumprocesocopia->depeid                 = $copiaDependencia['depeid'];
					$coddocumprocesocopia->codoppescopiadocumento = true;
					$coddocumprocesocopia->save();
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function solicitarFirma(Request $request)
	{
		$this->validate(request(),['codigo' => 'required','tipo' => 'required', 'observacionCambio' => 'required_if:tipo,E']);

		DB::beginTransaction();
		try {
			$infodocumento =  DB::table('coddocumprocesocircular as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codoplconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre','cdp.codoprfecha','d.depecorreo','cl.carlabnombre',
							DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
							->join('persona as p', 'p.persid', '=', 'u.persid')
							->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
							->where('cdpc.codoplid', $request->codigo)->first();

			$firmaDocumentos =  DB::table('codigodocumentalproceso as cdp')
							->select('cdp.codoprid','p.perscorreoelectronico',
							DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"))							
							->join('coddocumprocesofirma as cdpf', 'cdpf.codoprid', '=', 'cdp.codoprid')
							->join('persona as p', 'p.persid', '=', 'cdpf.persid')
							->where('cdp.codoprid', $infodocumento->codoprid)->get();

			$codoprid         = $infodocumento->codoprid;
			$numeroDocumental = $infodocumento->consecutivoDocumento;
			$tipoDocumental   = $infodocumento->tipdocnombre;
			$fechaDocumento   = $infodocumento->codoprfecha;
			$nombreUsuario    = $infodocumento->nombreUsuario;
			$cargoUsuario     = $infodocumento->carlabnombre;
			$emailDependencia = $infodocumento->depecorreo;
			$fechaHoraActual  = Carbon::now();
			$estado           = ($request->tipo === 'S') ? 2 : 3;
			$observacion      = ($request->tipo === 'S') ? 'Solicitud de firma de documento realizada por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual : $request->observacionCambio;
			$idCorreo         = ($request->tipo === 'S') ? 'solicitaFirmaDocumento' : 'anularSolicitudFirmaDocumento';

			$codigodocumentalproceso                      = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->codoprsolicitafirma = true;
			$codigodocumentalproceso->tiesdoid            = $estado;

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceuserid      = Auth::id();
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = $observacion;
			$codigodocumentalprocesocambioestado->save(); 

			//Enviamos la notificacion
			$notificar         = new Notificar();
			$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', $idCorreo)->first();
			$correoNotificados = '';
			foreach($firmaDocumentos as $firmaDocumento){
				$email              = $firmaDocumento->perscorreoelectronico;
				$correoNotificados .= $email.', ';
				$nombreFeje         = $firmaDocumento->nombreJefe;
				$buscar             = Array('numeroDocumental', 'nombreFeje', 'tipoDocumental', 'fechaDocumento','nombreUsuario','cargoUsuario','observacionAnulacionFirma');
				$remplazo           = Array($numeroDocumental, $nombreFeje,  $tipoDocumental, $fechaDocumento, $nombreUsuario, $cargoUsuario, $request->observacionCambio); 
				$asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
				$msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
				$enviarcopia        = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
				$notificar->correo([$email], $asunto, $msg, '', $emailDependencia, $enviarcopia, $enviarpiepagina);
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito, Se ha enviado notificación al correo '.substr($correoNotificados, 0, -2) ]);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function verificarSellado(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);
		try {
			$infodocumento =  DB::table('coddocumprocesocircular as cdpc')
								->select('cdpc.codoprid', DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1) AS totalFirma'),
								  DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1 AND codoprid = cdpc.codoprid) AS totalFirmaRealizadas'))
								->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
								->where('cdpc.codoplid', $request->codigo)->first();

			$firmado = ($infodocumento->totalFirma = $infodocumento->totalFirmaRealizadas) ? true : false;

			return response()->json(['success' => true, 'message' => $firmado ]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function sellar(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);
		try {

			$empresa       = DB::table('empresa')->select('emprnombre','emprsigla','emprcorreo')->where('emprid', 1)->first();
			$infodocumento =  DB::table('coddocumprocesocircular as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codoplconsecutivo) as consecutivoDocumento"),
											'cdp.codoprnombredirigido','cdp.codoprcorreo','d.depecorreo','d.depenombre','p.perscorreoelectronico',
							 DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"),
							 DB::raw("CONCAT(tdc.tipdoccodigo,'-', d.depesigla,'-', cdpc.codoplconsecutivo,'-', cdp.codoprfecha,'.pdf') as rutaDocumento"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('persona as p', 'p.persid', '=', 'd.depejefeid')	
							->where('cdpc.codoplid', $request->codigo)->first();

			$codoprid          = $infodocumento->codoprid;
			$numeroDocumental  = $infodocumento->consecutivoDocumento;
			$jefeDependencia   = $infodocumento->nombreJefe;
			$nombreUsuario     = $infodocumento->codoprnombredirigido;
			$correoNotificados = $infodocumento->codoprcorreo;
			$email             = explode(",", $correoNotificados);
			$nombreDependencia = $infodocumento->depenombre;
			$emailDependencia  = $infodocumento->depecorreo;
			$nombreEmpresa     = $empresa->emprnombre;
			$siglaEmpresa      = $empresa->emprsigla;
			$fechaHoraActual   = Carbon::now();
			$estado            = 5; //Sellado
			$mensajeCorreo     = '';
	
			$codigodocumentalproceso                      = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid            = $estado;
			$codigodocumentalproceso->codoprsellado       = true;
			$codigodocumentalproceso->codoprrutadocumento = Crypt::encrypt($infodocumento->rutaDocumento);
			$codigodocumentalproceso->save();

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceuserid      = Auth::id();
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = 'Solicitud de sellado del documento realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
			$codigodocumentalprocesocambioestado->save();

			//Genero una copia del documento en el servidor
			$generarPdf = new generarPdf();
			$rutaPdf    = $generarPdf->circular($request->codigo, 'F');
			if($email != null or $email != ''){//Enviamos la notificacion al usuario
				$notificar         = new Notificar();
				$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarEnvioDocumento')->first();
				$buscar            = Array('numeroDocumental', 'nombreUsuario', 'jefeDependencia', 'nombreEmpresa','nombreDependencia');
				$remplazo          = Array($numeroDocumental, $nombreUsuario,  $jefeDependencia, $nombreEmpresa, $nombreDependencia); 
				$asunto            = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
				$msg               = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
				$enviarcopia       = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
				$notificar->correo($email, $asunto, $msg, $rutaPdf, $emailDependencia, $enviarcopia, $enviarpiepagina);
				$mensajeCorreo     = ', Se ha enviado notificación al correo  '.$correoNotificados;
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito '.$mensajeCorreo ]);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function anular(Request $request)
	{
		$this->validate(request(),['codigo' => 'required', 'observacionCambio' => 'required|string|min:20|max:500']);

		DB::beginTransaction();
		try {
			
			$fechaHoraActual  = Carbon::now();
			$estado           = 10;

			$coddocumprocesocircular =  CodigoDocumentalProcesoCircular::findOrFail($request->codigo);
			$codoprid = $coddocumprocesocircular->codoprid;

			$codigodocumentalproceso           = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid = $estado;
			$codigodocumentalproceso->save();

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceuserid      = Auth::id();
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = $request->observacionCambio;
			$codigodocumentalprocesocambioestado->save();

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function trazabilidad(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);
		$cambioEstados = DB::table('coddocumprocesocambioestado as cdpce')
						->select('cdpce.codpcefechahora','cdpce.codpceobservacion','ted.tiesdonombre',
						DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdpce.tiesdoid')
						->join('usuario as u', 'u.usuaid', '=', 'cdpce.codpceuserid')
						->where('cdpce.codoprid', $request->codigo)->get();

		return response()->json(['cambioEstados' => $cambioEstados]);
	}

	public function showPdf(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);  
		try {
			$generarPdf    = new generarPdf();
			$dataDocumento = $generarPdf->circular($request->codigo, 'S');
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    //Funcion que permite obtener el consecutivo del documento
	public function obtenerConsecutivo($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesocircular')->select('codoplconsecutivo')
								->where('codoplanio', $anioActual)->where('codoplsigla', $sigla)
								->orderBy('codoplid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codoplconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}