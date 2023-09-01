<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use Illuminate\Http\Request;
use App\Util\notificar;
use App\Models\User;
use Auth, DB, URL;

class UsuarioController extends Controller
{
	public function index()
	{
        $data = DB::table('usuario as u')
                    ->select('u.usuaid','u.tipideid','u.usuadocumento','u.usuanombre','u.usuaapellidos',
                            'u.usuanick','u.usuaemail','u.usuabloqueado','u.usuaactivo','u.usuacambiarpassword',
                            DB::raw("CONCAT(ti.tipidesigla,'-', u.usuadocumento ) as tipoDocumento"),
                            DB::raw("if(u.usuaactivo = 1,'Sí', 'No') as estado"),
                            DB::raw("if(u.usuabloqueado = 1,'Sí', 'No') as bloqueado"),
                            DB::raw("if(u.usuacambiarpassword = 1,'Sí', 'No') as cambiarpassword"))
                    ->leftjoin('tipoidentificacion as ti', 'ti.tipideid', '=', 'u.tipideid')
                    ->orderBy('u.usuanombre')->orderBy('u.usuaapellidos')->get();

		return response()->json(['success' => true, "data" => $data]);
	} 

	public function salve(Request $request)
	{
        $id      = $request->codigo;
        $usuario = ($id != 000) ? User::findOrFail($id) : new User();

	    $this->validate(request(),[
            'tipoIdentificacion'=> 'required',
            'documento'         => 'required|string|min:6|max:15',
            'nombre'            => 'required|string|min:5|max:50',
            'apellido'          => 'required|string|min:5|max:50',
            'usuario'           => 'required|string|min:5|max:20|unique:users,usuario,'.$usuario->id,
            'correo'            => 'required|email|string|max:80|unique:users,email,'.$usuario->id,
            'ciudadExpedicion'  => 'required|string|min:4|max:80',
            'numeroEvaluacion'  => 'nullable|numeric',
            'puntajeEvaluacion' => 'nullable|numeric',
            'esInvitado'        => 'required|numeric',
            'esAsociado'        => 'required|numeric',
            'activo'            => 'required|numeric'
        ]);

        DB::beginTransaction();
		try {
            $tokenFirma                 = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $nombre                     = mb_strtoupper($request->nombre,'UTF-8');
            $apellido                   = mb_strtoupper($request->apellido,'UTF-8');
            $nickUsuario                = mb_strtoupper($request->usuario,'UTF-8');
            $usuario->tipideid          = $request->tipoIdentificacion;
            $usuario->usuadocumento     = $request->documento;
			$usuario->usuanombre        = $nombre;
			$usuario->usuaapellidos     = $apellido;
            $usuario->usuanick          = $nickUsuario;
			$usuario->usuaemail         = $request->correo;
            ($request->tipo  === 'I' ) ? $usuario->password = bcrypt($request->documento): '';
            $usuario->save();

            DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function perfil()
	{
		$usuario = User::findOrFail(Auth::id());
		$tipoidentificaciones = DB::table('tipoidentificacion')->get();
        return response()->json(['success' => true,"usuario" => $usuario,'tipoidentificaciones' => $tipoidentificaciones]);
	}

    //Funcion para actualizar los datos
  	public function updatePerfil(Request $request)
  	{
        $usuario = User::findOrFail(Auth::id());
   		$this->validate(request(),[ 
   			'tipoIdentificacion' => 'required',
            'documento'          => 'required|string|min:6|max:15',
            'nombre'             => 'required|string|min:5|max:50',
            'apellido'           => 'required|string|min:5|max:50',
            'usuario'            => 'required|string|min:6|max:15|unique:users,usuario,'.$usuario->id,
            'correo'             => 'required|email|string|unique:users,email,'.$usuario->id
           ]);
   
        try {
            $usuario->tipideid  = $request->tipoIdentificacion;
            $usuario->documento = $request->documento;
            $usuario->nombre    = $request->nombre;
            $usuario->apellidos = $request->apellido;
            $usuario->usuario   = mb_strtoupper($request->usuario,'UTF-8'); 
            $usuario->email     = $request->correo;
            $usuario->save();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            return response()->json(['success' => true, 'message' => 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }    

    //Funcion para actualizar los datos
	public function updatePassword(Request $request)
	{
		$this->validate(request(),[
			'password'    => 'required|string|min:6',
			'repPassword' => 'required|confirmed',
			'repPassword' => 'required|same:password'
		]);

		$generales               = new generales();
		list($success, $message) = $generales->validarContrasena($request->password);
		if(!$success){
			return response()->json(['success' => false, 'message'=> $message]);
		}

		//Verifico que la contraseña no la halla utilizado el usuario
		$historialcontrasenas = DB::table('historialcontrasena')
								->select('hisconid','hisconpassword')
								->where('usuaid', Auth::id())->get();
		foreach($historialcontrasenas as $historialcontrasena){
			if (password_verify($request->password, $historialcontrasena->hisconpassword)) {
				return response()->json(['success' => false, 'message'=> 'Lo siento, pero esta contraseña ya ha sido utilizada en el pasado. Por favor, elige una contraseña diferente']);
			}
		}

		DB::beginTransaction();
		try {
			$historialcontrasena                 = new HistorialContrasena();
			$historialcontrasena->usuaid         = Auth::id();
			$historialcontrasena->hisconpassword = bcrypt($request->password);
			$historialcontrasena->save();

			$usuario = User::findOrFail(Auth::id());
			$usuario->password = bcrypt($request->password);
			$usuario->usuacambiarpassword = false;
			$usuario->save();
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Contraseña modificada con éxito por favor cierra sesión y vuelve a ingresar al sistema']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
	
	public function destroy(Request $request)
	{
        //consulto que no tenga evaluacion
        $data = DB::table('evaluacionusuario')->select('evausuuserid')
				                        ->where('evausuuserid', $request->codigo)->first();
		if($data){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está relacionado con una evaluación']);
		}else{
			try {
                $usuario = User::findOrFail($request->codigo);
                $usuario->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}