<?php

namespace App\Console\Commands;

use App\Models\Conductor\ConductorCambioEstado;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Procesos\ProcesosAutomaticos;
use App\Console\Commands\FuncionesGenerales;
use App\Models\Conductor\Conductor;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Console\Command;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB;

class Noche
{
    public static function iniciar()
    {
        $mensaje = "Inicia procesos automaticos de la noche en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje.'<br>';
    }

    public static function finalizar($mensaje)
    {
        $emailnotificacion = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $email             = $emailnotificacion->emprcorreo;

        $fechaHoraActual   = Carbon::now();
        $enviarEmail       = new notificar();
        $asunto            = 'Notificación de proceso automaticos de la noche realizado en la fecha '.$fechaHoraActual;
        $email             = 'radasa10@hotmail.com';
        $enviarEmail->correo([$email], $asunto, $mensaje);

        echo"Notificación de proceso automaticos de la noche realizado en la fecha ".$fechaHoraActual.", y enviado al correo ".$email."\r\n";
    }
 
    public static function prueba($esEjecucionManual = false)
    {    
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("VencimientoLicencias") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionConductor')->first();   
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;            

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(1);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success  = true;
            DB::commit();            
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el conductor por falta de licencia en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }
}