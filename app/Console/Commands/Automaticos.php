<?php

namespace App\Console\Commands;

use App\Models\Conductor\ConductorCambioEstado;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Conductor\Conductor;
use App\Models\Vehiculos\Vehiculo;
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
                $condid              = $conductorLicencia->condid;
                $nombreConductor     = $conductorLicencia->nombreConductor;
                $correoPersona       = $conductorLicencia->perscorreoelectronico;
                $fechaVencimiento    = $conductorLicencia->conlicfechavencimiento;

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
                                        ->select('v.vehiid','vs.vehsoafechafinal', 'vs.vehsoanumero','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vs.vehsoafechafinal', '>', $fechaActual)
                                        ->whereDate('vs.vehsoafechafinal', function ($query) {
                                            $query->select(DB::raw('max(vehsoafechafinal)'))
                                                ->from('vehiculosoat');
                                        })
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para suspender por falta de SOAT en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $vehiid           = $vehiculoNotificado->vehiid;
                $placaVehiculo    = $vehiculoNotificado->vehiplaca;
                $numeroPoliza     = $vehiculoNotificado->vehsoanumero;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $fechaVencimiento = $vehiculoNotificado->vehsoafechafinal;
                $numeroInterno    = $vehiculoNotificado->vehinumerointerno;
                $correoPersona    = $vehiculoNotificado->perscorreoelectronico;

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

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, 'SOAT');
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

    public static function suspenderVehiculosCRT()
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

            $vehiculosNotificados = DB::table('vehiculocrt as vcrt')
                                        ->select('v.vehiid','vcrt.vehcrtfechafinal', 'vcrt.vehcrtnumero','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vcrt.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vs.vehsoafechafinal', '>', $fechaActual)
                                        ->whereDate('vs.vehsoafechafinal', function ($query) {
                                            $query->select(DB::raw('max(vehsoafechafinal)'))
                                                ->from('vehiculosoat');
                                        })
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para suspender por falta de CRT en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $vehiid           = $vehiculoNotificado->vehiid;
                $placaVehiculo    = $vehiculoNotificado->vehiplaca;
                $numeroPoliza     = $vehiculoNotificado->vehcrtnumero;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $fechaVencimiento = $vehiculoNotificado->vehcrtfechafinal;
                $numeroInterno    = $vehiculoNotificado->vehinumerointerno;
                $correoPersona    = $vehiculoNotificado->perscorreoelectronico;

                $vehiculo           = Vehiculo::findOrFail($vehiid);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = 1;
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "Se ha suspendido el vehículo por falta de CRT vigente";
                $vehiculocambioestado->save();

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, 'CRT');
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de suspender vehiculo por falta de CRT en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de CRT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $mensaje;
        return $mensajeCorreo.'<br>';
    }

/*Asunto: Suspensión de servicios por falta de documentación obligatoria de vehículo con número interno numeroInterno

Estimado nombreAsociado, por medio de la presente nos dirigimos a usted para informarle sobre una situación importante relacionada con su vehículo registrado en nuestra cooperativa.
De acuerdo con nuestros registros, hemos identificado que el vehículo con placa placaVehiculo y número interno numeroInterno actualmente se encuentra suspendido debido a la falta de documentación obligatoria. Esta suspensión afecta la posibilidad de realizar cualquier trámite con la cooperativa hasta que se regularice la situación.

La documentación faltante corresponde al tipoDocumentacion la cual vence en la fecha fechaVencimiento. Para levantar la suspensión y poder continuar con sus actividades, le solicitamos amablemente que nos proporcione la documentación requerida a la brevedad posible.
Por favor, tenga en cuenta que este requisito es fundamental para cumplir con las normativas legales y garantizar la seguridad y el bienestar de todos nuestros asociados. Estamos comprometidos en brindar un servicio de calidad y, por ello, es indispensable contar con la documentación actualizada.
Si tiene alguna pregunta o requiere asistencia para completar este proceso, no dude en comunicarse con nuestro equipo de atención al cliente. Estaremos encantados de ayudarle en lo que necesite.
Agradecemos su pronta atención a este asunto y su colaboración para mantener al día la documentación de su vehículo. Valoramos su compromiso con la seguridad y el cumplimiento de las normativas.

Cordial saludos,


Suspensión de conducción por vencimiento de licencia numeroLicencia

Estimado nombreConductor, por medio de la presente nos dirigimos a usted para informarle sobre una situación importante relacionada con su licencia de conducir.
Lamentablemente, hemos detectado que su licencia con número numeroLicencia ha vencido en la fecha fechaVencimiento. Como medida preventiva, hemos suspendido su capacidad para cubrir rutas en la cooperativa hasta que regularice su situación.
La suspensión como conductor es una medida necesaria para cumplir con las normativas de seguridad y garantizar el bienestar de todos los miembros de nuestra cooperativa. Para levantar la suspensión y poder continuar con sus actividades como conductor de nuestra empresa, le solicitamos amablemente que renueve su licencia a la mayor brevedad posible.
Entendemos que la renovación de la licencia puede llevar tiempo, por lo que le instamos a iniciar el proceso inmediatamente. Si ya ha renovado su licencia, por favor, ignore este mensaje y proporciónenos la documentación actualizada.
Si tiene alguna pregunta o necesita orientación sobre el proceso de renovación, no dude en comunicarse con nuestro departamento de recursos humanos.
Agradecemos su comprensión y colaboración en este asunto. La seguridad de nuestros conductores y pasajeros es nuestra prioridad, y confiamos en que tomará las medidas necesarias para regularizar su situación a la mayor brevedad posible.
Quedamos a su disposición para cualquier consulta adicional que pueda tener.
Cordial saludos,


*/

}