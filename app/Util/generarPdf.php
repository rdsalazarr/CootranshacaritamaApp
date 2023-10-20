<?php

namespace App\Util;

use setasign\FpdiProtection\FpdiProtection;
use Exception, Auth, PDF, DB, URL, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Util\showTipoDocumental;
use setasign\Fpdi\Fpdi;
use App\Util\generales;
use App\Util\encrypt;
use Carbon\Carbon;

class generarPdf
{
	function acta($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->acta($id);
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;
		$convocatoriaDescripcion = ($convocatoria == 1) ? $this->obtenerConvocatoriaActa($convocatorialugar, $convocatoriafecha, $convocatoriahora) : '';		

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 36, 20);
		PDF::AddPage('P', array(216,332));
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(24); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(12);
	    PDF::SetFont('helvetica', 'B', 12);
	    PDF::Cell(165, 4, 'ACTA ' . $consecutivo, 0, 0, 'C');
	    PDF::Ln(4);
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
		}
		PDF::Ln(12);

		$this->imprimirFirmasDocumento($firmasDocumento, $estadoDocumento);

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(20);
		}

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
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
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$fechaDocumento 	  = $ciudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Certificado, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
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
		PDF::Ln(8);
		PDF::Cell(60,4,$fechaDocumento,0,0,'');
		PDF::Ln(26);
		
		foreach ($firmasDocumento as $firma)
		{
			$remitente      = $firma->nombrePersona;
			$cargoRemitente = $firma->carlabnombre;
			$rutaFirma      = $firma->firmaPersona;
			$firmado        = $firma->codopffirmado;

			($firmado == 1) ? PDF::Image($rutaFirma, 80, PDF::GetY(), 46, 13) : $this->mensajeFirmarCentro(); 
			PDF::Ln(($firmado == 1) ? 4 : 0);

			PDF::Ln(8);
			PDF::SetFont('helvetica','B',12); 
			PDF::Cell(165,4,$remitente,0,0,'C'); 
			PDF::Ln(4);
			PDF::Cell(165,4,$cargoRemitente,0,0,'C');
		}

		PDF::Ln(12);
		PDF::SetFont('helvetica','',9);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
		}

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
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$fechaDocumento 	  = $ciudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;
		
        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Circular, documento,'.$siglaEmpresa.', '.$asunto.', '.$circularNumero);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
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

		PDF::SetFont('helvetica', 'I', 10);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
		}

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
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$fechaDocumento 	  = $ciudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$fechaRealizacion     = $funcion->formatearFecha($infodocumento->codoptfecharealizacion);
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
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

		PDF::SetFont('helvetica', 'I', 10);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
		}

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
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$fechaDocumento 	  = $ciudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Constancia, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
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
		PDF::Ln(8);
		PDF::Cell(60,4,$fechaDocumento,0,0,'');
		PDF::Ln(26);
		
		foreach ($firmasDocumento as $firma)
		{
			$remitente      = $firma->nombrePersona;
			$cargoRemitente = $firma->carlabnombre;
			$rutaFirma      = $firma->firmaPersona;
			$firmado        = $firma->codopffirmado;

			($firmado == 1) ? PDF::Image($rutaFirma, 80, PDF::GetY(), 46, 13) : $this->mensajeFirmarCentro(); 
			PDF::Ln(($firmado == 1) ? 4 : 0);

			PDF::Ln(8);
			PDF::SetFont('helvetica','B',12); 
			PDF::Cell(165,4,$remitente,0,0,'C'); 
			PDF::Ln(4);
			PDF::Cell(165,4,$cargoRemitente,0,0,'C');
		}		

		PDF::Ln(12);
		PDF::SetFont('helvetica', 'I', 10);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
		}

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
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

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
		$fechaDocumento 	  = $ciudadEmpresa.", " .$funcion->formatearFecha($infodocumento->codoprfecha);
		$totalFirmaDocumento  = $infodocumento->totalFirmaDocumento;
		$totalFirmaRealizadas = $infodocumento->totalFirmaRealizadas;

        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Oficio, documento,'.$siglaEmpresa.', '.$asunto);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
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
		if($tieneCopia == 1 || $correos !== null){
			$this->imprimirCopiaDocumento($copiaDependencias, $nombreCopia, $correos);
		}
	
		PDF::SetFont('helvetica', 'I', 10);
		PDF::Cell(30,4,$transcriptor,0,0,'');

		if ($totalFirmaDocumento === $totalFirmaRealizadas and $tipoMedio !== 1){
			$this->firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $codigoInstitucional, $firmasDocumento);
		}

	    //Imprimimos salida del pdf
		return $this->outputPdfDocumental($codigoInstitucional,$fechaActualDocumento, $codigoDocumental, $metodo, $siglaDependencia,$anioDocumento);
    }

	function consultarEmpresa(){
		$empresa          = DB::table('empresa as e')
									->select('e.emprdireccion','e.emprnombre','e.emprurl','e.emprsigla','e.emprtelefonofijo', 'e.emprpersoneriajuridica',
											'e.emprtelefonocelular', 'e.emprcorreo','e.emprlema','e.emprlogo', 'm.muninombre','e.emprbarrio',
											DB::raw("CONCAT('NIT: ', e.emprnit,' - ', e.emprdigitoverificacion) as nit"))
									->join('municipio as m', 'm.muniid', '=', 'e.emprmuniid')
									->where('e.emprid', 1)
									->first();

		$direccionEmpresa 	= $empresa->emprdireccion;
		$ciudadEmpresa    	= $empresa->muninombre;
		$barrioEmpresa    	= $empresa->emprbarrio; 
		$telefonoEmpresa  	= $empresa->emprtelefonofijo;
		$celularEmpresa   	= $empresa->emprtelefonocelular;
		$urlEmpresa       	= $empresa->emprurl;
		$nombreEmpresa      = $empresa->emprnombre; 
		$lemaEmpresa        = $empresa->emprlema;
		$siglaEmpresa       = $empresa->emprsigla;	
		$nit                = $empresa->nit;
		$personeriaJuridica = $empresa->emprpersoneriajuridica;
		$logoEmpresa        = $empresa->emprlogo;

		return array ($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $nombreEmpresa, $lemaEmpresa, $siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa);
	}
	
	function headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa){
		PDF::setHeaderCallback(function($pdf) use ($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa){
			$linea = str_pad('',  71, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,24,4,26,24);
			PDF::SetY(8);
            PDF::SetX(46);
			PDF::SetFont('helvetica','',13);
		    PDF::Cell(144,5,$nombreEmpresa,0,0,'C');
			PDF::SetFont('helvetica','B',14);
		    PDF::Ln(6);
		    PDF::SetX(46);			
		    PDF::Cell(144,4,$siglaEmpresa,0,0,'C');
			PDF::SetFont('helvetica','I',9);
			PDF::Ln(6);
		    PDF::SetX(46);
		    PDF::Cell(144,4,$personeriaJuridica,0,0,'C');
			PDF::Ln(4);
		    PDF::SetX(46);
		    PDF::Cell(144,4,$nit,0,0,'C');
			PDF::SetFont('helvetica','I',12);
		    PDF::Ln(1);
			PDF::SetX(24);	
		    PDF::Cell(170,4,$linea,'0',0,'C');
		    PDF::Ln(6);
		    PDF::SetFont('helvetica','I',9);
		    PDF::SetX(25);
			PDF::Cell(25,4,$codigoInstitucional,0,0,'');
			PDF::Ln(4);
			PDF::SetX(25);
			PDF::Cell(25,4,$codigoDocumental,0,0,'');
			PDF::SetY(27);
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
			PDF::Cell(165,4,$urlEmpresa,0,0,'C');

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
			if($idCifrado !== ''){
				$url = asset('verificar/documento/'.urlencode($idCifrado));
				//PDF::write2DBarcode($url, 'QRCODE,H', 20, 264, 30, 30, $style, 'N');
				PDF::write2DBarcode($url, 'QRCODE,H', 20, $posicionY, 30, 30, $style, 'N');
			}			

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

	function firmaDocumentoDigital($siglaEmpresa, $nombreEmpresa, $consecutivoDocumental, $firmasDocumento){	
		$informacioncertificado = array(
			'Name'        => 'Sistema CRM de '.$siglaEmpresa,
			'Location'    => $nombreEmpresa,
			'Reason'      => 'Tipo documental con consecutivo '.$consecutivoDocumental,
			'ContactInfo' => URL::to('/')
		);

		$posicionX   = 194;
		$totalFirmas = count($firmasDocumento);
		foreach ($firmasDocumento as $firma)
		{		
			//$certificate 	  = 'file://'.realpath(public_path('/archivos/persona/1978917/1978917.crt'));
			//$primaryKey 	  = 'file://'.realpath(public_path('/archivos/persona/1978917/1978917.pem'));
			//$claveCertificado = '123456';

			if($firma->codopffirmado === 1 && $firma->persrutacrt !== null){

				$rutaCompletaCrt = $firma->rutaCrt.'/'.Crypt::decrypt($firma->persrutacrt);
				$rutaCompletaPem = $firma->rutaPem.'/'.Crypt::decrypt($firma->persrutapem);

				$certificate 	  = 'file://'.realpath(public_path($rutaCompletaCrt));
				$primaryKey 	  = 'file://'.realpath(public_path($rutaCompletaPem));
				$claveCertificado = $firma->persclavecertificado;
				PDF::setSignature($certificate, $primaryKey, $claveCertificado, '', 2, $informacioncertificado);

				//Abrimos el certificado
				$fp = fopen(public_path($rutaCompletaCrt), "r");
				$contenidoCertficado = '';
				while (!feof($fp)){
					$contenidoCertficado .= fgets($fp);
				}

				//Creamos el xml para adjuntarlo al documento
				$xml = new \DomDocument('1.0', 'UTF-8'); 
				$raiz = $xml->appendChild($xml->createElement('firmaDocumento'));

				$datosPersona = $raiz->appendChild($xml->createElement('datosPersona'));
				$datosPersona->appendChild($xml->createElement('documento',$firma->persdocumento));
				$datosPersona->appendChild($xml->createElement('nombre',$firma->nombrePersona));
				$datosPersona->appendChild($xml->createElement('correo',$firma->perscorreoelectronico));
				$datosPersona->appendChild($xml->createElement('celular',$firma->persnumerocelular));

				$informacionFirma = $raiz->appendChild($xml->createElement('informacionFirma'));
				$informacionFirma->appendChild($xml->createElement('consecutivoDocumental',$consecutivoDocumental));
				$informacionFirma->appendChild($xml->createElement('fechaNotificacion',$firma->codopffechahoranotificacion));
				$informacionFirma->appendChild($xml->createElement('fechaFirma',$firma->codopffechahorafirmado));
				$informacionFirma->appendChild($xml->createElement('tokenFirma',$firma->codopftoken));
				$informacionFirma->appendChild($xml->createElement('medioCorreo',$firma->codopfmensajecorreo));
				$informacionFirma->appendChild($xml->createElement('medioCelular',$firma->codopfmensajecelular));

				$informacioncertificado = $raiz->appendChild($xml->createElement('informacionCertificado'));
				$informacioncertificado->appendChild($xml->createElement('certificado',$contenidoCertficado));

				$xml->preserveWhiteSpace = false;
				$xml->formatOutput       = true;
				$xmlString               = $xml->saveXML();

				Storage::disk('public')->put('firma.xml',$xmlString);
				$xmlFirma = public_path('storage/firma.xml');
				//$xmlFirma = public_path().'/archivos/xml/firma_'.$firma->persdocumento.'.xml';

				PDF::Annotation(85, 27, 5, 5, 'Informacion de la firma', array('Subtype'=>'FileAttachment', 'Name' => 'PushPin', 'T' => 'Documento firmado', 'Subj' => $siglaEmpresa, 'FS' => $xmlFirma));
				
				PDF::Ln(8);
				PDF::SetFont('helvetica', '', 6);
				if($totalFirmas <= 4){
					PDF::setXY($posicionX, 264);
					PDF::StartTransform();
					PDF::Rotate(90);
					PDF::MultiCell(200,3,"En constancia se firma digitalmente el día ".$firma->codopffechahorafirmado.", mediante el token número ".$firma->codopftoken." por ".$firma->nombrePersona."\n",0,'J',0);
					PDF::StopTransform();
					$posicionX += 3;
				}else{
					PDF::MultiCell(200,3,"En constancia se firma digitalmente el día ".$firma->codopffechahorafirmado.", mediante el token número ".$firma->codopftoken." por ".$firma->nombrePersona."\n",0,'J',0);
				}
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
			$rutaPdf      = $rutaCarpeta.'/'.$nombrePdf;
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	/*Para la radicacion de documentos*/
	public function validarPuedeAbrirPdf($ruta){
        try {
            $tcpdf = new FPDI();
            $pageCount = $tcpdf->setSourceFile($ruta);
            return true;
		} catch (Exception $e) {
            return false;
		}
    }

    public function radicarDocumentoExterno($rutaCarpeta, $nombreFile, $data, $dataCopia, $descargarPdf = false){
		$consecutivo    = $data->consecutivo;
		$fecha          = $data->fechaRadicado;
		$dependencia    = $data->dependencia;
		$asuntoRadicado = $data->asunto;
		$correo         = $data->correo;
		$funcionario    = $data->usuario;
		
		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();
		
		$documentoRadicado = true;
        $mensajeRadicar    = '';     
		$tcpdf =new FPDI();
        $tcpdf->SetAuthor('IMPLESOFT');
        $tcpdf->SetCreator($nombreEmpresa);
        $tcpdf->SetTitle($consecutivo);
        $tcpdf->SetSubject('Formato de registro de radicado externo');
        $tcpdf->SetKeywords('Radicacion, '.$consecutivo);  
  
        try {
            $pageCount = $tcpdf->setSourceFile($rutaCarpeta.'/'.$nombreFile);
		} catch (Exception $e) {
            $mensajeRadicar    = 'El documento no se pudo abrir por las siguientes causas => '. $e->getMessage();
            $documentoRadicado = false;
		}

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$tcpdf->addPage();

			try {
				$templateId = $tcpdf->importPage($pageNo);
				$tcpdf->useTemplate($templateId, 0, 0, 210);
			} catch (Exception $e) {
                $mensajeRadicar    = 'El documento no se pudo impotar por las siguientes causas => '. $e->getMessage();
                $documentoRadicado = false;
			}

            $tcpdf->SetFillColor(255, 255, 255);
            $tcpdf->SetFont('Helvetica','B',6);
            $tcpdf->SetY(8);
            $tcpdf->Cell(115,1,'',0,0,'',false);
            $tcpdf->Cell(82,3,$nombreEmpresa,'LTR',0,'C',true);
            $tcpdf->Ln(3);
            $tcpdf->SetFont('Helvetica','',7);
            $tcpdf->Cell(115,3,'',0,0,'');
            $tcpdf->Cell(12,3,'Radicado: ','L',0,'',true);
            $tcpdf->Cell(70,3,$consecutivo,'R',0,'',true);
            $tcpdf->Ln(3);
            $tcpdf->Cell(115,3,'',0,0,'');
            $tcpdf->Cell(12,3,'Fecha: ','L',0,'',true);
            $tcpdf->Cell(70,3,$fecha,'R',0,'',true);
            $tcpdf->Ln(3);
            $tcpdf->Cell(115,3,'',0,0,'');
            $tcpdf->Cell(12,3,'Destino: ','L',0,'',true);
            $tcpdf->Cell(70,3,substr($dependencia, 0, 47),'R',0,'',true);
            if($correo !=''){
                $tcpdf->Ln(3);
                $tcpdf->Cell(115,3,'',0,0,'');
                $tcpdf->Cell(12,3,'Correo: ','L',0,'',true);
                $tcpdf->Cell(70,3,$correo,'R',0,'',true);
            }
            $tcpdf->Ln(3);
            $tcpdf->Cell(115,3,'',0,0,'');
            $tcpdf->Cell(12,3,'Usuario: ','L',0,'',true);
            $tcpdf->Cell(70,3,$funcionario,'R',0,'',true);
            $tcpdf->Ln(3);
            $tcpdf->SetFont('Helvetica','',6);
            $tcpdf->Cell(115,3,'',0,0,'');
            $tcpdf->Cell(82,3,$urlEmpresa,'LBR',0,'R',true);
            $tcpdf->Ln(8);

            //Genero las copias solo en la pagina uno
            if(count($dataCopia) > 0 and $pageNo === 1){
                $tcpdf->SetFont('Helvetica','B',6);
                $tcpdf->Cell(115,1,'',0,0,'',false);
                $tcpdf->Cell(82,3,$nombreEmpresa,'LTR',0,'C',true);
                $tcpdf->Ln(3);            
                $tcpdf->SetFont('Helvetica','',7);
                $tcpdf->Cell(115,3,'',0,0,'');
                $tcpdf->Cell(12,3,'Radicado: ','L',0,'',true);
                $tcpdf->Cell(70,3,$consecutivo,'R',0,'',true);
                $tcpdf->Ln(3);
                $tcpdf->Cell(115,3,'',0,0,'');
                $tcpdf->Cell(12,3,'Fecha: ','L',0,'',true);
                $tcpdf->Cell(70,3,$fecha,'R',0,'',true);
                $tcpdf->Ln(3);

                foreach($dataCopia as $copia){
                    $tcpdf->Cell(115,3,'',0,0,'');
                    $tcpdf->Cell(12,3,'Copia: ','L',0,'',true);
                    $tcpdf->Cell(60,4,$copia->dependencia,0,0,'',true);
                    $tcpdf->Ln(3);
                }

                if($correo !=''){
                    $tcpdf->Cell(115,3,'',0,0,'');
                    $tcpdf->Cell(12,3,'Correo: ','L',0,'',true);
                    $tcpdf->Cell(70,3,$correo,'R',0,'',true);
                }
                $tcpdf->Ln(3);
                $tcpdf->Cell(115,3,'',0,0,'');
                $tcpdf->Cell(12,3,'Usuario: ','L',0,'',true);
                $tcpdf->Cell(70,3,$funcionario,'R',0,'',true);
                $tcpdf->Ln(3);
                $tcpdf->SetFont('Helvetica','',6);
                $tcpdf->Cell(115,3,'',0,0,'');
                $tcpdf->Cell(82,3,$urlEmpresa,'LBR',0,'R',true);
                $tcpdf->Ln(12);
            }
        }

        $nombrePDF       = 'Rad-'.$consecutivo.'.pdf';
        if($descargarPdf){
		    $nombrePDF = $rutaCarpeta.'/'.$nombreFile;
            $metodo    = 'F';
        }else{
            $metodo    = 'S';
        }

        $pdfGenerado = $tcpdf->Output($nombrePDF, $metodo);

        $datos = array(
                    'documentoRadicado' => $documentoRadicado,
                    'mensajeRadicar'    => $mensajeRadicar,
                    'rutaPdfRadicado'   => ($descargarPdf) ?  $nombrePDF :  base64_encode($pdfGenerado),
                    );
       
        return json_encode($datos);
    }

    public function generarStickersRadicado($data, $dataCopia, $descargarPdf = false){
        $consecutivo    = $data->consecutivo;
        $fecha          = $data->fechaRadicado;
        $dependencia    = $data->dependencia;
		$asuntoRadicado = $data->asunto;
        $correo         = $data->correo;
        $funcionario    = $data->usuario;

		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();
				
		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($asuntoRadicado);
		PDF::SetKeywords('Stickers, documento,'.$siglaEmpresa.', '.$asuntoRadicado);
        PDF::SetTitle($consecutivo);
        PDF::SetKeywords('Radicado, '.$siglaEmpresa.',  Stickers, '.$consecutivo);  
        PDF::SetProtection(array('copy','modify'), '', null, 0, null);
        PDF::SetPrintHeader(false);
        PDF::SetPrintFooter(false); 
        PDF::AddPage('L', array(110,35));
   
        PDF::SetMargins(25, 1, 0);//Funciona con la impresora propia 25
		PDF::Image('archivos/logoEmpresa/'.$logoEmpresa, 90, 4, 15, 10);
        PDF::SetAutoPageBreak(true, 1);
        PDF::SetFont('Helvetica','B',6);
        PDF::SetY(0.5);
        PDF::Cell(45,4,$nombreEmpresa,0,0,'');     
        PDF::Ln(3);
        PDF::SetFont('Helvetica','',7);
        PDF::Cell(12,4,'Radicado: ',0,0,'');
        PDF::Cell(30,4,$consecutivo,0,0,'');
        PDF::Ln(2.8);
        PDF::Cell(12,4,'Fecha: ',0,0,'');
        PDF::Cell(30,4,$fecha,0,0,'');
        PDF::Ln(2.8);
        PDF::Cell(12,4,'Destino: ',0,0,'');
        PDF::Cell(30,4,$dependencia,0,0,'');
        if($correo!=''){
            PDF::Ln(2.8);
            PDF::Cell(12,4,'Correo: ',0,0,'');
            PDF::Cell(30,4,$correo,0,0,'');
        }
        PDF::Ln(2.8);
        PDF::Cell(12,4,'Usuario: ',0,0,'');
        PDF::Cell(30,4,$funcionario,0,0,'');
        PDF::Ln(2.8);
        PDF::SetFont('Helvetica','',6);
        PDF::Cell(56,2, $urlEmpresa ,0,0,'R');

        if(count($dataCopia) > 0 ){ 
            PDF::AddPage('L', array(110,35));
			PDF::Image('archivos/logoEmpresa/'.$logoEmpresa, 90, 4, 15, 10);
            PDF::SetFont('Helvetica','B',6);
            PDF::SetY(2.6);
            PDF::Cell(45,4, $nombreEmpresa ,0,0,'');         
            PDF::Ln(3);
            PDF::SetFont('Helvetica','',7);
            PDF::Cell(12,4,'Radicado: ',0,0,'');
            PDF::Cell(15,4,$consecutivo,0,0,'');
            PDF::Ln(2.3);
            PDF::Cell(12,4,'Fecha: ',0,0,'');
            PDF::Cell(30,4,$fecha,0,0,'');
            PDF::Ln(2.4);           
            foreach($dataCopia as $copia){
                PDF::SetFont('Helvetica','',6);
                PDF::Cell(12,4,'Copia: ',0,0,'');
                PDF::SetFont('Helvetica','',5);
                PDF::Cell(60,4,$copia->dependencia,0,0,'');
                PDF::Ln(2.4);
            }    
            PDF::SetFont('Helvetica','',6);
            if($correo !=''){
                PDF::Cell(12,4,'Correo: ',0,0,'');
                PDF::Cell(30,4,$correo,0,0,'');
            }
            PDF::Ln(2.5);
            PDF::Cell(12,4,'Usuario: ',0,0,'');
            PDF::Cell(30,4,$funcionario,0,0,'');
            PDF::Ln(2.5);
            PDF::SetFont('Helvetica','',5);
            PDF::Cell(56,2, $urlEmpresa ,0,0,'R');
        }

        $nombrePDF       = 'Stickers-'.$consecutivo.'.pdf';
        if($descargarPdf){
            $rutaPdfGenerado = sys_get_temp_dir().'/'.$nombrePDF;
            fopen($rutaPdfGenerado, "w+"); 
            $metodo    = 'F';
            $nombrePDF = $rutaPdfGenerado;
        }else{
            $metodo          = 'S';
            $rutaPdfGenerado = '';
        }

        $pdfGenerado = PDF::Output($nombrePDF, $metodo);

        return ($descargarPdf) ?  $rutaPdfGenerado :  base64_encode($pdfGenerado);
    }

	public function expedienteArchivoHistorico($digitalizados, $metodo = 'S'){

		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();
			
        $mensajeRadicar    = '';
		$tcpdf = new FpdiProtection();
        $tcpdf->SetAuthor('IMPLESOFT');
        $tcpdf->SetCreator($nombreEmpresa);
        $tcpdf->SetTitle("Expediente");
        $tcpdf->SetSubject('Generacion de expediente del archivo historico');
        $tcpdf->SetKeywords('Expediente, Archivo historico, '.$siglaEmpresa);
		$tcpdf->setProtection(FALSE | FALSE);
	    //$tcpdf->setProtection(FpdiProtection::PERM_PRINT | FpdiProtection::PERM_COPY,[contraseña de usuario],[contraseña maestra]);

		foreach($digitalizados as $digitalizado){
			try {
				$pageCount = $tcpdf->setSourceFile($digitalizado->rutaDigitalizacion.'/'.Crypt::decrypt($digitalizado->rutaPdf));
				for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
					$tcpdf->addPage();
					try {
						$templateId = $tcpdf->importPage($pageNo);
						$tcpdf->useTemplate($templateId, 0, 0, 210);
					} catch (Exception $e) {
						$mensajeRadicar    = 'El documento no se pudo impotar por las siguientes causas => '. $e->getMessage();						
					}
				}
			} catch (Exception $e) {
				$mensajeRadicar    = 'El documento no se pudo abrir por las siguientes causas => '. $e->getMessage();				
			}			
		}

		$nombrePDF   = 'Expediente.pdf';

		return base64_encode($tcpdf->output($nombrePDF, $metodo));
	}

	public function generarContratoVehiculo($titulo, $contenido, $numeroContrato, $placa, $metodo = 'S'){

		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();

		$codigoInstitucional = '';
		$codigoDocumental    = '';
		$idCifrado           = '';
		$estadoDocumento     = '';
        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo.' '.$placa);
		PDF::SetKeywords('Contrato, Vehículo, '.$siglaEmpresa.', '.$numeroContrato.', '.$placa);
        PDF::SetTitle($titulo);	

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $codigoInstitucional, $codigoDocumental, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 36, 14);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(16);
		PDF::Ln(20);
		PDF::SetFont('helvetica', 'B', 13);
		PDF::Cell(176, 4, $titulo, 0, 0, 'C');
		PDF::Ln(12);
		PDF::SetFont('helvetica', '', 11);
		PDF::writeHTML($contenido, true, false, true, false, '');
	 
		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en el servidor	
			$rutaCarpeta  = public_path().'/archivos/vehiculo/'.$placa;
			$carpetaServe = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
			$rutaPdf      = $rutaCarpeta.'/'.$numeroContrato.'.pdf';
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	public function generarPagareColocacion($titulo, $contenido, $numeroPagare, $documento, $metodo = 'S'){

		list($direccionEmpresa, $ciudadEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa,
			$nombreEmpresa, $lemaEmpresa,	$siglaEmpresa, $nit, $personeriaJuridica, $logoEmpresa) = $this->consultarEmpresa();
	
        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator($nombreEmpresa);
		PDF::SetSubject($titulo.' '.$documento);
		PDF::SetKeywords('Colocación, Vehículo, '.$siglaEmpresa.', '.$numeroPagare.', '.$documento);
        PDF::SetTitle($titulo);	

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetMargins(20, 36, 14);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(10);
		PDF::Ln(4);
		PDF::SetFont('helvetica', 'B', 13);
		PDF::Cell(176, 4, $titulo, 0, 0, 'C');
		PDF::Ln(12);
		PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');
		PDF::Ln(4);
		PDF::Cell(130, 4, '', '', 0, 'L');
		PDF::MultiCell(30, 30, '', 1, 'C', false, 1);
		PDF::Cell(80, 4, 'DEUDOR ', 'T', 0, 'L');
		PDF::Cell(50, 4, '', '', 0, 'L');
		PDF::Cell(30, 4, 'HUELLA', '', 0, 'L');
		PDF::Ln(4);
		PDF::Cell(80, 4, 'C.C. ', 0, 0, 'L');
		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en el servidor	
			$rutaCarpeta  = public_path().'/archivos/vehiculo/'.$documento.'/'.$numeroPagare;
			$carpetaServe = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
			$rutaPdf      = $rutaCarpeta.'/'.$numeroPagare.'.pdf';
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}
}