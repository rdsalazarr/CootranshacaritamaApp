<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Http\Controllers\Controller;
use App\Models\Caja\AbrirCaja;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
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
        $cajaNumero      = $caja->cajanumero;
        $data            = DB::table('abrircaja')->select('abrcajsaldofinal')
                                    ->whereDate('abrcajfechahoraapertura', $fechaActual)
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', $cajaId)->first();

        $ultimoSaldo     = DB::table('abrircaja')->select('abrcajsaldofinal')
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', $cajaId)
                                    ->orderBy('abrcajid', 'desc')
                                    ->first();

        $saldoAnterior   = ($ultimoSaldo) ? $ultimoSaldo->abrcajsaldofinal  : null; 

        return response()->json(["data" => $data,    "saldoAnterior" => $saldoAnterior, "cajaNumero" => $cajaNumero, 
                                "cajaId" => $cajaId, "nombreUsuario" => $nombreUsuario]);
    }

    public function abrirDia(Request $request)
	{
        $this->validate(request(),['saldoInicial' => 'required|numeric|between:1,99999999']);

        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $abrircaja       = DB::table('abrircaja')->select('abrcajsaldofinal')
                                ->whereDate('abrcajfechahoraapertura', $fechaActual)
                                ->where('usuaid', Auth::id())
                                ->where('cajaid', auth()->user()->cajaid)->first();
        if ($abrircaja){
            $message         = 'No es posible abrir la caja para esta fecha, ya existe un registro previo. ';
            $message         .= ' Por favor, contacte al administrador del sistema y solicite la apertura de su caja nuevamente';
            return response()->json(['success' => false, 'message'=> $message]);
        }

        try {
            $abrircaja                          = new AbrirCaja();
            $abrircaja->usuaid                  = Auth::id();
            $abrircaja->cajaid                  = auth()->user()->cajaid;
            $abrircaja->abrcajfechahoraapertura = $fechaHoraActual;
            $abrircaja->abrcajsaldoinicial      = $request->saldoInicial;
            $abrircaja->save();
        	return response()->json(['success' => true, 'message' => 'Caja abierta exitosamente']);
		} catch (Exception $error){
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

        /*$vehiculo = DB::table('vehiculo as v')->select('v.vehiid', 'tmv.timoveid', 'tmv.timovecuotasostenimiento', 'tmv.timovedescuentopagoanticipado', 'tmv.timoverecargomora')
                                ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                ->whereIn('v.vehiid', $request->vehiculoId)->first();*/

        /*$vehiculoResponsabilidades = DB::table('vehiculoresponsabilidad')->select('vehresid','vehiid','vehresfechacompromiso','vehresvalorresponsabilidad',
                                        DB::raw("CONCAT('$ ', FORMAT(vehresvalorresponsabilidad, 0)) as valorResponsabilidad"))
                                    //->whereNull('vehresvalorpagado')
                                    ->whereIn('vehiid', $request->vehiculoId)
                                    ->orderBy('vehresid')->get();*/


       return response()->json(['success' => true, "vehiculoResponsabilidades" => []]);
    } 

    public function tipoDocumentos(){
        $tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();

        return response()->json(["tipoIdentificaciones" => $tipoIdentificaciones]);
    } 
    
}