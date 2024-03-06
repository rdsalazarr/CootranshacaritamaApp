<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Vehiculos\VehiculoResponsabilidadPagoParcial;
use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Cartera\ColocacionCambioEstado;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Caja\ComprobanteContable;
use App\Models\Asociado\AsociadoSancion;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use App\Models\Caja\CuentaContable;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Cartera\Colocacion;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;

class ProcesarMovimientoController extends Controller
{
    public function index()
    {
        try{
            $cajaId          = auth()->user()->cajaid;
            $nombreUsuario   = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');
            $caja            = DB::table('caja')->select('cajanumero')->where('cajaid', $cajaId)->first();
            $cajaNumero      = ($caja) ? $caja->cajanumero : '';
            $data            = DB::table('movimientocaja')->select(DB::raw('COALESCE(movcajsaldofinal, 0) as movcajsaldofinal'))
                                        ->whereNull('movcajsaldofinal')
                                        ->whereDate('movcajfechahoraapertura', $fechaActual)
                                        ->where('usuaid', Auth::id())
                                        ->where('cajaid', $cajaId)->first();

            $ultimoSaldo     = DB::table('movimientocaja')->select(DB::raw('CAST(COALESCE(movcajsaldofinal, 0) AS UNSIGNED) as movcajsaldofinal'))
                                        ->where('usuaid', Auth::id())
                                        ->where('cajaid', $cajaId)
                                        ->orderBy('movcajid', 'desc')
                                        ->first();

            $saldoAnterior   = ($ultimoSaldo) ? $ultimoSaldo->movcajsaldofinal  : null;

            return response()->json(['success'   => true,        "data"    => $data,   "saldoAnterior" => $saldoAnterior, 
                                    "cajaNumero" => $cajaNumero, "cajaId" => $cajaId, "nombreUsuario" => $nombreUsuario ]);                           
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function abrirDia(Request $request)
	{
        $this->validate(request(),['saldoInicial' => 'required|numeric|between:1,99999999']);

        $fechaHoraActual = Carbon::now();
        $anioActual      = $fechaHoraActual->year;
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $nombreUsuario   = mb_strtoupper(auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos,'UTF-8');
        $movimientocaja  = DB::table('movimientocaja')->select('movcajsaldofinal')
                                    ->whereDate('movcajfechahoraapertura', $fechaActual)
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', auth()->user()->cajaid)->first();
        if ($movimientocaja){
            $message         = 'No es posible abrir la caja para esta fecha, ya existe un registro previo. ';
            $message         .= ' Por favor, contacte al administrador del sistema y solicite la apertura de su caja nuevamente';
            return response()->json(['success' => false, 'message'=> $message]);
        } 

        $caja                   = DB::table('caja')->select('cajanumero')->where('cajaid', auth()->user()->cajaid)->first();
        $cajaNumero             = ($caja) ? $caja->cajanumero : '';
        $descripcionComprobante = 'Registro de movimientos financieros correspondientes a la apertura y cierre de caja número '.$cajaNumero;
        $descripcionComprobante .= ', así como ingresos y egresos realizados por el usuario '.$nombreUsuario.' durante el año '.$anioActual.'. ';
        $descripcionComprobante .= 'Este comprobante contable detalla las transacciones efectuadas, garantizando la transparencia y ';
        $descripcionComprobante .= 'trazabilidad de las operaciones financieras del usuario en el sistema';

        DB::beginTransaction();
        try {

            $movimientocaja                          = new MovimientoCaja();
            $movimientocaja->usuaid                  = Auth::id();
            $movimientocaja->cajaid                  = auth()->user()->cajaid;
            $movimientocaja->movcajfechahoraapertura = $fechaHoraActual;
            $movimientocaja->movcajsaldoinicial      = $request->saldoInicial;
            $movimientocaja->save();

            $movimientoCajaConsecutivo              = MovimientoCaja::latest('movcajid')->first();
			$movcajid                               = $movimientoCajaConsecutivo->movcajid;

            $comprobantecontable                    = new ComprobanteContable();
            $comprobantecontable->movcajid          = $movcajid;
            $comprobantecontable->usuaid            = Auth::id();
            $comprobantecontable->cajaid            = auth()->user()->cajaid;
            $comprobantecontable->agenid            = auth()->user()->agenid;
            $comprobantecontable->comconanio        = $anioActual;
            $comprobantecontable->comconconsecutivo = ComprobanteContable::obtenerConsecutivo($anioActual);
            $comprobantecontable->comconfechahora   = $fechaHoraActual;
            $comprobantecontable->comcondescripcion = $descripcionComprobante;
            $comprobantecontable->save();

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Caja abierta exitosamente']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function listVehiculos(){
        try{
            $vehiculos = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                    ->whereIn('v.tiesveid', ['A','S'])
                                    ->orderBy('v.vehinumerointerno')->get();

            return response()->json(['success' => true, "vehiculos" => $vehiculos]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarVehiculo(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric']);

        try{
            $generales       = new generales();
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');
            $vehiculo        = DB::table('vehiculo as v')
                                ->select('v.vehiid', 'tmv.timoveid', 'tmv.timovecuotasostenimiento', 'tmv.timovedescuentopagoanticipado', 'tmv.timoverecargomora',
                                DB::raw('(SELECT SUM(vereppvalorpagado) FROM vehiculoresponpagoparcial WHERE vehiid = v.vehiid AND vereppprocesado = 0) AS totalAbono'))
                                ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                ->where('v.vehiid', $request->vehiculoId)->first();

            $vehiculoResponsabilidades = DB::table('vehiculoresponsabilidad')->select('vehresid','vehresfechacompromiso','vehresvalorresponsabilidad')
                                        ->whereNull('vehresvalorpagado')
                                        ->where('vehiid', $request->vehiculoId)
                                        ->orderBy('vehresid')->get();

            $pagoMensualidad        = [];
            $pagoTotal              = [];
            $valorMora              = 0;
            $valorDesAnticipado     = 0;
            $totalAPagar            = 0;
            $fechaCompromisoInicial = '';
            $mensajeError           = 'No se ha encontrado información de liquidación de mensualidad para el vehículo seleccionado. ';
            $mensajeError           .= 'Por favor, asegúrese de que la información esté disponible o consulte con el administrador del sistema';

            if(count($vehiculoResponsabilidades) > 0){
                $descuentoAnticipado  = $vehiculo->timovedescuentopagoanticipado;
                $recargoMora          = $vehiculo->timoverecargomora;
                $recargoMora          = $vehiculo->timoverecargomora;
                $totalAbono           = $vehiculo->totalAbono;

                foreach($vehiculoResponsabilidades as $key => $vehiculoResponsabilidad){
                    $fechaCompromiso      = $vehiculoResponsabilidad->vehresfechacompromiso;
                    $valorResponsabilidad = $vehiculoResponsabilidad->vehresvalorresponsabilidad;
                    $resultadoMensualidad = $generales->calcularMensualidadVehiculo($fechaCompromiso, $valorResponsabilidad, $descuentoAnticipado, $recargoMora);
                    $valorMora            += $resultadoMensualidad['mora'];
                    $valorDesAnticipado   += $resultadoMensualidad['descuento'];
                    $totalAPagar          += $resultadoMensualidad['totalPagar'];

                    if($key === 0)  {
                        $fechaCompromisoInicial   = $fechaCompromiso;
                        $mensualiad = [
                            'idResponsabilidad'   => $vehiculoResponsabilidad->vehresid,
                            'fechaCompromiso'     => $vehiculoResponsabilidad->vehresfechacompromiso,
                            'valorAPagarMostrar'  => number_format($valorResponsabilidad,0,',','.'),
                            'interesMoraMostrar'  => number_format($valorMora,0,',','.'),
                            'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                            'totalAPagarMostrar'  => number_format($totalAPagar,0,',','.'),
                            'totalAbono'          => number_format($totalAbono,0,',','.'),  
                            'valorAPagar'         => $valorResponsabilidad,
                            'valorDesAnticipado'  => $valorDesAnticipado,
                            'interesMora'         => $valorMora,
                            'totalAPagar'         => $totalAPagar       
                        ];
                        array_push($pagoMensualidad, $mensualiad);
                    }
                }

                $totalizado = [
                    'idResponsabilidad'   => '',
                    'fechaCompromiso'     => $fechaCompromisoInicial,
                    'valorAPagarMostrar'  => number_format($valorResponsabilidad,0,',','.'),
                    'interesMoraMostrar'  => number_format($valorMora,0,',','.'),
                    'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                    'totalAPagarMostrar'  => number_format($totalAPagar,0,',','.'),
                    'totalAbono'          => number_format($totalAbono,0,',','.'),  
                    'valorAPagar'         => $valorResponsabilidad,
                    'interesMora'         => $valorMora,
                    'interesMora'         => $valorMora,
                    'totalAPagar'         => $totalAPagar
                ];
                array_push($pagoTotal, $totalizado);
            }

        return response()->json(['success' => (count($vehiculoResponsabilidades) > 0) ? true : false, 'message' => $mensajeError,
                                 "pagoMensualidad" => $pagoMensualidad, "pagoTotal" => $pagoTotal]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salveMensualidad(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric', 'totalAPagar' => 'required', 'formaPago' => 'required']); 

        DB::beginTransaction();
        try {
            $generales          = new generales();
            $fechaHoraActual    = Carbon::now();
            $fechaActual        = $fechaHoraActual->format('Y-m-d');
            $totalAPagarMensual = $request->totalAPagar;
            $valorAPagar        = $request->valorAPagar;
            $agenciaId          = auth()->user()->agenid;
            $usuarioId          = Auth::id();
            $totalAPagar        = $totalAPagarMensual;
            $vehiculo           = DB::table('vehiculo as v')
                                        ->select('v.tiesveid','tmv.timovedescuentopagoanticipado', 'tmv.timoverecargomora',
                                        DB::raw('(SELECT SUM(vereppvalorpagado) FROM vehiculoresponpagoparcial WHERE vehiid = v.vehiid AND vereppprocesado = 0) AS totalAbono'))
                                        ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                        ->where('v.vehiid', $request->vehiculoId)->first();
            $descuentoAnticipado  = $vehiculo->timovedescuentopagoanticipado;
            $recargoMora          = $vehiculo->timoverecargomora;
            $estadoVehiculo       = $vehiculo->tiesveid;
            $totalAbono           = $vehiculo->totalAbono;
            $debeCambiarEstado    = (($totalAbono + $totalAPagar ) >= $valorAPagar ) ? true : false;

            //Pago parcial
            if($request->formaPago === 'P'){
                $cuentaContableId                             = CuentaContable::consultarId('cxpPagoMensualidadParcial');
                $mensajeFactura                               = 'FACTURA DE PAGO MENSUALIDAD PARCIAL';
                $vehiculoresponpagoparcial                    = new VehiculoResponsabilidadPagoParcial();
                $vehiculoresponpagoparcial->vehiid            = $request->vehiculoId;
                $vehiculoresponpagoparcial->agenid            = $agenciaId;
                $vehiculoresponpagoparcial->usuaid            = $usuarioId;
                $vehiculoresponpagoparcial->vereppvalorpagado = $totalAPagarMensual;
                $vehiculoresponpagoparcial->vereppfechapagado = $fechaHoraActual;
                $vehiculoresponpagoparcial->save();
            }

            //Pago mensual
            if($request->formaPago === 'M' ){
                $mensajeFactura                             = 'FACTURA DE PAGO MENSUALIDAD'; 
                $cuentaContableId                           = CuentaContable::consultarId('cxpPagoMensualidad');
                $vehiculoresponsabilidad                    = VehiculoResponsabilidad::findOrFail($request->idResponsabilidad);
                $vehiculoresponsabilidad->vehresfechapagado = $fechaHoraActual;
                $vehiculoresponsabilidad->vehresvalorpagado = $totalAPagarMensual;
                $vehiculoresponsabilidad->vehresdescuento   = $request->valorDesAnticipado;
                $vehiculoresponsabilidad->vehresinteresmora = $request->interesMora;
                $vehiculoresponsabilidad->agenid            = $agenciaId;
                $vehiculoresponsabilidad->usuaid            = $usuarioId;
                $vehiculoresponsabilidad->save();
            }
            
            //Pago total
            if($request->formaPago === 'T'){
                $cuentaContableId = CuentaContable::consultarId('cxpPagoMensualidadTotal');
                $totalAPagar      = 0;
                $mensajeFactura   = 'FACTURA DE PAGO MENSUALIDAD TOTAL';              

                $vehiculoResponsabilidades = DB::table('vehiculoresponsabilidad')->select('vehresid','vehresfechacompromiso','vehresvalorresponsabilidad')
                                            ->whereNull('vehresvalorpagado')
                                            ->where('vehiid', $request->vehiculoId)
                                            ->orderBy('vehresid')->get();

                foreach($vehiculoResponsabilidades as $vehiculoResponsabilidad){
                    
                    $fechaCompromiso      = $vehiculoResponsabilidad->vehresfechacompromiso;
                    $valorResponsabilidad = $vehiculoResponsabilidad->vehresvalorresponsabilidad;
                    $resultadoMensualidad = $generales->calcularMensualidadVehiculo($fechaCompromiso, $valorResponsabilidad, $descuentoAnticipado, $recargoMora);
                    $totalAPagar          += $resultadoMensualidad['totalPagar'];

                    $vehiculoresponsabilidad                    = VehiculoResponsabilidad::findOrFail($vehiculoResponsabilidad->vehresid);
                    $vehiculoresponsabilidad->vehresfechapagado = $fechaHoraActual;
                    $vehiculoresponsabilidad->vehresvalorpagado = $resultadoMensualidad['totalPagar'];
                    $vehiculoresponsabilidad->vehresdescuento   = $resultadoMensualidad['descuento'];
                    $vehiculoresponsabilidad->vehresinteresmora = $resultadoMensualidad['mora'];
                    $vehiculoresponsabilidad->agenid            = $agenciaId;
                    $vehiculoresponsabilidad->usuaid            = $usuarioId;
                    $vehiculoresponsabilidad->save();
                }
            }

            if($debeCambiarEstado and $estadoVehiculo === 'S'){//Suspendido
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = 'A';
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = 'A';
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = 'Se realiza el pago de la mensualidad levantando la sanción. Este procedimiento fue llevado a cabo por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $vehiculocambioestado->save();
            }

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = $cuentaContableId;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $vehiculocontrato  = DB::table('vehiculocontrato as vc')
                                    ->select('p.persdocumento', 'p.persdireccion', 'p.persnumerocelular',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                                    ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('vc.vehiid', $request->vehiculoId)->first();

            $agencia          = $this->consultarAgencia($agenciaId);

            $arrayDatos   = [
                "fechaPago"         => $fechaHoraActual,
                "valorPago"         => number_format($valorAPagar, 0,',','.'),
                "descuentoPago"     => ($request->formaPago !== 'P') ? number_format($request->valorDesAnticipado, 0,',','.') : 0,
                "interesCorriente"  => 0,
                "interesMora"       => ($request->formaPago !== 'P') ? number_format($request->interesMora, 0,',','.') : 0,
                "valorTotalPago"    => number_format($totalAPagar, 0,',','.'),
                "documentoCliente"  => $vehiculocontrato->persdocumento,
                "nombreCliente"     => $vehiculocontrato->nombreAsociado,
                "direccionCliente"  => $vehiculocontrato->persdireccion,
                "telefonoCliente"   => $vehiculocontrato->persnumerocelular,
                "usuarioElabora"    => $agencia->nombreUsuario,
                "nombreAgencia"     => $agencia->agennombre,
                "direccionAgencia"  => $agencia->agendireccion,
                "telefonoAgencia"   => $agencia->telefonoAgencia,
                "mensajePlanilla"   => 'Gracias por su pago',
                "tituloFactura"     => $mensajeFactura,
                "metodo"            => 'S'
            ];

            $generarPdf  = new generarPdf();
            $dataFactura = $generarPdf->facturaPagoMensualidad($arrayDatos);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', "dataFactura" => $dataFactura ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function tipoDocumentos(){
        try{
            $tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')
                                        ->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();

            return response()->json(['success' => true, "tipoIdentificaciones" => $tipoIdentificaciones]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarCredito(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        try{
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $colocaciones = DB::table('colocacion as c')
                                ->select('c.coloid', 'lc.lincrenombre', 'c.colofechacolocacion', 'Q.colliqfechavencimiento','Q.colliqid',
                                    DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                    DB::raw("CONCAT('$ ', FORMAT(c.colovalordesembolsado, 0)) as valorDesembolsado"),
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid') 
                                ->join(DB::raw("(SELECT colliqid, coloid, colliqfechavencimiento 
                                                FROM colocacionliquidacion
                                                WHERE colliqfechapago IS NULL order by colliqid limit 1 ) as Q"), 
                                                function ($join) {
                                                    $join->on('Q.coloid', '=', 'c.coloid');
                                                })
                                ->whereIn('c.tiesclid', ['V', 'J', 'R'])
                                ->where('p.tipideid', $request->tipoIdentificacion)
                                ->where('p.persdocumento', $request->documento)
                                ->orderBy('c.coloid', 'asc')
                                ->get();

            $success        = (count($colocaciones) > 0) ? true : false;

            return response()->json(['success' => $success, 'message' => 'Lo siento, no se encontraron registros con la información proporcionada', 
                                     "datosEncontrado" => $success,  "creditoAsociados" => ($success) ? $colocaciones : []]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function calcularCuota(Request $request)
	{
        $this->validate(request(),['colocacionId' => 'required|numeric','liquidacionId' => 'required|numeric']);

        try{
            $generales             = new generales();
            $pagoMensualidad       = [];
            $pagoTotal             = [];
            $valorInteresesTotal   = 0;
            $valorInteresMoraTotal = 0;
            $totalAPagarTotal      = 0;
            $interesMensualTotal   = 0;

            $colocacion = DB::table('colocacionliquidacion as cl')
                            ->select('cl.coloid','cl.colliqvalorcuota', 'cl.colliqfechavencimiento', 'cl.colliqnumerocuota', 'c.colofechacolocacion',
                                'c.colotasa','c.colonumerocuota','c.colovalordesembolsado','lc.lincreinteresmora', DB::raw('DATE(c.colofechahoradesembolso) as fechaDesembolso'),
                                DB::raw("(SELECT SUM(cl1.colliqvalorcapitalpagado) FROM colocacionliquidacion as cl1 WHERE cl1.coloid = c.coloid AND cl.colliqfechapago is null) AS capitalPagado"))
                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->where('c.coloid', $request->colocacionId)
                            ->whereNull('cl.colliqfechapago')
                            ->first();

            //Pago de la cuota
            $montoPrestamo         = $colocacion->colovalordesembolsado;
            $tasaInteresMensual    = $colocacion->colotasa;
            $plazo                 = $colocacion->colonumerocuota;
            $fechaVencimiento      = $colocacion->colliqfechavencimiento;
            $valorCuota            = $colocacion->colliqvalorcuota;
            $fechaDesembolso       = $colocacion->fechaDesembolso;
            $fechaColocacion       = $colocacion->colofechacolocacion;
            $interesMora           = $colocacion->lincreinteresmora;
            $saldoColocacion       = ($colocacion->capitalPagado !== null) ? ($montoPrestamo - $colocacion->capitalPagado ): $montoPrestamo;
            $numeroDiasCambioFecha = ($colocacion->colliqnumerocuota === '1') ? $generales->calcularDiasCambiosFechaDesembolso($fechaDesembolso, $fechaColocacion) : 0;
            $arrayInteresMensual   = $generales->calcularValorInteresDiario($montoPrestamo, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha);
            $valorIntereses        = $arrayInteresMensual['valorIntereses'];
            $valorInteresMora      = $arrayInteresMensual['valorInteresMora'];
            $interesDevuelto       = $arrayInteresMensual['valorInteresDevuelto'];
            $interesMensualTotal   = $arrayInteresMensual['interesMensualTotal'];
            $totalAPagar           = ($valorCuota + $valorInteresMora ) - $interesDevuelto;

            $mensualiad = [
                            'fechaCuota'           => $fechaVencimiento,
                            'valorCuota'           => $valorCuota,
                            'totalAPagar'          => $totalAPagar,
                            'valorIntereses'       => $valorIntereses,
                            'interesMensualTotal'  => $interesMensualTotal,
                            'valorInteresMora'     => $valorInteresMora,
                            'valorInteresDevuelto' => $interesDevuelto
                        ];
            array_push($pagoMensualidad, $mensualiad);

            $totalPago = [
                        'fechaCuota'           => $fechaVencimiento,
                        'valorCuota'           => $valorCuota,
                        'totalAPagar'          => $saldoColocacion - $interesMensualTotal,
                        'valorIntereses'       => $valorIntereses,
                        'valorInteresMora'     => $valorInteresMora,
                        'interesMensualTotal'  => $interesMensualTotal,
                        'valorInteresDevuelto' => $interesDevuelto
                    ];
            array_push($pagoTotal, $totalPago);

            return response()->json(['success' => true, "pagoMensualidad" => $pagoMensualidad, "pagoTotal" => $pagoTotal]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salvePagoCredito(Request $request)
	{
        $this->validate(request(),['colocacionId' => 'required|numeric','liquidacionId' => 'required|numeric', 'formaPago' => 'required']);

        DB::beginTransaction();
        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
            $comprobanteContableId = ComprobanteContable::obtenerId($fechaActual);
            $agenciaId             = auth()->user()->agenid;
            $estadoColocacion      = 'S';
            $valorPagado           = 0;
            $colocacion = DB::table('colocacionliquidacion as cl')
                            ->select('cl.coloid', 'cl.colliqvalorcuota','cl.colliqfechavencimiento', 'lc.lincreinteresmora','c.colotasa', 'c.colovalordesembolsado','p.persdocumento','p.persdireccion', 'p.persnumerocelular',                           
                            DB::raw("(CASE WHEN cl.colliqfechavencimiento < CURDATE() THEN 'SI' ELSE 'NO' END) AS cuotaVencida"),
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"),
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion WHERE colliqid = cl.colliqid AND colliqfechapago IS NULL) AS totalCuotasPorPagar'),
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion WHERE colliqid = cl.colliqid AND colliqfechapago IS NOT NULL) AS totalCuotasPagadas'),
                            DB::raw('(SELECT SUM(cl3.colliqvalorcapitalpagado) FROM colocacionliquidacion as cl3 WHERE cl3.coloid = cl.coloid AND cl3.colliqfechapago IS NOT NULL) AS capitalPagado'))
                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->join('persona as p', 'p.persid', '=', 'sc.persid')
                            ->where('cl.coloid', $request->colocacionId)
                            ->where('cl.colliqid', $request->liquidacionId)
                            ->first();

            $valorDesembolsado       = intval($colocacion->colovalordesembolsado);
            $tasaInteresMensual      = $colocacion->colotasa;
            $fechaVencimiento        = $colocacion->colliqfechavencimiento;
            $cuotaVencida            = $colocacion->cuotaVencida;
            $interesMora             = $colocacion->lincreinteresmora;
            $saldoColocacion         = ($colocacion->capitalPagado !== null) ? ($valorDesembolsado - $colocacion->capitalPagado ) : $colocacion->colovalordesembolsado;
            $cambiarEstadoColocacion = ($colocacion->totalCuotasPorPagar + 1 === $colocacion->totalCuotasPagadas) ? true : false;
            $valorCuota              = $colocacion->colliqvalorcuota;
            $interesCobrados         = $request->interesCorrienteTotal;
            $valorInteresMora        = $request->interesMora;
            $interesDevuelto         = $request->interesDevuelto;
            $valorPagado             = $request->totalAPagar;
            $abonoCapital            = round($valorCuota - $interesCobrados, 0);
            $saldoCapital            = $saldoColocacion - $abonoCapital;

            if($request->formaPago === 'M'){//Pago mensual
                $cuentaContableId                                  = CuentaContable::consultarId('cxpPagoCuotaCredito');
                $mensajeFactura                                    = 'FACTURA DE PAGO CRÉDITO';
                $colocacionliquidacion 				               = ColocacionLiquidacion::findOrFail($request->liquidacionId);
                $colocacionliquidacion->colliqfechapago            = $fechaActual;
                $colocacionliquidacion->comconid                   = $comprobanteContableId;
                $colocacionliquidacion->colliqvalorpagado          = $valorPagado;
                $colocacionliquidacion->colliqsaldocapital         = $saldoCapital;
                $colocacionliquidacion->colliqvalorcapitalpagado   = $abonoCapital;
                $colocacionliquidacion->colliqvalorinterespagado   = $interesCobrados;
                $colocacionliquidacion->colliqvalorinteresmora     = $valorInteresMora;
                $colocacionliquidacion->colliqvalorinteresdevuelto = $interesDevuelto;
                $colocacionliquidacion->save();
            }

            if($request->formaPago === 'T'){//Pago total
                $mensajeFactura          = 'FACTURA DE PAGO CRÉDITO TOTAL';
                $cambiarEstadoColocacion = true;
                $cuentaContableId        = CuentaContable::consultarId('cxpPagoCreditoTotal');
                $estadoColocacion        = 'C';
                $colocacionLiquidaciones = DB::table('colocacionliquidacion as cl')->select('cl.colliqid')->whereNull('cl.colliqfechapago')->get();

                $valorPagadoTotal          = $valorPagado;
                $saldoCapitalTotal         = $saldoCapital;
                $abonoCapitalTotal         = $abonoCapital;
                $interesCobradosTotal      = $interesCobrados;
                $valorInteresMoraTotal     = $valorInteresMora;
                $interesDevueltoTotal      = $interesDevuelto;
                foreach($colocacionLiquidaciones as $colocacionLiquidacion){
                    $colocacionliquidacion 				               = ColocacionLiquidacion::findOrFail($colocacionLiquidacion->colliqid);
                    $colocacionliquidacion->colliqfechapago            = $fechaActual;
                    $colocacionliquidacion->comconid                   = $comprobanteContableId;
                    $colocacionliquidacion->colliqvalorpagado          = $valorPagadoTotal;
                    $colocacionliquidacion->colliqsaldocapital         = $saldoCapitalTotal;
                    $colocacionliquidacion->colliqvalorcapitalpagado   = $abonoCapitalTotal;
                    $colocacionliquidacion->colliqvalorinterespagado   = $interesCobradosTotal;
                    $colocacionliquidacion->colliqvalorinteresmora     = $valorInteresMoraTotal;
                    $colocacionliquidacion->colliqvalorinteresdevuelto = $interesDevueltoTotal;
                    $colocacionliquidacion->save();
                    $valorPagadoTotal      = 0;
                    $saldoCapitalTotal     = 0;
                    $abonoCapitalTotal     = 0;
                    $interesCobradosTotal  = 0;
                    $valorInteresMoraTotal = 0;
                    $interesDevueltoTotal  = 0;
                }
            }

            if($request->formaPago === 'A'){//Abono a capital

                if($saldoColocacion <= $valorPagado){
                    return response()->json(['success' => false, 'message' => 'No es posible realizar un abono a una colocación por un valor igual o mayor al saldo pendiente']);
                }

                if($cuotaVencida === 'SI'){
                    return response()->json(['success' => false, 'message' => 'No es posible realizar un abono a una colocación en mora']);
                }

                $arrayInteresMensual = $generales->calcularValorInteresDiario($saldoColocacion, $tasaInteresMensual, $fechaVencimiento, $interesMora, 0);
                $valorDescuento      = $arrayInteresMensual['valorInteresDevuelto'];
                $interesMensualTotal = $arrayInteresMensual['interesMensualTotal'];
                $capitalPagado       = round($valorPagado - $interesMensualTotal, 0);
                $abonoCapital        = round($saldoColocacion - $capitalPagado, 0);
                $interesCobrados     = $interesMensualTotal;
                $valorInteresMora    = 0;
                $interesDevuelto     = 0;
                $valorCuota          = $valorPagado;

                $mensajeFactura                                    = 'FACTURA ABONO AL CRÉDITO';
                $colocacionliquidacion 				               = new ColocacionLiquidacion();
                $colocacionliquidacion->colliqvalorcuota           = $valorPagado;
                $colocacionliquidacion->coloid                     = $request->colocacionId;
                $colocacionliquidacion->colliqnumerocuota          = 'A' ;
                $colocacionliquidacion->colliqfechavencimiento     = $fechaActual;
                $colocacionliquidacion->colliqfechapago            = $fechaActual;
                $colocacionliquidacion->comconid                   = $comprobanteContableId;
                $colocacionliquidacion->colliqvalorpagado          = $valorPagado;
                $colocacionliquidacion->colliqsaldocapital         = $abonoCapital;
                $colocacionliquidacion->colliqvalorcapitalpagado   = $capitalPagado;
                $colocacionliquidacion->colliqvalorinterespagado   = $interesMensualTotal;
                $colocacionliquidacion->colliqvalorinteresmora     = $valorInteresMora;
                $colocacionliquidacion->colliqvalorinteresdevuelto = $interesDevuelto;
                $colocacionliquidacion->save();
            }

            if($cambiarEstadoColocacion){
                $colocacion           = Colocacion::findOrFail($request->colocacionId);
                $colocacion->tiesclid = $estadoColocacion;
                $colocacion->save();

                $colocacioncambioestado 				   = new ColocacionCambioEstado();
                $colocacioncambioestado->coloid            = $request->colocacionId;
                $colocacioncambioestado->tiesclid          = $estadoColocacion;
                $colocacioncambioestado->cocaesusuaid      = Auth::id();
                $colocacioncambioestado->cocaesfechahora   = $fechaHoraActual;
                $colocacioncambioestado->cocaesobservacion = 'Cancelación total del crédito en la fecha '.$fechaHoraActual;
                $colocacioncambioestado->save();
            }

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorPagado;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = $cuentaContableId;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorPagado;
            $comprobantecontabledetalle->save();

            $agencia                = $this->consultarAgencia($agenciaId);

            $arrayDatos   = [
                "fechaPago"         => $fechaHoraActual,
                "valorPago"         => number_format($valorCuota, 0,',','.'),
                "descuentoPago"     => number_format($interesDevuelto, 0,',','.'),
                "interesCorriente"  => number_format($interesCobrados, 0,',','.'),
                "interesMora"       => number_format($valorInteresMora, 0,',','.'),
                "valorTotalPago"    => number_format($valorPagado, 0,',','.'),
                "documentoCliente"  => $colocacion->persdocumento,
                "nombreCliente"     => $colocacion->nombrePersona,
                "direccionCliente"  => $colocacion->persdireccion,
                "telefonoCliente"   => $colocacion->persnumerocelular,
                "usuarioElabora"    => $agencia->nombreUsuario,
                "nombreAgencia"     => $agencia->agennombre,
                "direccionAgencia"  => $agencia->agendireccion,
                "telefonoAgencia"   => $agencia->telefonoAgencia,
                "mensajePlanilla"   => 'Gracias por su pago',
                "tituloFactura"     => $mensajeFactura,
                "metodo"            => 'S'
            ];

            $generarPdf  = new generarPdf();
            $dataFactura = $generarPdf->facturaPagoMensualidad($arrayDatos);

            DB::commit();
           return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', "dataFactura" => $dataFactura ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function consultarSancionAsociado(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric']);

        try{
            $mensajeError      = 'No se ha encontrado sanción por procesar para el vehículo seleccionado';
            $sancionesAsociado = DB::table('asociadosancion as as')
                                        ->select('as.asosanid','ts.tipsannombre','as.asosanfechahora','as.asosanfechamaximapago','as.asosanmotivo','as.asosanvalorsancion',
                                        DB::raw("DATE(as.asosanfechahora) as fechaSancion"),
                                        DB::raw("CONCAT('$ ', FORMAT(as.asosanvalorsancion, 0)) as valorSancion"))
                                        ->join('tiposancion as ts', 'ts.tipsanid', '=', 'ts.tipsanid')
                                        ->join('vehiculo as v', 'v.asocid', '=', 'as.asocid')
                                        ->where('as.asosanprocesada', false)
                                        ->where('v.vehiid', $request->vehiculoId)->get();

            return response()->json(['success'          => (count($sancionesAsociado) > 0) ? true : false, 'message' => $mensajeError,
                                    "sancionesAsociado" => $sancionesAsociado]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salveSancion(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric', 'totalAPagar' => 'required', 'sancionesAsociado' => 'required|array|min:1',]); 

        DB::beginTransaction();
        try {
            $generales          = new generales();
            $fechaHoraActual    = Carbon::now();
            $fechaActual        = $fechaHoraActual->format('Y-m-d');
            $agenciaId          = auth()->user()->agenid;
            $usuarioId          = Auth::id();
            $totalAPagar        = $request->totalAPagar;            

            foreach($request->sancionesAsociado as $sancionAsociado){
                $asociadosancion                  = AsociadoSancion::findOrFail($sancionAsociado['asosanid']);
                $asociadosancion->asosanprocesada = true;
                $asociadosancion->save();
            }

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        =  CuentaContable::consultarId('cxpPagoSancion');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $vehiculocontrato   = DB::table('vehiculocontrato as vc')
                                    ->select('p.persdocumento', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"),
                                    'p.persdireccion', 'p.persnumerocelular')
                                    ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('vc.vehiid', $request->vehiculoId)->first();

            $agencia            = $this->consultarAgencia($agenciaId);

            $arrayDatos   = [
                "fechaPago"         => $fechaHoraActual,
                "valorPago"         => 0,
                "descuentoPago"     => 0,
                "interesMora"       => 0,
                "interesCorriente"  => 0,
                "valorTotalPago"    => number_format($totalAPagar, 0,',','.'),
                "documentoCliente"  => $vehiculocontrato->persdocumento,
                "nombreCliente"     => $vehiculocontrato->nombreAsociado,
                "direccionCliente"  => $vehiculocontrato->persdireccion,
                "telefonoCliente"   => $vehiculocontrato->persnumerocelular,
                "usuarioElabora"    => $agencia->nombreUsuario,
                "nombreAgencia"     => $agencia->agennombre,
                "direccionAgencia"  => $agencia->agendireccion,
                "telefonoAgencia"   => $agencia->telefonoAgencia,
                "mensajePlanilla"   => 'Gracias por su pago',
                "tituloFactura"     => 'FACTURA DE PAGO SANCIÓN',
                "metodo"            => 'S'
            ];

            $generarPdf  = new generarPdf();
            $dataFactura = $generarPdf->facturaPagoMensualidad($arrayDatos);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', "dataFactura" => $dataFactura ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function consultarAgencia($agenciaId)
	{
        return  DB::table('agencia as a')
                    ->select(DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                        DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"))
                    ->join('usuario as u', 'u.agenid', '=', 'a.agenid')
                    ->where('a.agenid', $agenciaId)->first();
    }
}