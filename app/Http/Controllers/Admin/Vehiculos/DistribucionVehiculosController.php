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

    public function datos(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);

        $tipoVehiculoDistribuciones = DB::table('tipovehiculodistribucion')->select('tivediid','tipvehid','tivedinumero')
                                    ->where('tipvehid', $request->codigo)->orderBy('tivediid')->get();

        return response()->json(["tipoVehiculoDistribuciones" => $tipoVehiculoDistribuciones]);
    }

    public function salve(Request $request)
	{
	    $this->validate(request(),[
	   	        'tipoVehiculo'  => 'required|string', 
				'puestosVehiculo' => 'required|array|min:1',
	        ]);
 
		DB::beginTransaction();
        try {	
			foreach($request->puestosVehiculo as $ubicacion){
				$idPuesto                               = $ubicacion['idPuesto'];
				$tipovehiculodistribucion               = ($idPuesto !== '0') ? TipoVehiculoDistribucion::findOrFail($idPuesto) : new TipoVehiculoDistribucion();
				$tipovehiculodistribucion->tipvehid     = $request->tipoVehiculo;
				$tipovehiculodistribucion->tivedinumero = $ubicacion['contenido']; 
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