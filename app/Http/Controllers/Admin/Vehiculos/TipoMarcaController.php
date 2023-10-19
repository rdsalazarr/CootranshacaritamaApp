<?php

namespace App\Http\Controllers\Admin\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\TipoMarcaVehiculo;
use Illuminate\Http\Request;
use Exception, DB;

class TipoMarcaController extends Controller
{
    public function index()
    {
        $data = DB::table('tipomarcavehiculo')->select('timaveid','timavenombre','timaveactiva',
                                    DB::raw("if(timaveactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('timavenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                = $request->codigo;
        $tipomarcavehiculo = ($id != 000) ? TipoMarcaVehiculo::findOrFail($id) : new TipoMarcaVehiculo();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50',
	            'estado' => 'required'
	        ]);

        try {
            $tipomarcavehiculo->timavenombre = mb_strtoupper($request->nombre,'UTF-8');
            $tipomarcavehiculo->timaveactiva = $request->estado;
            $tipomarcavehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('timaveid')->where('timaveid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tipomarcavehiculo = TipoMarcaVehiculo::findOrFail($request->codigo);
				$tipomarcavehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}