<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
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
        $data            = DB::table('movimientocaja')->select('movcajsaldofinal')
                                    ->whereDate('movcajfechahoraapertura', $fechaActual)
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', $cajaId)->first();

        $ultimoSaldo     = DB::table('movimientocaja')->select('movcajsaldofinal')
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

            $movimientoCajaConsecutivo              = movimientocaja::latest('movcajid')->first();
			$movcajid                               = $movimientoCajaConsecutivo->movcajid;

            $comprobantecontable                    = new ComprobanteContable();
            $comprobantecontable->movcajid          = $movcajid;
            $comprobantecontable->usuaid            = Auth::id();
            $comprobantecontable->cajaid            = auth()->user()->cajaid;
            $comprobantecontable->agenid            = auth()->user()->agenid;
            $comprobantecontable->comconanio        = $anioActual;
            $comprobantecontable->comconconsecutivo = $this->obtenerConsecutivo($anioActual);
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
                        'interesMora'         => number_format($valorMora,0,',','.'),
                        'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                        'totalAPagarMostrar'  => number_format($totalAPagar,0,',','.'),
                        'totalAPagar'         => $totalAPagar
                    ];
                array_push($pagoMensualidad, $mensualiad);
                }            
            }

            $totalizado = [
                'idResponsabilidad'   => '',
                'fechaCompromiso'     => $fechaCompromisoInicial,
                'valorAPagar'         => number_format($valorResponsabilidad,0,',','.'),
                'interesMora'         => number_format($valorMora,0,',','.'),
                'descuentoAnticipado' => number_format($valorDesAnticipado,0,',','.'),
                'totalAPagar'         => number_format($totalAPagar,0,',','.')
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
                    $vehiculoresponsabilidad->agenid            = $agenciaId;
                    $vehiculoresponsabilidad->usuaid            = $usuarioId;
                    $vehiculoresponsabilidad->save();
                }
            }

            //Se realiza la contabilizacion
            $comprobantecontable    = DB::table('comprobantecontable')->select('comconid')
                                            ->whereDate('comconfechahora', $fechaActual)
                                            ->where('cajaid', auth()->user()->cajaid)
                                            ->where('agenid', auth()->user()->agenid)
                                            ->where('usuaid', Auth::id())
                                            ->where('comconestado', 'A')
                                            ->orderBy('comconid', 'Desc')
                                            ->first();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobantecontable->comconid;
            $comprobantecontabledetalle->cueconid        = $cuentaContableId;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobantecontable->comconid;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $totalAPagar;
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
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


    
    


    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoComprobanteContable = DB::table('comprobantecontable')->select('comconconsecutivo as consecutivo')
                                                        ->where('comconanio', $anioActual)
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->orderBy('comconid', 'Desc')->first();
        $consecutivo = ($consecutivoComprobanteContable === null) ? 1 : $consecutivoComprobanteContable->consecutivo + 1;
        return str_pad($consecutivo,  5, "0", STR_PAD_LEFT);
    }
    
}