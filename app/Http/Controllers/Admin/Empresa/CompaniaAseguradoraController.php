<?php

namespace App\Http\Controllers\Admin\Empresa;

use App\Models\Empresa\CompaniaAseguradora;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class CompaniaAseguradoraController extends Controller
{
    public function index()
	{
        try{
            $data = DB::table('companiaaseguradora')->select('comaseid','comasenombre','comasenumeropoliza')->first();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salve(Request $request)
	{
		$this->validate(request(),[
                'codigo'       => 'required|numeric',
                'nombre'       => 'required|string|min:4|max:99',
                'numeroPoliza' => 'required|string|min:4|max:30'
			]);
 
        DB::beginTransaction();
		try {

			$companiaaseguradora                     = CompaniaAseguradora::findOrFail($request->codigo);
            $companiaaseguradora->comasenombre       = $request->nombre;
			$companiaaseguradora->comasenumeropoliza = $request->numeroPoliza;
			$companiaaseguradora->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}