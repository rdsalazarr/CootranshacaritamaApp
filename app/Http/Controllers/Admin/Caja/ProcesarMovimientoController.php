<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Caja\ConsignacionBancaria;
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

        return response()->json(["data" => $data,    "saldoAnterior" => $saldoAnterior, "cajaNumero" => $cajaNumero, 
                                "cajaId" => $cajaId, "nombreUsuario" => $nombreUsuario]);
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
        $vehiculos = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                ->whereIn('v.tiesveid', ['A','S'])
                                ->orderBy('v.vehinumerointerno')->get();

        return response()->json(["vehiculos" => $vehiculos]);
    }

    public function consultarVehiculo(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric']);

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

            $agencia    = DB::table('agencia as a')
                                    ->select(DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                                    DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"))
                                    ->join('usuario as u', 'u.agenid', '=', 'a.agenid')
                                    ->where('a.agenid', $agenciaId)->first();

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
        $tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')
                                    ->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();

        return response()->json(["tipoIdentificaciones" => $tipoIdentificaciones]);
    }

    public function consultarCredito(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        try{
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $colocaciones = DB::table('colocacion as c')
                                ->select('c.coloid', 'lc.lincrenombre',
                                DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT('$ ', FORMAT(c.colovalordesembolsado, 0)) as valorDesembolsado"),
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"),
                                'cli.colliqnumerocuota','cli.colliqfechavencimiento',
                                DB::raw("CONCAT('$ ', FORMAT(cli.colliqvalorcuota, 0)) as valorCuota"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                ->whereIn('c.tiesclid', ['V', 'J', 'R'])
                                ->where('p.tipideid', $request->tipoIdentificacion)
                                ->where('p.persdocumento', $request->documento)
                                ->join('colocacionliquidacion as cli', function ($join) use ($fechaActual) {
                                    $join->on('c.coloid', '=', 'cli.coloid')
                                        ->whereDate('cli.colliqfechavencimiento', '<=', $fechaActual)
                                        ->whereNull('cli.colliqfechapago');
                                    })
                                ->orderBy('c.coloid')
                                ->get();

            return response()->json(['success' => true, "colocaciones" => $colocaciones]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function calcularCuota(Request $request)
	{
        $this->validate(request(),['colocacionId' => 'required|numeric']);

        try{
            $colocacionLiquidacion = [];
            
            return response()->json(['success' => true, "colocacionLiquidacion" => $colocacionLiquidacion]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salvePagoCredito(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $consignacionbancaria                    = ConsignacionBancaria::findOrFail($request->idResponsabilidad);
            $consignacionbancaria->entfinid          = $request->entidadFinaciera;
            $consignacionbancaria->usuaid            = Auth::id();
            $consignacionbancaria->agenid            = auth()->user()->agenid;
            $consignacionbancaria->conbanfechahora   = $fechaHoraActual;
            $consignacionbancaria->conbanmonto       = $request->monto;
            $consignacionbancaria->conbandescripcion = $request->descripcion;
            $consignacionbancaria->save();

            $fechaVencimiento                                = $generales->obtenerFechaPagoCuota($fechaActual);
            $colocacionliquidacion 				             = new ColocacionLiquidacion();
            $colocacionliquidacion->colliqfechapago          = $coloid;
            $colocacionliquidacion->colliqnumerocomprobante  = $cuota;
            $colocacionliquidacion->colliqvalorpagado        = $fechaVencimiento;
            $colocacionliquidacion->colliqsaldocapital       = $valorCuota; 
            $colocacionliquidacion->colliqvalorcapitalpagado = $cuota;
            $colocacionliquidacion->colliqvalorinterespagado = $fechaVencimiento;
            $colocacionliquidacion->colliqvalorinteresmora   = $valorCuota; 
            $colocacionliquidacion->save();

            /* 
            $table->bigIncrements('colliqid')->unsigned()->comment('Identificador de la tabla colocación liquidación');
            $table->integer('coloid')->unsigned()->comment('Identificador de la solicitud de crédito');
            $table->string('colliqnumerocuota', 3)->comment('Número de cuota de la colocación');
            $table->string('colliqvalorcuota', 10)->comment('Monto o valor de la cuota de la colocación');
            $table->date('colliqfechavencimiento')->comment('Fecha de vencimiento de la cuota de la colocación');

            $table->date('colliqfechapago')->nullable()->comment('Fecha de pago de la cuota de la colocación');
            $table->string('colliqnumerocomprobante', 10)->nullable()->comment('Número de comprobante de pago de la cuota de la colocación');
            $table->decimal('colliqvalorpagado', 12, 0)->nullable()->comment('Valor pagado en la cuota de la colocación');
            $table->decimal('colliqsaldocapital', 10, 0)->nullable()->comment('Saldo a capital de la colocación');
            $table->decimal('colliqvalorcapitalpagado', 10, 0)->nullable()->comment('Valor capital pagado la colocación');
            $table->decimal('colliqvalorinterespagado', 10, 0)->nullable()->comment('Valor interés pagado la colocación');
            $table->decimal('colliqvalorinteresmora', 10, 0)->nullable()->comment('Valor interés de mora pagado la colocación');*/


            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->monto;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 5;//Pago de cuota credito; 6  pago de cuota de credito mensual
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->monto;
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro realizado con éxito' ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function consultarSancionAsociado(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required|numeric']);

        $mensajeError      = 'No se ha encontrado sanción por procesar para el vehículo seleccionado';
        $sancionesAsociado = DB::table('asociadosancion as as')
                                    ->select('as.asosanid','ts.tipsannombre','as.asosanfechahora','as.asosanfechamaximapago','as.asosanmotivo','as.asosanvalorsancion',
                                    DB::raw("DATE(as.asosanfechahora) as fechaSancion"),
                                    DB::raw("CONCAT('$ ', FORMAT(as.asosanvalorsancion, 0)) as valorSancion"))
                                    ->join('tiposancion as ts', 'ts.tipsanid', '=', 'ts.tipsanid')
                                    ->join('vehiculo as v', 'v.asocid', '=', 'as.asocid')
                                    ->where('as.asosanprocesada', false)
                                    ->where('v.vehiid', $request->vehiculoId)->get();

        return response()->json(['success'           => (count($sancionesAsociado) > 0) ? true : false, 'message' => $mensajeError,
                                 "sancionesAsociado" => $sancionesAsociado]);
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

            $vehiculocontrato  = DB::table('vehiculocontrato as vc')
                                    ->select('p.persdocumento', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"),
                                    'p.persdireccion', 'p.persnumerocelular')
                                    ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('vc.vehiid', $request->vehiculoId)->first();

            $agencia    = DB::table('agencia as a')
                                    ->select(DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                                    DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"))
                                    ->join('usuario as u', 'u.agenid', '=', 'a.agenid')
                                    ->where('a.agenid', $agenciaId)->first();

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

    public function listConsignacion()
    {
        $consignacionBancarias = DB::table('consignacionbancaria as cb')
                                    ->select('cb.conbanid','cb.entfinid','cb.conbanfechahora','cb.conbanmonto','cb.conbandescripcion','ef.entfinnombre',
                                    DB::raw("FORMAT(cb.conbanmonto, 0) as monto"))
                                    ->join('entidadfinanciera as ef', 'ef.entfinid', '=', 'cb.entfinid')
                                    ->where('cb.usuaid', Auth::id())
                                    ->orderBy('cb.conbanid')->get();

        return response()->json(['data' => $consignacionBancarias ]);
    }

    public function datosConsignacion()
    {
        $entidadFinancieras = DB::table('entidadfinanciera')->select('entfinid','entfinnombre')
                                    ->where('entfinactiva', true)
                                    ->orderBy('entfinnombre')->get();

        return response()->json(['entidadFinancieras' => $entidadFinancieras ]);
    }

    public function salveConsignacion(Request $request)
	{
        $this->validate(request(),['entidadFinaciera' => 'required|numeric',
                                    'monto'           => 'required|numeric|between:1,999999999',
                                    'descripcion'     => 'required|string|min:10|max:200']);

        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $consignacionbancaria                    =  new ConsignacionBancaria();
            $consignacionbancaria->entfinid          = $request->entidadFinaciera;
            $consignacionbancaria->usuaid            = Auth::id();
            $consignacionbancaria->agenid            = auth()->user()->agenid;
            $consignacionbancaria->conbanfechahora   = $fechaHoraActual;
            $consignacionbancaria->conbanmonto       = $request->monto;
            $consignacionbancaria->conbandescripcion = $request->descripcion;
            $consignacionbancaria->save();

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Banco
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->monto;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 2;//Caja;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->monto;
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro realizado con éxito' ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}