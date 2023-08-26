<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Notificar;
use DB, Auth, URL, Artisan;
use Carbon\Carbon;

class MantenimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('clear');
    }

   /* public function __construct()
    {
      $this->middleware('guest',['only' => 'clear']);
    }*/

    public function clear()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache'); 
        Artisan::call('optimize');
        return "Datos eliminados";
    }
    
    public function optimize()
    {
        Artisan::call('optimize');
        return "Ruta optimizados";
        // php artisan optimize
    }

    public function down()
    {
        Artisan::call('down --secret="COOTRANSHACARITAMAAPP"');
        return response()->view('errors.503',['title' =>'Creando modo mantenimiento']);
    }

    public function up()
    {   
        Artisan::call('up');
        return response()->view('errors.upMantenimiento',['title' =>'Subiendo el modo mantenimiento']);
    }

    public function email(){
        $notificar       = new Notificar();
        $fechaHoraActual = Carbon::now();

        $email           = 'rdsalazarr@ufpso.edu.co';
        $nombre_empresa  = 'IMPLESOFT';
        $nombre_empleado = "NOMBRE DE PRUEBAS";  
        $nombre_usuario  = "PRUEBAS";
        $tokenFirma      = '123456789'; 
        $url_sistema     =  URL::to('/');
        $emailEmpresa    = '';      
    
       /* $informacioncorreo = DB::table('informacioncorreonotificacion')->where('inconoid', 2)->first();
        
        $buscar   = Array('nombre_empresa', 'nombre_empleado', 'nombre_usuario','contrasena_usuario', 'url_sistema');
        $remplazo = Array($nombre_empresa, $nombre_empleado, $nombre_usuario, $tokenFirma, $url_sistema);  

        $asunto = str_replace($buscar,$remplazo,$informacioncorreo->inconotitulo);
        $msg = str_replace($buscar,$remplazo,$informacioncorreo->inconocontenido); 
        $enviarcopia = $informacioncorreo->inconoenviarcopia;
        $enviarpiepagina = $informacioncorreo->inconoenviarpiepagina;*/

        $asunto         = 'ASUNTO 01';
        $msg             = 'MSG ASUNTO 01';
        $enviarcopia     = 0;
        $enviarpiepagina = 0;

        $enviarcopia = 0;
        $mensajeCorreo = ', '.$notificar->correo([$email], $asunto, $msg, '', $emailEmpresa, $enviarcopia, $enviarpiepagina);

       dd($mensajeCorreo);      
    }    
}