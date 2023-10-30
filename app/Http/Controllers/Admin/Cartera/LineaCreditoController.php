<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\LineaCredito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class LineaCreditoController extends Controller
{
    public function index()
    {
        $data = DB::table('lineacredito')->select('lincreid','lincrenombre','lincreporcentaje','lincreactiva',
                                    DB::raw("if(lincreactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('ticavenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                     = $request->codigo;
        $lineacredito = ($id != 000) ? LineaCredito::findOrFail($id) : new LineaCredito();

	    $this->validate(request(),[
	   	        'nombre'     => 'required|string|min:4|max:100',
	            'porcentaje' => 'required',
                'estado'    => 'required'
	        ]);

        try {
            $lineacredito->lincrenombre     = mb_strtoupper($request->nombre,'UTF-8');
            $lineacredito->lincreporcentaje = $request->porcentaje;
            $lineacredito->lincreactiva     = $request->estado;
            $lineacredito->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$solicitudcredito = DB::table('solicitudcredito')->select('lincreid')->where('lincreid', $request->codigo)->first();
		if($solicitudcredito){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una solicitud de crédito del sistema']);
		}else{
			try {
				$lineacredito = LineaCredito::findOrFail($request->codigo);
				$lineacredito->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}