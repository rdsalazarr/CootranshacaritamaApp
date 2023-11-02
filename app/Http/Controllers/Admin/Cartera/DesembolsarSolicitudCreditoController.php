<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\SolicitudCreditoDesembolso;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB, URL;
use Carbon\Carbon;

class DesembolsarSolicitudCreditoController extends Controller
{
    public function index()
    {
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();

        return response()->json(["tipoIdentificaciones" => $tipoIdentificaciones]);
    }

    public function consultar(Request $request)
    {
        $this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15'
                                ]);

        $url              = URL::to('/');
        $solicitudCredito = DB::table('solicitudcredito as sc')->select('a.asocid','p.persid','sc.solcreid','sc.solcrefechasolicitud','sc.solcredescripcion',
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
                                    ->where('p.tipideid', $request->tipoIdentificacion)
                                    ->where('p.persdocumento', $request->documento)
                                    ->where('sc.tiesscid', 'A')->first();

        $array = ($solicitudCredito !== null) ? ['success' => true, "solicitudCredito" => $solicitudCredito ] :
                                                ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda ingresados'];

        return response()->json($array);
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['solicitudId'      => 'required|numeric', 
                                    'observacion'     => 'required|string|min:10|max:500']);

        DB::beginTransaction();
        $fechaHoraActual  = Carbon::now();
        $estadoSolicitud  = 'A';
        try {
            $solcreid                   = $request->solicitudId;
            $solicitudcredito           = SolicitudCredito::findOrFail($solcreid);
            $solicitudcredito->tiesscid = $estadoSolicitud;
            $solicitudcredito->save();

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $estadoSolicitud;
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

}
