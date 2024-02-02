<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Http\Controllers\Controller;
use App\Models\Caja\CuentaContable;
use Illuminate\Http\Request;
use Exception, DB;

class CuentaContableController extends Controller
{
    public function index()
    {
		try{
			$data = DB::table('cuentacontable')->select('cueconid','cueconnombre','cueconnaturaleza','cueconcodigo','cueconactiva',
										DB::raw("if(cueconnaturaleza = 'C' ,'Crédito', 'Debito') as naturaleza"),
										DB::raw("if(cueconactiva = 1 ,'Sí', 'No') as estado"))
										->orderBy('cueconid')->get();

			return response()->json(['success' => true, "data" => $data]);
		}catch(Exception $e){
			return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
		}
    }

    public function salve(Request $request)
	{
        $id             = $request->codigo;
        $cuentacontable = ($id != 000) ? CuentaContable::findOrFail($id) : new CuentaContable();

	    $this->validate(request(),[
                'nombre'         => 'required|string|min:4|max:200',
                'naturaleza'     => 'required|string|max:1',
                'codigoContable' => 'required|string|max:20',
                'estado'         => 'required'
	        ]);

        try {
            $cuentacontable->cueconnombre     = mb_strtoupper($request->nombre,'UTF-8');
            $cuentacontable->cueconnaturaleza = $request->naturaleza;
            $cuentacontable->cueconcodigo     = $request->codigoContable;
            $cuentacontable->cueconactiva     = $request->estado;
            $cuentacontable->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$movimientoCaja = DB::table('comprobantecontabledetalle')->select('cueconid')->where('cueconid', $request->codigo)->first();
		if($movimientoCaja){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un moviemiento de caja del sistema']);
		}else{
			try {
				$cuentacontable = CuentaContable::findOrFail($request->codigo);
				$cuentacontable->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}