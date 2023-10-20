<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\TipoColorVehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class TipoColorController extends Controller
{
    public function index()
    {
        $data = DB::table('tipocolorvehiculo')->select('ticoveid','ticovenombre','ticoveactivo',
                                    DB::raw("if(ticoveactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('ticovenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                = $request->codigo;
        $tipocolorvehiculo = ($id != 000) ? TipoColorVehiculo::findOrFail($id) : new TipoColorVehiculo();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50',
	            'estado' => 'required'
	        ]);

        try {
            $tipocolorvehiculo->ticovenombre = mb_strtoupper($request->nombre,'UTF-8');
            $tipocolorvehiculo->ticoveactivo = $request->estado;
            $tipocolorvehiculo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('ticoveid')->where('ticoveid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$tipocolorvehiculo = TipoColorVehiculo::findOrFail($request->codigo);
				$tipocolorvehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}