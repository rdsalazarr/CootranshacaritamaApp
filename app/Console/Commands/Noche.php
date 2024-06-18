<?php

namespace App\Console\Commands;

use App\Models\Vehiculos\VehiculoResponsabilidadPagoParcial;
use App\Models\Despacho\PersonaServicioPuntosAcomulados;
use App\Models\Despacho\PersonaServicioFidelizacion;
use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Procesos\ProcesosAutomaticos;
use App\Console\Commands\FuncionesGenerales;
use App\Models\Caja\ComprobanteContable;
use App\Models\Despacho\PlanillaRuta;
use App\Models\Despacho\Encomienda;
use App\Models\Caja\MovimientoCaja;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Console\Command;
use App\Util\generarPdf;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;
use DB, Artisan;

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
 
    public static function procesarPagoMensualidad($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales(); 
        $fechaProceso    = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("ProcesarPagoMensualidad") : $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de liquidacion de la totalidad del compromiso del vehiculo en la fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;
        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarPagoMensualidadCompletada')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $pagosParciales = DB::table('vehiculoresponpagoparcial')
                                ->select('vehiid', DB::raw('SUM(vereppvalorpagado) as valorPagado'))
                                ->where('vereppprocesado', 0)
                                ->groupBy('vehiid')
                                ->get();
            $mensaje        .= (count($pagosParciales) === 0) ? "No existe pagos de mensualidad por procesar en la fecha ".$fechaProceso."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';                   
            foreach($pagosParciales as $pagoParcial){
                $vehiculoId  = $pagoParcial->vehiid;
                $valorPagado = $pagoParcial->valorPagado;

                $vehiculoResponsabilidad = DB::table('vehiculo as v')
                                            ->select('v.vehiid','vr.vehresid', 'tiesveid','tmv.timoveid', 'tmv.timovedescuentopagoanticipado', 
                                            'tmv.timoverecargomora', 'vr.vehresfechacompromiso','vr.vehresvalorresponsabilidad','p.perscorreoelectronico',
                                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                            ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                            ->join('vehiculoresponsabilidad as vr', 'vr.vehiid', '=', 'v.vehiid')
                                            ->join('vehiculocontrato as vc', 'vc.vehiid', '=', 'v.vehiid')
                                            ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                                            ->where('v.vehiid', $vehiculoId)
                                            ->orderBy('vehresid')->first();

                $vehresid             = $vehiculoResponsabilidad->vehresid;
                $recargoMora          = $vehiculoResponsabilidad->timoverecargomora;
                $descuentoAnticipado  = $vehiculoResponsabilidad->timovedescuentopagoanticipado;
                $valorResponsabilidad = $vehiculoResponsabilidad->vehresvalorresponsabilidad;
                $fechaCompromiso      = $vehiculoResponsabilidad->vehresfechacompromiso;
                $estadoVehiculo       = $vehiculoResponsabilidad->tiesveid;
                $nombreAsociado       = $vehiculoResponsabilidad->nombreAsociado;
                $correoAsociado       = $vehiculoResponsabilidad->perscorreoelectronico;

                if($valorPagado >= $valorResponsabilidad){

                    //Realizo el pago de la responsabilida
                    $resultadoMensualidad = $generales->calcularMensualidadVehiculo($fechaCompromiso, $valorResponsabilidad, $descuentoAnticipado, $recargoMora);
                    $valorMora            = $resultadoMensualidad['mora'];
                    $valorDesAnticipado   = $resultadoMensualidad['descuento'];
                    $totalAPagar          = $resultadoMensualidad['totalPagar'];
                    $saldoARegistrar      = $valorPagado - $totalAPagar;

                    $vehiculoresponsabilidad                    = VehiculoResponsabilidad::findOrFail($vehresid);
                    $vehiculoresponsabilidad->vehresfechapagado = $fechaHoraActual;
                    $vehiculoresponsabilidad->vehresvalorpagado = $totalAPagar;
                    $vehiculoresponsabilidad->vehresdescuento   = $valorDesAnticipado;
                    $vehiculoresponsabilidad->vehresinteresmora = $valorMora;
                    $vehiculoresponsabilidad->agenid            = 101; //Agencia principal
                    $vehiculoresponsabilidad->usuaid            = 1;
                    $vehiculoresponsabilidad->save();

                    $responsabilidadPagoParciales = DB::table('vehiculoresponpagoparcial')
                                                        ->select('vereppid')
                                                        ->where('vereppprocesado', 0)
                                                        ->where('vehiid', $vehiculoId)
                                                        ->get();

                    foreach($responsabilidadPagoParciales as $resPagoParcial){
                        $vehiculoResponPagoParcialUdate                  = VehiculoResponsabilidadPagoParcial::findOrFail($resPagoParcial->vereppid);
                        $vehiculoResponPagoParcialUdate->vereppprocesado = true;
                        $vehiculoResponPagoParcialUdate->save();
                    }

                    if($saldoARegistrar > 0){ //Registro el saldo
                        $vehiculoresponpagoparcial                    = new VehiculoResponsabilidadPagoParcial();
                        $vehiculoresponpagoparcial->vehiid            = $vehiculoId;
                        $vehiculoresponpagoparcial->agenid            = 101; //Agencia principal
                        $vehiculoresponpagoparcial->usuaid            = 1;
                        $vehiculoresponpagoparcial->vereppvalorpagado = $saldoARegistrar;
                        $vehiculoresponpagoparcial->vereppfechapagado = $fechaHoraActual;
                        $vehiculoresponpagoparcial->save();
                    }

                    if($estadoVehiculo === 'S'){//Suspendido
                        $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                        $vehiculo->tiesveid = 'A';
                        $vehiculo->save();

                        $vehiculocambioestado 					 = new VehiculoCambioEstado();
                        $vehiculocambioestado->vehiid            = $request->vehiculoId;
                        $vehiculocambioestado->tiesveid          = 'A';
                        $vehiculocambioestado->vecaesusuaid      = 1;
                        $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                        $vehiculocambioestado->vecaesobservacion = 'Se realiza el pago de la mensualidad con el saldo acomulado levantando la sanción';
                        $vehiculocambioestado->save();
                    }

                    //Notifico al asociado
                    $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarPagoMensualidadCompletada')->first();
                    $buscar             = Array('nombreAsociado', 'nombreGerente');
                    $remplazo           = Array($nombreAsociado, $nombreGerente); 
                    $innocoasunto       = $informacionCorreo->innocoasunto;
                    $innococontenido    = $informacionCorreo->innococontenido;
                    $enviarcopia        = $informacionCorreo->innocoenviarcopia;
                    $enviarpiepagina    = $informacionCorreo->innocoenviarpiepagina;
                    $asunto             = str_replace($buscar, $remplazo, $innocoasunto);
                    $msg                = str_replace($buscar, $remplazo, $innococontenido);
                    ($correoAsociado !== '') ? $notificar->correo([$correoAsociado], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                    $mensaje            .= "Proceso de notificacion de pago de mensualidad en la fecha  ".$fechaProceso.", al correo ".$correoAsociado."\r\n";
                    $mensajeCorreo      .= $mensaje.'<br>';
                }
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(13);
            $procesoAutomatico->proautfechaejecucion = $fechaProceso;
            $procesoAutomatico->save();

            $success      = true;
            $mensajeVista = "Proceso de pago de mensualidad realizado con éxito";
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al procesar el pago de mensualidad en la fecha ".$fechaProceso."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }

    public static function cerrarMovimientoCaja($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
        $fechaProceso    = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("CerrarMovimientoCaja") : $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de cierre de caja para la  fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;

        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarPagoMensualidadCompletada')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $searcComprobanteContables = DB::table('comprobantecontable')->select('comconid', 'usuaid','agenid','cajaid')->where('comconestado',  'A')->get();
            $mensaje                   .= (count($searcComprobanteContables) === 0) ? "No existe pagos de comprobantes abierto en la fecha ".$fechaProceso."\r\n" : '';
            $mensajeCorreo             .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($searcComprobanteContables as $searcComprobanteContable){
                $comconid  = $searcComprobanteContable->comconid;
                $idUsuario = $searcComprobanteContable->usuaid;
                $agenciaId = $searcComprobanteContable->agenid;
                $cajaId    = $searcComprobanteContable->cajaid;

                //movimientocaja as mc
                $comprobanteContableId = DB::table('comprobantecontable as cc')
                                            ->select('cc.comconid', 'cc.movcajid', 'u.usuaemail',
                                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),
                                            DB::raw("(SELECT movcajsaldoinicial from movimientocaja
                                                            where date(movcajfechahoraapertura) = '$fechaProceso' 
                                                            and usuaid = '$idUsuario'
                                                            and cajaid = '$cajaId') as saldoInicial"),
                                            DB::raw("(SELECT SUM(ccd.cocodemonto)
                                                    FROM comprobantecontabledetalle as ccd
                                                    INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
                                                    INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
                                                    INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
                                                    WHERE cc.cueconnaturaleza = 'D'
                                                    AND mc.usuaid = '$idUsuario'
                                                    AND mc.cajaid = '$cajaId'
                                                    AND cct.agenid = '$agenciaId'
                                                    AND DATE(mc.movcajfechahoraapertura) = '$fechaProceso'
                                                ) AS valorDebito"))
                                            ->join('usuario as u', 'u.usuaid', '=', 'cc.usuaid')
                                            ->whereDate('cc.comconfechahora', $fechaProceso)
                                            ->where('cc.usuaid', $idUsuario)
                                            ->where('cc.agenid', $agenciaId)
                                            ->where('cc.cajaid', $cajaId)
                                            ->first();

                $nombreUsuario         = $comprobanteContableId->nombreUsuario;
                $correoUsuario         = $comprobanteContableId->usuaemail;
                $saldoCajaCerrar       = $comprobanteContableId->saldoInicial + $comprobanteContableId->valorDebito;
                $movimientoCajaId      = $comprobanteContableId->movcajid;
                $comprobanteContableId = $comprobanteContableId->comconid;

                $comprobantecontable                        = ComprobanteContable::findOrFail($comprobanteContableId);
                $comprobantecontable->comconfechahoracierre = $fechaHoraActual;
                $comprobantecontable->comconestado          = 'C';
                $comprobantecontable->save();

                $comprobanteContableDetalles = DB::table('comprobantecontabledetalle')->select('cocodeid')->whereDate('comconid', $comconid)->get();
                foreach($comprobanteContableDetalles as $comprobanteContableDetalleId){
                    $comprobantecontabledetalle                       = ComprobanteContableDetalle::findOrFail($comprobanteContableDetalleId->cocodeid);
                    $comprobantecontabledetalle->cocodecontabilizado = true;
                    $comprobantecontabledetalle->save();
                }

                $movimientocaja                               = MovimientoCaja::findOrFail($movimientoCajaId);
                $movimientocaja->movcajfechahoracierre        = $fechaHoraActual;
                $movimientocaja->movcajsaldofinal             = $saldoCajaCerrar;
                $movimientocaja->movcajcerradoautomaticamente = true;
                $movimientocaja->save();

                $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionCierreCajaAutomatico')->first();
                $buscar             = Array('nombreEmpleado', 'nombreGerente');
                $remplazo           = Array($nombreUsuario, $nombreGerente); 
                $innocoasunto       = $informacionCorreo->innocoasunto;
                $innococontenido    = $informacionCorreo->innococontenido;
                $enviarcopia        = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacionCorreo->innocoenviarpiepagina;
                $asunto             = str_replace($buscar, $remplazo, $innocoasunto);
                $msg                = str_replace($buscar, $remplazo, $innococontenido);
                ($correoUsuario !== '') ? $notificar->correo([$correoUsuario], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;
                $mensaje            .= "Proceso de notificacion de pago de mensualidad en la fecha  ".$fechaProceso.", al correo ".$correoUsuario."\r\n";
                $mensajeCorreo      .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(14);
            $procesoAutomatico->proautfechaejecucion = $fechaProceso;
            $procesoAutomatico->save();

            $success      = true;
            $mensajeVista = "Proceso de notificación de cerrar movimiento de caja realizado con éxito";
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el conductor por falta de licencia en la fecha ".$fechaProceso."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }

    public static function marcarRecibidoPlanilla($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
        $fechaProceso    = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("MarcarRecibidoPlanilla") : $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de marcacion de planillas en la fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;
        $fechaProcesar   = Carbon::parse($fechaProceso)->subDays(5);

        DB::beginTransaction();
		try {

            $planillaRutas = DB::table('planillaruta')->select('plarutid', DB::raw("CONCAT(plarutanio,'',plarutconsecutivo) as numeroPlanilla"))
                                    ->whereDate('plarutfechahoraregistro', $fechaProcesar)
                                    ->whereNull('plarutfechallegadaaldestino')
                                    ->where('plarutdespachada', true)->get();

            $mensaje        .= (count($planillaRutas) === 0) ? "No existen planillas por marcar en la fecha ".$fechaProceso."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($planillaRutas as $dataPlanillaRuta){ 
                $planillaruta                              = PlanillaRuta::findOrFail($dataPlanillaRuta->plarutid);
                $planillaruta->plarutfechallegadaaldestino = $fechaHoraActual;
                $planillaruta->save();

                $mensaje          .= "Proceso de marcacion de planilla con numero ".$dataPlanillaRuta->numeroPlanilla."\r\n";
                $mensajeCorreo    .= $mensaje.'<br>';

                $encomiendas  = DB::table('encomienda')->select('encoid')->where('plarutid', $dataPlanillaRuta->plarutid)->get();
                foreach($encomiendas as $encomienda){

                    $encomienda           = Encomienda::findOrFail($encomienda->encoid);
                    $encomienda->tiesenid = 'D';
                    $encomienda->save();

                    $encomiendacambioestado 				   = new EncomiendaCambioEstado();
                    $encomiendacambioestado->encoid            = $encomienda->encoid;
                    $encomiendacambioestado->tiesenid          = 'D';
                    $encomiendacambioestado->encaesusuaid      = 1;
                    $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
                    $encomiendacambioestado->encaesobservacion = 'En terminal destino. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                    $encomiendacambioestado->save();

                    $mensaje          .= "Proceso de marcacion de encomienda con numero ". $encomienda->encoanio.''.$encomienda->encoconsecutivo."\r\n";
                    $mensajeCorreo    .= $mensaje.'<br>';
                }
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(15);
            $procesoAutomatico->proautfechaejecucion = $fechaProceso;
            $procesoAutomatico->save();

            $success      = true;
            $mensajeVista = "Proceso de marcacion de planilla realizado con éxito";
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al generar la marcacion de planilla en la fecha ".$fechaProceso."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }

    public static function marcarRedencionPuntos($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
        $fechaProceso    = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("MarcarRedencionPuntos") : $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de redención de puntos en la fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;
        $fechaProcesar   = Carbon::parse($fechaProceso)->subDays(5);

        DB::beginTransaction();
        try {

            $fidelizacioncliente = DB::table('fidelizacioncliente')->select('fidclipuntosminimoredimir' ,'fidclivalorpunto')->where('fidcliid', 1)->first();
            $puntosMinimoRedimir = $fidelizacioncliente->fidclipuntosminimoredimir;
            $valorPunto          = $fidelizacioncliente->fidclivalorpunto;
            $empresa             = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa       = $empresa->emprcorreo;
            $nombreGerente       = $empresa->nombreGerente;

            $personasFidelizaciones = DB::table('personaserviciofidelizacion as psf')
                                    ->select('psf.perserid', 'ps.persercorreoelectronico', 
                                        DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                                        ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombrePersonaServicio"),
                                        DB::raw('SUM(psf.pesefinumeropunto) as totalPuntos'))
                                    ->join('personaservicio as ps', 'ps.perserid', '=', 'psf.perserid')
                                    ->groupBy('psf.perserid','ps.persercorreoelectronico','nombrePersonaServicio')
                                    ->havingRaw('SUM(psf.pesefinumeropunto) >= ?', [$puntosMinimoRedimir])
                                    ->where('psf.pesefiredimido', false)
                                    ->get();

            $mensaje       .= (count($personasFidelizaciones) === 0) ? "No existen redención de puntos por marcar en la fecha ".$fechaProceso."\r\n" : '';
            $mensajeCorreo .= ($mensaje !== '') ? $mensaje.'<br>' : '';
            foreach($personasFidelizaciones as $personaFidelizacion){

                $correoPersona = $personaFidelizacion->persercorreoelectronico;
                $nombrePersona = $personaFidelizacion->nombrePersonaServicio;

                $personasSerFidelizaciones = DB::table('personaserviciofidelizacion')->select('pesefiid')->where('perserid', $personaFidelizacion->perserid)->where('pesefiredimido', false)->get();
                foreach($personasSerFidelizaciones  as $personaSerFidelizacion){
                    $personaserviciofidelizacion                          = personaServicioFidelizacion::findOrFail($personaSerFidelizacion->pesefiid);
                    $personaserviciofidelizacion->pesefifechahoraredimido = $fechaHoraActual;
                    $personaserviciofidelizacion->pesefiredimido          = true;
                    $personaserviciofidelizacion->save();
                }

                $valorRedimido                                   = $generales->redondearCienMasCercano($personaFidelizacion->totalPuntos * $valorPunto);
                $personaserpuntosacomulados                      = new PersonaServicioPuntosAcomulados();
                $personaserpuntosacomulados->perserid            = $personaFidelizacion->perserid;
                $personaserpuntosacomulados->pesepavalorredimido = $valorRedimido;
                $personaserpuntosacomulados->save();

                //Notificamos 
                $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionRedencionPuntos')->first();
                $buscar             = Array('nombrePersona', 'nombreGerente','fechaPuntos','valorRedimido');
                $remplazo           = Array($nombrePersona, $nombreGerente, $fechaProceso, number_format($valorRedimido,0,',','.') ); 
                $innocoasunto       = $informacionCorreo->innocoasunto;
                $innococontenido    = $informacionCorreo->innococontenido;
                $enviarcopia        = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacionCorreo->innocoenviarpiepagina;
                $asunto             = str_replace($buscar, $remplazo, $innocoasunto);
                $msg                = str_replace($buscar, $remplazo, $innococontenido);
               ($correoPersona !== '') ? $notificar->correo([$correoPersona], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina) : null;           
         
                $mensaje            .= "Proceso de redencion de punto para la persona ".$nombrePersona."\r\n";
                $mensajeCorreo      .= $mensaje.'<br>';
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(16);
            $procesoAutomatico->proautfechaejecucion = $fechaProceso;
            $procesoAutomatico->save();

            $success      = true;
            $mensajeVista = "Proceso de redención de punto realizado con éxito";
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al marcar la redención de punto en la fecha ".$fechaProceso."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }

    public static function crearBackup($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
        $fechaProceso    = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("CrearBackup") : $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de generacion de backup en la fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;

        DB::beginTransaction();
		try {
 
            $resultado = Artisan::call('backup:run');
            $mensaje  .= ($resultado === 0) ? "La copia de seguridad se realizó correctamente \r\n"
                                             : "Se produjo un error durante la copia de seguridad. Codigo de salida: ".$resultado."\r\n";

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(17);
            $procesoAutomatico->proautfechaejecucion = $fechaProceso;
            $procesoAutomatico->save();

            $success       = true;
            $mensajeVista  = "Proceso de notificación de generar backup realizado con éxito";
            $mensajeCorreo = '<br>'.$mensaje.'<br>';
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al generar backup en la fecha ".$fechaProceso."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }
}