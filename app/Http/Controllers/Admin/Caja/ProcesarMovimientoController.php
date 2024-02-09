<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Cartera\ColocacionCambioEstado;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Caja\ComprobanteContable;
use App\Models\Asociado\AsociadoSancion;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
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
                                ->select('v.vehiid', 'tmv.timoveid', 'tmv.timovecuotasostenimiento', 'tmv.timovedescuentopagoanticipado', 'tmv.timoverecargomora')
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
                foreach($vehiculoResponsabilidades as $key => $vehiculoResponsabilidad){ 
                    $descuentoAnticipado  = $vehiculo->timovedescuentopagoanticipado;
                    $recargoMora          = $vehiculo->timoverecargomora;
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
                            'valorAPagar'         => number_format($valorResponsabilidad,0,',','.'),
                            'interesMoraMostrar'  => number_format($valorMora,0,',','.'),
                            'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                            'totalAPagarMostrar'  => number_format($totalAPagar,0,',','.'),
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
                    'valorAPagar'         => number_format($valorResponsabilidad,0,',','.'),
                    'interesMoraMostrar'  => number_format($valorMora,0,',','.'),
                    'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                    'totalAPagarMostrar'  => number_format($totalAPagar,0,',','.'),
                    'valorDesAnticipado'  => $valorDesAnticipado,
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
        $this->validate(request(),['vehiculoId' => 'required|numeric', 'pagoTotal' => 'required']); 

        DB::beginTransaction();
        try {
            $generales          = new generales();
            $fechaHoraActual    = Carbon::now();
            $fechaActual        = $fechaHoraActual->format('Y-m-d');
            $totalAPagarMensual = $request->totalAPagar;
            $agenciaId          = auth()->user()->agenid;
            $usuarioId          = Auth::id();
            $totalAPagar        = $totalAPagarMensual;
            $cuentaContableId   = 3;

            if($request->pagoTotal === 'N'){
                $vehiculoresponsabilidad                    = VehiculoResponsabilidad::findOrFail($request->idResponsabilidad);
                $vehiculoresponsabilidad->vehresfechapagado = $fechaHoraActual;
                $vehiculoresponsabilidad->vehresvalorpagado = $totalAPagarMensual;
                $vehiculoresponsabilidad->vehresdescuento   = $request->valorDesAnticipado;
                $vehiculoresponsabilidad->vehresinteresmora = $request->interesMora;
                $vehiculoresponsabilidad->agenid            = $agenciaId;
                $vehiculoresponsabilidad->usuaid            = $usuarioId;
                $vehiculoresponsabilidad->save();
            }else{
                $cuentaContableId = 4;
                $totalAPagar      = 0;
                $vehiculo         = DB::table('vehiculo as v')
                                        ->select('tmv.timovedescuentopagoanticipado', 'tmv.timoverecargomora')
                                        ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                        ->where('v.vehiid', $request->vehiculoId)->first();

                $vehiculoResponsabilidades = DB::table('vehiculoresponsabilidad')->select('vehresid','vehresfechacompromiso','vehresvalorresponsabilidad')
                                            ->whereNull('vehresvalorpagado')
                                            ->where('vehiid', $request->vehiculoId)
                                            ->orderBy('vehresid')->get();

                foreach($vehiculoResponsabilidades as $vehiculoResponsabilidad){
                    $descuentoAnticipado  = $vehiculo->timovedescuentopagoanticipado;
                    $recargoMora          = $vehiculo->timoverecargomora;
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

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
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
                                    ->select('p.persdocumento', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"),
                                    'p.persdireccion', 'p.persnumerocelular')
                                    ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('vc.vehiid', $request->vehiculoId)->first();

            $agencia          = $this->consultarAgencia($agenciaId);

            $arrayDatos   = [
                "fechaPago"         => $fechaHoraActual,
                "valorPago"         => number_format($request->valorAPagar, 0,',','.'),
                "descuentoPago"     => number_format($request->valorDesAnticipado, 0,',','.'),
                "interesMora"       => number_format($request->interesMora, 0,',','.'),
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
                "tituloFactura"     => 'FACTURA DE PAGO MENSUALIDAD',
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

            /*colliqfechavencimiento <= '$fechaActual' AND*/
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
            $valorDescuentoTotal   = 0;
            $totalAPagarTotal      = 0;
            $interesMensualTotal   = 0;
            $colocaciones = DB::table('colocacionliquidacion as cl')
                            ->select('cl.coloid','cl.colliqvalorcuota', 'cl.colliqfechavencimiento', 'cl.colliqnumerocuota', 'cl.colliqvalorcuota', 'c.colofechacolocacion',
                                 'c.colotasa','c.colonumerocuota','c.colovalordesembolsado','lc.lincreinteresmora', DB::raw('DATE(c.colofechahoradesembolso) as fechaDesembolso'))
                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->where('c.coloid', $request->colocacionId)
                            ->whereNull('cl.colliqfechapago')
                            ->get();

            foreach($colocaciones as $colocacion){
                if($colocacion->coloid  = $request->liquidacionId){
                    //Pago de la cuota
                    $montoPrestamo         = $colocacion->colovalordesembolsado;
                    $tasaInteresMensual    = $colocacion->colotasa;
                    $plazo                 = $colocacion->colonumerocuota;
                    $fechaVencimiento      = $colocacion->colliqfechavencimiento;
                    $valorCuota            = $colocacion->colliqvalorcuota;
                    $fechaDesembolso       = $colocacion->fechaDesembolso;
                    $fechaColocacion       = $colocacion->colofechacolocacion;
                    $interesMora           = $colocacion->lincreinteresmora;
                    $numeroDiasCambioFecha = ($colocacion->colliqnumerocuota === '1') ? $generales->calcularDiasCambiosFechaDesembolso($fechaDesembolso, $fechaColocacion) : 0;
                    $arrayInteresMensual   = $generales->calcularValorInteresDiario($montoPrestamo, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha);
                    $valorIntereses        = $arrayInteresMensual['valorIntereses'];
                    $valorInteresMora      = $arrayInteresMensual['valorInteresMora'];
                    $valorDescuento        = $arrayInteresMensual['valorDescuento'];
                    $interesMensualTotal   = $arrayInteresMensual['interesMensualTotal'];
                         
                    $totalAPagar           = ($valorCuota + $valorInteresMora ) - $valorDescuento;

                    $mensualiad = [
                                    'fechaCuota'          => $fechaVencimiento,
                                    'valorCuota'          => $valorCuota,
                                    'totalAPagar'         => $totalAPagar,
                                    'valorIntereses'      => $valorIntereses,
                                    'interesMensualTotal' => $interesMensualTotal,
                                    'valorInteresMora'    => $valorInteresMora,
                                    'valorDescuento'      => $valorDescuento
                                ];
                    array_push($pagoMensualidad, $mensualiad);
                }

                //Pago total
                $arrayInteresMensualTota = $generales->calcularValorInteresDiario($montoPrestamo, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha);
                $valorInteresesTotal     += $arrayInteresMensualTota['valorIntereses'];
                $valorInteresMoraTotal   += $arrayInteresMensualTota['valorInteresMora'];
                $valorDescuentoTotal     += $arrayInteresMensualTota['valorDescuento'];
                $interesMensualTotal     += $arrayInteresMensual['interesMensualTotal'];
                $totalAPagarTotal        += ($valorCuota + $arrayInteresMensualTota['valorInteresMora'] ) - $arrayInteresMensualTota['valorDescuento'];
            }

            $totalPago = [
                'fechaCuota'          => $fechaVencimiento,
                'valorCuota'          => $valorCuota,
                'totalAPagar'         => $totalAPagarTotal,
                'valorIntereses'      => $valorInteresesTotal,
                'valorInteresMora'    => $valorInteresMoraTotal,
                'interesMensualTotal' => $interesMensualTotal,
                'valorDescuento'      => $valorDescuentoTotal
            ];

            array_push($pagoTotal, $totalPago);

            return response()->json(['success' => true, "pagoMensualidad" => $pagoMensualidad, "pagoTotal" => $pagoTotal]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salvePagoCredito(Request $request)
	{
        $this->validate(request(),['colocacionId' => 'required|numeric','liquidacionId' => 'required|numeric']);

        DB::beginTransaction();
        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
            $comprobanteContableId = ComprobanteContable::obtenerId($fechaActual);
            $agenciaId             = auth()->user()->agenid;
            $cuentaContableId      = 5;
            $estadoColocacion      = 'S';
            $valorPagado           = 0;
            $colocacion = DB::table('colocacionliquidacion as cl')
                            ->select('cl.coloid', 'cl.colliqvalorcuota','c.colovalordesembolsado','p.persdocumento','p.persdireccion', 'p.persnumerocelular',
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"),
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion WHERE colliqid = cl.colliqid AND colliqfechapago IS NULL) AS totalCuotasPorPagar'),
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion WHERE colliqid = cl.colliqid AND colliqfechapago IS NOT NULL) AS totalCuotasPagadas'),
                            DB::raw('(SELECT SUM(colliqvalorcapitalpagado) FROM colocacionliquidacion WHERE colliqid = cl.colliqid AND colliqfechapago IS NOT NULL) AS totalPagadoColocacion'))
                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('persona as p', 'p.persid', '=', 'sc.persid')
                            ->where('cl.coloid', $request->colocacionId)
                            ->where('cl.colliqid', $request->liquidacionId)
                            ->first();

            $cambiarEstadoColocacion = ($colocacion->totalCuotasPorPagar + 1 === $colocacion->totalCuotasPagadas) ? true : false;
            $valorDesembolsado       = intval($colocacion->colovalordesembolsado);
            $valorCuota              = $colocacion->colliqvalorcuota;
            $totalPagado             = $colocacion->totalPagadoColocacion;

            if($request->pagoTotal === 'N'){
    
                $interesCobrados     = $request->interesCorrienteTotal;
                $valorInteresMora    = $request->interesMora;
                $descuentoAnticipado = $request->descuentoAnticipado;
                $valorPagado         = $request->totalAPagar;
                $abonoCapital        = round($valorCuota - $interesCobrados, 0);
                $saldocapital        = ($totalPagado > 0) ? ($valorDesembolsado - $totalPagado - $abonoCapital ) : ($valorDesembolsado - $abonoCapital);

                $colocacionliquidacion 				                   = ColocacionLiquidacion::findOrFail($request->liquidacionId);
                $colocacionliquidacion->colliqfechapago                = $fechaActual;
                $colocacionliquidacion->colliqnumerocomprobante        = $comprobanteContableId;
                $colocacionliquidacion->colliqvalorpagado              = $valorPagado;
                $colocacionliquidacion->colliqsaldocapital             = $saldocapital; 
                $colocacionliquidacion->colliqvalorcapitalpagado       = $abonoCapital;
                $colocacionliquidacion->colliqvalorinterespagado       = $interesCobrados;
                $colocacionliquidacion->colliqvalorinteresmora         = $valorInteresMora;
                $colocacionliquidacion->colliqvalordescuentoanticipado = $descuentoAnticipado;
                $colocacionliquidacion->save();
            }else{
                //$cambiarEstadoColocacion = true;
                $cuentaContableId        = 6;
                $estadoColocacion        = 'C';
                $colocaciones     = DB::table('colocacionliquidacion as cl')
                                        ->select('cl.colliqid', 'c.colotasa', 'cl.colliqvalorcuota','c.colovalordesembolsado','c.colofechacolocacion',
                                            'lc.lincreinteresmora','cl.colliqfechavencimiento','cl.colliqnumerocuota',DB::raw('DATE(c.colofechahoradesembolso) as fechaDesembolso'))
                                        ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                                        ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                        ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                        ->where('cl.coloid', $request->colocacionId)
                                        ->whereNull('cl.colliqfechapago')
                                        ->whereIn('cl.colliqid',[1,2,3])//Borrar
                                        ->get();

                foreach($colocaciones as $colocacionPago){
                    $tasaInteresMensual    = $colocacionPago->colotasa;
                    $fechaDesembolso       = $colocacionPago->fechaDesembolso; 
                    $fechaColocacion       = $colocacionPago->colofechacolocacion;
                    $fechaVencimiento      = $colocacionPago->colliqfechavencimiento;
                    $interesMora           = $colocacionPago->lincreinteresmora;
                    $numeroDiasCambioFecha = ($colocacionPago->colliqnumerocuota === '1') ? $generales->calcularDiasCambiosFechaDesembolso($fechaDesembolso, $fechaColocacion) : 0;
                    $arrayInteresTotal     = $generales->calcularValorInteresDiario($valorDesembolsado, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha);

                    $valorInteresMora      = $arrayInteresTotal['valorInteresMora'];
                    $descuentoAnticipado   = $arrayInteresTotal['valorDescuento'];
                    $interesCobrados       = $arrayInteresTotal['interesMensualTotal'];
                    $totalAPagar           = ($valorCuota + $arrayInteresTotal['valorInteresMora'] ) - $arrayInteresTotal['valorDescuento'];
                    $abonoCapital          = round($valorCuota - $interesCobrados, 0);
                    $saldocapital          = ($totalPagado > 0) ? ($valorDesembolsado - $totalPagado - $abonoCapital ) : ($valorDesembolsado - $abonoCapital);
                    $totalPagado           += $totalAPagar;
                    $valorPagado           += $totalAPagar;


                    $colocacionliquidacion 				                   = ColocacionLiquidacion::findOrFail($colocacionPago->colliqid);
                    $colocacionliquidacion->colliqfechapago                = $fechaActual;
                    $colocacionliquidacion->colliqnumerocomprobante        = $comprobanteContableId;
                    $colocacionliquidacion->colliqvalorpagado              = $totalAPagar;
                    $colocacionliquidacion->colliqsaldocapital             = $saldocapital;
                    $colocacionliquidacion->colliqvalorcapitalpagado       = $abonoCapital;
                    $colocacionliquidacion->colliqvalorinterespagado       = $interesCobrados;
                    $colocacionliquidacion->colliqvalorinteresmora         = $valorInteresMora;
                    $colocacionliquidacion->colliqvalordescuentoanticipado = $descuentoAnticipado;
                    $colocacionliquidacion->save();
                }               
            }



            if($cambiarEstadoColocacion){
                $colocacioncambioestado 				   = new ColocacionCambioEstado();
                $colocacioncambioestado->coloid            = $request->colocacionId;
                $colocacioncambioestado->tiesclid          = $estadoColocacion;
                $colocacioncambioestado->cocaesusuaid      = Auth::id();
                $colocacioncambioestado->cocaesfechahora   = $fechaHoraActual;
                $colocacioncambioestado->cocaesobservacion = 'Cancelación total del crédito en la fecha '.$fechaHoraActual;
                $colocacioncambioestado->save();
            }

            //Se realiza la contabilizacion
           /* $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorPagado;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = $cuentaContableId;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorPagado;
            $comprobantecontabledetalle->save();*/

            $agencia                = $this->consultarAgencia($agenciaId);

            $arrayDatos   = [
                "fechaPago"         => $fechaHoraActual,
                "valorPago"         => number_format($valorPagado, 0,',','.'),
                "descuentoPago"     => number_format($valorPagado, 0,',','.'),
                "interesMora"       => number_format($valorPagado, 0,',','.'),
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
                "tituloFactura"     => 'FACTURA DE PAGO MENSUALIDAD',
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
            $cuentaContableId   = 7;

            foreach($request->sancionesAsociado as $sancionAsociado){
                $asociadosancion                  = AsociadoSancion::findOrFail($sancionAsociado['asosanid']);
                $asociadosancion->asosanprocesada = true;
                $asociadosancion->save();
            }

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = $cuentaContableId;
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
                "valorPago"         => '',
                "descuentoPago"     => '',
                "interesMora"       => '',
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