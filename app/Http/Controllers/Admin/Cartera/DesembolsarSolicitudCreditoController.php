<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\ColocacionCambioEstado;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use App\Models\Cartera\Colocacion;
use Exception, DB, Auth, URL;
use Illuminate\Http\Request;
use App\Util\generales;
use Carbon\Carbon;

class DesembolsarSolicitudCreditoController extends Controller
{
    public function index()
    {
        try{
            $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();

            return response()->json(['success' => true, "tipoIdentificaciones" => $tipoIdentificaciones]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultar(Request $request)
    {
        $this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15'
                                ]);

        $url              = URL::to('/');
        $solicitudCredito = DB::table('solicitudcredito as sc')->select('a.asocid','p.persid','sc.solcreid','sc.solcrefechasolicitud','sc.solcredescripcion','sc.lincreid',
                                    'sc.solcrenumerocuota','sc.solcreobservacion','sc.solcretasa','sc.solcrevalorsolicitado','p.persdocumento', 'p.persprimernombre',
                                    'p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento','p.tipperid',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','a.asocfechaingreso','lc.lincrenombre as lineaCredito','tesc.tiesscnombre as estadoActual',
                                    DB::raw("CONCAT(sc.solcretasa,' %') as tasaNominal"),
                                    DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"))
                                    ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                    ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'sc.tiesscid')
                                    ->leftJoin('asociado as a', 'a.persid', '=', 'p.persid')
                                    ->where('p.tipideid', $request->tipoIdentificacion)
                                    ->where('p.persdocumento', $request->documento)
                                    ->where('sc.tiesscid', 'A')
                                    ->first();
   
        $lineasCreditos = DB::table('lineacredito')
                                    ->select('lincreid','lincrenombre','lincretasanominal','lincremontominimo','lincremontomaximo', 'lincreplazomaximo')
                                    ->where('lincreactiva', true)->get();

        $array = ($solicitudCredito !== null) ? ['success' => true, "solicitudCredito" => $solicitudCredito, "lineasCreditos" => $lineasCreditos ] :
                                                ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda ingresados'];

        return response()->json($array);
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['personaId'           => 'required|numeric',
                                    'solicitudId'        => 'required|numeric',
                                    'lineaCredito'       => 'required|numeric',
                                    'valorSolicitado'    => 'required|numeric|between:1,999999999',
                                    'valorAprobado'      => 'required|numeric|between:1,999999999',
                                    'tasaNominal'        => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                                    'plazo'              => 'required|numeric|between:1,99',
                                    'observacionGeneral' => 'nullable|string|min:20|max:500'
                                ]);

        DB::beginTransaction();
        try {

            $fechaHoraActual  = Carbon::now();
            $fechaActual      = $fechaHoraActual->format('Y-m-d');
            $estadoSolicitud  = 'D';//Desembolsado
            $estadoColocacion = 'V';//Vigente
            $anioActual       = Carbon::now()->year;
            $numeroColocacion = $this->obtenerConsecutivo($anioActual); //Consulto el numero de desembolso del año actual
            $numerosCuota     = $request->plazo;
            $tasaInteres      = $request->tasaNominal;
            $valorPrestamo    = $request->valorSolicitado;
            $valorAprobado    = $request->valorAprobado;
            $generales        = new generales();

            //Genero la observacion para almacenar en el estado de la solicitud de credito
            $descripcionCambioEstado = "La solicitud fue desembolsa con número de colocación ". $anioActual."-".$numeroColocacion ." en la fecha ".$fechaHoraActual.". ";
            if($valorAprobado > $valorPrestamo){
                $descripcionCambioEstado .=  "A petición de la persona se disminuyó el monto aprobado, el cual paso de ".$valorAprobado." a ".$valorPrestamo.". ";
            }            
            $descripcionCambioEstado .= "Este proceso fue realizado por ".auth()->user()->usuanombre.".";

            $solcreid                   = $request->solicitudId;
            $solicitudcredito           = SolicitudCredito::findOrFail($solcreid);
            $solicitudcredito->tiesscid = $estadoSolicitud;
            $solicitudcredito->save();

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $estadoSolicitud;
            $solicitudcreditocambioestado->socrceusuaid      = Auth::id();
            $solicitudcreditocambioestado->socrcefechahora   = $fechaHoraActual;
            $solicitudcreditocambioestado->socrceobservacion = $descripcionCambioEstado;
            $solicitudcreditocambioestado->save();

            $fechaInicialColocacion            = $generales->obtenerFechaInicialColocacion();
            $colocacion 					   = new Colocacion();
            $colocacion->usuaid                = Auth::id();
            $colocacion->solcreid              = $solcreid;
            $colocacion->tiesclid              = $estadoColocacion;
            $colocacion->colofechahoradesembolso = $fechaHoraActual;
            $colocacion->colofechacolocacion   = $fechaInicialColocacion;
            $colocacion->coloanio              = $anioActual;
            $colocacion->colonumerodesembolso  = $numeroColocacion;
            $colocacion->colovalordesembolsado = $valorPrestamo;
            $colocacion->colotasa              = $tasaInteres;
            $colocacion->colonumerocuota       = $numerosCuota;
            $colocacion->save();

            $colocacionMaxConsecutio = Colocacion::latest('coloid')->first();
            $coloid                  = $colocacionMaxConsecutio->coloid;

            $colocacioncambioestado 				   = new ColocacionCambioEstado();
            $colocacioncambioestado->coloid            = $coloid;
            $colocacioncambioestado->tiesclid          = $estadoColocacion;
            $colocacioncambioestado->cocaesusuaid      = Auth::id();
            $colocacioncambioestado->cocaesfechahora   = $fechaHoraActual;
            $colocacioncambioestado->cocaesobservacion = $request->observacionGeneral;
            $colocacioncambioestado->save();

            $fechaInicialColocacion = $generales->obtenerFechaInicialColocacion();
            $fechaVencimiento       = $generales->obtenerFechaMesSiguiente($fechaInicialColocacion);
            $numeroDiasCambioFecha  = $generales->calcularDiasCambiosFechaDesembolso($fechaInicialColocacion, $fechaActual);
            $valorCuota             = $generales->calculcularValorCuotaMensual($valorPrestamo, $tasaInteres, $numerosCuota);
            $arrayInteresMensual    = $generales->calcularValorInteresDiario($valorPrestamo, $tasaInteres, $fechaVencimiento, 0, $numeroDiasCambioFecha);
            $valorInteres           = $arrayInteresMensual['valorIntereses'];
            $saldoCapital           =  $valorPrestamo;
            for ($cuota = 1; $cuota <= $numerosCuota; $cuota++) {

                $abonoCapital     = round($valorCuota - $valorInteres, 0);

                if ($saldoCapital < $valorCuota) {
                    $abonoCapital = $saldoCapital;
                    $valorCuota   = $saldoCapital + $valorInteres;
                }

                $saldoCapital -= $abonoCapital;
                $colocacionliquidacion 				           = new ColocacionLiquidacion();
                $colocacionliquidacion->coloid                 = $coloid;
                $colocacionliquidacion->colliqnumerocuota      = $cuota;
                $colocacionliquidacion->colliqfechavencimiento = $fechaVencimiento;
                $colocacionliquidacion->colliqvalorcuota       = $valorCuota;
                $fechaVencimiento                              = $generales->obtenerFechaMesSiguiente($fechaVencimiento);
                $colocacionliquidacion->save();
                $valorInteres           = $generales->calcularValorInteresMensual($saldoCapital, $tasaInteres);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoPagare = DB::table('colocacion')->select('colonumerodesembolso as consecutivo')
								->where('coloanio', $anioActual)->orderBy('coloid', 'desc')->first();

        $consecutivo = ($consecutivoPagare === null) ? 1 : $consecutivoPagare->consecutivo + 1;

        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}