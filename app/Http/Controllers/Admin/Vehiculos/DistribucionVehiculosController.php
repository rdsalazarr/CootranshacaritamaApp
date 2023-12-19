<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\TipoVehiculoDistribucion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class DistribucionVehiculosController extends Controller
{
    public function index()
    {
        $tipoVehiculos = DB::table('tipovehiculo')->select('tipvehid','tipvehnombre','tipvehreferencia','tipvecapacidad','tipvenumerofilas','tipvenumerocolumnas')
                                    ->where('tipvehactivo', true)
                                    ->whereNotIn('tipvehid', [32])                                  
                                    ->orderBy('tipvehnombre')->get();
        return response()->json(["tipoVehiculos" => $tipoVehiculos]);
    }


    public function salve(Request $request)
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