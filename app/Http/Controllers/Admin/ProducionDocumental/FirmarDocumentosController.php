<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Http\Requests\CertificadoRequests;
use App\Http\Requests\ConstanciaRequests;
use App\Http\Requests\CircularRequests;
use App\Http\Requests\CitacionRequests;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActaRequests;
use App\Util\manejadorDocumentos;
use App\Util\showTipoDocumental;
use Illuminate\Http\Request;
use App\Models\TokenFirma;
use App\Util\generarPdf;
use App\Util\notificar;
use Auth, DB;

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
                                'codopt.codoptid as citacionId','codopn.codopnid as constanciaId','codopo.codopoid as oficioId'
                                )
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
							    $consulta = $consulta->where('cdpf.codopffirmado', false);
                                                     // ->where('cdp.tiesdoid', 2);

                           if($request->tipo === 'FIRMADOS')
                                $consulta = $consulta->where('cdpf.codopffirmado', true);

                    $data = $consulta->orderBy('cd.coddocfechahora')->get();

        return response()->json(["data" => $data]);
    }

    public function verificar(Request $request)
	{
        $this->validate(request(),['id' => 'required', 'tipo' => 'required']);
        $codoprid       = $request->id;
        $tipoDocumental = $request->tipo;


        dd($request->id);
    }

    //Funcion para solicitar firma
    public function solicitarToken(Request $request)
    {
        DB::beginTransaction();
		try {
            $tokenGenerado   = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $fechaHoraActual = Carbon::now();  
            $tiempoToken     = 5;
            $fechaHoraMaxima = Carbon::now()->addMinutes($tiempoToken);
            $solicitud       =  DB::table('solicitud')->select('soliid','soliconsecutivo','solifechahora','solifecharespuesta',
                                    'solidescripcion')
                                ->where('soliid', $soliid)->first();

            $persona         =  DB::table('persona')->select('perscargo','perscorreo','perstelefonomovil')->where('persid', auth()->user()->persid)->first();

            $empresa        = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
            $correoEmpresa  = $empresa->emprcorreo;
            $correoUsuario  = $persona->perscorreo;
            $celularUsuario = $persona->perstelefonomovil;

            $mensajeCorreo  = 'El día '.$fechaHoraActual.' se envió notificación al correo '.$correoUsuario;
            $mensajeCorreo .= ' para continuar con la firma del documento ';
            $mensajeCorreo .= 'con token número '.$tokenGenerado ;

            $mensajeCelular  = 'El día '.$fechaHoraActual.' se envió notificación al celular '.$celularUsuario;
            $mensajeCelular .= ' para continuar con la firma del documento ';
            $mensajeCelular .= 'con token número '.$tokenGenerado;

            $tokenfirma = new TokenFirma();
            $tokenfirma->tokfirtoken                 = $tokenGenerado;
            $tokenfirma->tokfirfechahoranotificacion = $fechaHoraActual;
            $tokenfirma->tokfirfechahoramaxvalidez   = $fechaHoraMaxima; 
            $tokenfirma->tokfirmsjcorreo             = $mensajeCorreo;
            $tokenfirma->tokfirmsjcelular            = '';
            $tokenfirma->save();

            $mensajeMostrar  = 'Para continuar con el proceso de firmado electrónico de este documento, ';
            $mensajeMostrar  .= 'se ha generado un código el cual fue enviado al correo '.$correoUsuario;
            $mensajeMostrar .= ($celularUsuario != '') ? ' y al celular con número '.$celularUsuario :'.';
            $mensajeMostrar .= 'Este token es necesario para completar su proceso de verificación y garantizar la seguridad de su cuenta.';
            $mensajeMostrar .= 'Por favor, tenga en cuenta que este token será válido durante los próximos '.$tiempoToken.' minutos.';
            $mensajeMostrar .= 'Si excede este tiempo o cierra la ventana sin completar el proceso, deberá solicitar un nuevo token.';
            $mensajeMostrar .= 'Si no ha recibido el correo electrónico con el token, le recomendamos verificar su carpeta de spam o solicitar uno nuevo.';
            $mensajeMostrar .= '¡Gracias por su colaboración y compromiso con la seguridad de nuestros servicios!';

            $notificar         = new notificar();
            $informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarFirmadoDocumento')->first();
               foreach($firmaDocumentos as $firmaDocumento){
                $email              = $firmaDocumento->perscorreoelectronico;        
                $nombreFeje         = $firmaDocumento->nombreJefe;
                $buscar             = Array('numeroDocumental', 'nombreFeje', 'tokenAcceso');
                $remplazo           = Array($numeroDocumental, $nombreFeje,  $tokenAcceso); 
                $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
                $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
                $enviarcopia        = $informacioncorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
                $notificar->correo([$email], $asunto, $msg, '', $emailDependencia, $enviarcopia, $enviarpiepagina);
            }

             // return view('admin.solicitud.verificar.firmar-documento',['solicitud' =>$solicitud, 'fechaHoraActual' =>$fechaHoraActual,'mensajeMostrar' => $mensajeMostrar]);
        	DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}      
    }

    //Funcion que firma el documento
	public function procesar(Request $request){ 
		$idUser = Auth::id();
		DB::beginTransaction();
		try {

            $fechaHoraActual = Carbon::now();
            $tokenfirma      = DB::table('tokenfirma')
                                    ->select('tokfirtoken','tokfirfechahoranotificacion',
                                            'tokfirmsjcorreo','tokfirmsjcelular','tokfirid')
                                    ->where('tokfirutilizado', false)
                                    ->where('tokfirtoken', $request->token)
                                    ->whereTime('tokfirfechahoranotificacion','<=', $fechaHoraActual)
                                    ->whereTime('tokfirfechahoramaxvalidez','>=', $fechaHoraActual)
                                    ->first();

            if(!$tokenfirma){
                return response()->json(['msgError' => 'El token con número '.$request->token.', no concuerda o el tiempo de actividad expiro']);
            }   

            $tokenfirma = TokenFirma::findOrFail($tokenfirma->tokfirid);               
            $tokenfirma->tokfirutilizado = true;
            $tokenfirma->save();

            /*  $solicitudfirma = new SolicitudFirma();
                $solicitudfirma->soliid = $request->id_solicitud;
                $solicitudfirma->persid = auth()->user()->persid;
                $solicitudfirma->solfircargo = $persona->perscargo; 
                $solicitudfirma->solfirfechahoraquefirma = $fechaHoraActual;
                $solicitudfirma->solfirfechahoranotificacion = $tokenfirma->tokfirfechahoranotificacion;                
                $solicitudfirma->solfirtoken = $tokenfirma->tokfirtoken;
                $solicitudfirma->solfirmediocorreoverificacion = $tokenfirma->tokfirmsjcorreo;
                $solicitudfirma->solfirmediocelularverificacion = $tokenfirma->tokfirmsjcelular;
                $solicitudfirma->save(); */


			//consulto para saber cuantas firma tiene el documento
			$totalFirma = DB::table('coddocumprocesofirma as cdpf')	
					->select('cdpf.codopfid')
					->join('persona as p', 'p.persid', '=', 'cdpf.persid')
					->join('users as u', 'u.persid', '=', 'p.persid')
					->where('cdpf.codopffirmado', false)
					->where('cdpf.codoprid', $request->id)
					->get();
	
			if(count($totalFirma) == 1){
				$codigodocumentalproceso = CodigoDocumentalProceso::findOrFail($request->id); 
				$codigodocumentalproceso->codoprfirmado = true; //Documento firmado
				$codigodocumentalproceso->tiesdoid = '4'; //Firmar documento		
				$codigodocumentalproceso->save();  	
			}
			
			//Firmo el documento 
			$firmaPersona = DB::table('coddocumprocesofirma as cdpf')
					->select('cdpf.codopfid')
					->join('persona as p', 'p.persid', '=', 'cdpf.persid')
					->join('users as u', 'u.persid', '=', 'p.persid')
					->where('cdpf.codopffirmado', false)
					->where('cdpf.codoprid', $request->id)
					->where('u.id', $idUser)
					->first();
						
			//Marco como relizado el proceso de la firma
			$codigodocumentalprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($firmaPersona->codopfid);		   	
		    $codigodocumentalprocesofirma->codopffirmado = true;
			$codigodocumentalprocesofirma->save();	

            $codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
            $codigodocumentalprocesocambioestado->codoprid          = $request->id;
            $codigodocumentalprocesocambioestado->tiesdoid          = '4';//firmado
            $codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
            $codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
            $codigodocumentalprocesocambioestado->codpceobservacion = 'Documento firmado por '.auth()->user()->usuanombre;
            $codigodocumentalprocesocambioestado->save();     
			
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function editarDocumentos(Request $request)
	{
		$this->validate(request(),['id' => 'required','tipo' => 'required']);
	
		$id                = $request->id;
		$tipoDocumental    = $request->tipo;
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