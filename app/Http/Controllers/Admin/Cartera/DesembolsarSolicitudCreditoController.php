<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\ColocacionCambioEstado;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Cartera\SolicitudCredito;
use App\Models\Cartera\Colocacion;
use App\Http\Controllers\Controller;
use App\Util\convertirNumeroALetras;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use Exception, DB, URL;
use App\Util\generales;
use Carbon\Carbon;

class DesembolsarSolicitudCreditoController extends Controller
{
    public function index()
    {
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();

        return response()->json(["tipoIdentificaciones" => $tipoIdentificaciones]);
    }

    public function consultar(Request $request)
    {
        $this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15'
                                ]);

        $url              = URL::to('/');
        $solicitudCredito = DB::table('solicitudcredito as sc')->select('a.asocid','p.persid','sc.solcreid','sc.solcrefechasolicitud','sc.solcredescripcion',
                                    'sc.solcrenumerocuota','sc.solcreobservacion','p.persdocumento', 'p.persprimernombre',
                                    'p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','a.asocfechaingreso','lc.lincrenombre as lineaCredito','tesc.tiesscnombre as estadoActual',
                                    DB::raw("CONCAT(sc.solcretasa,' %') as tasaNominal"),
                                    DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"))
                                    ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                    ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'sc.tiesscid')
                                    ->where('p.tipideid', $request->tipoIdentificacion)
                                    ->where('p.persdocumento', $request->documento)
                                    ->where('sc.tiesscid', 'A')->first();

        $array = ($solicitudCredito !== null) ? ['success' => true, "solicitudCredito" => $solicitudCredito ] :
                                                ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda ingresados'];

        return response()->json($array);
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['solicitudId'      => 'required|numeric', 
                                    'observacion'     => 'required|string|min:10|max:500']);

        DB::beginTransaction();
        $fechaHoraActual  = Carbon::now();
        $fechaActual      = $fechaHoraActual->format('Y-m-d');
        $estadoSolicitud  = 'D';//Desembolsado
        $estadoColocacion = 'V';//Vigente
        $anioActual       = Carbon::now()->year;
        $numeroColocacion = $this->obtenerConsecutivo($anioActual); //Consulto el numero de desembolso del año actual
        $numerosCuota     = $request->numeroCuota;
        $tasaInteres      = $request->tasaNominal;
        $valorPrestamo    = $request->valorDesembolso;
        $generales        = new generales();

        try {
            $solcreid                   = $request->solicitudId;
            $solicitudcredito           = SolicitudCredito::findOrFail($solcreid);
            $solicitudcredito->tiesscid = $estadoSolicitud;
            $solicitudcredito->save();

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $estadoSolicitud;
            $solicitudcreditocambioestado->socrceusuaid      = Auth::id();
            $solicitudcreditocambioestado->socrcefechahora   = $fechaHoraActual;
            $solicitudcreditocambioestado->socrceobservacion = $request->observacion;
            $solicitudcreditocambioestado->save();

            $colocacion 					   = new Colocacion();
            $colocacion->usuaid                = Auth::id();
            $colocacion->solcreid              = $solcreid; 
            $colocacion->tiesclid              = $estadoColocacion;
            $colocacion->colofechahoraregistro = $fechaHoraActual;
            $colocacion->colofechadesembolso   = $fechaActual;
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
            $colocacioncambioestado->cocaesobservacion = $request->observacion;
            $colocacioncambioestado->save();

            for ($cuota = 1; $cuota <= $numerosCuota; $cuota++) {
                $fechaVencimiento                              = $generales->obtenerFechaPagoCuota($fechaActual);
                $colocacionliquidacion 				           = new ColocacionLiquidacion();
                $colocacionliquidacion->coloid                 = $coloid;
                $colocacionliquidacion->colliqnumerocuota      = $cuota;
                $colocacionliquidacion->colliqfechavencimiento = $fechaVencimiento;
                $colocacionliquidacion->colliqvalorcuota       = $generales->calculcularValorCuotaMensual($valorPrestamo, $tasaInteres, $numerosCuota);
                $fechaActual                                   = $fechaVencimiento;
                $colocacionliquidacion->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }         
    }

    public function imprimir(Request $request)
    {
        $this->validate(request(),['personaId'    => 'required|numeric',
                                    'asociadoId'  => 'required|numeric',
                                    'solicitudId' => 'required|numeric',
                                    'url'         => 'required']);
        try {
            if($request->url === 'SOLICITUDCREDITO'){
                $dataDocumento = $this->generarSolicitudCredito($request);
            }else if($request->url === 'CARTAINSTRUCCIONES'){
                $dataDocumento = $this->generarCartaInstrucciones($request);
            }else if($request->url === 'FORMATO'){
                $dataDocumento = $this->generarPagare($request);
            }else{//De lo contrario genera el pagaré
                $dataDocumento = $this->generarPagare($request);
            }
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
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

    function generarSolicitudCredito($request){

        $lineaCredito        = 'EMERGENCIA PERSONAL ';
        $asociado            = 'MAYERLY PAOLA CASTRO PAEZ';
        $descripcionCredito  = 'Cunplir con pagos no relacionados en el dia de hoy';
        $valorSolicitado     = 2000000; 
        $tasaNominal         = 1.3; 
        $plazoMensual        = 12;

        $generarPdf           = new generarPdf();  
        return $generarPdf->solicitudCredito($lineaCredito, $asociado, $descripcionCredito, $valorSolicitado, $tasaNominal, $plazoMensual,  'S');
    }

    function generarCartaInstrucciones($request){
        $dataRadicado        = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 3)->first();   
        $nombreAsociado      = 'DIONISIO DE JESUS ANGARITA ANGARITA ';   
        $fechaLargaPrestamo  = '29 de AGOSTO del 2023';
        $numeroPagare        = '55546';
        $documento           = '88143913';
        $documentoAsociado   = number_format($documento, 0, ',', '.');
        $buscar              = Array('nombreAsociado', 'numeroPagare', 'fechaLargaPrestamo');
        $remplazo            = Array( $nombreAsociado, $numeroPagare, $fechaLargaPrestamo); 
        $titulo              = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido           = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);
        $generarPdf           = new generarPdf();       
        return $generarPdf->generarCartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, 'S');
    }

    function generarPagare($request){

        $generarPdf             = new generarPdf();
        $convertirNumeroALetras = new convertirNumeroALetras();
        $dataRadicado           = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'pagareColocacion')->first();  
        $numeroPagare           = '55546';
        $valorTotalCredito      = '600000';
        $valorCredito           = number_format($valorTotalCredito, 0, ',', '.');
        $valorCuota             = number_format('100000', 0, ',', '.');
        $fechaSolicitud         = '2023-09-04'; 
        $fechaDesembolso        = '2023-09-04';
        $fechaPrimeraCuota      = '2023-10-03';
        $fechaUltimaCuota       = '2024-03-03';
        $interesMensual         = '1.09';
        $numeroCuota            = '6';
        $destinacionCredito     = 'VARIOS';
        $referenciaCredito      = '2023';
        $garantiaCredito        = 'VEHICULO';
        $numeroInternoVehiculo  = '471';
        $placaVehiculo          = 'TFT187';
        $nombreAsociado         = 'DIONISIO DE JESUS ANGARITA ANGARITA ';
        $tpDocumentoAsociado    = 'CC';
        $documento              = '88143913';
        $documentoAsociado      = number_format($documento, 0, ',', '.');
        $interesMoratorio       = '1.02';
        $valorEnLetras          = trim($convertirNumeroALetras->valorEnLetras($valorTotalCredito));
        $fechaLargaPrestamo     = '4 de septiembre de 2023' ;
        $fechaLargaDesembolso   = '05 días del mes de septiembre de 2023';

        $buscar                  = Array('numeroPagare', 'valorCredito', 'fechaSolicitud', 'fechaDesembolso','fechaPrimeraCuota','fechaUltimaCuota',
                                            'interesMensual','numeroCuota', 'destinacionCredito', 'referenciaCredito', 'garantiaCredito',
                                            'numeroInternoVehiculo', 'placaVehiculo', 'nombreAsociado', 'tpDocumentoAsociado', 'documentoAsociado', 'interesMoratorio',
                                            'valorEnLetras', 'fechaLargaDesembolso', 'valorCuota'
                                        );
        $remplazo                = Array($numeroPagare, $valorCredito, $fechaSolicitud, $fechaDesembolso, $fechaPrimeraCuota, $fechaUltimaCuota,
                                            $interesMensual, $numeroCuota, $destinacionCredito, $referenciaCredito, $garantiaCredito,
                                            $numeroInternoVehiculo, $placaVehiculo, $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $interesMoratorio,
                                            $valorEnLetras, $fechaLargaDesembolso, $valorCuota
                                        ); 
        $titulo                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);

        return $generarPdf->generarPagareColocacion($titulo, $contenido, $numeroPagare, $documento, 'S');
    }
}