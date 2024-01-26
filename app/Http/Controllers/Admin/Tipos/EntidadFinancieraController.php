<?php

namespace App\Http\Controllers\Admin\Tipos;

use App\Models\Tipos\EntidadFinanciera;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class EntidadFinancieraController extends Controller
{
    public function index()
    {
        $data = DB::table('entidadfinanciera')->select('entfinid','entfinnombre','entfinnumerocuenta','entfinactiva',
                                    DB::raw("if(entfinactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('entfinnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                = $request->codigo;
        $entidadfinanciera = ($id != 000) ? EntidadFinanciera::findOrFail($id) : new EntidadFinanciera();

	    $this->validate(request(),[
	   	        'nombre'       => 'required|string|min:4|max:100', 
                'numeroCuenta' => 'required|string|max:20', 
	            'estado'       => 'required'
	        ]);

        try {
            $entidadfinanciera->entfinnombre       = mb_strtoupper($request->nombre,'UTF-8');
            $entidadfinanciera->entfinnumerocuenta = $request->numeroCuenta;
            $entidadfinanciera->entfinactiva       = $request->estado;
            $entidadfinanciera->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
        try {
            $entidadfinanciera = EntidadFinanciera::findOrFail($request->codigo);
            $entidadfinanciera->delete();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
        }
	}
}