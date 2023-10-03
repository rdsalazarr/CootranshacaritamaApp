<?php

namespace App\Http\Controllers\Admin\Notificar;
use App\Models\InformacionNotificacionCorreo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class InformacionCorreoController extends Controller
{
	public function index()
	{
        $data = DB::table('informacionnotificacioncorreo')
                    ->select('innocoid','innoconombre','innocoasunto','innococontenido','innocoenviarpiepagina','innocoenviarcopia',
                    DB::raw("if(innocoenviarcopia = 1 ,'Sí', 'No') as enviarPiePagina"),
                    DB::raw("if(innocoenviarcopia = 1 ,'Sí', 'No') as enviarCopia"))->get();

        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{ 
        $id      = $request->codigo;
        $infocorreonotificacion = ($id != 000) ? InformacionNotificacionCorreo::findOrFail($id) : new InformacionNotificacionCorreo();

		$this->validate(request(),[
                'nombre'     => 'required|string|min:6|max:50|unique:informacionnotificacioncorreo,innocoid,'.$infocorreonotificacion->innocoid.',innocoid',
                'asunto'     => 'required|string|min:4|max:100',
                'contenido'  => 'required|string',
                'piePagina'  => 'required',
                'copia'      => 'required'
			]);

		try {
            $infocorreonotificacion->innoconombre          = $request->nombre;
            $infocorreonotificacion->innocoasunto          = $request->asunto;
            $infocorreonotificacion->innococontenido       = $request->contenido;
            $infocorreonotificacion->innocoenviarpiepagina = $request->piePagina;
            $infocorreonotificacion->innocoenviarcopia     = $request->copia;
            $infocorreonotificacion->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{	
		try {
			$infocorreonotificacion = InformacionNotificacionCorreo::findOrFail($request->codigo);
			$infocorreonotificacion->delete();
			return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
		}
	} 
}