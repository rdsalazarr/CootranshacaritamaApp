<?php

namespace App\Http\Controllers\Home;

use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\ProducionDocumental\TokenFirmaPersona;
use App\Models\Vehiculos\VehiculoContratoFirma;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\GenerarContrato;
use Illuminate\Http\Request;
use Exception, DB, URL;
use App\Util\notificar;
use App\Util\generales;
use App\Util\encrypt;
use Carbon\Carbon;

class FirmarContratoPersonaController extends Controller
{
	public function index($id, $id2)
	{
		//Verifico las variables
		try {
	    	$contratoId = Crypt::decrypt($id);
			$firmaId    = Crypt::decrypt($id2);
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}

        return view('home.firmarContrato',['contratoId' => $id,'firmaId' => $id2, 'title' => 'Realizar proceso de firmado de contrato']);
	}

    public function infoContrato(Request $request)
	{
        $this->validate(request(),['firmaId'=> 'required']);

        try {
            $firmaId  = Crypt::decrypt($request->firmaId);
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}

        try {
            $vehiculoContrato = DB::table('vehiculocontrato as vc')
                                    ->select('vc.vehconid', DB::raw("if(vcf.vecofifirmado = 1 ,'SI', 'NO') as contratoFirmado"),
                                    DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),'v.vehiplaca',
                                    DB::raw("CONCAT('Contrato_',vc.vehconanio,vc.vehconnumero,'.pdf' ) as rutaPdfContrato"),
                                    DB::raw('(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 WHERE vcf1.vehconid = vc.vehconid ) AS totalFirmas'),
                                    DB::raw('(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 WHERE vcf2.vehconid = vc.vehconid and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas'))	
                                    ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                    ->where('vcf.vecofiid', $firmaId)->first();

            $placaVehiculo   = Crypt::encrypt($vehiculoContrato->vehiplaca);
            $rutaPdfContrato = Crypt::encrypt($vehiculoContrato->rutaPdfContrato);
            $tiempoToken     = 5;

            return response()->json(['success' => true, "data" => $vehiculoContrato, "tiempoToken" => $tiempoToken, "placaVehiculo" => $placaVehiculo, "rutaPdfContrato" => $rutaPdfContrato ]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function downloadContrato(Request $request)
	{
		$this->validate(request(),['contratoId'=> 'required']);	

        try {
	    	$contratoId = Crypt::decrypt($request->contratoId);
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}

        try {
            return response()->json(['success' => true, "data" => GenerarContrato::vehiculo($contratoId, 'S')]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function solicitarToken(Request $request)
	{
		$this->validate(request(),['contratoId'=> 'required', 'firmaId'=> 'required']);	

        try {
	    	$contratoId = Crypt::decrypt($request->contratoId);
            $firmaId    = Crypt::decrypt($request->firmaId);
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}
    
        try {

            $vehiculoContrato = DB::table('vehiculocontrato as vc')
                                ->select(DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"), 'p.persid','p.perscorreoelectronico','p.persnumerocelular',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))	
                                ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                ->where('vcf.vecofifirmado', false)
                                ->where('vc.vehconid', $contratoId)
                                ->where('vcf.vecofiid', $firmaId)->first();

            $empresa          = DB::table('empresa as e')->select('e.emprcorreo',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                                    ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                    ->where('emprid', 1)->first();

            $tokenGenerado   = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $fechaHoraActual = Carbon::now(); 
            $tiempoToken     = 5; //Tiempo para el token
            $fechaHoraMaxima = Carbon::now()->addMinutes($tiempoToken);
            $correoEmpresa   = $empresa->emprcorreo;
            $nombreGerente   = $empresa->nombreGerente;
            $correoUsuario   = $vehiculoContrato->perscorreoelectronico;
            $celularUsuario  = $vehiculoContrato->persnumerocelular;
            $personaId       = $vehiculoContrato->persid;
            $numeroContrato  = $vehiculoContrato->numeroContrato;
            $nombreAsociado  = $vehiculoContrato->nombreAsociado;
            $notificarMovil  = false;

            //Informacion que se almacena en la bd para tenerlo como soporte
            $mensajeCorreo    = 'El día '.$fechaHoraActual.' se envió notificación al correo '.$correoUsuario;
            $mensajeCorreo    .= ' para continuar con la firma del documento ';
            $mensajeCorreo    .= 'con token número '.$tokenGenerado ;

            $mensajeCelular   = 'El día '.$fechaHoraActual.' se envió notificación al celular '.$celularUsuario;
            $mensajeCelular  .= ' para continuar con la firma del documento ';
            $mensajeCelular  .= 'con token número '.$tokenGenerado;

            $tokenfirmas      = DB::table('tokenfirmapersona')->select('tofipeid')
                                        ->where('tofipeutilizado', false)->where('persid', $personaId)->get();

            foreach($tokenfirmas as $tokenfirma){
                $tokenfirmapersonaUpdate                  = TokenFirmaPersona::findOrFail($tokenfirma->tofipeid);
                $tokenfirmapersonaUpdate->tofipeutilizado = true;
                $tokenfirmapersonaUpdate->save();
            }

            $tokenfirmapersona                              = new TokenFirmaPersona();
            $tokenfirmapersona->persid                      = $personaId;
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
            $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'solicitudTokeFirmaContratoAsociado')->first();
            $buscar             = Array('numeroContrato', 'nombreAsociado', 'tokenAcceso', 'tiempoToken','nombreGerente');
            $remplazo           = Array($numeroContrato, $nombreAsociado,  $tokenGenerado, $tiempoToken, $nombreGerente); 
            $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
            $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
            $enviarcopia        = $informacioncorreo->innocoenviarcopia;
            $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
            $notificar->correo([$correoUsuario], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

        	DB::commit();
			return response()->json(['success' => true,  'mensajeMostrar' => $mensajeMostrar, 'tiempoToken' => $tiempoToken * 60, 'idToken' => $idToken]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function salveFirma(Request $request)
    {
        $this->validate(request(),['token'     => 'required|string|min:4|max:20',
                                  'tokenId'    => 'required',
                                  'contratoId' => 'required',
                                  'firmaId'    => 'required'
                                ]);

        try {
            $idToken    = Crypt::decrypt($request->tokenId);
            $contratoId = Crypt::decrypt($request->contratoId);
            $firmaId    = Crypt::decrypt($request->firmaId);
		} catch (DecryptException $e) {
            return response()->json(['success' => false, 'message'=> 'Se produjo un eror al optener el token de la firma, por favor contacte el equipo de soporte técnico']);
		}

		DB::beginTransaction();
		try {

            $token           = $request->token;
            $fechaHoraActual = Carbon::now();
            $generales       = new generales();
            $tokenfirma      = DB::table('tokenfirmapersona')
                                    ->select('tofipetoken','tofipefechahoranotificacion','tofipefechahoramaxvalidez', 'tofipemensajecorreo','tofipemensajecelular','tofipeid',
                                            DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 INNER JOIN vehiculocontrato as vc ON vc.vehconid = vcf1.vehconid WHERE vcf1.vehconid = '$contratoId') AS totalFirmas"),
                                            DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 INNER JOIN vehiculocontrato as vc ON vc.vehconid = vcf2.vehconid WHERE vcf2.vehconid = '$contratoId' and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                                    ->where('tofipeutilizado', false)
                                    ->where('tofipetoken', $token)
                                    ->where('tofipeid', $idToken)
                                    ->first();

            if(!$tokenfirma){
                return response()->json(['success' => false, 'message'=> 'El token con número '.$token.', no concuerda o el tiempo de actividad expiró']);
            }

            //Actualizo los datos
            $tokenfirma                  = TokenFirmaPersona::findOrFail($tokenfirma->tofipeid);
            $tokenfirma->tofipeutilizado = true;
            $tokenfirma->save();

            //Marco como relizado el proceso de la firma
           
            $vehiculocontratofirma                              = VehiculoContratoFirma::findOrFail($firmaId);
            $vehiculocontratofirma->vecofifirmado               = true;
            $vehiculocontratofirma->vecofifechahorafirmado      = $fechaHoraActual;
            $vehiculocontratofirma->vecofitoken                 = $tokenfirma->tofipetoken;
            $vehiculocontratofirma->vecofiipacceso              = $generales->optenerIP;
            $vehiculocontratofirma->vecofifechahoranotificacion = $tokenfirma->tofipefechahoranotificacion;
            $vehiculocontratofirma->vecofifechahoramaxvalidez   = $tokenfirma->tofipefechahoramaxvalidez;
            $vehiculocontratofirma->vecofimensajecorreo         = $tokenfirma->tofipemensajecorreo;
            $vehiculocontratofirma->vecofimensajecelular        = $tokenfirma->tofipemensajecelular;
            $vehiculocontratofirma->save();

            if($tokenfirma->totalFirmas === $tokenfirma->totalFirmasRealizadas + 1){
                GenerarContrato::vehiculo($contratoId, 'F');//Descargo el contrato
            }

            DB::commit();
			return response()->json(['success' => true, 'message' => 'Contrato firmado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}