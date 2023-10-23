<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use DB, Auth, URL, Artisan;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Util\generarPdf;
use App\Util\convertirNumeroALetras;



class MantenimientoController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth')->except('clear');
    }

   /* public function __construct()
    {
      $this->middleware('guest',['only' => 'clear']);
    }*/

    public function clear()
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear'); 
        Artisan::call('config:cache');
        Artisan::call('view:cache'); 
        Artisan::call('route:cache'); 
        Artisan::call('event:cache'); 
        
       // Artisan::call('optimize');
        return "Datos eliminados";
    } 

    public function down()
    {
        Artisan::call('down --secret="COOTRANSHACARITAMAAPP"');
        return response()->view('errors.503',['title' =>'Creando modo mantenimiento']);
    }

    public function up()
    {   
        Artisan::call('up');
        return response()->view('errors.upMantenimiento',['title' =>'Subiendo el modo mantenimiento']);
    }

    public function email(){
        $notificar       = new notificar();
        $fechaHoraActual = Carbon::now();

        $email             = 'rdsalazarr@ufpso.edu.co';
        $email             = 'radasa10@hotmail.com';
        $nombreUsuario     = 'RAMON DAVID SALAZAR RINCON';
        $siglaCooperativa  = 'COOTRANSHACARITAMA';
        $nombreEmpresa     = "Cooperativa de transporte HACARITAMA";  
        $usuarioSistema    = "RSALAZR";
        $contrasenaSistema = '123456789'; 
        $urlSistema        =  URL::to('/');
        $emailEmpresa      = '';
        $nombreGerente     = 'Luis manuel Ascanio'; 

        $rutaPdf            = public_path().'/archivos/radicacion/documentoEntrante/2023/270_1978917-cccoopigon.pdf';

        $informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innocoid', 2)->first();
        
        $buscar          = Array('siglaCooperativa', 'nombreUsuario', 'usuarioSistema', 'nombreEmpresa','contrasenaSistema','urlSistema','nombreGerente');
        $remplazo        = Array($siglaCooperativa, $nombreUsuario,  $usuarioSistema, $nombreEmpresa, $contrasenaSistema, $urlSistema,$nombreGerente); 
        $asunto          = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
        $msg             = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido); 
        $enviarcopia     = $informacioncorreo->innocoenviarcopia;
        $enviarpiepagina = $informacioncorreo->innocoenviarpiepagina;
        $enviarcopia     = 0;
        $enviarpiepagina = 1;

        $mensajeCorreo = ', '.$notificar->correo([$email], $asunto, $msg, [$rutaPdf], $emailEmpresa, $enviarcopia, $enviarpiepagina);

       dd($mensajeCorreo);      
    }
    
    public function Pdf()
    {  
		/*$dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 1)->first();       
        $numeroContrato             = 'A02765';
        $fechaContrato              = '2023-10-19';
        $nombreGerente              = 'LUIS MANUEL ASCANIO CLARO';
        $tpDocumentoGerente         = 'CC';
        $documentoGerente           = number_format('5036123', 0, ',', '.');
        $ciudadExpDocumentoGerente  = 'OCAÑA';
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';
        $tpDocumentoAsociado        = 'CC';
        $documentoAsociado          = number_format('88143913', 0, ',', '.');
        $ciudadExpDocumentoAsociado = 'OCAÑA';
        $direccionAsociado          = 'CALLE 18 N 22-85 LAS COLINAS';
        $telefonoAsociado           = '3152190167';
        $placaVehiculo              = 'TFT187';
        $numeroInternoVehiculo      = '208';
        $claseVehiculo              = 'AUTOMÓVIL';
        $cilindrajeVehiculo         = '1598';
        $carroceriaVehiculo         = 'Sedán';
        $modeloVehiculo             = '2020';
        $marcaVehiculo              = 'RENAULT';
        $colorVehiculo              = 'BLANCO GLACIAL (V)';
        $capacidadVehiculo          = '04';  
        $documentosAdionales        = '1 Fotografía, Fotocopia Cédula, Fotocopia Tarjeta de Propiedad a su nombre y Reseña del DAS.';
        $observacionGeneral         = 'VEHICULO EL DE PLACAS TFT187 INGRESA EN REPOSICION DEl UUA585';

        $buscar                     = Array('numeroContrato', 'fechaContrato', 'nombreGerente', 'tpDocumentoGerente','documentoGerente','ciudadExpDocumentoGerente',
                                            'nombreAsociado','tpDocumentoAsociado', 'documentoAsociado', 'ciudadExpDocumentoAsociado', 'direccionAsociado',
                                            'telefonoAsociado', 'placaVehiculo', 'numeroInternoVehiculo', 'claseVehiculo', 'cilindrajeVehiculo', 'carroceriaVehiculo',
                                            'modeloVehiculo', 'marcaVehiculo', 'colorVehiculo', 'capacidadVehiculo', 'documentosAdionales', 'observacionGeneral'
                                        );
        $remplazo                   = Array($numeroContrato, $fechaContrato, $nombreGerente, $tpDocumentoGerente, $documentoGerente, $ciudadExpDocumentoGerente,
                                            $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $ciudadExpDocumentoAsociado, $direccionAsociado,
                                            $telefonoAsociado, $placaVehiculo, $numeroInternoVehiculo, $claseVehiculo, $cilindrajeVehiculo, $carroceriaVehiculo,
                                            $modeloVehiculo, $marcaVehiculo, $colorVehiculo, $capacidadVehiculo, $documentosAdionales, $observacionGeneral
                                        ); 
        $titulo                      = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido); 
        $generarPdf                  = new generarPdf();
        $generarPdf->generarContratoVehiculo($titulo, $contenido, $numeroContrato, $placaVehiculo, 'I' );*/

        /*$convertirNumeroALetras = new convertirNumeroALetras();        
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 2)->first();  
        $numeroPagare               = '55546';
        $valorTotalCredito          = '600000';
        $valorCredito               = number_format($valorTotalCredito, 0, ',', '.');
        $valorCuota                 = number_format('100000', 0, ',', '.');        
        $fechaSolicitud             = '2023-09-04'; 
        $fechaDesembolso            = '2023-09-04';
        $fechaPrimeraCuota          = '2023-10-03';
        $fechaUltimaCuota           = '2024-03-03';
        $interesMensual             = '1.09';
        $numeroCuota                = '6';
        $destinacionCredito         = 'VARIOS';
        $referenciaCredito          = '2023';
        $garantiaCredito            = 'VEHICULO';
        $numeroInternoVehiculo      = '471';
        $placaVehiculo              = 'TFT187';
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';
        $tpDocumentoAsociado        = 'CC';
        $documento                  = '88143913';
        $documentoAsociado          = number_format($documento, 0, ',', '.');
        $interesMoratorio           = '1.02';
        $valorEnLetras              = trim($convertirNumeroALetras->valorEnLetras($valorTotalCredito));
        $fechaLargaPrestamo         = '4 de septiembre de 2023' ;
        $fechaLargaDesembolso       = '05 días del mes de septiembre de 2023';

        $buscar                     = Array('numeroPagare', 'valorCredito', 'fechaSolicitud', 'fechaDesembolso','fechaPrimeraCuota','fechaUltimaCuota',
                                            'interesMensual','numeroCuota', 'destinacionCredito', 'referenciaCredito', 'garantiaCredito',
                                            'numeroInternoVehiculo', 'placaVehiculo', 'nombreAsociado', 'tpDocumentoAsociado', 'documentoAsociado', 'interesMoratorio',
                                            'valorEnLetras', 'fechaLargaDesembolso', 'valorCuota'
                                        );
        $remplazo                   = Array($numeroPagare, $valorCredito, $fechaSolicitud, $fechaDesembolso, $fechaPrimeraCuota, $fechaUltimaCuota,
                                            $interesMensual, $numeroCuota, $destinacionCredito, $referenciaCredito, $garantiaCredito,
                                            $numeroInternoVehiculo, $placaVehiculo, $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $interesMoratorio,
                                            $valorEnLetras, $fechaLargaDesembolso, $valorCuota
                                        ); 
        $titulo                      = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);

        $generarPdf                  = new generarPdf();
       
        $generarPdf->generarPagareColocacion($titulo, $contenido, $numeroPagare, $documento, 'I' );*/


        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 3)->first();   
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';   
        $fechaLargaPrestamo         = '29 de AGOSTO del 2023';
        $numeroPagare               = '55546';
        $documento                  = '88143913';
        $documentoAsociado          = number_format($documento, 0, ',', '.');
        $buscar                     = Array('nombreAsociado', 'numeroPagare', 'fechaLargaPrestamo');
        $remplazo                   = Array( $nombreAsociado, $numeroPagare, $fechaLargaPrestamo); 
        $titulo                     = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                  = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);
        $generarPdf                  = new generarPdf();       
        $generarPdf->generarCartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, 'I' );


        //

        

        /*$rutaCarpeta       = public_path().'/archivos/radicacion/documentoEntrante/2023';
        $nombreArchivoPdf = '913_autorizacion.pdf';
        $dataCopias    = [];

        $dataRadicado = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo',    'rde.radoenasunto as asunto',
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid) AS totalCopias'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('dependencia as d', 'd.depeid', '=', 'rde.depeid')
                        ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                        ->where('rde.radoenid', 11)->first();

                if($dataRadicado->totalCopias > 0){
                $dataCopias    =  DB::table('radicaciondocentdependencia as rded')
                            ->select('d.depenombre','d.depecorreo')
                            ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                            ->where('rded.radoenid', 11)->get();
                }

                $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivoPdf, $dataRadicado, $dataCopias, true);

                dd("verificado");
     
        try {       

           $sourcePdf = public_path('prueba.pdf');

            // Crea una instancia de FPDI
            $pdf = new FPDI();
    
            // Agrega la página del archivo PDF fuente al PDF actual
            $pageId = $pdf->setSourceFile($sourcePdf);
            $pdf->AddPage();
            $tplId = $pdf->importPage($pageId);
            $pdf->useTemplate($tplId);
    
            // Opcional: puedes agregar tu contenido o modificaciones aquí
            // Por ejemplo, agregar texto
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(10, 10);
            $pdf->Write(0, '2222 Este es un PDF generado con FPDI y TCPDF. ....');
    
            // Genera la salida del PDF
            $pdf->Output('output.pdf', 'I');

            //return true;
		} catch (Exception $e) {
           // return false;
		}*/

    }

}