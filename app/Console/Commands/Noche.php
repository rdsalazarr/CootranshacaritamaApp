<?php

namespace App\Console\Commands;

use App\Models\Vehiculos\VehiculoResponsabilidadPagoParcial;
use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Procesos\ProcesosAutomaticos;
use App\Console\Commands\FuncionesGenerales;
use App\Models\Caja\ComprobanteContable;
use App\Models\Caja\MovimientoCaja;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Console\Command;
use App\Util\generarPdf;
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
 
    public static function procesarPagoMensualidad($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
        $fechaActual     = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("ProcesarPagoMensualidad") : $fechaHoraActual->format('Y-m-d');
        $mensaje         =  "Iniciando proceso de liquidacion de la totalidad del compromiso del vehiculo en la fecha ".$fechaActual."\r\n";
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
                        $vehiculocambioestado->vecaesusuaid      = Auth::id();
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
                    $mensajeNotificar   = $notificar->correo([$correoAsociado], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                    $mensaje            .= "Proceso de notificacion de pago de mensualidad en la fecha  ".$fechaActual.", al correo ".$correoAsociado."\r\n";
                    $mensajeCorreo      .= $mensaje.'<br>';
                }
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(13);
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

    public static function cerrarMovimientoCaja($esEjecucionManual = false)
    {    
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales();
       // $fechaActual     = ($esEjecucionManual) ? FuncionesGenerales::consultarFechaProceso("ProcesarPagoMensualidad") : $fechaHoraActual->format('Y-m-d');
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de cierre de caja para la  fecha ".$fechaActual."\r\n";
        $mensajeCorreo   = '';
        $success         = false;   

        DB::beginTransaction();
		try {

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarPagoMensualidadCompletada')->first();
            $empresa            = FuncionesGenerales::consultarInfoEmpresa();
            $correoEmpresa      = $empresa->emprcorreo;
            $nombreGerente      = $empresa->nombreGerente;

            $searcComprobanteContables = DB::table('comprobantecontable')->select('comconid', 'usuaid','agenid','cajaid')->whereDate('comconestado', 'A')->get();
            foreach($searcComprobanteContables as $searcComprobanteContable){
                $comconid  = $searcComprobanteContable->comconid;
                $idUsuario = $searcComprobanteContable->usuaid;
                $agenciaId = $searcComprobanteContable->agenid;
                $cajaId    = $searcComprobanteContable->cajaid;
    
                //movimientocaja as mc
                $comprobanteContableId = DB::table('comprobantecontable as cc')
                                            ->select('cc.comconid', 'cc.movcajid', 'cc.comcondescripcion', 'a.agennombre', 'c.cajanumero','u.usuaalias',
                                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 
                                            DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                                            DB::raw("(SELECT movcajsaldoinicial from movimientocaja 
                                                            where date(movcajfechahoraapertura) = '$fechaActual' 
                                                            and usuaid = '$idUsuario' 
                                                            and cajaid = '$cajaId') as saldoInicial"),
                                            DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                                            DB::raw("(SELECT SUM(ccd.cocodemonto)
                                                    FROM comprobantecontabledetalle as ccd
                                                    INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
                                                    INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
                                                    INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
                                                    WHERE cc.cueconnaturaleza = 'D'
                                                    AND mc.usuaid = '$idUsuario'
                                                    AND mc.cajaid = '$cajaId'
                                                    AND cct.agenid = '$agenciaId'
                                                    AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'
                                                ) AS valorDebito"))
                                            ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                                            ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                                            ->join('usuario as u', 'u.usuaid', '=', 'cc.usuaid')
                                            ->whereDate('cc.comconfechahora', $fechaActual)
                                            ->where('cc.usuaid', $idUsuario)
                                            ->where('cc.agenid', $agenciaId)
                                            ->where('cc.cajaid', $cajaId)
                                            ->first();

                $nombreUsuario         = $comprobanteContableId->nombreUsuario;
                $correoUsuario         = $comprobanteContableId->usuaalias;
                $nuemeroComprobante    = $comprobanteContableId->numeroComprobante;
                $fechaComprobante      = $comprobanteContableId->fechaComprobante;
                $nombreAgencia         = $comprobanteContableId->agennombre;
                $numeroCaja            = $comprobanteContableId->cajanumero;
                $conceptoComprobante   = $comprobanteContableId->comcondescripcion;
                $saldoCajaCerrar       = $comprobanteContableId->saldoInicial + $comprobanteContableId->valorDebito;
                $movimientoCajaId      = $comprobanteContableId->movcajid;
                $comprobanteContableId = $comprobanteContableId->comconid;
    
                $comprobantecontable                        = ComprobanteContable::findOrFail($comprobanteContableId);
                $comprobantecontable->comconfechahoracierre = $fechaHoraActual;
                $comprobantecontable->comconestado          = 'C';
                //$comprobantecontable->save();
    
                $comprobanteContableDetalles = DB::table('comprobantecontabledetalle')->select('cocodeid')->whereDate('comconid', $comconid)->get();
                foreach($comprobanteContableDetalles as $comprobanteContableDetalleId){
                    $comprobantecontabledetalle                       = ComprobanteContableDetalle::findOrFail($comprobanteContableDetalleId->cocodeid);
                    $comprobantecontabledetalle->cocodecontabilizado = true;
                   // $comprobantecontabledetalle->save();
                }
    
                $movimientocaja                        = MovimientoCaja::findOrFail($movimientoCajaId);
                $movimientocaja->movcajfechahoracierre = $fechaHoraActual;
                $movimientocaja->movcajsaldofinal      = $saldoCajaCerrar;
               // $movimientocaja->save();
    
                $arrayDatos = [ 
                        "nombreUsuario"       => $nombreUsuario,
                        "nuemeroComprobante"  => $nuemeroComprobante,
                        "fechaComprobante"    => $fechaComprobante,
                        "nombreAgencia"       => $nombreAgencia,
                        "numeroCaja"          => $numeroCaja,
                        "conceptoComprobante" => $conceptoComprobante,
                        "mensajeImpresion"    => 'Documento impreso el dia '.$fechaHoraActual,
                        "metodo"              => 'F'
                    ];
    
                $generarPdf  = new generarPdf();
                $rutaPdf     = []; 
                $dataFactura = $generarPdf->generarComprobanteContable($arrayDatos, MovimientoCaja::obtenerMovimientosContablesPdf($fechaActual, $idUsuario, $agenciaId, $cajaId));
                array_push($rutaPdf, $dataFactura);    
    
                $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionCierreCajaAutomatico')->first();
                $buscar             = Array('nombreEmpleado', 'nombreGerente');
                $remplazo           = Array($nombreUsuario, $nombreGerente); 
                $innocoasunto       = $informacionCorreo->innocoasunto;
                $innococontenido    = $informacionCorreo->innococontenido;
                $enviarcopia        = $informacionCorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacionCorreo->innocoenviarpiepagina;
                $asunto             = str_replace($buscar, $remplazo, $innocoasunto);
                $msg                = str_replace($buscar, $remplazo, $innococontenido);
                $mensajeNotificar   = $notificar->correo([$correoUsuario], $asunto, $msg, [$rutaPdf], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                $mensaje            .= "Proceso de notificacion de pago de mensualidad en la fecha  ".$fechaActual.", al correo ".$correoUsuario."\r\n";
                $mensajeCorreo      .= $mensaje.'<br>';    
            }

            $procesoAutomatico                       = ProcesosAutomaticos::findOrFail(14);
            $procesoAutomatico->proautfechaejecucion = $fechaActual;
            $procesoAutomatico->save();

            $success  = true;
            //DB::commit();
            DB::rollback();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error al suspender el conductor por falta de licencia en la fecha ".$fechaActual."\r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensaje] : $mensajeCorreo.'<br>';
    }
}