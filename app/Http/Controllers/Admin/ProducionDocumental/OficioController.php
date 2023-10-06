<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Models\RadicacionDocumentoEntranteCambioEstado;
use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoOficio;
use App\Models\RadicacionDocumentoEntrante;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\manejadorDocumentos;
use App\Util\showTipoDocumental;
use Exception, Auth, DB, File;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon; 

class OficioController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

		$consulta   = DB::table('coddocumprocesooficio as cdpo')
						->select('cdpo.codopoid as id', 'cdpo.codoprid', DB::raw("CONCAT(cdpo.codopoanio,' - ', cdpo.codopoconsecutivo) as consecutivo"),
								'cdp.codoprfecha as fecha', 'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 
								'd.depenombre as dependencia', 'ted.tiesdonombre as estado')
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
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
		$manejadorDocumentos = new manejadorDocumentos();
		$areas = $manejadorDocumentos->consultarAreaTrabajo();

		return response()->json(["areas" => $areas]);
	}

	public function datos(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

		$id                 = $request->id;
		$tipo               = $request->tipo;
		$depeid             = $request->dependencia;
		$data               = '';
		$firmasDocumento    = [] ;
		$copiaDependencias  = [] ;
		$anexosDocumento    = [] ;
		$radicadosDocumento = [];
		if($tipo === 'U'){			
			$visualizar  = new showTipoDocumental();
			list($data, $firmasDocumento, $copiaDependencias, $anexosDocumento, $radicadosDocumento) = $visualizar->oficio($id);
			$depeid      = $data->depeid;
		}

		$radicadosRecibidos = DB::table('radicaciondocumentoentrante as rde')
									->select('rde.radoenid', 'rde.radoenanio', 'rde.radoenconsecutivo')
									->join('dependencia as d', 'd.depeid', '=', 'rde.depeid')
									->join('radicaciondocentdependencia as rded', function($join)
										{
												$join->on('rded.radoenid', '=', 'rde.radoenid');
												$join->on('rded.depeid', '=', 'rde.depeid'); 
										})
									->where('rde.tierdeid', 3) //Recibido
									->where('rde.radoenrequiererespuesta', true)
									->whereIn('rded.depeid', function($query) {
											$query->select('depperdepeid')->from('dependenciapersona')
													->where('depperpersid',  auth()->user()->persid);
											})->get();

		$manejadorDocumentos = new manejadorDocumentos();
		list($fechaActual, $tipoDestinos, $tipoMedios, $tipoSaludos, $tipoDespedidas, $dependencias,
		     $personas, $cargoLaborales, $tipoActas, $tipoPersonaDocumentales) = $manejadorDocumentos->consultarInformacionMaestra('O', $depeid);

        return response()->json(["fechaActual"       => $fechaActual,      "tipoDestinos"         => $tipoDestinos,      "tipoMedios"      => $tipoMedios,
                                "tipoSaludos"        => $tipoSaludos,       "tipoDespedidas"      => $tipoDespedidas,    "dependencias"    => $dependencias,
								"personas"           => $personas,          "cargoLaborales"      => $cargoLaborales,    "data"            => $data,
								"firmasDocumento"    => $firmasDocumento,   "copiaDependencias"   => $copiaDependencias, "anexosDocumento" => $anexosDocumento,
								"radicadosRecibidos" => $radicadosRecibidos, "radicadosDocumento" => $radicadosDocumento]);
	}

    public function salve(OficioRequests $request){
		$manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->oficio($request);
	}

	public function solicitarFirma(Request $request)
	{
		$this->validate(request(),['codigo' => 'required','tipo' => 'required', 'observacionCambio' => 'required_if:tipo,E']);

		DB::beginTransaction();
		try {
			$infodocumento =  DB::table('coddocumprocesooficio as cdpo')
							->select('cdpo.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpo.codopoconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre','cdp.codoprfecha','d.depecorreo','cl.carlabnombre',
							DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
							->join('persona as p', 'p.persid', '=', 'u.persid')
							->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
							->where('cdpo.codopoid', $request->codigo)->first();

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
			$codigodocumentalproceso->save(); 

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceusuaid      = Auth::id();
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = $observacion;
			$codigodocumentalprocesocambioestado->save();

			//Enviamos la notificacion
			$notificar         = new notificar();
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
				$notificar->correo([$email], $asunto, $msg, [], $emailDependencia, $enviarcopia, $enviarpiepagina);
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
			$infodocumento =  DB::table('coddocumprocesooficio as cdpo')
								->select('cdpo.codoprid', DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1) AS totalFirma'),
								  DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1 AND codoprid = cdpo.codoprid) AS totalFirmaRealizadas'))
								->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
								->where('cdpo.codopoid', $request->codigo)->first();

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
			$infodocumento =  DB::table('coddocumprocesooficio as cdpo')
							->select('cdpo.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpo.codopoconsecutivo) as consecutivoDocumento"),
											'cdp.codoprnombredirigido','cdp.codoprcorreo','d.depecorreo','d.depenombre','p.perscorreoelectronico',
							 DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"),
						     DB::raw("CONCAT(tdc.tipdoccodigo,'-', d.depesigla,'-', cdpo.codopoconsecutivo,'-', cdp.codoprfecha,'.pdf') as rutaDocumento"),
							 DB::raw('(SELECT COUNT(cdprdeid) AS cdprdeid FROM coddocumprocesoraddocentrante WHERE codoprid = cdp.codoprid) AS totalRadicados'))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('persona as p', 'p.persid', '=', 'd.depejefeid')	
							->where('cdpo.codopoid', $request->codigo)->first();

			$codoprid          = $infodocumento->codoprid;
			$numeroDocumental  = $infodocumento->consecutivoDocumento;
			$jefeDependencia   = $infodocumento->nombreJefe;
			$nombreUsuario     = $infodocumento->codoprnombredirigido;
			$correoNotificados = $infodocumento->codoprcorreo;
			$totalRadicados    = $infodocumento->totalRadicados;
			$email             = explode(",", $correoNotificados);
			$nombreDependencia = $infodocumento->depenombre;
			$emailDependencia  = $infodocumento->depecorreo;
			$nombreEmpresa     = $empresa->emprnombre;
			$siglaEmpresa      = $empresa->emprsigla;
			$fechaHoraActual   = Carbon::now();
			$estado            = 5; //Sellado
			$mensajeCorreo     = '';

			if($totalRadicados > 0){
				$radicadosDocumento = DB::table('coddocumprocesoraddocentrante as ddprde')
								->select('ddprde.cdprdeid','rde.radoenid', 'rde.radoenanio', 'rde.radoenconsecutivo')
								->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'ddprde.radoenid')
								->where('ddprde.codoprid', $codoprid)
								->get();

				foreach($radicadosDocumento as $radicadosocumento){
					$radoenid       = $radicadosocumento->radoenid;
					$estadoRadicado = '4'; //Respondido
					$radicaciondocumentoentrante           =  RadicacionDocumentoEntrante::findOrFail($radicadosocumento->radoenid);
					$radicaciondocumentoentrante->tierdeid = $estadoRadicado;
					$radicaciondocumentoentrante->save();

					//Almaceno la trazabilidad del radicado
					$radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
					$radicaciondocentcambioestado->radoenid          = $radoenid;
					$radicaciondocentcambioestado->tierdeid          = $estadoRadicado;
					$radicaciondocentcambioestado->radeceusuaid      = Auth::id();
					$radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
					$radicaciondocentcambioestado->radeceobservacion = 'Radicado respondido por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual.', mediante el tipo documental número '.$numeroDocumental;
					$radicaciondocentcambioestado->save();
				}
			}
	
			$codigodocumentalproceso                      = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid            = $estado;
			$codigodocumentalproceso->codoprsellado       = true;
			$codigodocumentalproceso->codoprrutadocumento = Crypt::encrypt($infodocumento->rutaDocumento);
			$codigodocumentalproceso->save();

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceusuaid      = Auth::id();
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = 'Solicitud de sellado del documento realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
			$codigodocumentalprocesocambioestado->save();

			//Genero una copia del documento en el servidor
			$generarPdf = new generarPdf();
			$rutaPdf    = $generarPdf->oficio($request->codigo, 'F');
			if($email != null or $email != ''){//Enviamos la notificacion al usuario
				$notificar         = new notificar();
				$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarEnvioDocumento')->first();
				$buscar            = Array('numeroDocumental', 'nombreUsuario', 'jefeDependencia', 'nombreEmpresa','nombreDependencia');
				$remplazo          = Array($numeroDocumental, $nombreUsuario,  $jefeDependencia, $nombreEmpresa, $nombreDependencia); 
				$asunto            = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
				$msg               = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
				$enviarcopia       = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
				$notificar->correo($email, $asunto, $msg, [$rutaPdf], $emailDependencia, $enviarcopia, $enviarpiepagina);
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

			$codigodocumentalprocesooficio =  CodigoDocumentalProcesoOficio::findOrFail($request->codigo);
			$codoprid = $codigodocumentalprocesooficio->codoprid;

			$codigodocumentalproceso           = CodigoDocumentalProceso::findOrFail($codoprid);
			$codigodocumentalproceso->tiesdoid = $estado;
			$codigodocumentalproceso->save();

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
			$codigodocumentalprocesocambioestado->codoprid          = $codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = $estado;
			$codigodocumentalprocesocambioestado->codpceusuaid      = Auth::id();
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

		$manejadorDocumentos = new manejadorDocumentos();
		$cambioEstados = $manejadorDocumentos->trazabilidad($request->codigo);

		return response()->json(['cambioEstados' => $cambioEstados]);
	}

	public function showPdf(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);
		try {
			$generarPdf    = new generarPdf();
			$dataDocumento = $generarPdf->oficio($request->codigo, 'S');
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}