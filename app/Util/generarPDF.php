<?php

namespace App\Util;
use Illuminate\Support\Facades\Crypt;
use App\Util\showTipoDocumental;
use Auth, PDF, DB, URL, File;
use App\Util\generales;
use App\Util\encrypt;
use Carbon\Carbon;

class generarPDF
{
	function acta($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->acta($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
			 $urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codopaanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codopasigla;
		$consecutivo          = $infodocumento->codopaconsecutivo;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codopaconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;
	
		$dependencia  		  = $infodocumento->depenombre;
		$tipactid     		  = $infodocumento->tipactid;
		$tipoActa     		  = $infodocumento->tipactnombre;
		$horaInicial  		  = $infodocumento->codopahorainicio." a ".$infodocumento->codopahorafinal." horas";
		$lugar        		  = $infodocumento->codopalugar;   
		$asistente    		  = $infodocumento->codoprnombredirigido;
		$invitados    		  = $infodocumento->codopainvitado;
		$ausentes     		  = $infodocumento->codopaausente;
		$ordenDia     		  = $infodocumento->codopaordendeldia;
		$quorum       		  = $infodocumento->codopaquorum;
		$contenido    		  = $infodocumento->codoprcontenido;
		$convocatoria 		  = $infodocumento->codopaconvocatoria;
		$tipoMedio 			  = $infodocumento->tipmedid;
		$convocatorialugar    = $infodocumento->codopaconvocatorialugar;
		$convocatoriafecha    = $infodocumento->codopaconvocatoriafecha;
		$convocatoriahora     = $infodocumento->codopaconvocatoriahora;
		$convocatoriaDescripcion = ($convocatoria == 1) ? $this->obtenerConvocatoriaActa($convocatorialugar, $convocatoriafecha, $convocatoriahora) : '';	

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		//PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36, 20);
		PDF::AddPage('P', array(216,332));
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(24); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(16);
	    PDF::SetFont('helvetica', 'B', 12);
	    PDF::Cell(165, 4, 'ACTA ' . $consecutivo, 0, 0, 'C');
	    PDF::Ln(6);
	    PDF::MultiCell(165, 0, $dependencia, 0, 'C', false, 1);
	    if ($tipactid >= '2') {
	        PDF::Cell(0, 5, $tipoActa, 0, 0, 'C');
	        PDF::Ln(4);
	    }
		PDF::Ln(8);
	    PDF::Cell(40, 4, 'HORA: ', 0, 0, '');
        PDF::SetFont('helvetica', '', 12);
        PDF::Cell(125, 4, $horaInicial, 0, 0, '');
        PDF::Ln(8);
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(40, 4, 'LUGAR: ', 0, 0, '');
        PDF::SetFont('helvetica', '', 12);
        PDF::MultiCell(125, 4, $lugar."\n", 0, 'J', 0);
        PDF::Ln(8);
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(40, 4, 'ASISTENTES:', 0, 0, '');
        PDF::SetFont('helvetica', '', 12);
        PDF::MultiCell(125, 4, $asistente."\n", 0, 'J', 0);
        PDF::Ln(8);

        if ($invitados != '') {
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Cell(40, 4, 'INVITADOS:', 0, 0, '');
            PDF::SetFont('helvetica', '', 12);
            PDF::MultiCell(125, 4, $invitados."\n", 0, 'J', 0);
            PDF::Ln(8);
        }

        if ($ausentes != '') {
            PDF::SetFont('helvetica', 'B', 12);
            PDF::Cell(40, 4, 'AUSENTES:', 0, 0, '');
            PDF::SetFont('helvetica', '', 12);
            PDF::MultiCell(125, 4, $ausentes."\n", 0, 'J', 0);
            PDF::Ln(8);
        }
       
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(40, 4, 'ORDEN DEL DÍA:', 0, 0, '');
        PDF::SetFont('helvetica', '', 12);
        PDF::MultiCell(125, 4,$ordenDia."\n", 0, 'J', 0);
        PDF::Ln(4);
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(40, 4, 'DESARROLLO', 0, 0, '');
        PDF::Ln(8);

        if ($quorum != '') {
            PDF::SetFont('helvetica', '', 12);
            PDF::MultiCell(170, 4, $quorum."\n", 0, 'J', 0);
            PDF::Ln(4);
        }

		PDF::writeHTML($contenido, true, 1, true, true);		
		PDF::Ln(8);

		if ($convocatoria === 1) {
			PDF::SetX(26);    
	        PDF::SetFont('helvetica', 'B', 12);
	        PDF::Cell(38, 4, 'CONVOCATORIA.', 0, 0, '');	
	        PDF::SetFont('helvetica', '', 12);
	        PDF::MultiCell(127, 4,$convocatoriaDescripcion."\n", 0, 'J', 0);
	        PDF::Ln(12);
	    }else{
			PDF::Ln(4);
		}

		$this->imprimirFirmasDocumento($firmasDocumento, $estadoDocumento);

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(20);
		}

		//Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
	}

	function certificado($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->certificado($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
			 $urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codopcanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codopcsigla;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codopcconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;

		$titulo               = $infodocumento->codopctitulo;
		$contenido            = '<p style="text-align:justify">'.$infodocumento->tipedonombre.' <b>'.$infodocumento->codoprnombredirigido.'</b>, '.$infodocumento->codopccontenidoinicial.'</p>';
		$contenidoAdicional   = $infodocumento->codoprcontenido;
		$tipoMedio            = $infodocumento->tipmedid;
		$firmado              = $infodocumento->codoprfirmado;
		$transcriptor         = $infodocumento->alias;
		$fechaDocumento 	  = $cidudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Certificado, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,$titulo,0,0,'C'); 
		PDF::Ln(20);
		PDF::Cell(175,4,'CERTIFICA QUE: ',0,0,'C');
		PDF::SetFont('helvetica','',12); 
		PDF::Ln(20);
		PDF::writeHTML($contenido, true, 1, true, true);
		PDF::Ln(4);
		PDF::writeHTML($contenidoAdicional, true, 1, true, true);
		PDF::Ln(4);
		PDF::Cell(60,4,$fechaDocumento,0,0,'');
		PDF::Ln(26);
		
		foreach ($firmasDocumento as $firma)
		{
			$remitente      = $firma->nombrePersona;
			$cargoRemitente = $firma->carlabnombre;
			$rutaFirma      = $firma->firmaPersona;
			$firmado        = $firma->codopffirmado;

			($firmado == 1) ? PDF::Image($rutaFirma, 80, PDF::GetY(), 46, 13) : $this->mensajeFirmarCentro(); 
			PDF::Ln(($firmado == 1) ? 6 : 0);

			PDF::Ln(8);
			PDF::SetFont('helvetica','B',12); 
			PDF::Cell(165,4,$remitente,0,0,'C'); 
			PDF::Ln(4);
			PDF::Cell(165,4,$cargoRemitente,0,0,'C');
		}

		PDF::Ln(12);
		PDF::SetFont('helvetica','',9);
		PDF::Cell(30,4,$transcriptor,0,0,'');

	    //Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
	}

	function circular($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->circular($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
		$urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codoplanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codoplsigla;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codoplconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;

		$circularNumero       = " CIRCULAR Nº  ".$infodocumento->codoplconsecutivo;
		$correos              = $infodocumento->codoprcorreo;
		$asunto               = $infodocumento->codoprasunto;
		$nombreDirigido       = $infodocumento->codoprnombredirigido;
		$cargoDirigido        = $infodocumento->codoprcargonombredirigido;	
		$contenido            = $infodocumento->codoprcontenido;
		$despedida            = $infodocumento->tipdesnombre;
		$tipoDestino          = $infodocumento->tipdetid;
		$tieneCopia           = $infodocumento->codoprtienecopia; 
		$tieneAnexo     	  = $infodocumento->codoprtieneanexo;
		$nombreAnexo    	  = $infodocumento->codopranexonombre;
		$nombreCopia    	  = $infodocumento->codoprcopianombre;
		$tipoMedio      	  = $infodocumento->tipmedid;
		$firmado        	  = $infodocumento->codoprfirmado;
		$transcriptor   	  = $infodocumento->alias;
		$fechaDocumento 	  = $cidudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		
        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Circular, documento,'.$siglaEmpresa.', '.$asunto.', '.$circularNumero);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(34);
		PDF::Ln(4);
		PDF::Cell(0, 5, $circularNumero, 0, 0, 'C');
	    PDF::Ln(12);
	    PDF::SetFont('helvetica', 'I', 12);
	    PDF::Cell(70, 4, $fechaDocumento, 0, 0, '');
	    PDF::Ln(12);
	    PDF::SetFont('helvetica', '', 12);
	    PDF::Cell(22, 4, 'PARA: ', 0, 0, '');
		PDF::SetFont('helvetica', 'B', 12);
	    PDF::MultiCell(0, 4, $nombreDirigido."\n", 0, 'J', 0);
	    PDF::Ln(8);
	    PDF::SetFont('helvetica', '', 12);
	    PDF::Cell(22, 4, 'ASUNTO:', 0, 0, '');
	    PDF::MultiCell(0, 4, $asunto."\n", 0, 'J', 0);
	    PDF::Ln(12);
	    PDF::writeHTML($contenido, true, 0, true, true);
	    PDF::Ln(8);
	    PDF::Cell(60, 4,$despedida, 0, 0, '');
	    PDF::Ln(28);

		$this->imprimirFirmasDocumento($firmasDocumento, $estadoDocumento);

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(16);
		}

		//verifico si tiene adjunto
		if($tieneAnexo == 1){
			PDF::Cell(20, 4, 'Anexos:', 0, 0, '');
			$this->imprimirRutaAnexo($anexosDocumento, $siglaDependencia, $anioDocumento);
			if($nombreAnexo != '') {
	            PDF::MultiCell(0, 4, $nombreAnexo, 0, '', 0);
				PDF::Ln(4);
	        }
		}

		//Verifico si tiene copia
		if($tieneCopia == 1 || $correos !== ''){
			$this->imprimirCopiaDocumento($copiaDependencias, $nombreCopia, $correos);			
		}

		PDF::Cell(30,4,$transcriptor,0,0,'');

	    //Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
    }

	function citacion($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $firmaInvitados) = $visualizar->citacion($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
			 $urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codoptanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codoptsigla;
		$consecutivo          = $infodocumento->codoptconsecutivo;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codoptconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;

		$correos              = $infodocumento->codoprcorreo;
		$dependencia  		  = $infodocumento->dependencia;
		$tipactid     		  = $infodocumento->tipactid;
		$tipoActa     		  = $infodocumento->tipactnombre;
		$contenido    		  = $infodocumento->codoprcontenido;
		$lugar        		  = $infodocumento->codoptlugar; 
		$hora 			      = $infodocumento->codopthora; 
		$tipoMedio 			  = $infodocumento->tipmedid;

		$tipoMedio      	  = $infodocumento->tipmedid;
		$firmado        	  = $infodocumento->codoprfirmado;
		$transcriptor   	  = $infodocumento->alias;
		$fechaDocumento 	  = $cidudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$fechaRealizacion     = $funcion->formatearFecha($infodocumento->codoptfecharealizacion);

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		//PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36, 20);
		PDF::AddPage('P', array(216,332));
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(24); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(16);
	    PDF::SetFont('helvetica', 'B', 12);
	    PDF::MultiCell(170, 0, $dependencia, 0, 'C', false, 1);
	    PDF::Cell(170, 4, 'CITACIÓN ' . $consecutivo, 0, 0, 'C');  
	    if ($tipactid >= '2'){
	    	PDF::Ln(5);
	        PDF::Cell(170, 5, $tipoActa, 0, 0, 'C');
	        PDF::Ln(4);
	    }

	    PDF::SetFont('helvetica', '', 12);
	    PDF::Ln(18); 
	    PDF::Cell(20, 4, 'FECHA:', 0, 0, '');
	    PDF::SetX(50);
	    PDF::Cell(70, 4, $fechaDocumento, 0, 0, '');
	    PDF::Ln(5); 
	    PDF::Cell(20, 4, 'HORA:', 0, 0, '');
	    PDF::SetX(50);
	    PDF::Cell(70, 4, $hora, 0, 0, '');
	    PDF::Ln(4);
	    PDF::Cell(20, 4, 'LUGAR:', 0, 0, '');
	    PDF::SetX(50);
	    PDF::Cell(70, 4, $lugar, 0, 0, '');
	    PDF::Ln(16); 
	    PDF::Cell(170, 4, 'ORDEN DEL DÍA', 0, 0, '');
	    PDF::Ln(8); 
	    PDF::writeHTML($contenido, true, 0, true, true);
	    PDF::Ln(8);
	    PDF::Cell(120, 4, $fechaRealizacion, 0, 0, '');
	    PDF::Ln(24);
	    PDF::SetFont('helvetica', '', 11);

		$this->imprimirFirmasDocumento($firmasDocumento, $estadoDocumento);

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(20);
		}

		//Firmas para los invitados
		if (count($firmaInvitados) > 0) {
			PDF::Ln(12); 
			PDF::SetFont('helvetica', 'B', 11);
			PDF::Cell(80, 4, 'INVITADOS', 0, 0, '');
			PDF::Ln(20); 
			PDF::SetFont('helvetica', '', 11);
			$this->imprimirFirmasDocumento($firmaInvitados, $estadoDocumento);
			PDF::Ln(16);
		}

		if($correos !== ''){
			$this->imprimirCopiaDocumento([], '', $correos);
		}

		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		//Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
	}

	function constancia($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->constancia($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
			 $urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codopnanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codopnsigla;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codopnconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;

		$titulo               = $infodocumento->codopntitulo;
		$contenido            = '<p style="text-align:justify">'.$infodocumento->tipedonombre.' <b>'.$infodocumento->codoprnombredirigido.'</b>, '.$infodocumento->codopncontenidoinicial.'</p>';
		$contenidoAdicional   = $infodocumento->codoprcontenido;	
		$tipoMedio            = $infodocumento->tipmedid;
		$firmado              = $infodocumento->codoprfirmado;
		$transcriptor         = $infodocumento->alias;
		$fechaDocumento 	  = $cidudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Constancia, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,$titulo,0,0,'C'); 
		PDF::Ln(20);
		PDF::Cell(175,4,'HACER CONSTAR QUE: ',0,0,'C');
		PDF::SetFont('helvetica','',12); 
		PDF::Ln(20);
		PDF::writeHTML($contenido, true, 1, true, true);
		PDF::Ln(4);
		PDF::writeHTML($contenidoAdicional, true, 1, true, true);
		PDF::Ln(4);
		PDF::Cell(60,4,$fechaDocumento,0,0,'');
		PDF::Ln(26);
		
		foreach ($firmasDocumento as $firma)
		{
			$remitente      = $firma->nombrePersona;
			$cargoRemitente = $firma->carlabnombre;
			$rutaFirma      = $firma->firmaPersona;
			$firmado        = $firma->codopffirmado;

			($firmado == 1) ? PDF::Image($rutaFirma, 80, PDF::GetY(), 46, 13) : $this->mensajeFirmarCentro(); 
			PDF::Ln(($firmado == 1) ? 6 : 0);

			PDF::Ln(8);
			PDF::SetFont('helvetica','B',12); 
			PDF::Cell(165,4,$remitente,0,0,'C'); 
			PDF::Ln(4);
			PDF::Cell(165,4,$cargoRemitente,0,0,'C');
		}		

		PDF::Ln(12);
		PDF::SetFont('helvetica','',9);		
		PDF::Cell(30,4,$transcriptor,0,0,'');

	    //Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
	}

    function oficio($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $copiaDependencias, $anexosDocumento) =  $visualizar->oficio($id);
		list($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa,
		$urlEmpresa, $nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $logoEmpresa) = $this->consultarEmpresa();

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codopoanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codoposigla;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codopoconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;
			
		$correos              = $infodocumento->codoprcorreo;
		$titulo               = $infodocumento->codopotitulo;
		$nombreDirigido       = $infodocumento->codoprnombredirigido;
		$cargoDirigido        = $infodocumento->codoprcargonombredirigido;
		$ciudad               = $infodocumento->codopociudad;
		$asunto               = $infodocumento->codoprasunto;
		$saludo               = $infodocumento->tipsalnombre;
		$contenido            = $infodocumento->codoprcontenido;
		$despedida            = $infodocumento->tipdesnombre;
		$tipoDestino          = $infodocumento->tipdetid;
		$empresaOficio        = $infodocumento->codopoempresa;
		$direreccionOficio    = $infodocumento->codopodireccion;
		$telefonoOficio       = $infodocumento->codopotelefono; 
		$tieneCopia           = $infodocumento->codoprtienecopia; 
		$tieneAnexo     	  = $infodocumento->codoprtieneanexo;
		$nombreAnexo    	  = $infodocumento->codopranexonombre;
		$nombreCopia    	  = $infodocumento->codoprcopianombre;
		$tipoMedio      	  = $infodocumento->tipmedid;
		$firmado        	  = $infodocumento->codoprfirmado;
		$transcriptor   	  = $infodocumento->alias;
		$fechaDocumento 	  = $cidudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);

        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Oficio, documento,'.$siglaEmpresa.', '.$asunto);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(16);
		PDF::Ln(24);
		PDF::SetFont('helvetica', '', 12);
	    PDF::Cell(80, 4, $fechaDocumento, 0, 0, '');
	    PDF::Ln(20);

	    if($titulo!=''){
		    PDF::Cell(80, 4, $titulo, 0, 0, '');
		    PDF::Ln(5);
	    }

	    PDF::SetFont('helvetica', 'B', 12);
	    PDF::MultiCell(0, 4, $nombreDirigido, 0, '', 0);
	    PDF::SetFont('helvetica', '', 12);

	    if ($cargoDirigido != '') {
	        PDF::Cell(100, 4, $cargoDirigido, 0, 0, '');
	        PDF::Ln(5);
	    }

		if ($tipoDestino != 1){//si el tipo destino es diferente de interno
			if($empresaOficio != ''){
				PDF::MultiCell(160, 4, $empresaOficio, 0, '', 0);
			}

			if($direreccionOficio){
				PDF::Cell(80, 4, $direreccionOficio, 0, 0, '');
				PDF::Ln(4);
			}

			if($telefonoOficio != ''){
				PDF::Cell(40, 4, $telefonoOficio, 0, 0, '');
				PDF::Ln(4);
			}
		}

	    PDF::Cell(80, 4, $ciudad, 0, 0, '');
	    PDF::Ln(16);
	    PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(20, 4, 'Asunto: ', 0, 0, '');
        PDF::SetFont('helvetica', '', 12);
	    PDF::MultiCell(0, 4, $asunto, 0, '', 0);
		
		PDF::Ln(8);
	    PDF::Cell(60, 4,$saludo, 0, 0, '');
		PDF::Ln(16);
		PDF::writeHTML($contenido, true, false, true, false, '');
	    PDF::Ln(8);
	    PDF::Cell(60, 4,$despedida, 0, 0, '');
	    PDF::Ln(20);

		$this->imprimirFirmasDocumento($firmasDocumento, $estadoDocumento);

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(20);
		}

		//verifico si tiene adjunto
		if($tieneAnexo == 1){
			PDF::Cell(20, 4, 'Anexos:', 0, 0, '');
			$this->imprimirRutaAnexo($anexosDocumento, $siglaDependencia, $anioDocumento);
			if($nombreAnexo != '') {
	            PDF::MultiCell(0, 4, $nombreAnexo, 0, '', 0);
	        }
		}

		//Verifico si tiene copia
		if($tieneCopia == 1 || $correos !== ''){
			$this->imprimirCopiaDocumento($copiaDependencias, $nombreCopia, $correos);
		}
	
		PDF::Cell(30,4,$transcriptor,0,0,'');

	    //Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
    }

	function consultarEmpresa(){
		$empresa          = DB::table('empresa as e')
									->select('e.emprdireccion','e.emprnombre','e.emprurl','e.emprsigla','e.emprtelefonofijo', 
											'e.emprtelefonocelular', 'e.emprcorreo','e.emprlema','e.emprlogo', 'm.muninombre')
									->join('municipio as m', 'm.muniid', '=', 'e.emprmuniid')
									->where('e.emprid', 1)
									->first();

		$direccionEmpresa = $empresa->emprdireccion;
		$cidudadEmpresa   = $empresa->muninombre;
		$barrioEmpresa    = 'Santa clara'; 
		$telefonoEmpresa  = $empresa->emprtelefonofijo;
		$celularEmpresa   = $empresa->emprtelefonocelular;
		$urlEmpresa       = $empresa->emprurl;
		$nombreEmpresa    = $empresa->emprnombre; 
		$lemaEmpresa      = $empresa->emprlema;
		$siglaEmpresa     = $empresa->emprsigla;
		$logoEmpresa      = $empresa->emprlogo;

		return array ($direccionEmpresa, $cidudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $nombreEmpresa, $lemaEmpresa, $siglaEmpresa, $logoEmpresa);
	}
	
	function headerDocumento($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa){
		PDF::setHeaderCallback(function($pdf) use ($nombreEmpresa, $lemaEmpresa, $codigoInstitucional, $codigoDocumental, $logoEmpresa){
			$linea = str_pad('',  71, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,24,4,24,18);
			PDF::SetY(8);
            PDF::SetX(46);
			PDF::SetFont('helvetica','B',14);
		    PDF::Cell(144,5,$nombreEmpresa,0,0,'C');
		    PDF::SetFont('helvetica','I',12);
		    PDF::Ln(6);
		    PDF::SetX(46);
		    PDF::Cell(144,4,$lemaEmpresa,0,0,'C');
		    PDF::Ln(4);
			PDF::SetX(24);	
		    PDF::Cell(170,5,$linea,'0',0,'C');
		    PDF::Ln(6);
		    PDF::SetFont('helvetica','I',9);
		    PDF::SetX(25);
			PDF::Cell(25,4,$codigoInstitucional,0,0,'');
			PDF::Ln(4);
			PDF::SetX(25);
			PDF::Cell(25,4,$codigoDocumental,0,0,'');
			PDF::SetY(22);
        	PDF::SetX(191);
			PDF::Cell(4, 10, 'Pag. ' . PDF::getAliasNumPage() . '(' . PDF::getAliasNbPages() . ')', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		});
	}

	function footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, $tipoDocumeto = 'LETTER'){
		PDF::setFooterCallback(function($pdf) use ($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, $tipoDocumeto){
			$posicionY  = ($tipoDocumeto === 'LETTER') ? 268 : 303;		
			$linea = str_pad('',  52, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::SetFont('helvetica','I',12);
			PDF::Ln(2);
			PDF::SetY($posicionY);
			PDF::SetX(30);
			PDF::Cell(165,4,$linea,0,0,'C');
			PDF::Ln(5);
			PDF::SetX(30);
			PDF::Cell(165,4,$direccionEmpresa.' | '.$barrioEmpresa,0,0,'C');
			PDF::Ln(5);
			PDF::SetX(30);
			PDF::Cell(165,4,'Teléfono: '.$telefonoEmpresa.' | Celular: '.$celularEmpresa.'',0,0,'C'); 
			PDF::Ln(5);
			PDF::SetX(30);
			PDF::Cell(165,4,$urlEmpresa.' '.$idCifrado,0,0,'C');

			$style = array(
				'border' => 0,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, 
				'module_width' => 1, 
				'module_height' => 1
			);

			$posicionY  = ($tipoDocumeto === 'LETTER') ? 264 : 298;	

			//Crypt::encrypt($id)
			$url = asset('verificar/documento/'.urlencode($idCifrado));
			//PDF::write2DBarcode($url, 'QRCODE,H', 20, 264, 30, 30, $style, 'N');
			PDF::write2DBarcode($url, 'QRCODE,H', 20, $posicionY, 30, 30, $style, 'N');

			if($estadoDocumento === 10){
				$posicionY  = ($tipoDocumeto === 'LETTER') ? 240 : 270;	
				PDF::SetFont('helvetica', 'B', 70);
				PDF::SetTextColor(229, 229, 229);
				PDF::StartTransform();	
				PDF::Rotate(52);
				PDF::Text(74, $posicionY, 'Documento anulado');
				PDF::StopTransform();
			}
		});
	}

	function mensajeFirmarCentro(){
		PDF::SetFont('helvetica', 'B', 12);
		PDF::SetTextColor(255, 0, 0);
		PDF::Cell(165,4,'Documento pendiente por firmar',0,0,'C');
		PDF::SetTextColor(0, 0, 0);
	}

	function obtenerConvocatoriaActa($lugar, $fecha, $hora){
		$funcion       = new generales();
		$fechaGenerada = $funcion->formatearFecha($fecha);
		return " La proxima reunión se realizará en ".$lugar." el día ".$fechaGenerada ." a partir de ".$hora." horas. ";
	}

	function imprimirFirmasDocumento($firmasDocumento, $estadoDocumento){
		$cont = 0;
		foreach ($firmasDocumento as $firma)
		{
			$remitente = $firma->nombrePersona;
			$cargo     = $firma->carlabnombre;
			$firmado   = $firma->codopffirmado;
			$rutaFirma = ($firmado == 1) ? $firma->firmaPersona : 'images/documentoSinFirma.png';

			if($cont == 0){
				//le quito -7 a la posicion y
				($estadoDocumento !== 10) ? PDF::Image($rutaFirma, 28, PDF::GetY() -7,50,8) : '';
			    PDF::writeHTMLCell(86, 4, 24, '', "<b>".$remitente."</b><br>".$cargo."<br>", 0, 0, 0, true, 'J');
			    $cont += 1;
			}else{
				($estadoDocumento !== 10) ? PDF::Image($rutaFirma, 114, PDF::GetY() -7,50,8) : '';
			    PDF::writeHTMLCell(86, 4, 112, '', "<b>".$remitente."</b><br>".$cargo."<br>", 0, 0, 0, true, 'J');
			    PDF::Ln(24);
			    $cont = 0;
			}
		}
	}

	function imprimirRutaAnexo($anexosDocumento, $siglaDependencia, $anioDocumento){
		foreach ($anexosDocumento as $anexo)
		{
			$nombreArchivo = $anexo->nombreOriginal;
			$nombreEditado = $anexo->nombreEditado;
			$rutaAdjunto   = asset('/archivos/produccionDocumental/adjuntos/'.$siglaDependencia.'/'.$anioDocumento.'/'.Crypt::decrypt($anexo->rutaAnexo)); 
$html = <<<EOD
	<a href="$rutaAdjunto" target="\_blank" title="$nombreArchivo">$nombreArchivo</a>
EOD;
			PDF::writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true); 
			PDF::Cell(20, 4, '', 0, 0, '');
		}
	}

	function imprimirCopiaDocumento($copiaDependencias, $nombreCopia, $correos){
		PDF::Ln(4);
		PDF::Cell(20, 4, 'Copia:', 0, 0, '');
		//imprimo las depedencias a las que va dirigida la copia
		foreach ($copiaDependencias as $copiaDependencia)
		{
			PDF::MultiCell(140, 4, $copiaDependencia->depenombre, 0, '', 0);
			PDF::Cell(20, 4, '', 0, 0, '');
		}

		($nombreCopia != '') ? PDF::MultiCell(140, 4, $nombreCopia, 0, '', 0) : '';	

		if ($correos != '') {
			$array = explode(',', $correos);
			$conta = 1;
			($nombreCopia != '') ? PDF::Cell(20, 4, '', 0, 0, '') : '';	
			foreach ($array as $dato) {
				//separación entre un correo y otro
				PDF::Cell(80, 4, $dato, 0, 0, '');
				if ($conta == 2) {
					PDF::Ln(4);
					PDF::SetX(30);
					PDF::Cell(14, 4, '', 0, 0, '');
					$conta = 1;
				} else {
					$conta++;
				}
			}
			PDF::Ln(8);
		}
	}

	function outputPdfDocumental($codigoInstitucional, $fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia, $anioDocumento){	
		$nombrePdf = $codigoInstitucional.'-'.$fechaActualDocumento.'.pdf';	
		$tituloPdf = $codigoDocumental.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en el servidor	
			$rutaCarpeta  = public_path().'/archivos/produccionDocumental/digitalizados/'.$siglaDependencia.'/'.$anioDocumento;
			$carpetaServe = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
			$rutaPdf      = $carpetaServe.'/'.$nombrePdf;
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}
}