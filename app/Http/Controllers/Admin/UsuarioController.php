<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use Illuminate\Http\Request;
use App\Models\UsuarioRol;
use App\Models\Persona;
use App\Util\notificar;
use App\Models\User;
use Auth, DB, URL;

class UsuarioController extends Controller
{
	public function index()
	{
        $data = DB::table('usuario as u')
                    ->select('u.usuaid','u.persid','p.tipideid','p.persdocumento','u.usuanombre','u.usuaapellidos','u.usuaalias',
                            'u.usuanick','u.usuaemail','u.usuabloqueado','u.usuaactivo','u.usuacambiarpassword',
                            DB::raw("CONCAT(ti.tipidesigla,'-', p.persdocumento ) as tipoDocumento"),
                            DB::raw("if(u.usuaactivo = 1,'Sí', 'No') as estado"),
                            DB::raw("if(u.usuabloqueado = 1,'Sí', 'No') as bloqueado"),
                            DB::raw("if(u.usuacambiarpassword = 1,'Sí', 'No') as cambiarpassword"))
                    ->join('persona as p', 'p.persid', '=', 'u.persid')
					->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                    ->orderBy('u.usuanombre')->orderBy('u.usuaapellidos')->get();

		return response()->json(['success' => true, "data" => $data]);
	}

	public function datos(Request $request)
	{
		$this->validate(request(),['codigo' => 'required','tipo' => 'required']);

		$tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->get();
		$roles                = DB::table('rol')->select('rolid','rolnombre')->orderBy('rolnombre')->get();
		$usuariosRoles        = [];
		if($request->tipo === 'U'){
			$usuariosRoles = DB::table('usuariorol as ur')->select('r.rolid','r.rolnombre', 'ur.usurolid')
									->join('rol as r', 'r.rolid', '=', 'ur.usurolrolid')
									->where('ur.usurolusuaid', $request->codigo)->get();
		}

        return response()->json(['success' => true,'tipoIdentificaciones' => $tipoIdentificaciones, 'roles' => $roles, 'usuariosRoles' => $usuariosRoles]);
	}

	public function consultar(Request $request)
	{
		$this->validate(request(),[
            'tipoIdentificacion'=> 'required',
            'documento'         => 'required|string|min:6|max:15'
        ]);	

		$personas = DB::table('persona')->select('persid', DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre)) as nombres"),
															DB::raw("CONCAT(persprimerapellido,' ',if(perssegundoapellido is null ,'', perssegundoapellido)) as apellidos"),
															'perscorreoelectronico','persgenero','persprimernombre','persprimerapellido'
															)
														->where('tipideid', $request->tipoIdentificacion)
														->where('persdocumento', $request->documento)->first();
		$array = ($personas !== null) ? ['success' => true,'personas' => $personas] : ['success' => false,'message' => 'No se encontró la persona. Por favor adicione la persona primero'];
        return response()->json($array);
	}

	public function salve(Request $request)
	{
        $usuaid  = $request->codigo;
        $usuario = ($usuaid != 000) ? User::findOrFail($usuaid) : new User();

	    $this->validate(request(),[
            'tipoIdentificacion'=> 'required',
            'documento'         => 'required|string|min:6|max:15',
			'persona'           => 'required|numeric',
            'nombre'            => 'required|string|min:5|max:50',
            'apellido'          => 'required|string|min:5|max:50',
            'usuario'           => 'required|string|min:5|max:20|unique:usuario,usuanick,'.$usuario->usuaid.',usuaid',
            'correo'            => 'required|email|string|max:80|unique:usuario,usuaemail,'.$usuario->usuaid.',usuaid',
			'cambiarPassword'   => 'required|numeric',
			'bloqueado'         => 'required|numeric',
            'estado'            => 'required|numeric',
			'roles'             => 'required|array|min:1'
        ]);

        DB::beginTransaction();
		try {
            $tokenFirma                   = strtoupper(strtr(substr(md5(microtime()), 0, 8),"01","97"));
            $nombre                       = mb_strtoupper($request->nombre,'UTF-8');
            $apellido                     = mb_strtoupper($request->apellido,'UTF-8');
            $nickUsuario                  = mb_strtoupper($request->usuario,'UTF-8');
            $usuario->persid              = $request->persona;
			$usuario->usuanombre          = $nombre;
			$usuario->usuaapellidos       = $apellido;
            $usuario->usuanick            = $nickUsuario;
			$usuario->usuaalias           = $request->alias;
			$usuario->usuaemail           = $request->correo;
			$usuario->usuacambiarpassword = $request->cambiarPassword;
			$usuario->usuabloqueado       = $request->bloqueado;
			$usuario->usuaactivo          = $request->estado;
            ($request->tipo  === 'I' ) ? $usuario->password = bcrypt($request->documento): '';
            $usuario->save();

			if($request->tipo  === 'I'){
				//Consulto el ultimo identificador del usuario
				$usuarioConsecutivo = User::latest('usuaid')->first();
				$usuaid             = $usuarioConsecutivo->usuaid;
			}

			foreach($request->roles as $dataRol){
				$identificador = $dataRol['identificador'];
				$rol           = $dataRol['rol'];
				$rolEstado     = $dataRol['estado'];
				if($rolEstado === 'I'){
					$usuariorol               = new UsuarioRol();
					$usuariorol->usurolusuaid = $usuaid;
					$usuariorol->usurolrolid  = $rol;
					$usuariorol->save();
				}else if($rolEstado === 'D'){
					$usuariorol = UsuarioRol::findOrFail($identificador);
					$usuariorol->delete();
				}else{//Omitir
				}
			}

			$mensajeCorreo      = '';
			if ($request->tipo  === 'I' ){
				$notificar         = new notificar();
				$nombreUsuario     = $nombre.' '. $apellido;

				$empresa           = DB::table('empresa as e')
										->select('e.emprnombre','e.emprsigla','e.emprcorreo',
												DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
												 p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
										->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
										->where('e.emprid', 1)->first();

				$siglaCooperativa  = $empresa->emprsigla;
				$nombreEmpresa     = $empresa->emprnombre;
				$contrasenaSistema = $request->documento; 
				$email             = $request->correo; 
				$urlSistema        =  URL::to('/');
				$emailEmpresa      = $empresa->emprcorreo;
				$nombreGerente     = $empresa->nombrePersona;

				$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'registroUsuario')->first();
				$buscar            = Array('siglaCooperativa', 'nombreUsuario', 'usuarioSistema', 'nombreEmpresa','contrasenaSistema','urlSistema','nombreGerente');
				$remplazo          = Array($siglaCooperativa, $nombreUsuario,  $nickUsuario, $nombreEmpresa, $contrasenaSistema, $urlSistema,$nombreGerente); 
				$asunto            = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
				$msg               = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido); 
				$enviarcopia       = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
				$mensajeCorreo     = ', Se ha enviado notificacion al correo  '.$notificar->correo([$email], $asunto, $msg, [], $emailEmpresa, $enviarcopia, $enviarpiepagina);
			}

            DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito'.$mensajeCorreo ]);
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
            'nombre'             => 'required|string|min:5|max:50',
            'apellido'           => 'required|string|min:5|max:50',
            'usuario'            => 'required|string|min:6|max:15|unique:usuario,usuanick,'.$usuario->usuaid.',usuaid',
            'correo'             => 'required|email|string|unique:usuario,usuaemail,'.$usuario->usuaid.',usuaid'
           ]);

        try {
            $usuario->usuanombre    = $request->nombre;
            $usuario->usuaapellidos = $request->apellido;
            $usuario->usuanick      = mb_strtoupper($request->usuario,'UTF-8'); 
            $usuario->email         = $request->correo;
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
		$historialContrasenas = DB::table('historialcontrasena')->select('hisconid','hisconpassword')->where('usuaid', Auth::id())->get();
		foreach($historialContrasenas as $historialContrasena){
			if (password_verify($request->password, $historialContrasena->hisconpassword)) {
				return response()->json(['success' => false, 'message'=> 'Lo siento, pero esta contraseña ya ha sido utilizada en el pasado. Por favor, elige una contraseña diferente']);
			}
		}

		DB::beginTransaction();
		try {
			$historialcontrasena                 = new HistorialContrasena();
			$historialcontrasena->usuaid         = Auth::id();
			$historialcontrasena->hisconpassword = bcrypt($request->password);
			$historialcontrasena->save();

			$usuario                      = User::findOrFail(Auth::id());
			$usuario->password            = bcrypt($request->password);
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
        //consulto que no tenga relacion 
        $data        = DB::table('codigodocumental')->select('usuaid')->where('usuaid', $request->codigo)->first();
		$dataIngreso = DB::table('ingresosistema')->select('usuaid')->where('usuaid', $request->codigo)->first();
		if($data){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está relacionado con un tipo documental producido en el sistema']);
		}else if($dataIngreso){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está relacionado con un ingreso al sistema']);
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