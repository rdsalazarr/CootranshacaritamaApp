<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenFirma;
use App\Util\notificar;
use Auth, DB;

class FirmarDocumentosController extends Controller
{
    public function index()
	{
        $data = DB::table('codigodocumentalproceso as cdp')	
					 		->select('cd.coddocid','cdp.codoprid','cdp.tiesdoid as estado','cdp.codoprfecha as fecha',
					 			'ted.tiesdonombre as nombreEstado','td.tipdocnombre as tipoDocumento','td.tipdoccodigo as codigoDocumental',
					 			'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombreDirigido','d.depenombre')
	  						->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
	  						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
	  						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
	  						->join('tipodocumento as td', 'td.tipdocid', '=', 'cd.tipdocid')
	  						->join('coddocumprocesofirma as cdpf', 'cdpf.codoprid', '=', 'cdp.codoprid')
							->where('cdpf.persid', auth()->user()->persid)
							->where('cdpf.codopffirmado', false)
							->where('cdp.tiesdoid', 2)//Solicita firma
	  						->orderBy('cdp.created_at')->get();	
        
        return response()->json(["data" => $data]);
    }

    //Funcion para solicitar firma
    public function solicitarFirma($soliid)
    {
        DB::beginTransaction();
		try {
            $tokenGenerado   = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $fechaHoraActual = Carbon::now();  
            $tiempoToken     = 10;
            $fechaHoraMaxima = Carbon::now()->addMinutes($tiempo_token);
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

           
            $asunto         = "Envió del token para firmar la solicitud número ".$solicitud->soliconsecutivo;
            $msg            = "El código para continuar con la firma del documento es ".$tokenGenerado;
           

            $mensajeMostrar  = 'El código para continuar con el proceso de firma fue enviado a '.$correoUsuario;
            $mensajeMostrar .= ($celularUsuario != '') ? ' y al celular con número '.$celularUsuario :'';
            $mensajeMostrar .= ', recuerde que solo tiene '.$tiempoToken.' minutos para realizar el ';
            $mensajeMostrar .= 'proceso de lo contrario deberá solicitar un nuevo token.';

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

             // return view('admin.solicitud.verificar.firmar-documento',['solicitud' =>$solicitud, 'fechaHoraActual' =>$fechaHoraActual,'mensajeMostrar' => $mensajeMostrar]);
        	DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}      
    }


    //Funcion que firma el documento
	public function firmar(Request $request){ 
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
                return response()->json(['msgError' => 'El token con número '.$request->token.', no concuerda o el tiempo de activad expiro.']);
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

    public function editarOficio(OficioRequests $request){ 


    }

}