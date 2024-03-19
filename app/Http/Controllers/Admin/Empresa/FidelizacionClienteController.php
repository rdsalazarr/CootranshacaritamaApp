<?php

namespace App\Http\Controllers\Admin\Empresa;

use App\Models\Empresa\FidelizacionCliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class FidelizacionClienteController extends Controller
{
    public function index()
	{
        try{
            $data = DB::table('fidelizacioncliente')->select('fidcliid','fidclivalorfidelizacion','fidclivalorpunto','fidclipuntosminimoredimir')->first();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salve(Request $request)
	{
		$this->validate(request(),[
                'codigo'       => 'required|numeric',              
                'valorFidelizacion'   => 'required|numeric|between:1,999999',
                'valorpunto'          => 'required|numeric|between:1,999999',
                'puntosMinimoRedimir' => 'required|numeric|between:1,999999',
			]);
 
        DB::beginTransaction();
		try {

			$fidelizacioncliente                            = FidelizacionCliente::findOrFail($request->codigo);
            $fidelizacioncliente->fidclivalorfidelizacion   = $request->valorFidelizacion;
			$fidelizacioncliente->fidclivalorpunto          = $request->valorpunto;
            $fidelizacioncliente->fidclipuntosminimoredimir = $request->puntosMinimoRedimir;
			$fidelizacioncliente->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}