<?php

namespace App\Console\Commands;

use App\Console\Commands\FuncionesGenerales;
use App\Models\Procesos\ProcesosAutomaticos;
use Illuminate\Console\Command;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB;

class Notificacion
{
    public static function iniciar()
    {
        $mensaje = "Inicia proceso de notificacion en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje.'<br>';
    }
 
    public static function vencimientoLicencias($esEjecucionManual = false)
    {
        $generales          = new generales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("NotificarVencimientoLicencia") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoLicencia' )->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $conductorNotificados = DB::table('conductorlicencia as cl')
                                        ->select('cl.conlicfechavencimiento', 'cl.conlicnumero','cl.conlicfechaexpedicion','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreConductor"))
                                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                        ->join('persona as p', 'p.persid', '=', 'c.persid')
                                        ->whereIn('cl.conlicfechavencimiento', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje        = (count($conductorNotificados) === 0) ? "No existen vencimiento de licencias para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
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
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de vencimiento de licencia envidada hoy ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(7);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje      = "Ocurrio un error al notificar vencimiento de licencia en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function vencimientoSoat($esEjecucionManual = false)
    {
        $generales          = new generales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("NotificarVencimientoVehiculosSoat") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoSoat')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculosoat as vs')
                                        ->select('vs.vehsoafechafinal', 'vs.vehsoanumero','vs.vehsoafechainicial','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereIn('vs.vehsoafechafinal', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vencimiento de SOAT para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
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
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de vencimiento de SOAT envidada hoy ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(8);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al notificar vencimiento de SOAT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function vencimientoCRT($esEjecucionManual = false)
    {
        $generales          = new generales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("NotificarVencimientoVehiculosCRT") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoCRT')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculocrt as vcrt')
                                        ->select('vcrt.vehcrtfechafinal', 'vcrt.vehcrtnumero','vcrt.vehcrtfechainicial','p.perscorreoelectronico',
                                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vcrt.vehiid')
                                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->whereIn('vcrt.vehcrtfechafinal', $fechasNotificacion)
                                        ->whereNotNull('p.perscorreoelectronico')
                                        ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vencimiento de CRT para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
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
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de vencimiento de CRT envidada hoy ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(9);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al notificar vencimiento de CRT en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function vencimientoPolizas($esEjecucionManual = false)
    {
        $generales          = new generales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("NotificarVencimientoVehiculosPolizas") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoPolizas')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculopoliza as vp')
                                    ->select('vp.vehpolfechafinal', 'vp.vehpolnumeropolizacontractual','vp.vehpolnumeropolizaextcontrac','vp.vehpolfechainicial','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                    ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->whereIn('vp.vehpolfechafinal', $fechasNotificacion)
                                    ->whereNotNull('p.perscorreoelectronico')
                                    ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vencimiento de polizas para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
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
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de vencimiento de polizas envidada hoy ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(10);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al notificar vencimiento de poliza en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function vencimientoTarjetaOperacion($esEjecucionManual = false)
    {
        $generales          = new generales();
        $notificar          = new notificar();
        $fechaHoraActual    = Carbon::now();
        $fechasNotificacion = $generales->definirRangoNotificacion();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("NotificarVencimientoVehiculosTarjetaOperacion") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();
        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoTarjetaOperacion')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $vehiculosNotificados = DB::table('vehiculotarjetaoperacion as vto')
                                    ->select('vto.vetaopfechainicial', 'vto.vetaopnumero','vto.vetaopfechafinal','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                                    ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->whereIn('vto.vetaopfechafinal', $fechasNotificacion)
                                    ->whereNotNull('p.perscorreoelectronico')
                                    ->get();

            $mensaje        = (count($vehiculosNotificados) === 0) ? "No existen vencimiento de tarjeta de operacion para notificar en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
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
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de vencimiento de tarjeta de operacion envidada hoy ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(11);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al notificar vencimiento de tarjeta de operacion en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }

    public static function cuotasCreditos($esEjecucionManual = false)
    {
        $generales          = new generales();
        $fechasNotificacion = [
                                Carbon::now()->toDateString(),
                                Carbon::now()->subDays(2)->toDateString(),
                                Carbon::now()->subDays(5)->toDateString(),
                                Carbon::now()->subDays(10)->toDateString(),
                                Carbon::now()->subDays(20)->toDateString(),
                                Carbon::now()->subDays(30)->toDateString(),
                                Carbon::now()->subDays(50)->toDateString(),
                                Carbon::now()->subDays(60)->toDateString(),
                            ];

        $fechaHoraActual    = Carbon::now();
        $fechaActual        = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("VencimientoCuotasCreditos") : $fechaHoraActual->format('Y-m-d');
        $mensaje            = '';
        $mensajeCorreo      = '';
        $success            = false;
        DB::beginTransaction();

        try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarVencimientoCuotaCredito')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $colocacionLiquidaciones = DB::table('colocacionliquidacion as cl')
                                    ->select('cl.colliqfechavencimiento', 'cl.colliqnumerocuota','c.colofechacolocacion', 'p.perscorreoelectronico',
                                    DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"), DB::raw("DATEDIFF(NOW(), c.colofechacolocacion) as diasMora"),                                    
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                    ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                                    ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                    ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                    ->whereIn('cl.colliqfechavencimiento', $fechasNotificacion)
                                    ->whereNull('cl.colliqfechapago')
                                    ->whereNotNull('p.perscorreoelectronico')
                                    ->get();

            $mensaje        = (count($colocacionLiquidaciones) === 0) ? "No existen cuotas de créditos vencidas en la fecha ".$fechaActual."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($colocacionLiquidaciones as $colocacionLiquidacion){
                $diasMora         = $colocacionLiquidacion->diasMora;
                $nombreAsociado   = $colocacionLiquidacion->nombreAsociado;
                $numeroCredito    = $colocacionLiquidacion->numeroColocacion;
                $numeroCuota      = $colocacionLiquidacion->colliqnumerocuota;
                $fechaPrestamo    = $colocacionLiquidacion->colofechacolocacion;
                $correoPersona    = $colocacionLiquidacion->perscorreoelectronico;
                $fechaVencimiento = $colocacionLiquidacion->colliqfechavencimiento;

                $buscar           = Array('nombreAsociado', 'numeroCredito', 'fechaPrestamo','numeroCuota', 'fechaVencimiento', 'diasMora');
                $remplazo         = Array($nombreAsociado, $numeroCredito, $fechaPrestamo, $numeroCuota, $fechaVencimiento, $diasMora); 
                $innocoasunto     = $informacionCorreo->innocoasunto;
                $innococontenido  = $informacionCorreo->innococontenido;
                $enviarcopia      = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina  = $informacionCorreo->innocoenviarpiepagina;
                $asunto           = str_replace($buscar, $remplazo, $innocoasunto);
                $msg              = str_replace($buscar, $remplazo, $innococontenido);
                ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje          .= "Proceso de notificacion de cuotas de creditos vencida en la fecha ".$fechaActual.", al correo ".$correoPersona."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(12);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success = true;
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al notificar vencimiento de cuotas de creditos vencida en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }
}