<?php
namespace App\Http\Controllers\Auth;

use App\Models\Usuario\IntentosFallidos;
use App\Models\Usuario\IngresoSistema;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generales;
use App\Models\User;
use Carbon\Carbon;
use DB, Auth;

class LoginController extends Controller
{
  //funcion para verificar datos
  public function login(Request $request)
  {    
    $credentials = $this->validate(request(),[
      'usuario'  => 'required|string',
      'password' => 'required|string|min:6'
    ]);

    //Determino la ip de donde accede
    //$clientIP  = (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

    if (Auth::attempt(['usuanick' => $request->usuario, 'password' => $request->password, 'usuaactivo' => 1, 'usuabloqueado' => 0]))
    {
      //registro el ingreso al sistema
      $generales       = new generales();
      $ingresosistema  = new IngresoSistema();
      $ingresosistema->usuaid                 = Auth::id();
      $ingresosistema->ingsisipacceso         = $generales->optenerIP;
      $ingresosistema->ingsisfechahoraingreso = Carbon::now();
      $ingresosistema->save();

      //Elimino los intentos fallidos 
      $intentosfallidos = DB::table('intentosfallidos')->select('intfalid')
                              ->where('intfalusurio', $request->usuario)->get();
      if($intentosfallidos){
        foreach($intentosfallidos as $intentofallido){
          $intentosfallidosUser = IntentosFallidos::findOrFail($intentofallido->intfalid);
          $intentosfallidosUser->delete();
        }
      }

      $ruta = (Auth::user()->usuacambiarpassword) ? '/reset' : '/dashboard';

      return response()->json(['success' => true, 'msg' => 'Usuario autenticado', 'ruta' => $ruta]); 
    }

    //Registro el inento fallido
    $intentosfallidos  = new IntentosFallidos();
    $intentosfallidos->intfalusurio   = $request->usuario;
    $intentosfallidos->intfalipacceso = $generales->optenerIP;
    $intentosfallidos->intfalfecha    = Carbon::now();
    $intentosfallidos->save();

    //Determino el numero de intento fallido de este usuario
    $intentosfallidosUser = DB::table('intentosfallidos as intf')->select('intf.intfalid', 'u.usuaid',
                                DB::raw('(SELECT COUNT(infa.intfalid) AS evausuid
                                              FROM intentosfallidos as infa
                                              WHERE infa.intfalid = intfalid
                                              ) AS numeroIntentos'))
                                ->join('usuario as u', 'u.usuanick', '=', 'intf.intfalusurio')
                                ->where('intf.intfalusurio', $request->usuario)
                                ->orderBy('intf.intfalfecha', 'DESC')->first();

    if($intentosfallidosUser){
      if ($intentosfallidosUser->numeroIntentos > 3){
        $usuario = User::findOrFail($intentosfallidosUser->usuaid);
        $usuario->usuabloqueado = true;
        $usuario->save();
      }
    }

    return response()->json(['success' => false, 'msg' => 'Estas credenciales no coinciden con nuestros registros o su usuario puede estar bloqueado', 'ruta' => '']); 
  }

  /**
  * Log the user out of the application.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function logout(Request $request)
  {
    //Registro la salida del usuario
    $usuario = DB::table('ingresosistema')->select('ingsisid')
                            ->where('ingsisfechahorasalida', '=', null)
                            ->where('usuaid', Auth::id())
                            ->orderBy('ingsisid', 'DESC')->first();

    if($usuario){
      $ingresosistema = IngresoSistema::findOrFail($usuario->ingsisid);
      $ingresosistema->ingsisfechahorasalida = Carbon::now();
      $ingresosistema->save();
    }

    Auth::logout();
    $request->session()->invalidate(); 
    $request->session()->regenerateToken();

    return redirect('/');
  }
}