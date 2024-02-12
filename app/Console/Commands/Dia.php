<?php

namespace App\Console\Commands;

use App\Models\Conductor\ConductorCambioEstado;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Procesos\ProcesosAutomaticos;
use App\Console\Commands\funcionesGenerales;
use App\Models\Conductor\Conductor;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Console\Command;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB;

class Dia
{
    public static function iniciar()
    {
        $mensaje = "Inicia procesos automaticos del dia en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje.'<br><br>';
    }

    public static function finalizar($mensaje)
    {
        $emailnotificacion = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $email             = $emailnotificacion->emprcorreo;

        $fechaHoraActual   = Carbon::now();
        $enviarEmail       = new notificar();
        $asunto            = 'Notificación de proceso automaticos del dia realizado en la fecha '.$fechaHoraActual;
        $email             = 'radasa10@hotmail.com';
        $enviarEmail->correo([$email], $asunto, $mensaje);

        echo"Notificación de proceso automaticos del dia realizado en la fecha ".$fechaHoraActual.", y enviado al correo ".$email."\r\n";
    }
 
    public static function suspenderConductor($esEjecucionManual = false)
    {    
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("VencimientoLicencias") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionConductor')->first();   
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $conductorLicencias = DB::table('conductorlicencia as cl')
                                    ->select('cl.condid', 'cl.conlicfechavencimiento', 'cl.conlicnumero', 'p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre, ' ', IFNULL(p.perssegundonombre, ''), ' ',
                                            p.persprimerapellido, ' ', IFNULL(p.perssegundoapellido, '')) as nombreConductor"))
                                    ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                                    ->whereDate('cl.conlicfechavencimiento', '<', $fechaActual)
                                    ->where('cl.conlicfechavencimiento', '=', function ($query) {
                                        $query->select(DB::raw('MAX(cl1.conlicfechavencimiento)'))
                                            ->from('conductorlicencia as cl1')
                                            ->join('conductor as c1', 'c1.condid', '=', 'cl1.condid')
                                            ->whereColumn('c1.condid', '=', 'c.condid');
                                    })
                                    ->where('c.tiescoid', 'A')
                                    ->get();

            $mensaje        = (count($conductorLicencias) === 0) ? "No existe conductores con licencias vencidas para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($conductorLicencias as $conductorLicencia){
                $condid              = $conductorLicencia->condid;
                $numeroLicencia      = $conductorLicencia->conlicnumero;
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

                $buscar           = Array('nombreConductor', 'numeroLicencia', 'fechaVencimiento', 'nombreGerente');
                $remplazo         = Array($nombreConductor, $numeroLicencia, $fechaVencimiento, $nombreGerente); 
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

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(1);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
            $success  = true;
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el conductor por falta de licencia en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function suspenderVehiculosSoat($esEjecucionManual = false)
    {
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("VencimientoSoat") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculo')->first();
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculosoat as vs')
                                        ->select('v.vehiid','vs.vehsoafechafinal', 'vs.vehsoanumero','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vs.vehsoafechafinal', '<', $fechaSuspencion)
                                        ->where('vs.vehsoafechafinal', '=', function ($query) {
                                            $query->select(DB::raw('MAX(vs1.vehsoafechafinal)'))
                                                ->from('vehiculosoat as vs1')
                                                ->join('vehiculo as v1', 'v1.vehiid', '=', 'vs1.vehiid')
                                                ->whereColumn('v1.vehiid', '=', 'vs.vehiid');
                                        })
                                        ->where('v.tiesveid', 'A')
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

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno','nombreGerente', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, $nombreGerente, 'al SOAT');
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

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(2);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de SOAT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function suspenderVehiculosCRT($esEjecucionManual = false)
    {
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("VencimientoCRT") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculo')->first();
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculocrt as vcrt')
                                        ->select('v.vehiid','vcrt.vehcrtfechafinal', 'vcrt.vehcrtnumero','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vcrt.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vcrt.vehcrtfechafinal', '<', $fechaSuspencion)
                                        ->where('vcrt.vehcrtfechafinal', '=', function ($query) {
                                            $query->select(DB::raw('MAX(vcrt1.vehcrtfechafinal)'))
                                                ->from('vehiculocrt as vcrt1')
                                                ->join('vehiculo as v1', 'v1.vehiid', '=', 'vcrt1.vehiid')
                                                ->whereColumn('v1.vehiid', '=', 'vcrt.vehiid');
                                        })
                                        ->where('v.tiesveid', 'A')
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

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno', 'nombreGerente', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, $nombreGerente,'al CRT');
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

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(3);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de CRT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function suspenderVehiculosPolizas($esEjecucionManual = false)
    {
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("VencimientoPolizas") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculo')->first();
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculopoliza as vp')
                                        ->select('v.vehiid','vp.vehpolfechafinal', 'vp.vehpolnumeropolizacontractual','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vp.vehpolfechafinal', '<', $fechaSuspencion)
                                        ->where('vp.vehpolfechafinal', '=', function ($query) {
                                            $query->select(DB::raw('MAX(vp1.vehpolfechafinal)'))
                                                ->from('vehiculopoliza as vp1')
                                                ->join('vehiculo as v1', 'v1.vehiid', '=', 'vp1.vehiid')
                                                ->whereColumn('v1.vehiid', '=', 'vp.vehiid');
                                        })
                                        ->where('v.tiesveid', 'A')
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para suspender por falta de poliza en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $vehiid           = $vehiculoNotificado->vehiid;
                $placaVehiculo    = $vehiculoNotificado->vehiplaca;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $fechaVencimiento = $vehiculoNotificado->vehpolfechafinal;
                $numeroInterno    = $vehiculoNotificado->vehinumerointerno;
                $correoPersona    = $vehiculoNotificado->perscorreoelectronico;
                $numeroPoliza     = $vehiculoNotificado->vehpolnumeropolizacontractual;

                $vehiculo           = Vehiculo::findOrFail($vehiid);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = 1;
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "Se ha suspendido el vehículo por falta de póliza vigente";
                $vehiculocambioestado->save();

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno', 'nombreGerente', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, $nombreGerente,'a la Póliza');
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de suspender vehiculo por falta de poliza en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(4);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de poliza en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function suspenderVehiculosTarjetaOperacion($esEjecucionManual = false)
    {
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("VencimientoTarjetaOperacion") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculo')->first();
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculotarjetaoperacion as vto')
                                        ->select('v.vehiid','vto.vetaopfechafinal', 'vto.vetaopnumero','v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vto.vetaopfechafinal', '<', $fechaSuspencion)
                                        ->where('vto.vetaopfechafinal', '=', function ($query) {
                                            $query->select(DB::raw('MAX(vto1.vetaopfechafinal)'))
                                                ->from('vehiculotarjetaoperacion as vto1')
                                                ->join('vehiculo as v1', 'v1.vehiid', '=', 'vto1.vehiid')
                                                ->whereColumn('v1.vehiid', '=', 'vto.vehiid');
                                        })
                                        ->where('v.tiesveid', 'A')
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para suspender por falta de tarjeta de operacion en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $vehiid           = $vehiculoNotificado->vehiid;
                $placaVehiculo    = $vehiculoNotificado->vehiplaca;
                $numeroPoliza     = $vehiculoNotificado->vetaopnumero;
                $nombreAsociado   = $vehiculoNotificado->nombreAsociado;
                $fechaVencimiento = $vehiculoNotificado->vetaopfechafinal;
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
                $vehiculocambioestado->vecaesobservacion = "Se ha suspendido el vehículo por falta de tarjeta de operación vigente";
                $vehiculocambioestado->save();

                $buscar           = Array('nombreAsociado', 'numeroPoliza', 'fechaVencimiento','placaVehiculo', 'numeroInterno', 'nombreGerente', 'tipoDocumentacion');
                $remplazo         = Array($nombreAsociado, $numeroPoliza, $fechaVencimiento, $placaVehiculo, $numeroInterno, $nombreGerente,'a la Tarjeta de Operación');
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de suspender vehiculo por falta de tarjeta de operacion en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(5);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el vehiculo por falta de tarjeta de operacion en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function levantarSancionVehiculo($esEjecucionManual = false)
    {
        $funcionesGenerales = new funcionesGenerales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechaSuspencion    = Carbon::now()->addDays(1)->toDateString();
        $fechaActual        = ($esEjecucionManual) ? $funcionesGenerales->consultarFechaProceso("LevantarSancionVehiculo") : $fechaHoraActual->format('Y-m-d');
        $estado             = 'S';
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionLevantamientoSuspension')->first();
            $empresa            = $funcionesGenerales->consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculosuspendido as vs')
                                        ->select('v.vehiid','vs.vehsusfechafinalsuspencion', 'vs.vehsusfechainicialsuspencion','vs.vehsusmotivo', 
                                            'v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereDate('vs.vehsusfechafinalsuspencion', '<', $fechaSuspencion)
                                        ->whereDate('vs.vehsusprocesada', false)
                                        ->where('v.tiesveid', 'S')
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vehiculos para levantar la suspencion en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($vehiculosNotificados as $vehiculoNotificado){

                $vehiid                = $vehiculoNotificado->vehiid;
                $placaVehiculo         = $vehiculoNotificado->vehiplaca;
                $motivoSuspencion      = $vehiculoNotificado->vehsusmotivo;
                $nombreAsociado        = $vehiculoNotificado->nombreAsociado;
                $numeroInterno         = $vehiculoNotificado->vehinumerointerno;
                $correoPersona         = $vehiculoNotificado->perscorreoelectronico;
                $fechaFinalSupencion   = $vehiculoNotificado->vehsusfechafinalsuspencion;
                $fechaInicialSupencion = $vehiculoNotificado->vehsusfechainicialsuspencion;

                $vehiculo           = Vehiculo::findOrFail($vehiid);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = 1;
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "La suspensión del vehículo ha sido levantada al cumplirse el plazo establecido";
                $vehiculocambioestado->save();

                $buscar           = Array('nombreAsociado', 'placaVehiculo', 'numeroInterno', 'nombreGerente', 'fechaInicialSupencion', 'fechaFinalSupencion', 'motivoSuspencion');
                $remplazo         = Array($nombreAsociado,  $placaVehiculo, $numeroInterno, $nombreGerente, $fechaInicialSupencion, $fechaFinalSupencion, $motivoSuspencion);
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar = $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);

                $mensaje       .= "Proceso de levantar suspensión del vehiculo al cumplirse el plazo establecido en la fecha ".$fechaActual."\r\n";
                $mensajeCorreo .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(6);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al levantar suspensión del vehiculo al cumplirse el plazo establecido en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }
}