<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Notificar;
use DB, Auth, URL, Artisan;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Util\generarPdf;

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


        $generarPdf          = new generarPdf();
        $rutaCarpeta       = public_path().'/archivos/radicacion/documentoEntrante/2023';
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
		}

    }

}