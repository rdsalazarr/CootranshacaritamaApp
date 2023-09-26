<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCertificado;
use App\Http\Requests\CertificadoRequests;
use App\Models\CodigoDocumentalProceso;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\showTipoDocumental;
use App\Util\editarDocumentos;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Auth, DB, File;
use Carbon\Carbon;

class CertificadoController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

		$consulta   = DB::table('coddocumprocesocertificado as cdpc')
						->select('cdpc.codopcid as id', 'cdpc.codoprid', DB::raw("CONCAT(cdpc.codopcanio,' - ', cdpc.codopcconsecutivo) as consecutivo"),
								'cdp.codoprfecha as fecha', 'cdpc.codopctitulo as asunto','cdp.codoprnombredirigido as nombredirigido', 
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
		$editarDocumento = new editarDocumento();
		$areas = $editarDocumento->consultarAreaTrabajo();

		return response()->json(["areas" => $areas]);
	}

	public function datos(Request $request)
	{ 
		$this->validate(request(),['tipo' => 'required']);
	
		$id                = $request->id;
		$tipo              = $request->tipo;
		$data              = '';
		$firmasDocumento   = [] ;
		if($tipo === 'U'){
			$visualizar  = new showTipoDocumental();
			list($data, $firmasDocumento) = $visualizar->certificado($id);
		}

		$fechaActual             = Carbon::now()->format('Y-m-d');
		$tipoDestinos            = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
		$tipoMedios              = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$tipoPersonaDocumentales = DB::table('tipopersonadocumental')->select('tipedoid','tipedonombre')->where('tipedoactivo', true)->orderBy('tipedonombre')->get();    
 		$personas                = DB::table('persona')->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
														->orderBy('nombrePersona')
														->whereIn('carlabid', [1, 2])->get();
        $cargoLaborales  = DB::table('cargolaboral')->select('carlabid','carlabnombre')->orderBy('carlabnombre')->whereIn('carlabid', [1, 2])->get();

        return response()->json(["fechaActual"            => $fechaActual,             "tipoDestinos"    => $tipoDestinos,   "tipoMedios"      => $tipoMedios,
                                "tipoPersonaDocumentales" => $tipoPersonaDocumentales, "personas"        => $personas,       "cargoLaborales"  => $cargoLaborales,
								"data"                    => $data,					   "firmasDocumento" => $firmasDocumento ]);
	}

    public function salve(CertificadoRequests $request){
		$editarDocumento = new editarDocumento();
		return $editarDocumento->certificado($request);
	}

	public function solicitarFirma(Request $request)
	{
		$this->validate(request(),['codigo' => 'required','tipo' => 'required', 'observacionCambio' => 'required_if:tipo,E']);

		DB::beginTransaction();
		try {
			$infodocumento =  DB::table('coddocumprocesocertificado as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopcconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre','cdp.codoprfecha','d.depecorreo','cl.carlabnombre',
							DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
							->join('persona as p', 'p.persid', '=', 'u.persid')
							->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
							->where('cdpc.codopcid', $request->codigo)->first();

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
			$codigodocumentalprocesocambioestado->codpceuserid      = Auth::id();
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
			$infodocumento =  DB::table('coddocumprocesocertificado as cdpc')
								->select('cdpc.codoprid', DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1) AS totalFirma'),
								  DB::raw('(SELECT COUNT(codopfid) AS codopfid FROM coddocumprocesofirma WHERE codopffirmado = 1 AND codoprid = cdpc.codoprid) AS totalFirmaRealizadas'))
								->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
								->where('cdpc.codopcid', $request->codigo)->first();

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
			$infodocumento =  DB::table('coddocumprocesocertificado as cdpc')
							->select('cdpc.codoprid', DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopcconsecutivo) as consecutivoDocumento"),
											'cdp.codoprnombredirigido','cdp.codoprcorreo','d.depecorreo','d.depenombre','p.perscorreoelectronico',
							 DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"),
							 DB::raw("CONCAT(tdc.tipdoccodigo,'-', d.depesigla,'-', cdpc.codopcconsecutivo,'-', cdp.codoprfecha,'.pdf') as rutaDocumento"))
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('persona as p', 'p.persid', '=', 'd.depejefeid')	
							->where('cdpc.codopcid', $request->codigo)->first();

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

			$coddocumprocesocertificado =  CodigoDocumentalProcesoCertificado::findOrFail($request->codigo);
			$codoprid = $coddocumprocesocertificado->codoprid;

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
			$dataDocumento = $generarPdf->certificado($request->codigo, 'S');
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}