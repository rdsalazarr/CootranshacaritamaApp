<?php

namespace App\Console\Commands;

use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Vehiculos\VehiculoContratoFirma;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Vehiculos\VehiculoContrato;
use Exception, Auth, DB, URL;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;

class Inicial
{
    public static function iniciar()
    {
        $mensaje = "Inicia procesos automaticos iniciales en la fecha ".Carbon::now()."\r\n";
        echo $mensaje;
        return $mensaje.'<br>';
    }

    public static function finalizar($mensaje)
    {
        $fechaHoraActual   = Carbon::now();
        $enviarEmail       = new notificar();
        $asunto            = 'Notificación de proceso automaticos inciciales realizado en la fecha '.$fechaHoraActual;
        $email             = 'radasa10@hotmail.com';
        //$enviarEmail->correo([$email], $asunto, $mensaje);

        echo"Notificación de proceso automaticos inciciales realizado en la fecha ".$fechaHoraActual.", y enviado al correo ".$email."\r\n";
    }

    public static function procesarAsignarContrato($esEjecucionManual = false)
    {
        $fechaHoraActual = Carbon::now();
        $notificar       = new notificar();
        $generales  	 = new generales(); 
        $fechaProceso    = $fechaHoraActual->format('Y-m-d');
        $mensaje         = "Iniciando proceso de asignacion de contratos de vehiculos en la fecha ".$fechaProceso."\r\n";
        $mensajeCorreo   = '';
        $success         = false;
        DB::beginTransaction();
		try {
            $representante               =  DB::table('empresa as e')->select('e.emprcorreo','p.persid','p.perscorreoelectronico',
                                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                                                ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                                ->where('emprid', '1')->first();

            $correoEmpresa                = $representante->emprcorreo;
            $nombreGerente                = $representante->nombreGerente;
            $correoGerente                = $representante->perscorreoelectronico;

            $fechaHoraActual              = Carbon::now();
            $anioActual                   = $fechaHoraActual->year;
            $fechaActual                  = $fechaHoraActual->format('Y-m-d');
            $fechaInicialContrato         = Carbon::parse($fechaActual);
            $fechaFinalContrato           = $fechaInicialContrato->copy()->addYear()->startOfYear()->addDays(4)->toDateString();
            $numeroContrato               = VehiculoContrato::obtenerConsecutivoContrato($anioActual);
            $estado                       = 'A';
            $vehiculos                    = DB::table('vehiculo as v')->select('v.vehiid','v.asocid','v.timoveid','a.persid')
                                                ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                                                ->where('v.tiesveid', 'A')
                                                ->where('v.vehiid', '1')
                                                ->get();

            foreach($vehiculos as $vehiculo){
                $vehiid                                  = $vehiculo->vehiid;
                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = 1;
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = 'Procedimiento para el registro inicial del contrato de vehículo';
                $vehiculocambioestado->save();

                $vehiculocontrato                     = new VehiculoContrato();
                $vehiculocontrato->asocid             = $vehiculo->asocid;
                $vehiculocontrato->vehiid             = $vehiid;
                $vehiculocontrato->persidgerente      = $representante->persid;
                $vehiculocontrato->vehconanio         = $anioActual;
                $vehiculocontrato->vehconnumero       = $numeroContrato;
                $vehiculocontrato->vehconfechainicial = $fechaInicialContrato;
                $vehiculocontrato->vehconfechafinal   = $fechaFinalContrato;
                $vehiculocontrato->vehconobservacion  = 'Se ha generado el contrato del vehículo por primera vez';
                $vehiculocontrato->save();

                $vehiculoContratoMaxConsecutio = VehiculoContrato::latest('vehconid')->first();
                $vehconid                      = $vehiculoContratoMaxConsecutio->vehconid;

                $tipoModalidadVehiculo   = DB::table('tipomodalidadvehiculo')->select('timovecuotasostenimiento')->where('timoveid', $vehiculo->timoveid)->first();
                $fechasCompromisos       = $generales->obtenerFechasCompromisoVehiculo($fechaInicialContrato);
                $valorMensualidadInicial = $generales->obtenerPrimerValorMensualidad($fechaInicialContrato, $tipoModalidadVehiculo->timovecuotasostenimiento);
                $valorCuotaSostenimiento = $tipoModalidadVehiculo->timovecuotasostenimiento;
                foreach($fechasCompromisos as $id => $fechaCompromiso){
                    $vehiculoresponsabilidad                             = new VehiculoResponsabilidad();
                    $vehiculoresponsabilidad->vehiid                     = $vehiid;
                    $vehiculoresponsabilidad->vehresfechacompromiso      = $fechaCompromiso;
                    $vehiculoresponsabilidad->vehresvalorresponsabilidad = $valorCuotaSostenimiento;
                    $vehiculoresponsabilidad->save();
                }

                $vehiculocontratofirma           = new VehiculoContratoFirma();
                $vehiculocontratofirma->vehconid = $vehconid;
                $vehiculocontratofirma->persid   = $representante->persid;
                $vehiculocontratofirma->save();

                //firma del asociado
                $vehiculocontratofirma           = new VehiculoContratoFirma();
                $vehiculocontratofirma->vehconid = $vehconid;
                $vehiculocontratofirma->persid   = $vehiculo->persid;
                $vehiculocontratofirma->save();
            }

            $success      = true;
            $mensajeVista = "Proceso de asignacion de contratos realizado con éxito";
            DB::commit();
        } catch (Exception $error){
            DB::rollback();
            $mensaje       = "Ocurrio un error en la asignacion de contratos en la fecha ".$fechaProceso.". Error =>".$error." \r\n";
            $mensajeCorreo = $mensaje.'<br>';
        }

        echo $esEjecucionManual ? '' : $mensaje;
        return $esEjecucionManual ? ['success' => $success, 'message' => $mensajeVista] : $mensajeCorreo.'<br>';
    }
}