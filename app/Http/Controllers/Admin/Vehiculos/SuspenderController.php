<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Vehiculos\VehiculoSuspendido;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generales;
use Carbon\Carbon;

class SuspenderController extends Controller
{
    public function index()
    {
        $data = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                                    ->where('v.tiesveid', 'A')
                                                    ->orderBy('v.vehinumerointerno')->get();

        $fechaActual   = Carbon::now()->format('Y-m-d');

        return response()->json(["data" => $data, "fechaActual" => $fechaActual]);
    }

    public function salve(Request $request)
	{
		$this->validate(request(),[ 'vehiculoId'             => 'required|numeric',
                                    'fechaInicialSuspencion' => 'required|date|date_format:Y-m-d',
                                    'fechaFinalSuspencion'   => 'required|date|date_format:Y-m-d',
                                    'motivo'                 => 'required|string|min:20|max:500'
                                ]);

		DB::beginTransaction();
        $estado          = 'S';
        $fechaHoraActual = Carbon::now();
		try {

            $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
            $vehiculo->tiesveid = $estado;
            $vehiculo->save();

            $vehiculocambioestado 					 = new VehiculoCambioEstado();
            $vehiculocambioestado->vehiid            = $request->vehiculoId;
            $vehiculocambioestado->tiesveid          = $estado;
            $vehiculocambioestado->vecaesusuaid      = Auth::id();
            $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
            $vehiculocambioestado->vecaesobservacion = mb_strtoupper($request->motivo,'UTF-8');
            $vehiculocambioestado->save();

            $vehiculosuspendido 					         = new VehiculoSuspendido();
            $vehiculosuspendido->vehiid                       = $request->vehiculoId;
            $vehiculosuspendido->usuaid                       = Auth::id();
            $vehiculosuspendido->vehsusfechahora              = $fechaHoraActual;
            $vehiculosuspendido->vehsusfechainicialsuspencion = $request->fechaInicialSuspencion;
            $vehiculosuspendido->vehsusfechafinalsuspencion   = $request->fechaFinalSuspencion;
            $vehiculosuspendido->vehsusmotivo                 = mb_strtoupper($request->motivo,'UTF-8');
            $vehiculosuspendido->save();

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}