<?php

namespace App\Util;
use Illuminate\Support\Facades\Crypt;
use App\Util\generales;
use Auth, PDF, DB;
use Carbon\Carbon;

class generarPDF
{
    function certificadoCurso($idUsuario, $metodo = 'I')
	{	
        $funcion         = new generales();
        $fechaHoraActual = Carbon::now();
        $fechaActual 	 = $fechaHoraActual->format('Y-m-d');
		$fechaActual = $funcion->formatearFecha($fechaActual);
   
        //Consulto la informacion 
        $usuario = DB::table('users as u')
                        ->select('u.documento','u.nombre','u.apellidos',
                            'u.ciudadexpedicion','ti.tipidesigla','ti.tipidenombre',
                            DB::raw('(SELECT max(evausufechahorafinal)
                                    FROM evaluacionusuario
                                    WHERE evausuuserid = u.id
                                    AND evausupuntaje >= u.puntajeevaluacion
                                    AND evausufechahorafinal IS NOT NULL
                                    ) AS fecha_respuesta')
                            )
                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'u.tipideid')
                        ->where('u.id', $idUsuario)->first(); 
        if($usuario){
            $nombrePersona     = mb_strtoupper($usuario->nombre,'UTF-8').' '. mb_strtoupper($usuario->apellidos,'UTF-8');
			$documento         = $usuario->documento;
            $tipoDocumento     = $usuario->tipidenombre;
			$numeroDocumento   = number_format($usuario->documento,0,',','.');   
            $ciudadExpedicion  = $usuario->ciudadexpedicion;
            $contenidoTres     = $funcion->formatearFechaCertificado(substr($usuario->fecha_respuesta, 0, 10) );
        }
			
		$contenidoUno  = 'Que el señor '. $nombrePersona.' Identificado con '. $tipoDocumento;
		$contenidoUno .= ' No '.$numeroDocumento.' expedida en '.$ciudadExpedicion.', está vinculado como asociado a COOPIGON.';

		$contenidoDos  = 'Realizo el curso de inducción cooperativa en COOPIGON para adquirir habilidad ';
		$contenidoDos .= 'como asociado, capacitación que incluyo temas como principios cooperativos, valores,';
		$contenidoDos .= 'estructura básica de la organización, su control, marco legal y jurídico aplicado, deberes, ';
		$contenidoDos .= 'derechos y demás que buscan crear conciencia cooperativa despertando el sentido de ';
		$contenidoDos .= 'pertenencia de nuestros asociados. ';

        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator('CURSO COOPERATIVISMO COOPIGON');
		PDF::SetSubject('Certificado curso de cooperativismo');
		PDF::SetKeywords('Certificado, curso, cooperativismo');
        PDF::SetTitle(mb_strtoupper("Certificado curso de cooperativismo de ".$nombrePersona,'UTF-8'));

		PDF::setHeaderCallback(function($pdf) {
				//PDF::Image('images/curso/logoLema.png',10,1,80,24); 
				//PDF::Image('images/curso/bannerCertificado.png',30,0.4,181,24); 
				PDF::Image('images/curso/bannerCertificado2.png',6,0.2,203.8,24); 
            }
        );

        //Pie de pagina
        PDF::setFooterCallback(function($pdf) use ($fechaActual){
			PDF::Image('images/curso/pieCertificado.png',0.2,268,210,22); 
			PDF::Ln(12);
			PDF::SetY(288);
			PDF::SetFont('helvetica','',7);
			PDF::Cell(200, 3, "Documento generado automáticamente desde www.coopigon.com el día ".$fechaActual, 0, 0, 'C');
        });

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		//Construccion del PDF
		PDF::AddPage('P', 'Letter');
		#Establecemos los márgenes izquierda, arriba y derecha: 
		PDF::SetMargins(30, 30 , 0);
		#Establecemos el margen inferior: 
		PDF::SetAutoPageBreak(true,30);	
		PDF::Ln(38); 
		PDF::SetFont('helvetica','B',12);
		PDF::Image('images/curso/logoBlancoNegro.png',7,48,174,196); 
		PDF::Image('images/curso/firmaCertificado.png',70,187,80,10); 

		PDF::MultiCell(160, 4, 'LA SUSCRITA SECRETARIA DE LA COOPERATIVA ESPECIALIZADA DE AHORRO Y CRÉDITO COOPIGON', 0, 'C', 0);
		PDF::MultiCell(160, 4, 'NIT:   800.145.149-3', 0, 'C', 0);
		PDF::Ln(12);
		PDF::MultiCell(160, 4, 'HACE CONSTAR:', 0, 'C', 0);
		PDF::Ln(16);
		PDF::SetFont('times', '', 11, '', false);
		PDF::MultiCell(160, 4, $contenidoUno."\n", 0, 'J', 0);
		PDF::Ln(6);
		PDF::MultiCell(160, 4, $contenidoDos."\n", 0, 'J', 0);
		PDF::Ln(8);
		PDF::MultiCell(160, 4, $contenidoTres."\n", 0, 'J', 0);
		PDF::Ln(4);
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(40); 

		PDF::MultiCell(160, 4, 'ANGELA CRISTINA BERMUDEZ ANGARITA', 0, 'C', 0);
		PDF::MultiCell(160, 4, 'Secretaria General', 0, 'C', 0);

		PDF::Ln(4);	
		PDF::SetFillColor(255, 0, 0);
		PDF::SetTextColor(255, 255, 255);
		PDF::Cell(160, 3, "Documento generado como prueba, no apto hasta que este en producción. ", 0, 0, 'C', true);

		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, 
			'module_width' => 1, 
			'module_height' => 1
		);	
	
		$url = asset('verificar/certificado/'.base64_encode($idUsuario));	
		PDF::write2DBarcode($url, 'QRCODE,H', 160, 214, 100, 100, $style, 'N');

        $tituloPDF = 'Certificado_'.$documento.'.pdf';
		if($metodo === 'S'){			
			return base64_encode(PDF::output($tituloPDF, 'S'));
		}else{
			PDF::output($tituloPDF, $metodo);
		}
    }
}