<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB;

class Tareas
{
    public static function inicioNotificacionVencimiento()
    {
        $mensaje = "Inicia proceso de notificacion de vencimiento  en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje;
    }
     
    public static function finalizarNotificacionVencimiento($mensaje)
    {
        $emailnotificacion = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $email             = $emailnotificacion->emprcorreo;

        $fechaHoraActual   = Carbon::now();
        $enviarEmail       = new notificar();
        $asunto            = 'Notificación de proceso de vencimiento realizado en la fecha '.$fechaHoraActual;
        $email             = 'radasa10@hotmail.com';
        $enviarEmail->correo([$email], $asunto, $mensaje);

        echo"Notifiacion de prueba enviada hoy ".$fechaHoraActual.", al email ".$email."\r\n";
    }
 
    public static function notificarVencimientoLicencias()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoLicencia' )->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa    = $empresa->emprcorreo;
            $nombreGerente    = $empresa->nombreGerente;

            $conductorNotificados = DB::table('conductorlicencia as cl')
                                        ->select('cl.conlicfechavencimiento', 'cl.conlicnumero','cl.conlicfechaexpedicion','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))
                                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                        ->join('persona as p', 'p.persid', '=', 'c.persid')
                                        ->whereIn('cl.conlicfechavencimiento', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje = (count($conductorNotificados) === 0) ? "No existe vencimiento de licencias para notificar en la fecha ".$fechaHoraActual."\r\n" : '';
            foreach($conductorNotificados as $conductorNotificado){
                $numeroLicencia   = $conductorNotificado->conlicnumero;
                $nombreConductor  = $conductorNotificado->nombreConductor;
                $correoPersona    = $conductorNotificado->perscorreoelectronico;
                $fechaExpedicion  = $conductorNotificado->conlicfechaexpedicion;
                $fechaVencimiento = $conductorNotificado->conlicfechavencimiento;

                $buscar           = Array('nombreConductor', 'fechaVencimiento', 'numeroLicencia','fechaExpedicion', 'nombreGerente');
                $remplazo         = Array($nombreConductor, $fechaVencimiento, $numeroLicencia, $fechaExpedicion, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de licencia envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }
        } catch (Exception $error){
            $mensaje = "Ocurrio un error al notificar vencimiento de licencia en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    public static function notificarVencimientoSoat()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $mensaje            = '';
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoSoat')->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculosoat as vs')
                                        ->select('vs.vehsoafechafinal', 'vs.vehsoanumero','vs.vehsoafechainicial','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereIn('vs.vehsoafechafinal', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje = (count($vehiculosNotificados) === 0) ? "No existe vencimiento de SOAT para notificar en la fecha ".$fechaHoraActual."\r\n" : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){
                $numeroPoliza     = $vehiculoNotificado->vehsoanumero;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $correoPersona    = $vehiculoNotificado->perscorreoelectronico;
                $fechaInicial     = $vehiculoNotificado->vehsoafechainicial;
                $fechaVencimiento = $vehiculoNotificado->vehsoafechafinal;

                $buscar           = Array('nombreAsociado', 'fechaVencimiento', 'numeroPoliza','fechaInicial', 'nombreGerente');
                $remplazo         = Array($nombreAsociado, $fechaVencimiento, $numeroPoliza, $fechaInicial, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de SOAT envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }
        } catch (Exception $error){
            $mensaje = "Ocurrio un error al notificar vencimiento de SOAT en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    public static function notificarVencimientoCRT()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoCRT')->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculocrt as vcrt')
                                        ->select('vcrt.vehcrtfechafinal', 'vcrt.vehcrtnumero','vcrt.vehcrtfechainicial','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vcrt.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereIn('vcrt.vehcrtfechafinal', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje = (count($vehiculosNotificados) === 0) ? "No existe vencimiento de CRT para notificar en la fecha ".$fechaHoraActual."\r\n" : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){
                $numeroCrt        = $vehiculoNotificado->vehcrtnumero;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $correoPersona    = $vehiculoNotificado->perscorreoelectronico;
                $fechaInicial     = $vehiculoNotificado->vehcrtfechainicial;
                $fechaVencimiento = $vehiculoNotificado->vehcrtfechafinal;

                $buscar           = Array('nombreAsociado', 'fechaVencimiento', 'numeroCrt','fechaInicial', 'nombreGerente');
                $remplazo         = Array($nombreAsociado, $fechaVencimiento, $numeroCrt, $fechaInicial, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de CRT envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }
        } catch (Exception $error){
            $mensaje = "Ocurrio un error al notificar vencimiento de CRT en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    public static function notificarVencimientoPolizas()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoPolizas')->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculopoliza as vp')
                                    ->select('vp.vehpolfechafinal', 'vp.vehpolnumeropolizacontractual','vp.vehpolnumeropolizaextcontrac','vp.vehpolfechainicial','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                    ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->whereIn('vp.vehpolfechafinal', $fechasNotificacion)
                                    ->whereNotNull('p.perscorreoelectronico')
                                    ->get();

            $mensaje = (count($vehiculosNotificados) === 0) ? "No existe vencimiento de polizas para notificar en la fecha ".$fechaHoraActual."\r\n" : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){
                $nombreAsociado             = $vehiculoNotificado->nombreAsociado;
                $fechaVencimiento           = $vehiculoNotificado->vehpolfechafinal;
                $fechaInicial               = $vehiculoNotificado->vehpolfechainicial;
                $correoPersona              = $vehiculoNotificado->perscorreoelectronico;
                $numeroPolizaContractual    = $vehiculoNotificado->vehpolnumeropolizacontractual;
                $numeroPolizaExtContractual = $vehiculoNotificado->vehpolnumeropolizaextcontrac;

                $buscar           = Array('nombreAsociado', 'fechaVencimiento', 'numeroPolizaContractual','numeroPolizaExtContractual','fechaInicial', 'nombreGerente');
                $remplazo         = Array($nombreAsociado, $fechaVencimiento, $numeroPolizaContractual, $numeroPolizaExtContractual, $fechaInicial, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de polizas envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }
        } catch (Exception $error){
            $mensaje = "Ocurrio un error al notificar vencimiento de poliza en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    public static function notificarVencimientoTarjetaOperacion()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoTarjetaOperacion')->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculotarjetaoperacion as vto')
                                    ->select('vto.vetaopfechainicial', 'vto.vetaopnumero','vto.vetaopfechafinal','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                                    ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->whereIn('vto.vetaopfechafinal', $fechasNotificacion)
                                    ->whereNotNull('p.perscorreoelectronico')
                                    ->get();

            $mensaje = (count($vehiculosNotificados) === 0) ? "No existe vencimiento de tarjeta de operacion para notificar en la fecha ".$fechaHoraActual."\r\n" : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){
                $numeroTarjetaOperacion = $vehiculoNotificado->vetaopnumero;                
                $nombreAsociado         = $vehiculoNotificado->nombreAsociado;
                $fechaInicial           = $vehiculoNotificado->vetaopfechafinal;
                $fechaVencimiento       = $vehiculoNotificado->vetaopfechainicial;                
                $correoPersona          = $vehiculoNotificado->perscorreoelectronico;

                $buscar           = Array('nombreAsociado', 'fechaVencimiento', 'numeroTarjetaOperacion','fechaInicial', 'nombreGerente');
                $remplazo         = Array($nombreAsociado, $fechaVencimiento, $numeroTarjetaOperacion, $fechaInicial, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de tarjeta de operacion envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }
        } catch (Exception $error){
            $mensaje = "Ocurrio un error al notificar vencimiento de tarjeta de operacion en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    public static function notificarVencimientoSoat1()
    {
        $generales          = new generales();
        $fechasNotificacion = $generales->definirRangoNotificacion();

        DB::beginTransaction();
        $notificar             = new notificar();
        $fechaHoraActual       = Carbon::now();
        $mensaje               = '';
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoLicencia' )->orderBy('innocoid')->first();
            $empresa            = DB::table('empresa as e')
                                        ->select('e.emprcorreo',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $correoEmpresa    = $empresa->emprcorreo;
            $nombreGerente    = $empresa->nombreGerente;

            $conductorLicencias = DB::table('conductorlicencia as cl')
                                    ->select('cl.conlicfechavencimiento', 'cl.conlicnumero','cl.conlicfechaexpedicion','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor")
                                    )
                                    ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                                    ->whereIn('cl.conlicfechavencimiento', $fechasNotificacion)
                                    ->get();

            foreach($conductorLicencias as $conductorLicencia){                
                $numeroLicencia   = $conductorLicencia->conlicnumero;
                $nombreConductor  = $conductorLicencia->nombreConductor;
                $correoPersona    = $conductorLicencia->perscorreoelectronico;
                $fechaExpedicion  = $conductorLicencia->conlicfechaexpedicion;
                $fechaVencimiento = $conductorLicencia->conlicfechavencimiento;                

                $buscar           = Array('nombreConductor', 'fechaVencimiento', 'numeroLicencia','fechaExpedicion', 'nombreGerente');
                $remplazo         = Array($nombreConductor, $fechaVencimiento, $numeroLicencia, $fechaExpedicion, $nombreGerente); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje          .= "Proceso de notificacion de vencimiento de licencia envidada hoy ".$fechaHoraActual.", al correo ".$correoPersona."\r\n";
            }

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje = "No existe vencimiento de licencia para notificar en la fecha ".$fechaHoraActual."\r\n";
        }

        echo $mensaje;
        return $mensaje;
    }

    /*//Funcion para notificar las solicitudes en estado inicial
    public function notificarSolicitudesEstadoInicial()
    {   
        $fechaHoraActual = Carbon::now();
        echo"Iniciando proceso de noticiar solicitudes en estado inicial hoy ".$fechaHoraActual." \r\n";
        $informacionemail  =  DB::table('informacioncorreonotificacion')->where('inconoid', 29)->first(); 
        $emailnotificacion =  DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $emailEmpresa = $emailnotificacion->emprcorreo;
        $enviarEmail  = new EnviarEmail();
       
       //Notifico las solicitudes en incial
        $solicitudes = DB::table('solicitud as s')
                                ->select('s.soliconsecutivo as consecutivo','s.solifechahora as fecha',
                                        'ts.tipsolnombre as tipo_solicitud', 'tm.tipmednombre as medio',
                                        's.solifecharespuesta','s.solianonimo',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',p.persprimerapellido,' ',if(p.perssegundoapellido is null ,'', p.perssegundoapellido)) as nombres")
                                        ) 
                                ->join('persona as p', 'p.persid', '=', 's.persid')
                                ->join('tipomedio as tm', 'tm.tipmedid', '=', 's.tipmedid')
                                ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                                ->whereIn('s.tiessoid', [1,2])
                                ->orderBy('s.soliconsecutivo')->get();
                                
        if(count($solicitudes) > 0){
            $table = "<table>
                    <thead>
                        <tr>
                            <td><b>Número Solicitud</b></td>
                            <td><b>Peticionario</b></td>
                            <td><b>Fecha Hora</b></td>
                            <td><b>Tipo Solicitud</b></td>
                            <td><b>Medio</b></td>
                            <td><b>Fecha Máxima</b></td>
                        </tr>
                    </thead>
                    <tbody> ";
            foreach($solicitudes as $solicitud){
                $nombrePeticionario = ($solicitud->solianonimo == 1)?'ANONIMO':$solicitud->nombres;
                $table .= "             
                        <tr>
                            <td>".$solicitud->consecutivo."</td>
                            <td>".$nombrePeticionario."</td>
                            <td>".$solicitud->fecha."</td>
                            <td>".$solicitud->tipo_solicitud."</td>
                            <td>".$solicitud->medio."</td>
                            <td>".$solicitud->solifecharespuesta."</td>
                        </tr>";

            }
            $table .= "</tbody></table>";
            
            $buscar   = Array("fecha_actual");
            $remplazo = Array($fechaHoraActual);

            $asunto          = str_replace($buscar,$remplazo,$informacionemail->inconotitulo);
            $msg             = str_replace($buscar,$remplazo,$informacionemail->inconocontenido); 
            $enviarcopia     = $informacionemail->inconoenviarcopia;
            $enviarpiepagina = $informacionemail->inconoenviarpiepagina;
            
            $enviarEmail->enviar([$emailEmpresa], $asunto, $msg.' '.$table, '', $emailEmpresa, $enviarcopia, $enviarpiepagina);
                  
            echo "Notificando al ".$emailEmpresa." las solicitudes en estado inicial\r\n";
        }else{
            echo"No hay solicitudes en estado incial para notificar\r\n";
        }      

    }

    //Funcion para notificar las solicitudes pediente por responer
    public function notificarSolicitudesPendientePorResponder()
    {
        $fechaHoraActual = Carbon::now();
        echo "Iniciando proceso de notificacion antes de vencer las solicitudes hoy ".$fechaHoraActual."\r\n";
        $dias = DB::table('dianotificacion')->select('dianotdias')->orderBy('dianotdias')->get();
    
        $fechas = []; 
        foreach($dias as $dia){
            $fecha_notificar = $fechaHoraActual->addDays($dia->dianotdias);
            array_push($fechas, $fecha_notificar->format('Y-m-d'));
            $fechaHoraActual = $fechaHoraActual->subDays($dia->dianotdias);  
        }

        $informacionemail  =  DB::table('informacioncorreonotificacion')->where('inconoid', 30)->first(); 
        $emailnotificacion =  DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $emailEmpresa = $emailnotificacion->emprcorreo;
        $enviarEmail  = new EnviarEmail();

        $cordinador_pqrs = 'No existe ningún coordinador en la base de datos';
        $usuario = DB::table('users as u')
                        ->select(DB::raw("CONCAT(u.name,' ', u.apellidos) as nombre"))
                        ->join('persona as p', 'p.persid', '=', 'u.persid')
                        ->where('p.tipresid', 2)->where('activo',true)->first();
        if($usuario){
            $cordinador_pqrs = $usuario->nombre; 
        }

        echo "Notificando al coordinador ".$cordinador_pqrs."\r\n";
               
        //Consulto las  solicitudes en este periodo
        $solicitudes = DB::table('solicitud as s')  
                            ->select('s.soliid','s.soliconsecutivo', 's.solifecharespuesta','ts.tipsolnombre as tipo_solicitud')
                            ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                            ->where('s.tiessoid', 3)
                            ->whereIn('s.solifecharespuesta', $fechas)
                            ->whereNotIn('s.soliid', function($query) use($fechas){ 
                                            $query->select('s1.soliid')
                                                ->from('solicitud as s1')
                                                ->Join('solicitudrespuesta as sr', 'sr.soliid', '=', 's1.soliid')
                                                ->whereIn('sr.solresrespuesta', $fechas)
                                                ->where('s1.tiessoid', 3);
                                        })->get();

        if(count($solicitudes) > 0){
            foreach ($solicitudes as $solicitud) {
                $soliid      = $solicitud->soliid;
                $consecutivo = $solicitud->soliconsecutivo;
                $fecha_max_respuesta = $solicitud->solifecharespuesta;
                $tipo_solicitud      = $solicitud->tipo_solicitud;

                $emailNotificaciones = DB::table('solicituddependencia as sd') ->select('d.depecorreo')
                                        ->join('dependencia as d', 'd.depeid', '=', 'sd.depeid')
                                        ->where('sd.soliid', $soliid)->get();
                
                foreach($emailNotificaciones as $emailNotificacion)
                {
                    $email[] = $emailNotificacion->depecorreo;
                }                     

                $buscar   = Array("consecutivo","fecha_max_respuesta","tipo_solicitud","cordinador_pqrs");
                $remplazo = Array($consecutivo,$fecha_max_respuesta,$tipo_solicitud,$cordinador_pqrs);

                $asunto          = str_replace($buscar,$remplazo,$informacionemail->inconotitulo);
                $msg             = str_replace($buscar,$remplazo,$informacionemail->inconocontenido); 
                $enviarcopia     = $informacionemail->inconoenviarcopia;
                $enviarpiepagina = $informacionemail->inconoenviarpiepagina;
                
                $enviarEmail->enviar($email, $asunto, $msg, '', $emailEmpresa, $enviarcopia, $enviarpiepagina);

                echo"Notifiacion enviada hoy ".$fechaHoraActual.", al email ".$email."\r\n";                
            }
        }else{
            echo"No existen solicitudes para notificar \r\n";
       }         
    }*/
}