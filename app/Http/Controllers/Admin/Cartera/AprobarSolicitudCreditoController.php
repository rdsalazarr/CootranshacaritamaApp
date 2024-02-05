<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use App\Util\notificar;
use Carbon\Carbon;

class AprobarSolicitudCreditoController extends Controller
{
    public function index()
    {
        try{
            $data = DB::table('solicitudcredito as sc')
                            ->select('sc.solcreid', 'p.persid', 'sc.solcrefechasolicitud','sc.solcredescripcion','sc.solcrenumerocuota',
                                DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),'lc.lincrenombre as lineaCredito',
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                            ->join('persona as p', 'p.persid', '=', 'sc.persid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->where('sc.tiesscid', 'R')
                            ->orderBy('sc.solcreid')->get();

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
        }
    }

    public function estados()
    {
        try{
            $tipoEstadosSolicitudCredito = DB::table('tipoestadosolicitudcredito')->select('tiesscid','tiesscnombre')->whereIn('tiesscid', ['A', 'N'])->get();

            return response()->json(['success' => true, "tipoEstadosSolicitudCredito" => $tipoEstadosSolicitudCredito]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
        }
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['codigo'           => 'required|numeric',
                                    'estadoSolicitud' => 'required|string',
                                    'observacion'     => 'required|string|min:10|max:500']);

        DB::beginTransaction();
        try {

            $notificar                  = new notificar();
            $fechaHoraActual            = Carbon::now();
            $solcreid                   = $request->codigo;
            $idNotificacionCorreo       = ($request->estadoSolicitud === 'A') ? "notificarAprobacionSolicitudCredito" : "notificarNegacionSolicitudCredito";

            $solicitudcredito           = DB::table('solicitudcredito as sc')
                                            ->select('sc.solcrenumerocuota','sc.solcretasa','sc.solcrefechasolicitud','p.perscorreoelectronico',
                                                DB::raw("CONCAT(FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),
                                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),'u.usuaemail',
                                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                                            ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                            ->join('usuario as u', 'u.usuaid', '=', 'sc.usuaid')
                                            ->where('sc.solcreid', $solcreid )->first();

            $nombreSolicitante          = $solicitudcredito->nombrePersona;
            $valorCredito               = $solicitudcredito->valorSolicitado;
            $montoAprobado              = $solicitudcredito->valorSolicitado;
            $nombreGerente              = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;
            $tasaInteres                = $solicitudcredito->solcretasa;
            $plazoCredito               = $solicitudcredito->solcrenumerocuota;
            $correoPersona              = $solicitudcredito->perscorreoelectronico;
            $fechaSolicitud             = $solicitudcredito->solcrefechasolicitud;
            $correoUsuarioCartera       = $solicitudcredito->usuaemail;
            $nombreUsuarioCartera       = $solicitudcredito->nombreUsuario;
            $correoPersonaSesion        = auth()->user()->usuaemail;
            $decisionTomada             = ($request->estadoSolicitud === 'A') ? 'Aprobada' : 'Rechazada';
            $mensajeNotificar           = '';

            $solicitudCredito           = SolicitudCredito::findOrFail($solcreid);
            $solicitudCredito->tiesscid = $request->estadoSolicitud;
            $solicitudCredito->save();

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $request->estadoSolicitud;
            $solicitudcreditocambioestado->socrceusuaid      = Auth::id();
            $solicitudcreditocambioestado->socrcefechahora   = $fechaHoraActual;
            $solicitudcreditocambioestado->socrceobservacion = $request->observacion;
            $solicitudcreditocambioestado->save();

            if ($correoPersona !== ''){
                $informacionCorreo    = DB::table('informacionnotificacioncorreo')->where('innoconombre', $idNotificacionCorreo )->orderBy('innocoid')->first();        
                $buscar               = Array('nombreSolicitante', 'valorCredito', 'montoAprobado','nombreGerente', 'tasaInteres', 'plazoCredito', 'observacionesGenerales');
                $remplazo             = Array($nombreSolicitante, $valorCredito, $montoAprobado, $nombreGerente, $tasaInteres, $plazoCredito, $request->observacion); 
                $innocoasunto         = $informacionCorreo->innocoasunto;
                $innococontenido      = $informacionCorreo->innococontenido;
                $enviarcopia          = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina      = $informacionCorreo->innocoenviarpiepagina;
                $asunto               = str_replace($buscar, $remplazo, $innocoasunto);
                $msg                  = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar     = ', se ha enviado notificación a '.$notificar->correo([$correoPersona], $asunto, $msg, [], $correoPersonaSesion, $enviarcopia, $enviarpiepagina);
            }

            //Notifico al encargado de cartera
            $informacionCorreo    = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarCarteraSolicitudCredito' )->orderBy('innocoid')->first();
            $buscar               = Array('valorCredito', 'nombreUsuario', 'nombreSolicitante','nombreGerente', 'fechaSolicitud', 'decisionTomada');
            $remplazo             = Array($valorCredito, $nombreUsuarioCartera, $nombreSolicitante, $nombreGerente, $fechaSolicitud, $decisionTomada);
            $innocoasunto         = $informacionCorreo->innocoasunto;
            $innococontenido      = $informacionCorreo->innococontenido;
            $enviarcopia          = $informacionCorreo->innocoenviarcopia;
            $enviarpiepagina      = $informacionCorreo->innocoenviarpiepagina;
            $asunto               = str_replace($buscar, $remplazo, $innocoasunto);
            $msg                  = str_replace($buscar, $remplazo, $innococontenido);
                                    $notificar->correo([$correoUsuarioCartera], $asunto, $msg, [], $correoPersonaSesion, $enviarcopia, $enviarpiepagina);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito'.$mensajeNotificar ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric']);

        try {
            $url              = URL::to('/');
            $solicitudcredito = DB::table('solicitudcredito as sc')->select('sc.solcrefechasolicitud','sc.solcredescripcion',
                                    'sc.solcrenumerocuota','sc.solcreobservacion','p.persdocumento', 'p.persprimernombre',
                                    'p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','a.asocfechaingreso','lc.lincrenombre as lineaCredito','tesc.tiesscnombre as estadoActual',
                                    DB::raw("CONCAT(sc.solcretasa,' %') as tasaNominal"),
                                    DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"))
                                    ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                    ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'sc.tiesscid')
                                    ->where('sc.solcreid', $request->codigo)->first();

        $cambiosEstadoSolicitudCredito =  DB::table('solicitudcreditocambioestado as cesc')
                                            ->select('cesc.socrcefechahora as fecha','cesc.socrceobservacion as observacion','tesc.tiesscnombre as estado',
                                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                            ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'cesc.tiesscid')
                                            ->join('usuario as u', 'u.usuaid', '=', 'cesc.socrceusuaid')
                                            ->where('cesc.solcreid', $request->codigo)->get();
        
			return response()->json(['success' => true, 'solicitudcredito' => $solicitudcredito, 'cambiosEstadoSolicitudCredito' => $cambiosEstadoSolicitudCredito]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
		}
    }
}