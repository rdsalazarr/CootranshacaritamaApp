<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AprobarSolicitudCreditoController extends Controller
{
    public function index()
    {
        $data = DB::table('solicitudcredito as sc')
                        ->select('sc.solcreid', 'p.persid', 'sc.solcrefechasolicitud','sc.solcredescripcion','sc.solcrenumerocuota',
                        DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),'lc.lincrenombre as lineaCredito',
                        DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                        ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                        ->where('sc.tiesscid', 'R')
                        ->orderBy('sc.solcreid')->get();

        return response()->json(["data" => $data]);
    }

    public function estados()
    {
        $tipoEstadosSolicitudCredito = DB::table('tipoestadosolicitudcredito')->select('tiesscid','tiesscnombre')->whereIn('tiesscid', ['A', 'N'])->get();
        return response()->json(["tipoEstadosSolicitudCredito" => $tipoEstadosSolicitudCredito]);
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['codigo'           => 'required|numeric', 
                                    'estadoSolicitud' => 'required|string',
                                    'observacion'     => 'required|string|min:10|max:500']);

        DB::beginTransaction();
        $fechaHoraActual  = Carbon::now();
        try {
            $solcreid                   = $request->codigo;
            $solicitudcredito           = SolicitudCredito::findOrFail($solcreid);
            $solicitudcredito->tiesscid = $request->estadoSolicitud;
            $solicitudcredito->save();

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $request->estadoSolicitud;
            $solicitudcreditocambioestado->socrceusuaid      = Auth::id();
            $solicitudcreditocambioestado->socrcefechahora   = $fechaHoraActual;
            $solicitudcreditocambioestado->socrceobservacion = $request->observacion;
            $solicitudcreditocambioestado->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
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
                                    ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
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