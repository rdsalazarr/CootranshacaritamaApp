<?php

namespace App\Http\Controllers\Admin\Asociado;

use App\Models\Asociado\AsociadoCambioEstado;
use App\Models\Asociado\AsociadoSancion;
use App\Http\Controllers\Controller;
use App\Models\Asociado\Asociado;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use Carbon\Carbon;

class SancionarController extends Controller
{
    public function index()
    {
        $tipoSanciones = DB::table('tiposancion')->select('tipsanid','tipsannombre')->where('tipsanactivo', true )->orderBy('tipsannombre')->get();
        $fechaActual   = Carbon::now()->format('Y-m-d');

        return response()->json(["tipoSanciones" => $tipoSanciones, "fechaActual" => $fechaActual]);
    }

    public function salve(Request $request)
	{
		$this->validate(request(),[ 'tipoSancion'          => 'required|numeric',
                                    'valorSancion'         => 'required|numeric|between:1,9999999',
                                    'fechaMaximaPago'      => 'required|date|date_format:Y-m-d',
                                    'numeroInternoInicial' => 'required|numeric', 
                                    'numeroInternoFinal'   => 'required|numeric',
                                    'motivo'               => 'required|string|min:20|max:500'
                                ]);

		DB::beginTransaction();
        $estado          = 'S';
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
		try {

            $vehiculos = DB::table('vehiculo')
                            ->select('vehiid','asocid')
                            ->where('vehinumerointerno', '>=', $request->numeroInternoInicial)
                            ->where('vehinumerointerno', '<=', $request->numeroInternoFinal)->get();

            foreach( $vehiculos as  $vehiculo){
                $asociadoId                             = $vehiculo->asocid;
                $asociadosancion 					    = new AsociadoSancion();
                $asociadosancion->asocid                = $vehiculo->asocid;
                $asociadosancion->usuaid                = Auth::id();
                $asociadosancion->tipsanid              = $request->tipoSancion;
                $asociadosancion->asosanfechahora       = $fechaHoraActual;
                $asociadosancion->asosanfechamaximapago = $request->fechaMaximaPago;
                $asociadosancion->asosanmotivo          = mb_strtoupper($request->motivo,'UTF-8');
                $asociadosancion->asosanvalorsancion    = $request->valorSancion;
                $asociadosancion->save();

                $asociado                               = Asociado::findOrFail($asociadoId);
                $asociado->tiesasid                     = $estado;
                $asociado->asocfecharetiro              = $fechaActual;
                $asociado->save();

                $asociadocambioestado 					 = new AsociadoCambioEstado();
                $asociadocambioestado->asocid            = $asociadoId;
                $asociadocambioestado->tiesasid          = $estado;
                $asociadocambioestado->ascaesusuaid      = Auth::id();
                $asociadocambioestado->ascaesfechahora   = $fechaHoraActual;
                $asociadocambioestado->ascaesobservacion = mb_strtoupper($request->motivo,'UTF-8');
                $asociadocambioestado->save();
            }

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	} 
}