<?php

namespace App\Http\Controllers\Admin\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\TipoReferenciaVehiculo;
use Illuminate\Http\Request;
use Exception, DB;

class TipoReferenciaController extends Controller
{
    public function index()
    {
        $data = DB::table('tiporeferenciavehiculo')->select('tireveid','tirevenombre','tireveactivo',
                                    DB::raw("if(tireveactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tirevenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                     = $request->codigo;
        $tiporeferenciavehiculo = ($id != 000) ? TipoReferenciaVehiculo::findOrFail($id) : new TipoReferenciaVehiculo();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50',
	            'estado' => 'required'
	        ]);

        try {
            $tiporeferenciavehiculo->tirevenombre = mb_strtoupper($request->nombre,'UTF-8');
            $tiporeferenciavehiculo->tireveactivo = $request->estado;
            $tiporeferenciavehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('tireveid')->where('tireveid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tiporeferenciavehiculo = TipoReferenciaVehiculo::findOrFail($request->codigo);
				$tiporeferenciavehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}