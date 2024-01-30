<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\ColocacionCambioEstado;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Colocacion;
use Illuminate\Http\Request;
use Exception, DB, Auth;
use Carbon\Carbon;

class GestionCobroCarteraController extends Controller
{
    public function index()
    {
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $data            = DB::table('colocacion as c')->select('c.solcreid','c.coloid','lc.lincrenombre',DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"), 
                                'c.colovalordesembolsado', 'c.colofechacolocacion', 'p.persdocumento','v.vehiplaca', 'v.vehinumerointerno',
                                DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia) ) as referenciaVehiculo"),
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"),
                                DB::raw("DATEDIFF(NOW(), c.colofechacolocacion) as diasMora"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                ->join('vehiculo as v', 'v.vehiid', '=', 'sc.vehiid')
                                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                ->whereIn('c.coloid', function($query) use ($fechaActual) {
                                    $query->select('coloid')
                                        ->from('colocacionliquidacion')
                                        ->whereDate('colliqfechavencimiento', '<=', $fechaActual)
                                        ->whereNull('colliqfechapago');
                                })
                                ->orderBy('c.colofechacolocacion')->get();

        return response()->json(["data" => $data]);
    }

    public function showColocacion(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric', 'tipo' => 'required']);

        try {
            $colocacion = DB::table('colocacion as c')
                            ->select('c.coloid','c.colofechahoradesembolso','c.colovalordesembolsado','c.colotasa','c.colonumerocuota','tec.tiesclnombre','c.tiesclid',
                                DB::raw("CONCAT('$ ', FORMAT(c.colovalordesembolsado, 0)) as valorDesembolsado"),
                                DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                            ->join('tipoestadocolocacion as tec', 'tec.tiesclid', '=', 'c.tiesclid')
                            ->join('usuario as u', 'u.usuaid', '=', 'c.usuaid')
                            ->where('c.solcreid', $request->codigo)->first();

            $colocacionLiquidacion   = [];
            $cambiosEstadoColocacion = [];
            $tipoEstadosColocacion   = [];
            if($request->tipo === 'H'){
                $colocacionLiquidacion  = DB::table('colocacionliquidacion as cl')
                                            ->select('cl.colliqnumerocuota as numeroCuota','cl.colliqfechavencimiento as fechaVencimiento',
                                                'cl.colliqnumerocomprobante as numeroComprobante','cl.colliqfechapago as fechaPago',
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorcuota, 0)) as valorCuota"),
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorpagado, 0)) as valorPagado"),
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqsaldocapital, 0)) as saldoCapital"),
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorcapitalpagado, 0)) as capitalPagado"),
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorinterespagado, 0)) as interesPagado"),
                                                DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorinteresmora, 0)) as interesMora"))
                                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                                            ->where('c.solcreid', $request->codigo)->get();

                $cambiosEstadoColocacion =  DB::table('colocacioncambioestado as cce')
                                                ->select('cce.cocaesfechahora as fecha','cce.cocaesobservacion as observacion','tec.tiesclnombre as estado',
                                                    DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                                ->join('tipoestadocolocacion as tec', 'tec.tiesclid', '=', 'cce.tiesclid')
                                                ->join('colocacion as c', 'c.coloid', '=', 'cce.coloid')
                                                ->join('usuario as u', 'u.usuaid', '=', 'cce.cocaesusuaid')
                                                ->where('c.solcreid', $request->codigo)->get();
            }

            if($request->tipo === 'S'){
                $tipoEstadosColocacion =  DB::table('tipoestadocolocacion')->select('tiesclid','tiesclnombre')
                                                ->whereIn('tiesclid', ['V','R','J'])->orderBy('tiesclnombre')->get();
            }

            return response()->json(['success' => true, 'colocacion'            => $colocacion,           'cambiosEstadoColocacion' => $cambiosEstadoColocacion,
                                                        'colocacionLiquidacion' => $colocacionLiquidacion, 'tipoEstadosColocacion'  => $tipoEstadosColocacion]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
        }
    }

    public function salveSeguimiento(Request $request)
    {
        $this->validate(request(),['solicitudId'       => 'required|numeric',
                                   'colocacionId'      => 'required|numeric',
                                   'tipoEstado'        => 'required',
                                   'tipoEstadoOld'     => 'required',
                                   'observacionCambio' => 'required|string|min:20|max:500']);
        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $coloid          = $request->colocacionId;
            if($request->tipoEstadoOld !== $request->tipoEstado){
                $colocacion           = Colocacion::findOrFail($coloid);
                $colocacion->tiesclid = $request->tipoEstado;
                $colocacion->save();
            }

            $colocacioncambioestado 				   = new ColocacionCambioEstado();
            $colocacioncambioestado->coloid            = $coloid;
            $colocacioncambioestado->tiesclid          = $request->tipoEstado;
            $colocacioncambioestado->cocaesusuaid      = Auth::id();
            $colocacioncambioestado->cocaesfechahora   = $fechaHoraActual;
            $colocacioncambioestado->cocaesobservacion = $request->observacionCambio;
            $colocacioncambioestado->save();

            DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}