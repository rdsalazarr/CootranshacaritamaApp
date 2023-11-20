<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\TipoModalidadVehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class TipoModalidadController extends Controller
{
    public function index()
    {
        $data = DB::table('tipomodalidadvehiculo')->select('timoveid', 'timovenombre','timovecuotasostenimiento','timovedescuentopagoanticipado',
                                    'timoverecargomora','timovetienedespacho',
                                    DB::raw("CONCAT('$ ', FORMAT(timovecuotasostenimiento, 0)) as cuotaSostenimiento"),
                                    DB::raw("CONCAT(timovedescuentopagoanticipado, '%') as descuentoPagoAnticipado"),
                                    DB::raw("CONCAT(timoverecargomora, '%') as moraRecargo"),
                                    DB::raw("if(timovetienedespacho = 1 ,'Sí', 'No') as tieneDespacho"))
                                    ->orderBy('timovenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                    = $request->codigo;
        $tipomodalidadvehiculo = ($id != 000) ? TipoModalidadVehiculo::findOrFail($id) : new TipoModalidadVehiculo();

	    $this->validate(request(),[
	   	        'nombre'                  => 'required|string|min:4|max:30',
                'cuotaSostenimiento'      => 'required|numeric|between:1,999999999',
                'descuentoPagoAnticipado' => 'required|numeric|between:1,99',
                'recargoMora'             => 'required|numeric|between:1,99',
	            'tieneDespacho'           => 'required'
	        ]);

        try {
            $tipomodalidadvehiculo->timovenombre                  = mb_strtoupper($request->nombre,'UTF-8');
            $tipomodalidadvehiculo->timovecuotasostenimiento      = $request->cuotaSostenimiento;
            $tipomodalidadvehiculo->timovedescuentopagoanticipado = $request->descuentoPagoAnticipado;
            $tipomodalidadvehiculo->timoverecargomora             = $request->recargoMora;
            $tipomodalidadvehiculo->timovetienedespacho           = $request->tieneDespacho;
            $tipomodalidadvehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('timoveid')->where('timoveid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tipomodalidadvehiculo = TipoModalidadVehiculo::findOrFail($request->codigo);
				$tipomodalidadvehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}