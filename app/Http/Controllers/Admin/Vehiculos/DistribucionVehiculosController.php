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
        $tipoVehiculos = DB::table('tipovehiculo')->select('tipvehid','tipvehnombre','tipvehreferencia','tipvecapacidad',
										'tipvenumerofilas','tipvenumerocolumnas','tipveclasecss',
										DB::raw("CONCAT(tipvehnombre,' ', if(tipvehreferencia is null ,'', tipvehreferencia) ) as nombreVehiculo"),	
										DB::raw("CONCAT('Filas (',tipvenumerofilas, ') Columnas (',tipvenumerocolumnas, ') Puestos (', tipvecapacidad,') ') as filasColumnaPuesto"))
                                    ->where('tipvehactivo', true)
                                    ->whereNotIn('tipvehid', [32])
                                    ->orderBy('tipvehnombre')->get();

        return response()->json(["tipoVehiculos" => $tipoVehiculos]);
    }

    public function datos(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);

		$tipoVehiculoDistribuciones = DB::table('tipovehiculodistribucion as tvd')
										->select('tvd.tivediid', 'tvd.tipvehid', 'tvd.tivedicolumna', 'tvd.tivedifila', 'tvd.tivedipuesto',
											DB::raw('(SELECT count(DISTINCT(tvd1.tivedifila)) FROM tipovehiculodistribucion as tvd1 WHERE tvd1.tipvehid = tvd.tipvehid) AS totalFilas'))
										->where('tvd.tipvehid', $request->codigo)
										->orderBy('tvd.tivediid')
										->get();

        return response()->json(["tipoVehiculoDistribuciones" => $tipoVehiculoDistribuciones]);
    }

    public function salve(Request $request)
	{
	    $this->validate(request(),[
	   	        'tipoVehiculo'    => 'required|string', 
				'puestosVehiculo' => 'required|array|min:1',
	        ]);

		DB::beginTransaction();
        try {

			if($request->existenDatos === 'S'){
				$tipoVehiculoDistribuciones = DB::table('tipovehiculodistribucion')->select('tivediid')->where('tipvehid', $request->tipoVehiculo)->orderBy('tivediid')->get();
				$ubicaciones                = $request->puestosVehiculo;
				foreach ($tipoVehiculoDistribuciones as $index => $tipoVehiculoDistribucion) {
					$tipovehiculodistribucion                = TipoVehiculoDistribucion::findOrFail($tipoVehiculoDistribucion->tivediid);
					$ubicacion                               = $ubicaciones[$index];
					$tipovehiculodistribucion->tipvehid      = $request->tipoVehiculo;
					$tipovehiculodistribucion->tivedicolumna = $ubicacion['columna'];
					$tipovehiculodistribucion->tivedifila    = $ubicacion['fila'];
					$tipovehiculodistribucion->tivedipuesto  = $ubicacion['puesto'];
					$tipovehiculodistribucion->save();
				}
			}else{
				foreach($request->puestosVehiculo as $ubicacion){
					$tipovehiculodistribucion                = new TipoVehiculoDistribucion();
					$tipovehiculodistribucion->tipvehid      = $request->tipoVehiculo;
					$tipovehiculodistribucion->tivedicolumna = $ubicacion['columna'];
					$tipovehiculodistribucion->tivedifila    = $ubicacion['fila'];
					$tipovehiculodistribucion->tivedipuesto  = $ubicacion['puesto']; 
					$tipovehiculodistribucion->save();
				}
			}
			DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}