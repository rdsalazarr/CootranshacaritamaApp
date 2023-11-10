<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use DB, PDF, Auth, URL, Artisan;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Util\generarPdf;
use App\Util\generales;
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
        $generales  = new generales();  
        $generarPdf = new generarPdf();
        $arrayDatos = [ 
                        "numeroContratoEspecial"       => '45400830220230693',
                        "numeroContratoCompletoUno"    => '454008302202306930781',
                        "numeroContratoCompletoDos"    => '454008302202306930782',
                        "numeroContrato"               => '0693',
                        "numeroExtracto"               => '0781', 
                        "nombreContratante"            => 'COLEGIO CRISTIANO LUZ Y VIDA', 
                        "documentoContratante"         => '37313214_8',
                        "objetoContrato"               => 'La prestación del servicio de transporte de los estudiantes entre el lugar de residencia y el establecimiento educativo u otros destinos que se requieran en razón de las actividades programadas por el plantel educativo, según el decreto 0348 del 245 de febrero de 2015',
                        "origenContrato"               => 'OCAÑA ( N DE S)',
                        "destinoContrato"              => 'CASCO URBANO DE OCAÑA', 
                        "descripcionRecorrido"         => 'CASCO URBANO DE OCAÑA',
                        "convenioContrato"             => 'X',
                        "consorcioContrato"            => '',
                        "unionTemporal"                => '',
                        "nombreUnionTemporal"          => '',
                        "placaVehiculo"                => 'TTR122',
                        "modeloVehiculo"               => '2013',
                        "marcaVehiculo"                => 'NISSAN',
                        "claseVehiculo"                => 'MICROBUS',
                        "numeroInternoVehiculo"        => '486',
                        "tarjetaOperacionVehiculo"     => '282487',
                        "nombreContratante"            => 'ELIZABETH LOPEZ BARBOSA',
                        "documentoContratante"         => '37313214',
                        "direccionContratante"         => 'BARRIO EL CENTRO',
                        "telefonoContratante"          => '3103006860',
                        "firmaGerente"                 => 'archivos/persona/5036123/firma_5036123.png',
                        "nombreGerente"                => 'LUIS MANUEL ASCANIO CLARO',
                        "documentoGerente"             => '37.336.963',
                        "valorContrato"                => '1,500,000',
                        "fechaInicialContrato"         => $generales->formatearFechaContratoServicioEspecial('2023-11-01'),
                        "fechaFinalContrato"           => $generales->formatearFechaContratoServicioEspecial('2023-11-30'),
                        "descripcionServicoContratado" => 'DOS (2) vehículo(s) con número(s) interno(s) 473, 486 con 16, 16 puestos',
                        "idCifrado"                    => '123',
                        "metodo"                       => 'I'
                    ];

        $arrayVigenciaContrato = [];
        $fechaInicio = [
                        "dia"  => '01',
                        "mes"  => '11',
                        "anio" => '2023',
                    ];        
        $fechaFin = [
                    "dia"  => '30',
                    "mes"  => '11',
                    "anio" => '2023',
                ];        
        array_push($arrayVigenciaContrato, $fechaInicio, $fechaFin);

        $arrayConductores  = [];
        $conductor = [
                    "nombreCompleto" => 'ELAIN MACHADO DOMINGUEZ ELAIN MACHADO DOMINGUEZ',
                    "documento"      => '88284528',
                    "numeroLicencia" => '88284528',
                    "vigencia"       => '2025-10-04',
                    ]; 
        array_push($arrayConductores, $conductor);

        $conductor = [
                        "nombreCompleto" => 'HUBERNEY SALAZAR AMAYA',
                        "documento"      => '10648419387',
                        "numeroLicencia" => '10648419387',
                        "vigencia"       => '2025-09-26',
                        ]; 
        array_push($arrayConductores, $conductor);

        $conductor = [
                        "nombreCompleto" => 'DEIVER MORA GARCIA',
                        "documento"      => '1977858',
                        "numeroLicencia" => '1977858',
                        "vigencia"       => '2025-02-08',
                        ]; 
       array_push($arrayConductores, $conductor);

        $generarPdf->contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $arrayConductores);

        /*PDF::setHeaderCallback(function($pdf) use ($idCifrado, $logoEmpresa) {
            $idCifrado = '123';
            $logoEmpresa = '890505424_logoHacaritama.png';

            $style = array(
				'border'        => 0,
				'vpadding'      => 'auto',
				'hpadding'      => 'auto',
				'fgcolor'       => array(0,0,0),
				'bgcolor'       => false,
				'module_width'  => 1,
				'module_height' => 1
			);

            $url = asset('verificar/contrato/servicio/especial/'.urlencode($idCifrado));
            PDF::write2DBarcode($url, 'QRCODE,H', 10, 6, 30, 30, $style, 'N');
            PDF::Image('images/logoColombiaPotenciaVida.jpg',50,12,70,14);
            PDF::Image('images/logoSuperTransporte.png',140,12,26,14);
            PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,170,9,26,26);
		});*/


        /*PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('Hacatitama');
		PDF::SetSubject("Formato único de extracto del contrato del servicio público de transporte terrestre automotor especial Nº ");
		PDF::SetKeywords('Formato, contrato, servicio público ');
        PDF::SetTitle("Formato contrato del servicio público de transporte terrestre ");
        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::SetMargins(10, 30 , 10);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(4);
        PDF::Cell(26, 4,"", 0, 0,'C'); 
        PDF::MultiCell(140, 4, "FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL Nº 454008302202306930781", 0, 'C', 0);
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'RAZON SOCIAL DE LA EMPRESA DE TRANSPORTE ESPECIAL','LTR',0,'L'); 
        PDF::Cell(60,5,'NIT','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5,'COOPERATIVA DE TRANSPORTADORES HACARITAMA','LBR',0,'L'); 
        PDF::Cell(60,5,'890505424-7','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATO:  0693','LBR',0,'L'); 
        PDF::Cell(60,5,'EXTRACTO:  0781','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATANTE','LTR',0,'L'); 
        PDF::Cell(60,5,'NIT/CC','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5,'COLEGIO CRISTIANO LUZ Y VIDA','LBR',0,'L'); 
        PDF::Cell(60,5,'37313214_8','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'OBJETO DEL CONTRATO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(190, 5, "La prestación del servicio de transporte de los estudiantes entre el lugar de residencia y el establecimiento educativo u otros destinos que se requieran en razón de las actividades programadas por el plantel educativo, según el decreto 0348 del 245 de febrero de 2015.", 'LTR', 'J', 0);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'ORIGEN','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,'OCAÑA ( N DE S)','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'DESTINO','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,'CASCO URBANO DE OCAÑA','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'DESCRIPCION DEL RECORRIDO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(190, 5, "CASCO URBANO DE OCAÑA", 'LTR', 'L', 0);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30,5,'CONVENIO:','LTR',0,'L');
        PDF::Cell(8,5,'X','LTR',0,'C');
        PDF::Cell(30,5,'CONSORCIO:','LTR',0,'L');
        PDF::Cell(8,5,' ','LTR',0,'C');
        PDF::Cell(34,5,'UNION TEMPORAL:','LTR',0,'L');
        PDF::Cell(8,5,' ','LTR',0,'C');
        PDF::Cell(72,5,'CON:','LTR',0,'L');
        PDF::Ln(5);
        PDF::Cell(190,5,'VIGENCIA DEL CONTRATO','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(47.5,5,'FECHA INICIAL','LTR',0,'C');
        PDF::Cell(47.5,5,'DIA','LTR',0,'C');
        PDF::Cell(47.5,5,'MES','LTR',0,'C');
        PDF::Cell(47.5,5,'AÑO','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'','LTR',0,'C');
        PDF::Cell(47.5,5,'01','LTR',0,'C');
        PDF::Cell(47.5,5,'11','LTR',0,'C');
        PDF::Cell(47.5,5,'2023','LTR',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(47.5,5,'FECHA INICIAL','LTR',0,'C');
        PDF::Cell(47.5,5,'DIA','LTR',0,'C');
        PDF::Cell(47.5,5,'MES','LTR',0,'C');
        PDF::Cell(47.5,5,'AÑO','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'','LTR',0,'C');
        PDF::Cell(47.5,5,'30','LTR',0,'C');
        PDF::Cell(47.5,5,'11','LTR',0,'C');
        PDF::Cell(47.5,5,'2023','LTR',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(190,5,'CARACTERISTICAS DEL VEHICULO','LTR',0,'C');
        PDF::Ln(5);        
        
        PDF::Cell(47.5,5,'PLACA','LTR',0,'C');
        PDF::Cell(47.5,5,'MODELO','LTR',0,'C');
        PDF::Cell(47.5,5,'MARCA','LTR',0,'C');
        PDF::Cell(47.5,5,'CLASE','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'TTR122','LTR',0,'C');
        PDF::Cell(47.5,5,'2013','LTR',0,'C');
        PDF::Cell(47.5,5,'NISSAN','LTR',0,'C');
        PDF::Cell(47.5,5,'MICROBUS','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(95,5,'NÚMERO INTERNO','LTR',0,'C');
        PDF::Cell(95,5,'TARJETA DE OPERACIÓN','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(95,5,'486','LTR',0,'C');
        PDF::Cell(95,5,'282487','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 1
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'ELAIN MACHADO DOMINGUEZ','LTR',0,'L');
        PDF::Cell(26,5,'88284528 ','LTR',0,'C');
        PDF::Cell(40,5,'88284528','LTR',0,'C');
        PDF::Cell(29,5,'2025-10-04','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 2
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'HUBERNEY SALAZAR AMAYA','LTR',0,'L');
        PDF::Cell(26,5,'1064841938 ','LTR',0,'C');
        PDF::Cell(40,5,'1064841938','LTR',0,'C');
        PDF::Cell(29,5,'2025-09-26','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 3
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'DEIVER MORA GARCIA','LTR',0,'L');
        PDF::Cell(26,5,'1977858 ','LTR',0,'C');
        PDF::Cell(40,5,'1977858','LTR',0,'C');
        PDF::Cell(29,5,'2025-02-08','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(190,5,'','T',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'RESPONSABLE','LTR',0,'L');
        PDF::Cell(65,5,'NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CÉDULA ','LTR',0,'C');
        PDF::Cell(40,5,'DIRECCIÓN','LTR',0,'C');
        PDF::Cell(29,5,'TELÉFONO','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONTRATANTE','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'ELIZABETH LOPEZ BARBOSA','LTR',0,'L');
        PDF::Cell(26,5,'37313214 ','LTR',0,'C');
        PDF::Cell(40,5,'BARRIO EL CENTRO','LTR',0,'C');
        PDF::Cell(29,5,'3103006860','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(95, 7,'','LTR',0,'L');
        PDF::Cell(95, 7,'','LTR',0,'L');
        PDF::Ln(7);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(95, 6,'La Ondina via a Rio de Oro - Ocaña N. de S.','LR',0,'C');
        PDF::Cell(95, 6,'','R',0,'L');
        PDF::Ln(6);
        PDF::Cell(95, 6,'Tel. 607-5611012 - Cel: 314 2197149 - 317 6686929','LR',0,'C');
        PDF::Cell(95, 6,'','R',0,'L');
        PDF::Ln(6);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(95, 6,'cootranshacaritama@hotmail.com.','LR',0,'C');
        PDF::Cell(95, 6,'(Ley 527 de 1999, Decreto 2364 de 2012)','R',0,'C');
        PDF::Ln(6);
        PDF::Cell(95, 6,'','LRB',0,'C');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(95, 6,'FIRMA Y SELLO GERENTE DEL CONTRATO.','RB',0,'C');
        
       
        //Inicia proceso de la hoja dos
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'fichaTecnica')->first();
        $numeroContratoServicioEspecial = '454008302202306930782';  
        $buscar       = Array('numeroContratoServicioEspecial');
        $remplazo     = Array($numeroContratoServicioEspecial);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);  
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
        PDF::AddPage('P', 'Letter');
        PDF::SetMargins(20, 30 , 20);
		PDF::Ln(4);
        PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');

        //Inicia proceso de la hoja 5
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'contratoTransporteEspecial')->first();    
        $buscar       = Array('numeroContratoServicioEspecial');
        $remplazo     = Array($numeroContratoServicioEspecial);
        $numeroContratoServicioEspecial = '454008302202306930782';  
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);  
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
        PDF::AddPage('P', 'Letter');
        PDF::SetMargins(20, 30 , 20);
  
		PDF::Ln(4);
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(190, 6,'CONTRATO DE TRANSPORTE ESPECIAL 45400830220230693',0,0,'C');
        
        PDF::Ln(12);
        PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');

        PDF::Ln(32);
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(90, 6,'COLEGIO CRISTIANO LUZ Y VIDA',0,0,'C');
        PDF::Cell(10, 6,'',0,0,'C');
        PDF::Cell(90, 6,'LUIS MANUEL ASCANIO CLARO',0,0,'C');
        PDF::Ln(6);

        PDF::Cell(90, 6,'NIT/C.C: 37313214_8',0,0,'C');
        PDF::Cell(10, 6,'',0,0,'C');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(90, 6,'EL CONTRATISTA',0,0,'C');
        PDF::Ln(6);
        PDF::Cell(90, 6,'EL CONTRATANTE',0,0,'C');


        //Inicia proceso de la hoja 6	       
    


        //Inicia proceso de la hoja tres
        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::AddPage('P', 'Letter');

        PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,"hola 3",0,0,'C'); 

        // Salida del PDF
        PDF::Output();



        /*
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,"hola",0,0,'C'); 
        PDF::Ln(24);

        PDF::Cell(15,5,"hola",0,0,'C'); 

        PDF::MultiCell(145, 4, "FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL Nº 454008302202306930781", 1, 'C', 0);

        PDF::output("formato.pdf", 'I');*/


    /*// Ejemplo de uso
    $fechaActual = Carbon::now();
    //2024-03-03
    $fechaActual = '2023-10-30';
    $fechaNueva = $this->obtenerFechaPagoCuota('2023-10-30');
    echo $fechaNueva->format('Y-m-d');



  $tabla = "<table border='1'>
            <tr>
                <th>Cuota</th>
                <th>Fecha</th>
                <th>Abono a Capital</th>
                <th>Valor Cuota</th>
                <th>Abono a Intereses</th>
            </tr>";

            for ($mes = 1; $mes <= 20; $mes++) {

                $fechaNueva = $this->obtenerFechaPagoCuota($fechaActual);
                $fechaNueva = $fechaNueva->format('Y-m-d');
                $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$fechaNueva </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";

                $fechaActual = $fechaNueva ;
            }

         echo   $tabla .= "</table>";/**/
    }


    function obtenerFechaPagoCuota($fecha) {
        $fecha        = Carbon::parse($fecha);
        $nuevaFecha        = Carbon::parse($fecha);
        $diaFecha     = $fecha->day;
        $mesFecha     = $fecha->month;
        $ultimoDiaMes = $fecha->daysInMonth;
       // $totalDiasAdicionar = ($mesFecha === 2) ? 29 : (($ultimoDiaMes === 31) ? $ultimoDiaMes : 30);


        $totalDiasAdicionar = ($ultimoDiaMes === 30 ) ? 30 :  $ultimoDiaMes;

        //$totalDiasAdicionar = ($totalDiasAdicionar === 31) ? 30 : $totalDiasAdicionar;
        


        //dd($fecha->format('Y-m-d'));
        
        // Sumar 30 días a la fecha actual
        $nuevaFecha = $fecha->addDays($totalDiasAdicionar);

       // dd($fecha->format('Y-m-d'), $nuevaFecha->format('Y-m-d'));
        //$nuevaFecha = $fecha->addDays(30);
/*
       if ($diaFecha === 1) {
            $nuevaFecha->addDay();
        } */

        // Verificar si es 28 o 29 de febrero
        if (($diaFecha === 28 || $diaFecha === 29) && $mesFecha === 2) {
            //var_dump("debe entrar1");
            // Ajustar a 01 de abril
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }
    
        // Verificar si la fecha resultante es el último día del mes
       /* if ($diaFecha === $ultimoDiaMes) {
            //var_dump("debe entrar");

            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();


            // Ajustar a 01 del mes siguiente
           // $nuevaFecha->addDay();
           // $nuevaFecha->day = 1;
        }*/

       // dd( $nuevaFecha->day);
    
        // Verificar si la fecha actual es el último día de un mes de 31 días
       if ($nuevaFecha->day === 31) { // || $fecha->day === 31
        var_dump("debe entrar");
           // $nuevaFecha->setMonth($mesFecha + 1);
           // $nuevaFecha->setDay(1);

            // Ajustar a 01 del mes siguiente
            $nuevaFecha = $nuevaFecha->addDay(1);
            $nuevaFecha->day = 1;
        } 
    
        // Ajustar para evitar un día extra en febrero
        /*if ($fecha->month === 2 && ($fecha->day === 29 || ($fecha->day === 28 && !$fecha->isLeapYear()))) {
            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();
        }*/
    
        return $nuevaFecha;
    }


  
    function obtenerFechaPagoCuota2($fecha) {
        $fecha = Carbon::parse($fecha);
    
        // Sumar 30 días a la fecha actual
        $nuevaFecha = $fecha->addDays(($fecha->day === 2) ? 28 : 30);

        if ($fecha->day === 1) {
            $nuevaFecha->addDay();
        }       
    
        // Verificar si es 28 o 29 de febrero
        if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            var_dump("entra en febero");
            // Ajustar a 01 de abril
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }
    
        // Verificar si la fecha actual es el último día del mes
        if ($fecha->day === $fecha->daysInMonth) {
            var_dump("entra en  2");
            // Ajustar a 01 del mes siguiente
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }
    
        // Verificar si la fecha actual es el último día de un mes de 31 días
        if ($fecha->day === 31) {
            var_dump("entra en el 31");
            // Ajustar a 01 del mes siguiente
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }

        // Ajustar para evitar un día extra en febrero
        if ($fecha->day === 29 && $fecha->month === 2 && !$fecha->isLeapYear()) {
            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();
        }
    
        return $nuevaFecha;
    }




/*
    function obtenerFechaPagoCuota($fecha) {
        $fecha      = Carbon::parse($fecha);
        $nuevaFecha = $fecha->addDays(30);

        if ($fecha->day === 1) {
            $nuevaFecha->addDay();
        }        

        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            $nuevaFecha->setMonth(3);
            $nuevaFecha->setDay(1);
        }

        // Verificar si la fecha resultante es el último día del mes para ajustar al 01 sigiente
        if ($fecha->day == $fecha->daysInMonth) {
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }

        //dd($fecha->format('Y-m-d'));
        return $fecha;
    }
    

    /*function obtenerFechaPagoCuota($fecha) {
        $fechaProcesar = Carbon::parse($fecha); 

        // Sumar 30 días a la fecha actual
        $fecha = $fechaProcesar->addDays(30);

      //var_dump($fecha->format('Y-m-d'));
        
        
        // Verificar si la fecha resultante es 28 o 29 de febrero
       if (($fechaProcesar->day === 28 || $fechaProcesar->day === 29) && $fechaProcesar->month === 2) {
        var_dump("entra aca");
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
         if ($fechaProcesar->day == $fechaProcesar->daysInMonth) {
             var_dump($fechaProcesar->day, $fechaProcesar->daysInMonth);
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }*
    
        return $fecha;
    }*/

    function sumar30Dias($fecha) {
        // Convertir la cadena de fecha a un objeto Carbon
        $fecha = Carbon::parse($fecha);
        
        // Sumar 30 días a la fecha actual
        $fecha = $fecha->addDays(30);
        
        // Validar si es 28 o 29 de febrero
        if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
        if ($fecha->day === $fecha->daysInMonth) {
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }
    
        return $fecha;
    }
    




    function calcularCuota($montoPrestamo, $tasaInteresMensual, $plazo) {
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
        $denominador        = 1 - pow(1 + $tasaInteresMensual, -$plazo);
        $valorCuota         = ($montoPrestamo * $tasaInteresMensual) / $denominador;
        return $valorCuota;
    }

    function redonderarCienMasCercano($valor){
        return round($valor/100.0,0)*100;
    }

    function generarTablaLiquidacion($montoPrestamo, $tasaInteresMensual, $plazo) {            
        $cuotaMensual = $this->calcularCuota($montoPrestamo, $tasaInteresMensual, $plazo);
  
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
           
        $tabla = "<table border='1'>
                    <tr>
                        <th>Mes</th>
                        <th>Saldo a Capital</th>
                        <th>Abono a Capital</th>
                        <th>Valor Cuota</th>
                        <th>Abono a Intereses</th>
                    </tr>";
    
        $saldo = $montoPrestamo;
        for ($mes = 1; $mes <= $plazo; $mes++) {
            $intereses    = $saldo * $tasaInteresMensual;
            $abonoCapital = $cuotaMensual - $intereses;
            $saldo        -= $abonoCapital;

            
            $saldo        = $this->redonderarCienMasCercano($saldo);
            $abonoCapital = $this->redonderarCienMasCercano($abonoCapital);
            $cuotaMensual = $this->redonderarCienMasCercano($cuotaMensual);
            $intereses    = $this->redonderarCienMasCercano($intereses);
           
      
            if($saldo < $abonoCapital){
                $saldo = $intereses;
				//$abonoCapital = $saldo;
				//$cuotaMensual = $saldo + $intereses;
			}

            /*	
            $valorInteres =  $generales->calcularValorInteres($saldoCapital, $tasaNominal);
			$abonoCapital = round($valorCuota - $valorInteres, 0);

			if($saldoCapital < $abonoCapital){
				$abonoCapital = $saldoCapital;
				$valorCuota = $saldoCapital + $valorInteres;
			}*/


            $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$saldo</td>
                        <td>$abonoCapital</td>
                        <td>$cuotaMensual</td>
                        <td>$intereses</td>
                    </tr>";
        }
    
        $tabla .= "</table>";
    
        return $tabla;
    }

}