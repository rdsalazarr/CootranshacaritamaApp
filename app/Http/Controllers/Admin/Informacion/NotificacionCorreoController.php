<?php

namespace App\Http\Controllers\Admin\Informacion;

use App\Models\Informacion\NotificacionCorreo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class NotificacionCorreoController extends Controller
{
	public function index()
	{
        $data = DB::table('informacionnotificacioncorreo')
                    ->select('innocoid','innoconombre','innocoasunto','innococontenido','innocoenviarpiepagina','innocoenviarcopia',
                    DB::raw("if(innocoenviarpiepagina = 1 ,'Sí', 'No') as enviarPiePagina"),
                    DB::raw("if(innocoenviarcopia = 1 ,'Sí', 'No') as enviarCopia"))->get();

        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{ 
        $id                 = $request->codigo;
        $notificacioncorreo = ($id != 000) ? NotificacionCorreo::findOrFail($id) : new NotificacionCorreo();

		$this->validate(request(),[
                'nombre'     => 'required|string|min:6|max:50|unique:informacionnotificacioncorreo,innocoid,'.$notificacioncorreo->innocoid.',innocoid',
                'asunto'     => 'required|string|min:4|max:120',
                'contenido'  => 'required|string',
                'piePagina'  => 'required',
                'copia'      => 'required'
			]);

		try {
            $notificacioncorreo->innoconombre          = $request->nombre;
            $notificacioncorreo->innocoasunto          = $request->asunto;
            $notificacioncorreo->innococontenido       = $request->contenido;
            $notificacioncorreo->innocoenviarpiepagina = $request->piePagina;
            $notificacioncorreo->innocoenviarcopia     = $request->copia;
            $notificacioncorreo->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{	
		try {
			$notificacioncorreo = NotificacionCorreo::findOrFail($request->codigo);
			$notificacioncorreo->delete();
			return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
		}
	} 
}