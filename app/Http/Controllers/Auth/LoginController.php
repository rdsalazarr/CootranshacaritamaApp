<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password, 'activo' => 1]))
    {      
      return response()->json(['success' => true, 'msg' => 'Usuario autenticado.', 'ruta' => '/dashboard']); 
    }
  
    return response()->json(['success' => false, 'msg' => 'Estas credenciales no coinciden con nuestros registros.', 'ruta' => '']); 
  }

  /**
  * Log the user out of the application.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function logout(Request $request)
  {   
    Auth::logout();    
    $request->session()->invalidate(); 
    $request->session()->regenerateToken();

    return redirect('/');
  }
}