<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\ProducionDocumental\TokenFirmaPersona;
use App\Models\Vehiculos\VehiculoContratoFirma;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use Exception, Auth, DB, URL;
use App\Util\GenerarContrato;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;

class FirmarContratoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate(request(),['tipo' => 'required']);

        try{

            $consulta = DB::table('vehiculocontratofirma as vcf')
                            ->select('v.vehiid','vcf.vecofiid', 'vc.vehconfechainicial as fechaContrato', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),
                            DB::raw("CONCAT(tv.tipvehnombre,' DE PLACA (',v.vehiplaca,') NÚMERO INTERNO (',v.vehinumerointerno,')') as nombreVehiculo"),
                            DB::raw("if((SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 WHERE vcf1.vecofiid = vcf.vecofiid) = 1 ,'SI', 'NO') as firmadoAsociado"),
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                            ->join('vehiculocontrato as vc', 'vcf.vehconid', '=', 'vc.vehconid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                            ->where('vcf.persid', Auth::id());
                            if($request->tipo === 'PENDIENTE')
                                $consulta = $consulta->whereNull('vcf.vecofitoken');
                            
                $data = $consulta->orderBy('vcf.vecofiid')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function solicitarToken(Request $request)
    {
        $this->validate(request(),['id' => 'required']);
        $firmaId = $request->id;
        
        try {

            $vehiculoContrato = DB::table('vehiculocontrato as vc')
                                ->select('vc.vehconid', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"), 'p.persid','p.perscorreoelectronico','p.persnumerocelular',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))	
                                ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                ->where('vcf.vecofifirmado', false)
                                ->where('vcf.persid', auth()->user()->persid)
                                ->where('vcf.vecofiid', $firmaId)->first();

            $asociadoVehiculo = DB::table('vehiculocontrato as vc')
                                ->select(DB::raw("(SELECT emprcorreo FROM empresa where emprid = '1' ) AS correoEmpresa"),
                                 DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))	
                                ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                ->where('vc.vehconid', $vehiculoContrato->vehconid)
                                ->whereNotIn('vcf.vecofiid', [$firmaId])->first();

            $tokenGenerado   = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $fechaHoraActual = Carbon::now(); 
            $tiempoToken     = 5; //Tiempo para el token
            $fechaHoraMaxima = Carbon::now()->addMinutes($tiempoToken);
            $correoEmpresa   = $asociadoVehiculo->correoEmpresa;
            $nombreGerente   = $vehiculoContrato->nombreGerente;
            $correoUsuario   = $vehiculoContrato->perscorreoelectronico;
            $celularUsuario  = $vehiculoContrato->persnumerocelular;
            $personaId       = $vehiculoContrato->persid;
            $numeroContrato  = $vehiculoContrato->numeroContrato;
            $nombreAsociado  = $asociadoVehiculo->nombreAsociado;
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
            $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'solicitudTokeFirmaContratoGerente')->first();
            $buscar             = Array('numeroContrato', 'nombreAsociado', 'tokenAcceso', 'tiempoToken','nombreGerente');
            $remplazo           = Array($numeroContrato, $nombreAsociado,  $tokenGenerado, $tiempoToken, $nombreGerente); 
            $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
            $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
            $enviarcopia        = $informacioncorreo->innocoenviarcopia;
            $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
            $notificar->correo([$correoUsuario], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

        	DB::commit();
			return response()->json(['success' => true,  'mensajeMostrar' => $mensajeMostrar, 'firma' => $firmaId, 'tiempoToken' => $tiempoToken * 60, 'idToken' => $idToken]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function salveFirma(Request $request)
    {
        $this->validate(request(),['id'       => 'required|numeric', 
                                    'token'   => 'required|string|min:4|max:20',
                                    'tokenId' => 'required',
                                    'firma'   => 'required|numeric'
                                ]);

		DB::beginTransaction();
		try {
            $contratoId      = $request->id;
            $token           = $request->token;
            $vecofiid        = $request->firma;
            $fechaHoraActual = Carbon::now();
    
            try {
                $idToken     = Crypt::decrypt($request->tokenId);
            } catch (DecryptException $e) {
                return response()->json(['success' => false, 'message'=> 'Se produjo un eror al optener el token de la firma, por favor contacte el equipo de soporte técnico']);
            }

            $tokenfirma      = DB::table('tokenfirmapersona')
                                    ->select('tofipetoken','tofipefechahoranotificacion','tofipefechahoramaxvalidez', 'tofipemensajecorreo','tofipemensajecelular',
                                            DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 INNER JOIN vehiculocontrato as vc ON vc.vehconid = vcf1.vehconid WHERE vcf1.vehconid = '$contratoId') AS totalFirmas"),
                                            DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 INNER JOIN vehiculocontrato as vc ON vc.vehconid = vcf2.vehconid WHERE vcf2.vehconid = '$contratoId' and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                                    ->where('tofipeutilizado', false)
                                    ->where('persid', auth()->user()->persid)
                                    ->where('tofipetoken', $token)
                                    ->where('tofipeid', $idToken)
                                    ->first();

            if(!$tokenfirma){
                return response()->json(['success' => false, 'message'=> 'El token con número '.$token.', no concuerda o el tiempo de actividad expiró']);
            }

            //Actualizo los datos
            $tokenfirma                  = TokenFirmaPersona::findOrFail($idToken);
            $tokenfirma->tofipeutilizado = true;
            $tokenfirma->save();

            //Marco como relizado el proceso de la firma
            $vehiculocontratofirma                              = VehiculoContratoFirma::findOrFail($vecofiid);
            $vehiculocontratofirma->vecofifirmado               = true;
            $vehiculocontratofirma->vecofifechahorafirmado      = $fechaHoraActual;
            $vehiculocontratofirma->vecofitoken                 = $tokenfirma->tofipetoken;
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

    public function verificarFirma(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric' ]);

		try {

            $data  = DB::table('vehiculocontratofirma as vcf')
                            ->select('vehconid',
                                    DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 WHERE vcf1.vehconid = vcf.vehconid) AS totalFirmas"),
                                    DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 WHERE vcf2.vehconid = vcf.vehconid and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                            ->where('vcf.vecofiid', $request->codigo)
                            ->first();

			return response()->json(['success' => true, 'data' => $data]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function contratoPdf(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric' ]);

		try {
            $url              = URL::to('/');
            $vehiculoContrato = DB::table('vehiculocontrato as vc')
                                ->select(DB::raw("CONCAT('/archivos/vehiculo/',v.vehiplaca,'/Contrato_',vc.vehconanio,vc.vehconnumero,'.pdf' ) as rutaPdfContrato"))	
                                ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                ->where('vcf.vecofiid', $request->codigo)->first();

            $rutaContrato = public_path().$vehiculoContrato->rutaPdfContrato;
            $data         =  file_exists($rutaContrato) ? base64_encode(file_get_contents($rutaContrato)) : 'El archivo no existe';
           return response()->json(['success' => true, 'data' => $data]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}