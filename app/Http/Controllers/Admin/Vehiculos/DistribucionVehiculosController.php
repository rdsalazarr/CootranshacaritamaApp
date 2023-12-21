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
        $tipoVehiculos = DB::table('tipovehiculo')->select('tipvehid','tipvehnombre','tipvehreferencia','tipvecapacidad','tipvenumerofilas','tipvenumerocolumnas','tipveclasecss'
                                    
                                    )
                                    ->where('tipvehactivo', true)
                                    ->whereNotIn('tipvehid', [32])
                                    ->where('tipvecapacidad', '<=', 8)
                                    ->orderBy('tipvehnombre')->get();
        return response()->json(["tipoVehiculos" => $tipoVehiculos]);
    }

    public function datos(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);

        $tipoVehiculoDistribuciones = DB::table('tipovehiculodistribucion')->select('tivediid','tipvehid','tivedinumero','tivedicontenido')
                                    ->where('tipvehid', $request->codigo)
                                    ->orderBy('tipvehid')->orderBy('tivedinumero')->get();

        return response()->json(["tipoVehiculoDistribuciones" => $tipoVehiculoDistribuciones]);
    }

    /*public function salve(Request $request)
	{
	    $this->validate(request(),[
	   	        'tipoVehiculo'  => 'required|string', 
				'puestosVehiculo' => 'required|array|min:1',
	        ]);
 
		DB::beginTransaction();
        try {	
			foreach($request->puestosVehiculo as $ubicacion){
				$puestoId                                  = $ubicacion['puestoId'];
				$tipovehiculodistribucion                  = ($puestoId !== '0') ? TipoVehiculoDistribucion::findOrFail($puestoId) : new TipoVehiculoDistribucion();
				$tipovehiculodistribucion->tipvehid        = $request->tipoVehiculo;
				$tipovehiculodistribucion->tivedinumero    = $ubicacion['puestoNumero'];
                $tipovehiculodistribucion->tivedicontenido = $ubicacion['contenido']; 
				$tipovehiculodistribucion->save();
			}
			DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}*/

    public function salve(Request $request)
	{
	    $this->validate(request(),[
	   	        'tipoVehiculo'  => 'required|string', 
				'puestosVehiculo' => 'required|array|min:1',
	        ]);
 
       // dd($request->puestosVehiculo);

		DB::beginTransaction();
        try {	
			foreach($request->puestosVehiculo as $ubicacion){
				$idPuesto                               = $ubicacion['puestoId'];
				$tipovehiculodistribucion               = ($idPuesto !== '0') ? TipoVehiculoDistribucion::findOrFail($idPuesto) : new TipoVehiculoDistribucion();

                /*if($idPuesto !== '0'){
                    $tipovehiculodistribucionBD = DB::table('tipovehiculodistribucion')->select('tivediid')
                                                ->where('tipvehid', $request->tipoVehiculo)
                                                ->where('tivedinumero', $ubicacion['puestoId'])->first();
                    $tipovehiculodistribucion   = TipoVehiculoDistribucion::findOrFail($tipovehiculodistribucionBD->tivediid);
                }else{
                    $tipovehiculodistribucion   =  new TipoVehiculoDistribucion();
                }*/
                
				$tipovehiculodistribucion->tipvehid        = $request->tipoVehiculo;
				$tipovehiculodistribucion->tivedinumero    = $ubicacion['puestoNumero']; 
                $tipovehiculodistribucion->tivedicontenido = $ubicacion['contenido']; 
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