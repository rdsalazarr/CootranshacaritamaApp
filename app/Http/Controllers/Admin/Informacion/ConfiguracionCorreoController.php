<?php

namespace App\Http\Controllers\Admin\Informacion;

use App\Models\Informacion\ConfiguracionCorreo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class ConfiguracionCorreoController extends Controller
{
	public function index()
	{
        $data = DB::table('informacionconfiguracioncorreo')
                    ->select('incocoid','incocohost','incocousuario','incococlave','incococlaveapi','incocopuerto')->get();

        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{ 
		$this->validate(request(),[
                'host'     => 'required|string|min:4|max:50',
                'usuario'  => 'required|string|min:4|max:80',
                'clave'    => 'required|string|min:6|max:20',
                'claveApi' => 'required|string|min:6|max:20',
                'puerto'   => 'required|string|min:1|max:4',
			]);

		try {
            $informacionconfiguracioncorreo                 =  InformacionConfiguracionCorreo::findOrFail($request->codigo);
            $informacionconfiguracioncorreo->incocohost     = $request->host;
            $informacionconfiguracioncorreo->incocousuario  = $request->usuario;
            $informacionconfiguracioncorreo->incococlave    = $request->clave;
            $informacionconfiguracioncorreo->incococlaveapi = $request->claveApi;
            $informacionconfiguracioncorreo->incocopuerto   = $request->puerto;
            $informacionconfiguracioncorreo->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{	
		try {
			$informacionconfiguracioncorreo = InformacionConfiguracionCorreo::findOrFail($request->codigo);
			$informacionconfiguracioncorreo->delete();
			return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
		}
	} 
}