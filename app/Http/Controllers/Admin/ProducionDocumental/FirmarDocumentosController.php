<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\ProducionDocumental\CodigoDocumentalProcesoCambioEstado;
use App\Models\ProducionDocumental\CodigoDocumentalProcesoFirma;
use App\Models\ProducionDocumental\CodigoDocumentalProceso;
use App\Models\ProducionDocumental\TokenFirmaPersona;
use App\Http\Requests\CertificadoRequests;
use App\Http\Requests\ConstanciaRequests;
use App\Http\Requests\CircularRequests;
use App\Http\Requests\CitacionRequests;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\OficioRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActaRequests;
use App\Util\manejadorDocumentos;
use App\Util\showTipoDocumental;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generarPdf;
use App\Util\notificar;
use Carbon\Carbon;

class FirmarDocumentosController extends Controller
{
    public function index(Request $request)
	{
        $this->validate(request(),['tipo' => 'required']);

        $consulta = DB::table('codigodocumentalproceso as cdp')	
					 		->select('cd.coddocid','cdp.codoprid',
                                'cdp.tiesdoid as estado','cdp.codoprfecha as fecha',
					 			'ted.tiesdonombre as nombreEstado','td.tipdocnombre as tipoDocumento','td.tipdoccodigo as codigoDocumental',
					 			'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombreDirigido','d.depenombre',
                                'codopa.codopaid as actaId','codopc.codopcid as certificadoId','codopl.codoplid as circularId',
                                'codopt.codoptid as citacionId','codopn.codopnid as constanciaId','codopo.codopoid as oficioId' )
	  						->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
	  						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
	  						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
	  						->join('tipodocumental as td', 'td.tipdocid', '=', 'cd.tipdocid')
	  						->join('coddocumprocesofirma as cdpf', 'cdpf.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesoacta as codopa', 'codopa.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesocertificado as codopc', 'codopc.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesocircular as codopl', 'codopl.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesocitacion as codopt', 'codopt.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesoconstancia as codopn', 'codopn.codoprid', '=', 'cdp.codoprid')
                            ->leftJoin('coddocumprocesooficio as codopo', 'codopo.codoprid', '=', 'cdp.codoprid')
							->where('cdpf.persid', auth()->user()->persid);

                            if($request->tipo === 'PENDIENTE')
							    $consulta = $consulta->where('cdpf.codopffirmado', false)->where('cdp.tiesdoid', 2);

                           if($request->tipo === 'FIRMADOS')
                                $consulta = $consulta->where('cdpf.codopffirmado', true);

                    $data = $consulta->orderBy('cd.coddocfechahora')->get();

        return response()->json(["data" => $data]);
    }

    //Funcion para solicitar token de la firma
    public function solicitarToken(Request $request)
    {
        $this->validate(request(),['id' => 'required']);

        DB::beginTransaction();
		try {

            $cdproceso = DB::table('codigodocumentalproceso as cdp')
                            ->select('cdpf.codopfid','td.tipdocnombre as tipoDocumento','td.tipdoccodigo',
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopa.codopaanio, '-', codopa.codopaconsecutivo) as consecutivoActa"),
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopc.codopcanio, '-', codopc.codopcconsecutivo) as consecutivoCertificado"),
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopl.codoplanio, '-', codopl.codoplconsecutivo) as consecutivoCircular"),
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopt.codoptanio, '-', codopt.codoptconsecutivo) as consecutivoCitacion"),
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopn.codopnanio, '-', codopn.codopnconsecutivo) as consecutivoConstancia"),
                            DB::raw("CONCAT(td.tipdoccodigo, '-', codopo.codopoanio, '-', codopo.codopoconsecutivo) as consecutivoOficio") )
                        ->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                        ->join('tipodocumental as td', 'td.tipdocid', '=', 'cd.tipdocid')
                        ->join('coddocumprocesofirma as cdpf', 'cdpf.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesoacta as codopa', 'codopa.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesocertificado as codopc', 'codopc.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesocircular as codopl', 'codopl.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesocitacion as codopt', 'codopt.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesoconstancia as codopn', 'codopn.codoprid', '=', 'cdp.codoprid')
                        ->leftJoin('coddocumprocesooficio as codopo', 'codopo.codoprid', '=', 'cdp.codoprid')
                        ->where('cdp.codoprid',$request->id)
                        ->where('cdpf.persid', auth()->user()->persid)->first();

            $tipoDocumental = ['A' => $cdproceso->consecutivoActa,
                                'B' => $cdproceso->consecutivoCertificado,
                                'C' => $cdproceso->consecutivoCircular,
                                'H' => $cdproceso->consecutivoCitacion,
                                'T' => $cdproceso->consecutivoConstancia,
                                'O' => $cdproceso->consecutivoOficio
                            ];

            $tokenGenerado    = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $fechaHoraActual  = Carbon::now();  
            $tiempoToken      = 5;
            $fechaHoraMaxima  = Carbon::now()->addMinutes($tiempoToken);
            $persona          = DB::table('persona')->select('perscorreoelectronico','persnumerocelular',
                                                            DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombreJefe"))
                                                            ->where('persid', auth()->user()->persid)->first();
            $empresa          = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();

            $firma            = $cdproceso->codopfid; //Identificador de la firma
            $correoEmpresa    = $empresa->emprcorreo;
            $correoUsuario    = $persona->perscorreoelectronico;
            $celularUsuario   = $persona->persnumerocelular;
            $numeroDocumental = $tipoDocumental[$cdproceso->tipdoccodigo];
            $nombreJefe       = $persona->nombreJefe;
            $notificarMovil   = false;

            //Informacion que se almacena en la bd para tenerlo como soporte
            $mensajeCorreo    = 'El día '.$fechaHoraActual.' se envió notificación al correo '.$correoUsuario;
            $mensajeCorreo    .= ' para continuar con la firma del documento ';
            $mensajeCorreo    .= 'con token número '.$tokenGenerado ;

            $mensajeCelular   = 'El día '.$fechaHoraActual.' se envió notificación al celular '.$celularUsuario;
            $mensajeCelular  .= ' para continuar con la firma del documento ';
            $mensajeCelular  .= 'con token número '.$tokenGenerado;

            $tokenfirmas      = DB::table('tokenfirmapersona')
                                        ->select('tofipeid')
                                        ->where('tofipeutilizado', false)->where('persid', auth()->user()->persid)->get();

            foreach($tokenfirmas as $tokenfirma){
                $tokenfirmapersonaUpdate = TokenFirmaPersona::findOrFail($tokenfirma->tofipeid);
                $tokenfirmapersonaUpdate->tofipeutilizado = true;
                $tokenfirmapersonaUpdate->save();
            }
        
            $tokenfirmapersona                              = new TokenFirmaPersona();
            $tokenfirmapersona->persid                      = auth()->user()->persid;
            $tokenfirmapersona->tofipetoken                 = $tokenGenerado;
            $tokenfirmapersona->tofipefechahoranotificacion = $fechaHoraActual;
            $tokenfirmapersona->tofipefechahoramaxvalidez   = $fechaHoraMaxima;
            $tokenfirmapersona->tofipemensajecorreo         = $mensajeCorreo;
            $tokenfirmapersona->tofipemensajecelular        = ($notificarMovil) ? $mensajeCelular : '';
            $tokenfirmapersona->save();

            $tokeMaxConsecutio  = TokenFirmaPersona::latest('tofipeid')->first();
            $idToken            = Crypt::encrypt($tokeMaxConsecutio->tofipeid);

            $mensajeMostrar  = 'Para continuar con el proceso de firmado electrónico de este documento, ';
            $mensajeMostrar  .= 'se ha generado un código el cual fue enviado al correo '.$correoUsuario;
            $mensajeMostrar .= ($notificarMovil) ? ' y al celular con número '.$celularUsuario.'.' :'.';
            $mensajeMostrar .= '<br /><br /> Este token es necesario para completar su proceso de verificación y garantizar la seguridad de su cuenta. ';
            $mensajeMostrar .= 'Por favor, tenga en cuenta que este token será válido durante los próximos '.$tiempoToken.' minutos.<br /><br />';
            $mensajeMostrar .= 'Si excede este tiempo o cierra la ventana sin completar el proceso, deberá solicitar un nuevo token. ';
            $mensajeMostrar .= 'Si no ha recibido el correo electrónico con el token, le recomendamos verificar su carpeta de spam o solicitar uno nuevo.<br />';
            $mensajeMostrar .= '<h2 style="text-align: center;">¡Gracias por su colaboración y compromiso con la seguridad de nuestros servicios!</h2>';

            $notificar          = new notificar();
            $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarFirmadoDocumento')->first();
            $buscar             = Array('numeroDocumental', 'nombreJefe', 'tokenAcceso', 'tiempoToken');
            $remplazo           = Array($numeroDocumental, $nombreJefe,  $tokenGenerado, $tiempoToken); 
            $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
            $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
            $enviarcopia        = $informacioncorreo->innocoenviarcopia;
            $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
            $notificar->correo([$correoUsuario], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

        	DB::commit();
			return response()->json(['success' => true,  'mensajeMostrar' => $mensajeMostrar, 'firma' => $firma, 'tiempoToken' => $tiempoToken * 60, 'idToken' => $idToken]);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    //Funcion que firma el documento
	public function procesar(Request $request){
        $this->validate(request(),['id'     => 'required|numeric', 
                                  'token'   => 'required|string|min:4|max:20',
                                  'tokenId' => 'required',
                                  'firma'   => 'required|numeric'
                                ]);

        $codoprid             = $request->id;
        $token                = $request->token;
        $codopfid             = $request->firma;
        $fechaHoraActual      = Carbon::now();

        try {
            $idToken           = Crypt::decrypt($request->tokenId);
		} catch (DecryptException $e) {
            return response()->json(['success' => false, 'message'=> 'Se produjo un eror al optener el token de la firma, por favor contacte el equipo de soporte técnico']);
		}

		DB::beginTransaction();
		try {

            $tokenfirma = DB::table('tokenfirmapersona')
                                    ->select('tofipetoken','tofipefechahoranotificacion','tofipefechahoramaxvalidez',
                                            'tofipemensajecorreo','tofipemensajecelular','tofipeid')
                                    ->where('tofipeutilizado', false)
                                    ->where('persid', auth()->user()->persid)
                                    ->where('tofipetoken', $token)
                                    ->where('tofipeid', $idToken)
                                    ->first();
            if(!$tokenfirma){
                return response()->json(['success' => false, 'message'=> 'El token con número '.$token.', no concuerda o el tiempo de actividad expiró']);
            }

			//consulto para saber cuantas firma tiene el documento
			$totalFirmas = DB::table('coddocumprocesofirma')->select('codoprid')
                                        ->where('codopffirmado', false)
                                        ->where('codopfid', $codopfid)->get();
	
			if(count($totalFirmas) == 1){
				$codigodocumentalproceso = CodigoDocumentalProceso::findOrFail($codoprid); 
				$codigodocumentalproceso->codoprfirmado = true; //Documento firmado
				$codigodocumentalproceso->tiesdoid      = '4'; //Firmado documento
				$codigodocumentalproceso->save();
			}

            $infodocumento =  DB::table('codigodocumentalproceso as cdp')
                        ->select('d.depecorreo','d.depenombre','p.perscorreoelectronico','tdc.tipdocnombre','cl.carlabnombre',
                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"))							
                        ->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                        ->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
                        ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
                        ->join('persona as p', 'p.persid', '=', 'd.depejefeid')	
                        ->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')	
                        ->where('cdp.codoprid', $codoprid)->first();

            //Datos de la notificacion
            $nombreDependencia = $infodocumento->depenombre;
            $tipoDocumental    = $infodocumento->tipdocnombre;
            $numeroDocumental  = '';
            $nombreJefe        = $infodocumento->nombreJefe;
            $cargoJefe         = $infodocumento->carlabnombre;
            $correoJefe        = $infodocumento->perscorreoelectronico;
            $correoDependencia = $infodocumento->depecorreo;

            //Actualizo los datos
            $tokenfirma                  = TokenFirmaPersona::findOrFail($tokenfirma->tofipeid);
            $tokenfirma->tofipeutilizado = true;
            $tokenfirma->save();

			//Marco como relizado el proceso de la firma
			$codigodocumentalprocesofirma                              = CodigoDocumentalProcesoFirma::findOrFail($codopfid);
		    $codigodocumentalprocesofirma->codopffirmado               = true;
            $codigodocumentalprocesofirma->codopffechahorafirmado      = $fechaHoraActual;
            $codigodocumentalprocesofirma->codopftoken                 = $tokenfirma->tofipetoken;
            $codigodocumentalprocesofirma->codopffechahoranotificacion = $tokenfirma->tofipefechahoranotificacion;
            $codigodocumentalprocesofirma->codopffechahoramaxvalidez   = $tokenfirma->tofipefechahoramaxvalidez;
            $codigodocumentalprocesofirma->codopfmensajecorreo         = $tokenfirma->tofipemensajecorreo;
            $codigodocumentalprocesofirma->codopfmensajecelular        = $tokenfirma->tofipemensajecelular;
			$codigodocumentalprocesofirma->save();	

            $codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
            $codigodocumentalprocesocambioestado->codoprid          = $codoprid;
            $codigodocumentalprocesocambioestado->tiesdoid          = '4';//firmado
            $codigodocumentalprocesocambioestado->codpceusuaid      = Auth::id();
            $codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
            $codigodocumentalprocesocambioestado->codpceobservacion = 'Documento firmado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
            $codigodocumentalprocesocambioestado->save();

            //Envio la notificacion
            $notificar          = new notificar();
            $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarFirmaTipoDocumental')->first();
            $buscar             = Array('nombreDependencia', 'tipoDocumental', 'numeroDocumental', 'nombreJefe','cargoJefe');
            $remplazo           = Array($nombreDependencia, $tipoDocumental,  $numeroDocumental, $nombreJefe, $cargoJefe); 
            $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
            $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
            $enviarcopia        = $informacioncorreo->innocoenviarcopia;
            $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
            $notificar->correo([$correoDependencia], $asunto, $msg, [], $correoJefe, $enviarcopia, $enviarpiepagina);

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Documento firmado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function editarDocumentos(Request $request)
	{
		$this->validate(request(),['id' => 'required','tipoDocumental' => 'required']);
	
		$id                = $request->id;
		$tipoDocumental    = $request->tipoDocumental;
        $visualizar        = new showTipoDocumental();
        $data              = '';
		$firmasDocumento   = [] ;
		$copiaDependencias = [] ;
		$anexosDocumento   = [] ;

        if($tipoDocumental === 'A'){
            list($data, $firmasDocumento) = $visualizar->acta($id);
        }else if($tipoDocumental === 'B'){
            list($data, $firmasDocumento) = $visualizar->certificado($id);
        }else if($tipoDocumental === 'C'){
            list($data, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->circular($id);
        }else if($tipoDocumental === 'H'){
            list($data, $firmasDocumento, $firmaInvitados) = $visualizar->citacion($id);
        }else if($tipoDocumental === 'T'){
            list($data, $firmasDocumento) = $visualizar->constancia($id);
        }else{
            list($data, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->oficio($id);
        }
        $depeid      = $data->depeid;

		$manejadorDocumentos = new manejadorDocumentos();
		list($fechaActual, $tipoDestinos, $tipoMedios, $tipoSaludos, $tipoDespedidas, $dependencias,
		     $personas, $cargoLaborales, $tipoActas, $tipoPersonaDocumentales) = $manejadorDocumentos->consultarInformacionMaestra($tipoDocumental, $depeid);

        return response()->json(["fechaActual"    => $fechaActual,    "tipoDestinos"            => $tipoDestinos,            "tipoMedios"      => $tipoMedios,
                                "tipoSaludos"     => $tipoSaludos,     "tipoDespedidas"          => $tipoDespedidas,         "dependencias"    => $dependencias,
								"personas"        => $personas,        "cargoLaborales"          => $cargoLaborales,         "data"            => $data,
								"firmasDocumento" => $firmasDocumento, "copiaDependencias"       => $copiaDependencias,      "anexosDocumento" => $anexosDocumento,
                                "tipoActas"       => $tipoActas,       "tipoPersonaDocumentales" => $tipoPersonaDocumentales ]);
	}

    public function salvarActa(ActaRequests $request){
		$manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->acta($request);
	}

    public function salvarCertificado(CertificadoRequests $request){
		$manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->certificado($request);
	}

    public function salvarCircular(CircularRequests $request){
		$manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->circular($request);
	}

    public function salvarCitacion(CitacionRequests $request){
        $manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->citacion($request);
	}

    public function salvarConstancia(ConstanciaRequests $request){
		$manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->constancia($request);
	}

    public function salvarOficio(OficioRequests $request){ 
        $manejadorDocumentos = new manejadorDocumentos();
		return $manejadorDocumentos->oficio($request);
    }

    public function showPdf(Request $request)
	{
		$this->validate(request(),['codigo' => 'required', 'tipo' => 'required']);  
        $tipoDocumental = $request->tipo;
		try {
			$generarPdf    = new generarPdf();
            if($tipoDocumental === 'A'){
                $dataDocumento = $generarPdf->acta($request->codigo, 'S');
            }else if($tipoDocumental === 'B'){
                $dataDocumento = $generarPdf->certificado($request->codigo, 'S');
            }else if($tipoDocumental === 'C'){
                $dataDocumento = $generarPdf->circular($request->codigo, 'S');
            }else if($tipoDocumental === 'H'){
                $dataDocumento = $generarPdf->citacion($request->codigo, 'S');
            }else if($tipoDocumental === 'T'){
                $dataDocumento = $generarPdf->constancia($request->codigo, 'S');
            }else {
                $dataDocumento = $generarPdf->oficio($request->codigo, 'S');
            }
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}