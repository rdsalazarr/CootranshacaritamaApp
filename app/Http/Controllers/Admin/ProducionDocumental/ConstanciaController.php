<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoConstancia;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use App\Models\CodigoDocumental;
use App\Util\showTipoDocumental;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Auth, DB, File;
use Carbon\Carbon;

class OficioController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

		$consulta   = DB::table('coddocumprocesoconstancia as cdpc')
						->select('cdpc.codopnid as id', 'cdpc.codoprid', DB::raw("CONCAT(cdpc.codopnanio,' - ', cdpc.codopnconsecutivo) as consecutivo"),
								'cdp.codoprfecha as fecha', 'cdpc.codopntitulo as asunto','cdpc.codopnnombredirigido as nombredirigido', 
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
		$id                = $request->id;
		$tipo              = $request->tipo;
		$data              = '';
		$firmasDocumento   = [] ;
		$copiaDependencias = [] ;
		$anexosDocumento   = [] ;
		if($tipo === 'U'){
			$visualizar  = new showTipoDocumental();
			list($data, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->oficio($id);
		}

		$fechaActual             = Carbon::now()->format('Y-m-d');
		$tipoDestinos            = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
		$tipoMedios              = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$tipopersonadocumentales = DB::table('tipopersonadocumental')->select('tipedoid','tipedonombre')->where('tipedoactivo', true)->orderBy('tipedonombre')->get();    
 		$personas                = DB::table('persona')->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
														->orderBy('nombrePersona')
														->whereIn('carlabid', [1, 2])->get();
        $cargoLaborales  = DB::table('cargolaboral')->select('carlabid','carlabnombre')->orderBy('carlabnombre')->whereIn('carlabid', [1, 2])->get();

        return response()->json(["fechaActual"            => $fechaActual,             "tipoDestinos"    => $tipoDestinos,   "tipoMedios"      => $tipoMedios,
                                "tipopersonadocumentales" => $tipopersonadocumentales, "personas"        => $personas,       "cargoLaborales"    => $cargoLaborales,
								"data"                    => $data,					   "firmasDocumento" => $firmasDocumento ]);
	}

    public function salve(OficioRequests $request){

        $coddocid      				   = $request->idCD;
	    $codoprid      				   = $request->idCDP;
	    $codopnid      				   = $request->idcdpc;
        $codigodocumental              = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso       = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesoconstancia     = ($codopnid != 000) ? CodigoDocumentalProcesoConstancia::findOrFail($codopnid) : new CodigoDocumentalProcesoConstancia();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid',$request->dependencia)->first();
			$sigla          = $dependencia->depesigla;

			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '5';//Constancia
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
	    	
	    	$codigodocumentalproceso->codoprfecha            = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido   = $request->nombreDirigido;
	    	$codigodocumentalproceso->codoprcorreo           = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido        = $request->contenido;
	    	$codigodocumentalproceso->save();  

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				  = CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				  = $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesoconstancia->codoprid          = $codoprid;
				$coddocumprocesoconstancia->usuaid            = $usuarioId;
				$coddocumprocesoconstancia->codopnconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual);
				$coddocumprocesoconstancia->codopnsigla       = $sigla;
				$coddocumprocesoconstancia->codopnanio        = $anioActual;
			}

			$coddocumprocesoconstancia->tipedoid                = $request->tipoPersona;
		   	$coddocumprocesoconstancia->codopntitulo            = $request->tituloPersona;
		   	$coddocumprocesoconstancia->codopnnombredirigido    = $request->nombreDirigido;
		   	$coddocumprocesoconstancia->codopncontenidoinicial  = $request->contenidoinicial;
		   	$coddocumprocesoconstancia->save();

			foreach($request->firmaPersona as $firmaPersona){
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
			$infodocumento =  DB::table('coddocumprocesoconstancia as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopnconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre','cdp.codoprfecha','d.depecorreo','cl.carlabnombre',
							DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
							->join('persona as p', 'p.persid', '=', 'u.persid')
							->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
							->where('cdpc.codopnid', $request->codigo)->first();

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

			$codigodocumentalproceso           = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid = $estado;
			$codigodocumentalproceso->save();

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
			$infodocumento =  DB::table('coddocumprocesoconstancia as cdpc')
								->select('cdpc.codoprid', DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1) AS totalFirma'),
								  DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1 AND codoprid = cdpc.codoprid) AS totalFirmaRealizadas'))
								->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
								->where('cdpc.codopnid', $request->codigo)->first();

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
			$infodocumento =  DB::table('coddocumprocesoconstancia as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopnconsecutivo) as consecutivoDocumento"),
											'cdp.codoprnombredirigido','cdp.codoprcorreo','d.depecorreo','d.depenombre','p.perscorreoelectronico',
							 DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('persona as p', 'p.persid', '=', 'd.depejefeid')	
							->where('cdpc.codopnid', $request->codigo)->first();

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
	
			$codigodocumentalproceso                = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid      = $estado;
            $codigodocumentalproceso->codoprsellado = true;
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
			$rutaPdf    = $generarPdf->oficio($request->codigo, 'F');
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

			$coddocumprocesoconstancia =  CodigoDocumentalProcesoConstancia::findOrFail($request->codigo);
			$codoprid = $coddocumprocesoconstancia->codoprid;

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
			$dataDocumento = $generarPdf->constancia($request->codigo, 'S');
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    //Funcion que permite obtener el consecutivo del documento
	public function obtenerConsecutivo($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesoconstancia')->select('codopnconsecutivo')
								->where('codopnanio', $anioActual)->where('codopnsigla', $sigla)
								->orderBy('codopnid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopnconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}