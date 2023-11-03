<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\ColocacionCambioEstado;
use App\Models\Cartera\ColocacionLiquidacion;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use App\Util\convertirNumeroALetras;
use App\Models\Cartera\Colocacion;
use Exception, DB, Auth, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
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
        $solicitudCredito = DB::table('solicitudcredito as sc')->select('a.asocid','p.persid','sc.solcreid','sc.solcrefechasolicitud','sc.solcredescripcion','sc.lincreid',
                                    'sc.solcrenumerocuota','sc.solcreobservacion','sc.solcretasa','sc.solcrevalorsolicitado','p.persdocumento', 'p.persprimernombre',
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
                                    //->where('sc.tiesscid', 'A')
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
                                    'asociadoId'         => 'required|numeric',
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
                $descripcionCambioEstado .=  "A petición del asociado se disminuyó el monto aprobado, el cual paso de ".$valorAprobado." a ".$valorPrestamo.". ";
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
            $colocacioncambioestado->cocaesobservacion = $request->observacionGeneral;
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

        $colocacion = DB::table('colocacion as c')->select('c.coloid', 'c.colovalordesembolsado','c.colotasa', 'c.colonumerocuota','lc.lincrenombre',
                                'sc.solcredescripcion','c.colofechadesembolso', DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                ->where('c.solcreid', $request->solicitudId)
                                ->where('sc.tiesscid', 'D')
                                ->first();

        $colocacionLiquidacion = DB::table('colocacionliquidacion')
                                    ->select('colliqnumerocuota','colliqfechavencimiento', 'colliqvalorcuota')
                                    ->where('coloid', $colocacion->coloid)
                                    ->get();

        $arrayDatos = [ "fechaDesembolso"       => $colocacion->colofechadesembolso,
                        "lineaCredito"          => $colocacion->lincrenombre,
                        "nombreAsociado"        => $colocacion->nombreAsociado,
                        "descripcionCredito"    => $colocacion->solcredescripcion,
                        "valorSolicitado"       => $colocacion->colovalordesembolsado,
                        "tasaNominal"           => $colocacion->colotasa,
                        "plazoMensual"          => $colocacion->colonumerocuota,
                        "numeroColocacion"      => $colocacion->numeroColocacion,
                        "metodo"                => 'S'
                        ];

        $generarPdf          = new generarPdf();
        return $generarPdf->solicitudCredito($arrayDatos, $colocacionLiquidacion);
    }

    function generarCartaInstrucciones($request){

        $colocacion = DB::table('colocacion as c')->select('c.colofechadesembolso','c.coloanio', 'c.colonumerodesembolso','p.persdocumento',DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->where('c.solcreid', $request->solicitudId)
                                ->where('sc.tiesscid', 'D')
                                ->first();

        $dataRadicado        = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'cartaInstrucciones')->first();

        $generales           = new generales();
        $nombreAsociado      = $colocacion->nombreAsociado;
        $fechaLargaPrestamo  = $generales->formatearFecha($colocacion->colofechadesembolso);
        $numeroPagare        = $colocacion->numeroColocacion;
        $documento           = $colocacion->persdocumento;

        $documentoAsociado   = number_format($documento, 0, ',', '.');
        $buscar              = Array('nombreAsociado', 'numeroPagare', 'fechaLargaPrestamo');
        $remplazo            = Array( $nombreAsociado, $numeroPagare, $fechaLargaPrestamo); 
        $titulo              = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido           = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);
        $generarPdf          = new generarPdf();
        return $generarPdf->generarCartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, 'S');
    }

    function generarPagare($request){

        $colocacion = DB::table('colocacion as c')->select('c.coloid', 'c.colovalordesembolsado','c.colotasa', 'c.colonumerocuota','lc.lincrenombre',
                                DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),'c.colonumerodesembolso','c.coloanio',
                                'c.colofechadesembolso', 'sc.solcredescripcion','sc.solcrefechasolicitud', 'ti.tipidesigla','p.persdocumento',
                                'v.vehiplaca','v.vehinumerointerno',DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia) ) as referenciaVehiculo"),
                            DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                            ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'sc.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->where('c.solcreid', $request->solicitudId)
                            ->where('sc.tiesscid', 'D')
                            ->first();

        $colLiPrimerRegistro = DB::table('colocacionliquidacion') ->select('colliqfechavencimiento', 'colliqvalorcuota')
                                ->where('coloid', $colocacion->coloid)->orderBy('colliqid')->first();

        $colLiUltimoRegistro = DB::table('colocacionliquidacion')->select('colliqfechavencimiento')
                                ->where('coloid', $colocacion->coloid) ->orderBy('colliqid', 'desc')->first();

        $generarPdf             = new generarPdf();
        $generales              = new generales();
        $convertirNumeroALetras = new convertirNumeroALetras();
        $dataRadicado           = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'pagareColocacion')->first();  
        $numeroPagare           = $colocacion->numeroColocacion;
        $valorTotalCredito      = $colocacion->colovalordesembolsado;
        $valorCredito           = number_format($valorTotalCredito, 0, ',', '.');
        $valorCuota             = number_format($colLiPrimerRegistro->colliqvalorcuota, 0, ',', '.');
        $fechaSolicitud         = $colocacion->solcrefechasolicitud;
        $fechaDesembolso        = $colocacion->colofechadesembolso;
        $fechaPrimeraCuota      = $colLiPrimerRegistro->colliqfechavencimiento;
        $fechaUltimaCuota       = $colLiUltimoRegistro->colliqfechavencimiento;
        $interesMensual         = $colocacion->colotasa;
        $numeroCuota            = $colocacion->colonumerocuota;
        $destinacionCredito     = $colocacion->solcredescripcion;
        $referenciaCredito      = $colocacion->coloanio;
        $garantiaCredito        = $colocacion->referenciaVehiculo;
        $numeroInternoVehiculo  = $colocacion->vehinumerointerno;
        $placaVehiculo          = $colocacion->vehiplaca;
        $nombreAsociado         = $colocacion->nombreAsociado;
        $tpDocumentoAsociado    = $colocacion->tipidesigla;
        $documento              = $colocacion->persdocumento;
        $documentoAsociado      = number_format($documento, 0, ',', '.');
        $interesMoratorio       = '1.02';
        $valorEnLetras          = trim($convertirNumeroALetras->valorEnLetras($valorTotalCredito));
        $fechaLargaPrestamo     = $generales->formatearFecha($colocacion->colofechadesembolso);  
        $fechaLargaDesembolso   = $generales->formatearFechaLargaPagare($colocacion->colofechadesembolso);

        $buscar                  = Array('numeroPagare', 'valorCredito', 'fechaSolicitud', 'fechaDesembolso','fechaPrimeraCuota','fechaUltimaCuota',
                                            'interesMensual','numeroCuota', 'destinacionCredito', 'referenciaCredito', 'garantiaCredito',
                                            'numeroInternoVehiculo', 'placaVehiculo', 'nombreAsociado', 'tpDocumentoAsociado', 'documentoAsociado', 'interesMoratorio',
                                            'valorEnLetras', 'fechaLargaDesembolso', 'valorCuota' ,'fechaLargaPrestamo'
                                        );
        $remplazo                = Array($numeroPagare, $valorCredito, $fechaSolicitud, $fechaDesembolso, $fechaPrimeraCuota, $fechaUltimaCuota,
                                            $interesMensual, $numeroCuota, $destinacionCredito, $referenciaCredito, $garantiaCredito,
                                            $numeroInternoVehiculo, $placaVehiculo, $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $interesMoratorio,
                                            $valorEnLetras, $fechaLargaDesembolso, $valorCuota, $fechaLargaPrestamo
                                        );
        $titulo                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);

        return $generarPdf->generarPagareColocacion($titulo, $contenido, $numeroPagare, $documento, 'S');
    }
}