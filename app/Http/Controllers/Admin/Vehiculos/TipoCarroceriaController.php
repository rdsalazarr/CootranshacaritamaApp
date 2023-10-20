<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\TipoCarroceriaVehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class TipoCarroceriaController extends Controller
{
    public function index()
    {
        $data = DB::table('tipocarroceriavehiculo')->select('ticaveid','ticavenombre','ticaveactivo',
                                    DB::raw("if(ticaveactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('ticavenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                     = $request->codigo;
        $tipocarroceriavehiculo = ($id != 000) ? TipoCarroceriaVehiculo::findOrFail($id) : new TipoCarroceriaVehiculo();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50',
	            'estado' => 'required'
	        ]);

        try {
            $tipocarroceriavehiculo->ticavenombre = mb_strtoupper($request->nombre,'UTF-8');
            $tipocarroceriavehiculo->ticaveactivo = $request->estado;
            $tipocarroceriavehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('ticaveid')->where('ticaveid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tipocarroceriavehiculo = TipoCarroceriaVehiculo::findOrFail($request->codigo);
				$tipocarroceriavehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}