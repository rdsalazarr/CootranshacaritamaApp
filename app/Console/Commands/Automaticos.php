<?php

namespace App\Console\Commands;


use App\Models\Conductor\ConductorCambioEstado;
use App\Models\Conductor\Conductor;

use Illuminate\Console\Command;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB;

class Automaticos
{
    public static function iniciar()
    {
        $mensaje = "Inicia procesos automaticos en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje.'<br>';
    }

    public static function finalizar($mensaje)
    {
        $emailnotificacion = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $email             = $emailnotificacion->emprcorreo;

        $fechaHoraActual   = Carbon::now();
        $enviarEmail       = new notificar();
        $asunto            = 'Notificación de proceso automaticos realizado en la fecha '.$fechaHoraActual;
        $email             = 'radasa10@hotmail.com';
        $enviarEmail->correo([$email], $asunto, $mensaje);

        echo"Notificación de proceso automaticos realizado en la fecha ".$fechaHoraActual.", y enviado al correo ".$email."\r\n";
    }
 
    public static function suspenderConductor()
    {
        $notificar       = new notificar();
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $estado          = 'S';
        $mensaje         = '';
        $mensajeCorreo   = '';
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionConductor')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa    = $empresa->emprcorreo;
            $nombreGerente    = $empresa->nombreGerente;

            $conductorLicencias = DB::table('conductorlicencia as cl')
                                        ->select('cl.condid','cl.conlicfechavencimiento', 'p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))
                                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                        ->join('persona as p', 'p.persid', '=', 'c.persid')
                                        ->whereDate('cl.conlicfechavencimiento', $fechaActual)
                                        ->whereDate('cl.conlicfechavencimiento', function ($query) {
                                            $query->select(DB::raw('max(conlicfechavencimiento)'))
                                                ->from('conductorlicencia');
                                        })
                                        ->get();

            $mensaje        = (count($conductorLicencias) === 0) ? "No existe conductores con licencias vencidas para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($conductorLicencias as $conductorLicencia){
                $nombreConductor     = $conductorLicencia->nombreConductor;
                $correoPersona       = $conductorLicencia->perscorreoelectronico;
                $fechaVencimiento    = $conductorLicencia->conlicfechavencimiento;

                $condid              = $conductorLicencia->condid;
                $conductor           = Conductor::findOrFail($condid);
                $conductor->tiescoid = $estado;
                $conductor->save();

                $conductorcambioestado 					  = new ConductorCambioEstado();
                $conductorcambioestado->condid            = $condid;
                $conductorcambioestado->tiescoid          = $estado;
                $conductorcambioestado->cocaesusuaid      = 1;
                $conductorcambioestado->cocaesfechahora   = $fechaHoraActual;
                $conductorcambioestado->cocaesobservacion = "Se ha suspendido el conductor porque a la fecha no se encuentra registra una licencia de conducción válida";
                $conductorcambioestado->save();

                $buscar           = Array('nombreConductor', 'numeroLicencia', 'fechaVencimiento');
                $remplazo         = Array($nombreConductor, $numeroLicencia, $fechaVencimiento); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de suspender el conductor ".$nombreConductor." en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }
            
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el conductor por falta de licencia en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $mensaje;
        return $mensajeCorreo.'<br>';
    }

    public static function suspenderVehiculosSoat()
    {
        $notificar       = new notificar();
        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $estado          = 'S';
        $mensaje         = '';
        $mensajeCorreo   = '';
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculo')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa    = $empresa->emprcorreo;
            $nombreGerente    = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculosoat as vs')
                                        ->select('v.vehiid','vs.vehsoafechafinal', 'vs.vehsoanumero','vs.vehsoafechainicial','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vs.vehsoafechafinal', $fechasNotificacion)
                                        ->whereDate('vs.vehsoafechafinal', function ($query) {
                                            $query->select(DB::raw('max(vehsoafechafinal)'))
                                                ->from('vehiculosoat');
                                        })
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para suspender por falta de SOAT en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $nombreConductor     = $vehiculoNotificado->nombreConductor;
                $correoPersona       = $vehiculoNotificado->perscorreoelectronico;
                $fechaVencimiento    = $vehiculoNotificado->conlicfechavencimiento;

                $vehiid              = $vehiculoNotificado->vehiid;

                $vehiculo           = Vehiculo::findOrFail($vehiid);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();
    
                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = 1;
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "Se ha suspendido el vehículo por falta de SOAT vigente";
                $vehiculocambioestado->save();

                $buscar           = Array('nombreConductor', 'numeroLicencia', 'fechaVencimiento');
                $remplazo         = Array($nombreConductor, $numeroLicencia, $fechaVencimiento); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de suspender vehiculo por falta de SOAT en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }
            
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de SOAT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $mensaje;
        return $mensajeCorreo.'<br>';

    }
}