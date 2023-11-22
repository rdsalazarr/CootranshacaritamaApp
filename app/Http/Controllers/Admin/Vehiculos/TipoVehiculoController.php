<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\TipoVehiculoDistribucion;
use App\Models\Vehiculos\TipoVehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class TipoVehiculoController extends Controller
{
    public function index()
    {
        $data = DB::table('tipovehiculo')->select('tipvehid','tipvehnombre','tipvehreferencia','tipvecapacidad','tipvenumerofilas','tipvenumerocolumnas','tipvehactivo',
                                    DB::raw("if(tipvehactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tipvehnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id           = $request->codigo;
        $tipovehiculo = ($id != 000) ? TipoVehiculo::findOrFail($id) : new TipoVehiculo();

	    $this->validate(request(),[
	   	        'nombre'            => 'required|string|min:4|max:50', 
                'referencia'        => 'nullable|string|max:30', 
                'capacidadPasajero' => 'required|numeric|min:1|max:9999',
                'numeroFilas'       => 'required|numeric|min:1|max:9999',
                'numeroColumnas'    => 'required|numeric|min:1|max:9999',
	            'estado'            => 'required'
	        ]);

        try {
            $tipovehiculo->tipvehnombre        = mb_strtoupper($request->nombre,'UTF-8');
            $tipovehiculo->tipvehreferencia    = mb_strtoupper($request->referencia,'UTF-8');
            $tipovehiculo->tipvecapacidad      = $request->capacidadPasajero;
            $tipovehiculo->tipvenumerofilas    = $request->numeroFilas;
            $tipovehiculo->tipvenumerocolumnas = $request->numeroColumnas;
            $tipovehiculo->tipvehactivo        = $request->estado;
            $tipovehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('tipvehid')->where('tipvehid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tipovehiculo = TipoVehiculo::findOrFail($request->codigo);
				$tipovehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}

	public function distribucion(Request $request)
	{
		dd($request->tpVehiculo);

	    $this->validate(request(),[
	   	        'tpVehiculo'  => 'required|string', 
				'ubicaciones' => 'required|array|min:1',
	        ]);

		DB::beginTransaction();
        try {
			//TipoVehiculoDistribucion::findOrFail($tivediid);
			foreach($request->ubicaciones as $ubicacion){
				$tipovehiculodistribucion               = new TipoVehiculoDistribucion();
				$tipovehiculodistribucion->tipvehid     = $request->tpVehiculo;
				$tipovehiculodistribucion->tivedinumero = $ubicacion['numero']; 
				$tipovehiculodistribucion->save();
			}
            
			DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}