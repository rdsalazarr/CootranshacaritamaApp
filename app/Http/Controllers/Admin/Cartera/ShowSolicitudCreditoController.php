<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB, URL;

class ShowSolicitudCreditoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric']);

        try {
            $url      = URL::to('/');
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