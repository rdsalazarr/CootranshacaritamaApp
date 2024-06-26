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
        $funcion              = new generales();
		$encrypt              = new encrypt();
        $fechaHoraActual      = Carbon::now();
		$fechaActual          = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar           = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) = $visualizar->acta($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		$convocatoriaDescripcion = ($convocatoria == 1) ? $funcion->obtenerConvocatoriaActa($convocatorialugar, $convocatoriafecha, $convocatoriahora) : '';

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40, 20);
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
        $funcion          	  = new generales();
		$encrypt          	  = new encrypt();
        $fechaHoraActual  	  = Carbon::now();
		$fechaActual      	  = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       	  = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->certificado($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Certificado, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40 , 20);
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
        $funcion              = new generales();
		$encrypt              = new encrypt();
        $fechaHoraActual      = Carbon::now();
		$fechaActual          = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar           = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->circular($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Circular, documento,'.$siglaEmpresa.', '.$asunto.', '.$circularNumero);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40, 20);
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
        $funcion              = new generales();
		$encrypt              = new encrypt();
        $fechaHoraActual      = Carbon::now();
		$fechaActual          = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));
	    $visualizar           = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $firmaInvitados) = $visualizar->citacion($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($lugar);
		PDF::SetKeywords('Acta, documento,'.$siglaEmpresa.', '.$lugar);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LEGAL');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40, 20);
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
        $funcion              = new generales();
		$encrypt              = new encrypt();
        $fechaHoraActual      = Carbon::now();
		$fechaActual          = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));
	    $visualizar           = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->constancia($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Constancia, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40, 20);
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
        $funcion              = new generales();
		$encrypt              = new encrypt();
        $fechaHoraActual      = Carbon::now();
		$fechaActual          = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));
	    $visualizar           = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $copiaDependencias, $anexosDocumento) = $visualizar->oficio($id);
		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;

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
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($asunto);
		PDF::SetKeywords('Oficio, documento,'.$siglaEmpresa.', '.$asunto);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional, $codigoDocumental);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento, 'LETTER');

		PDF::SetProtection(($tipoMedio == 2) ? array('print', 'copy') : array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(24, 40, 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(16);
		PDF::Ln(24);
		PDF::SetFont('helvetica', '', 12);
	    PDF::Cell(80, 4, $fechaDocumento, 0, 0, '');
	    PDF::Ln(20);

	    if($titulo !=''){
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
		return DB::table('empresa as e')
									->select('e.emprdireccion','e.emprnombre','e.emprurl','e.emprsigla','e.emprtelefonofijo', 'e.emprpersoneriajuridica',
											'e.emprtelefonocelular', 'e.emprcorreo','e.emprlema','e.emprlogo', 'm.muninombre','e.emprbarrio',
											DB::raw("CONCAT('NIT: ', e.emprnit,' - ', e.emprdigitoverificacion) as nit"))
									->join('municipio as m', 'm.muniid', '=', 'e.emprmuniid')
									->where('e.emprid', 1)
									->first();
	}

	function headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa, $codigoInstitucional = '', $codigoDocumental = ''){
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

	function footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado = '', $estadoDocumento = '', $tipoDocumeto = 'LETTER'){
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

	function headerFormato($titulo, $version, $numeroFormato, $fechaFormato, $areaFormato, $sigla, $logoEmpresa){
        PDF::setHeaderCallback(function($pdf) use ($titulo, $version, $numeroFormato, $fechaFormato, $areaFormato, $sigla, $logoEmpresa){
			PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,12,5,22,23);
            PDF::Ln(4);
            PDF::MultiCell(23, 25, '', 1, 'C', false, 0);
         	PDF::MultiCell(100, 11, $titulo, 'TRB', 'C', false, 0);
			PDF::MultiCell(24, 11, 'Versión: '.$version, 'TRB', 'C', false, 0);
			PDF::MultiCell(40, 11, 'Código: '.$numeroFormato, 'TRB', 'C', false, 1);
          	PDF::MultiCell(23, 7, '', 0, 'C', false, 0);
			PDF::MultiCell(100, 7, $sigla, 'RB', 'C', false, 0);
   			PDF::MultiCell(64, 7, '  Fecha: '.$fechaFormato, 'RB', 'C', false, 0);
            PDF::Ln(7);
            PDF::Cell(23, 7, '', 0, 0, 'L');       
		    PDF::MultiCell(100, 7, $areaFormato, 'RB', 'C', false, 0);
			PDF::Cell(64, 7, 'Página ' . PDF::getAliasNumPage() . ' de ' . PDF::getAliasNbPages(),'RB', 0, 'R');
		});
    }

	function headerDocumentoHorizontal($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa){
		PDF::setHeaderCallback(function($pdf) use ($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa){
			$linea = str_pad('',  106, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,24,4,26,24);
			PDF::SetY(8);
            PDF::SetX(46);
			PDF::SetFont('helvetica','',13);
		    PDF::Cell(204,5,$nombreEmpresa,0,0,'C');
			PDF::SetFont('helvetica','B',14);
		    PDF::Ln(6);
		    PDF::SetX(46);
		    PDF::Cell(204,4,$siglaEmpresa,0,0,'C');
			PDF::SetFont('helvetica','I',9);
			PDF::Ln(6);
		    PDF::SetX(46);
		    PDF::Cell(204,4,$personeriaJuridica,0,0,'C');
			PDF::Ln(4);
		    PDF::SetX(46);
		    PDF::Cell(204,4,$nit,0,0,'C');
			PDF::SetFont('helvetica','I',12);
		    PDF::Ln(1);
			PDF::SetX(24);
		    PDF::Cell(250,4,$linea,0,0,'C');
		    PDF::Ln(3);
		    PDF::SetFont('helvetica','I',9);
        	PDF::SetX(272);
			PDF::Cell(4, 10, 'Pag. ' . PDF::getAliasNumPage() . '(' . PDF::getAliasNbPages() . ')', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		});
	}

	function footerDocumentoHorizontal($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $mensajeImpresion = ''){
		PDF::setFooterCallback(function($pdf) use ($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $mensajeImpresion){	
			$linea = str_pad('',  106, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::Ln(2);
			if($mensajeImpresion !== ''){
				PDF::SetY('184');
				PDF::SetX(20);
				PDF::SetFont('helvetica','',6); 
				PDF::Cell(252,2,$mensajeImpresion,0,0,'R');
				PDF::Ln(1);
			}
            PDF::SetFont('helvetica','I',12);
            PDF::SetY('182');
			PDF::SetX(20);
			PDF::Cell(254,1,$linea,0,0,'C');
			PDF::Ln(5);
			PDF::SetX(20);
			PDF::Cell(254,4,$direccionEmpresa.' | '.$barrioEmpresa,0,0,'C');
			PDF::Ln(5);
			PDF::SetX(20);
			PDF::Cell(254,4,'Teléfono: '.$telefonoEmpresa.' | Celular: '.$celularEmpresa.'',0,0,'C'); 
			PDF::Ln(5);
			PDF::SetX(20);
			PDF::Cell(254,4,$urlEmpresa,0,0,'C');
		});
	}

	function mensajeFirmarCentro(){
		PDF::SetFont('helvetica', 'B', 12);
		PDF::SetTextColor(255, 0, 0);
		PDF::Cell(165,4,'Documento pendiente por firmar',0,0,'C');
		PDF::SetTextColor(0, 0, 0);
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
			'Name'        => 'Sistema ERP de '.$siglaEmpresa,
			'Location'    => $nombreEmpresa,
			'Reason'      => 'Tipo documental con consecutivo '.$consecutivoDocumental,
			'ContactInfo' => URL::to('/')
		);

		$posicionX   = 194;
		$totalFirmas = count($firmasDocumento);
		foreach ($firmasDocumento as $firma)
		{
			//Creamos el xml para adjuntarlo al documento
			$xml = new \DomDocument('1.0', 'UTF-8'); 
			$raiz = $xml->appendChild($xml->createElement('firmaDocumento'));

			if($firma->codopffirmado === 1 && $firma->persrutacrt !== null){ //Para la firma digital

				//$certificate 	  = 'file://'.realpath(public_path('/archivos/persona/1978917/1978917.crt'));
				//$primaryKey 	  = 'file://'.realpath(public_path('/archivos/persona/1978917/1978917.pem'));
				//$claveCertificado = '123456';

				$rutaCompletaCrt  = $firma->rutaCrt.'/'.Crypt::decrypt($firma->persrutacrt);
				$rutaCompletaPem  = $firma->rutaPem.'/'.Crypt::decrypt($firma->persrutapem);
				$certificate 	  = 'file://'.realpath(public_path($rutaCompletaCrt));
				$primaryKey 	  = 'file://'.realpath(public_path($rutaCompletaPem));
				$claveCertificado = $firma->persclavecertificado;
				PDF::setSignature($certificate, $primaryKey, $claveCertificado, '', 2, $informacioncertificado);

				//Abrimos el certificado
				$fp = fopen(public_path($rutaCompletaCrt), "r");
				$contenidoCertificado = '';
				while (!feof($fp)){
					$contenidoCertificado .= fgets($fp);
				}

				$informacioncertificado = $raiz->appendChild($xml->createElement('informacionCertificado'));
				$informacioncertificado->appendChild($xml->createElement('certificado',$contenidoCertificado));
			}

			if($firma->codopffirmado === 1 ){
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
				$informacionFirma->appendChild($xml->createElement('ipFirma',$firma->codopfipacceso));
				$informacionFirma->appendChild($xml->createElement('medioCorreo',$firma->codopfmensajecorreo));
				$informacionFirma->appendChild($xml->createElement('medioCelular',$firma->codopfmensajecelular));

				$xml->preserveWhiteSpace = false;
				$xml->formatOutput       = true;
				$xmlString               = $xml->saveXML();
				$titleXml                = 'FirmaDocumento_'.$consecutivoDocumental.'.xml';

				Storage::disk('public')->put($titleXml, $xmlString);
				$xmlFirma = public_path('storage/'.$titleXml);
				PDF::Annotation(85, 27, 5, 5, 'Informacion de la firma', array('Subtype'=>'FileAttachment', 'Name' => 'PushPin', 'T' => 'Documento firmado', 'Subj' => $siglaEmpresa, 'FS' => $xmlFirma));

				PDF::Ln(8);
				PDF::SetFont('helvetica', '', 6);
				if($totalFirmas <= 4){
					PDF::setXY($posicionX, 264);
					PDF::StartTransform();
					PDF::Rotate(90);
					PDF::MultiCell(200,3,"En constancia se firma electrónicamente el día ".$firma->codopffechahorafirmado.", mediante el token número ".$firma->codopftoken." por ".$firma->nombrePersona."\n",0,'J',0);
					PDF::StopTransform();
					$posicionX += 3;
				}else{
					PDF::MultiCell(200,3,"En constancia se firma electrónicamente el día ".$firma->codopffechahorafirmado.", mediante el token número ".$firma->codopftoken." por ".$firma->nombrePersona."\n",0,'J',0);
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
	function validarPuedeAbrirPdf($ruta){
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

		$empresa        = $this->consultarEmpresa();
		$urlEmpresa     = $empresa->emprurl;
		$nombreEmpresa  = $empresa->emprnombre;
		$siglaEmpresa   = $empresa->emprsigla;
		$logoEmpresa    = $empresa->emprlogo;

		$documentoRadicado = true;
        $mensajeRadicar    = '';     
		$fpdi = new FPDI();
        $fpdi->SetAuthor('IMPLESOFT');
        $fpdi->SetCreator('ERP '.$siglaEmpresa);
        $fpdi->SetTitle($consecutivo);
        $fpdi->SetSubject('Formato de registro de radicado externo');
        $fpdi->SetKeywords('Radicacion, '.$consecutivo);

        try {
            $pageCount = $fpdi->setSourceFile($rutaCarpeta.'/'.$nombreFile);
		} catch (Exception $e) {
            $mensajeRadicar    = 'El documento no se pudo abrir por las siguientes causas => '. $e->getMessage();
            $documentoRadicado = false;
		}

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			$fpdi->addPage();

			try {
				$templateId = $fpdi->importPage($pageNo);
				$fpdi->useTemplate($templateId, 0, 0, 210);
			} catch (Exception $e) {
                $mensajeRadicar    = 'El documento no se pudo impotar por las siguientes causas => '. $e->getMessage();
                $documentoRadicado = false;
			}

            $fpdi->SetFillColor(255, 255, 255);
            $fpdi->SetFont('Helvetica','B',6);
            $fpdi->SetY(8);
			
            $fpdi->Cell(115,1,'',0,0,'',false);
            $fpdi->Cell(82,3,$nombreEmpresa,'LTR',0,'C',true);
            $fpdi->Ln(3);
            $fpdi->SetFont('Helvetica','',7);
            $fpdi->Cell(115,3,'',0,0,'');
            $fpdi->Cell(12,3,'Radicado: ','L',0,'',true);
            $fpdi->Cell(70,3,$consecutivo,'R',0,'',true);
            $fpdi->Ln(3);
            $fpdi->Cell(115,3,'',0,0,'');
            $fpdi->Cell(12,3,'Fecha: ','L',0,'',true);
            $fpdi->Cell(70,3,$fecha,'R',0,'',true);
            $fpdi->Ln(3);
            $fpdi->Cell(115,3,'',0,0,'');
            $fpdi->Cell(12,3,'Destino: ','L',0,'',true);
            $fpdi->Cell(70,3,substr($dependencia, 0, 47),'R',0,'',true);
            if($correo !=''){
                $fpdi->Ln(3);
                $fpdi->Cell(115,3,'',0,0,'');
                $fpdi->Cell(12,3,'Correo: ','L',0,'',true);
                $fpdi->Cell(70,3,$correo,'R',0,'',true);
            }
            $fpdi->Ln(3);
            $fpdi->Cell(115,3,'',0,0,'');
            $fpdi->Cell(12,3,'Usuario: ','L',0,'',true);
            $fpdi->Cell(70,3,$funcionario,'R',0,'',true);
            $fpdi->Ln(3);
            $fpdi->SetFont('Helvetica','',6);
            $fpdi->Cell(115,3,'',0,0,'');
            $fpdi->Cell(82,3,$urlEmpresa,'LBR',0,'R',true);
            $fpdi->Ln(8);

			//Montamos el logo al radicado
			$fpdi->Image('archivos/logoEmpresa/'.$logoEmpresa, 190, 12, 15, 10);

            //Genero las copias solo en la pagina uno
            if(count($dataCopia) > 0 and $pageNo === 1){
                $fpdi->SetFont('Helvetica','B',6);
                $fpdi->Cell(115,1,'',0,0,'',false);
                $fpdi->Cell(82,3,$nombreEmpresa,'LTR',0,'C',true);
                $fpdi->Ln(3);            
                $fpdi->SetFont('Helvetica','',7);
                $fpdi->Cell(115,3,'',0,0,'');
                $fpdi->Cell(12,3,'Radicado: ','L',0,'',true);
                $fpdi->Cell(70,3,$consecutivo,'R',0,'',true);
                $fpdi->Ln(3);
                $fpdi->Cell(115,3,'',0,0,'');
                $fpdi->Cell(12,3,'Fecha: ','L',0,'',true);
                $fpdi->Cell(70,3,$fecha,'R',0,'',true);
                $fpdi->Ln(3);

                foreach($dataCopia as $copia){
                    $fpdi->Cell(115,3,'',0,0,'');
                    $fpdi->Cell(12,3,'Copia: ','L',0,'',true);
                    $fpdi->Cell(70,4,$copia->dependencia,'R',0,'',true);
                    $fpdi->Ln(3);
                }

                if($correo !=''){
                    $fpdi->Cell(115,3,'',0,0,'');
                    $fpdi->Cell(12,3,'Correo: ','L',0,'',true);
                    $fpdi->Cell(70,3,$correo,'R',0,'',true);
                }
                $fpdi->Ln(3);
                $fpdi->Cell(115,3,'',0,0,'');
                $fpdi->Cell(12,3,'Usuario: ','L',0,'',true);
                $fpdi->Cell(70,3,$funcionario,'R',0,'',true);
                $fpdi->Ln(3);
                $fpdi->SetFont('Helvetica','',6);
                $fpdi->Cell(115,3,'',0,0,'');
                $fpdi->Cell(82,3,$urlEmpresa,'LBR',0,'R',true);
                $fpdi->Ln(12);

				//Montamos el logo al radicado
				$fpdi->Image('archivos/logoEmpresa/'.$logoEmpresa, 190, 38, 15, 10);
            }
        }

        $nombrePDF       = 'Rad-'.$consecutivo.'.pdf';
        if($descargarPdf){
		    $nombrePDF = $rutaCarpeta.'/'.$nombreFile;
            $metodo    = 'F';
        }else{
            $metodo    = 'I';
        }

        $pdfGenerado = $fpdi->Output($nombrePDF, $metodo);

        $datos = array(
                    'documentoRadicado' => $documentoRadicado,
                    'mensajeRadicar'    => $mensajeRadicar,
                    'rutaPdfRadicado'   => ($descargarPdf) ?  $nombrePDF :  base64_encode($pdfGenerado),
                    );
       
        return json_encode($datos);
    }

    function stickersRadicado($data, $dataCopia, $descargarPdf = false){
        $consecutivo    = $data->consecutivo;
        $fecha          = $data->fechaRadicado;
        $dependencia    = $data->dependencia;
		$asuntoRadicado = $data->asunto;
        $correo         = $data->correo;
        $funcionario    = $data->usuario;

		$empresa        = $this->consultarEmpresa();
		$urlEmpresa     = $empresa->emprurl;
		$nombreEmpresa  = $empresa->emprnombre;
		$siglaEmpresa   = $empresa->emprsigla;
		$logoEmpresa    = $empresa->emprlogo;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
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

	function expedienteArchivoHistorico($digitalizados, $metodo = 'S'){

		$empresa           = $this->consultarEmpresa();
		$nombreEmpresa     = $empresa->emprnombre;
		$siglaEmpresa      = $empresa->emprsigla;
        $mensajeRadicar    = '';
		$tcpdf = new FpdiProtection();
        $tcpdf->SetAuthor('IMPLESOFT');
        $tcpdf->SetCreator('ERP '.$siglaEmpresa);
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

	function generarContenidoBDPdf($data, $metodo = 'S'){
		$empresa            = $this->consultarEmpresa();
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

		$titulo    = $data->ingpdftitulo;
		$contenido = $data->ingpdfcontenido;
        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Documento, Vehículo, '.$siglaEmpresa);
        PDF::SetTitle($titulo);	

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

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
		PDF::SetFont('helvetica', '', 9);
		PDF::writeHTML($contenido, true, false, true, false, '');

		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));	
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function simuladorCredito($lineaCredito, $nombrePersona, $descripcionCredito, $valorSolicitado, $tasaNominal, $plazoMensual, $metodo = 'I'){

		$empresa            = $this->consultarEmpresa();
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

		$titulo           = 'Simulación del crédito para la persona '.$nombrePersona;
		$generales        = new generales();
		$valorCuota       = $generales->calculcularValorCuotaMensual($valorSolicitado, $tasaNominal, $plazoMensual);
		$fechaHoraActual  = Carbon::now();
		$fechaActual      = $generales->formatearFecha($fechaHoraActual->format('Y-m-d'));

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Simulador, Crédito, '.$siglaEmpresa.', '.$valorSolicitado.', tasa nominal mensual de '.$tasaNominal.', Número total de meses '.$plazoMensual);
        PDF::SetTitle($titulo);	

		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 36, 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(20);
		PDF::Ln(20);
		PDF::SetFont('helvetica','B',12);
        PDF::Cell(180,5,'GENERACIÓN DEL PLAN DE PAGO',0,0,'C');
	    PDF::Ln(12); 
		PDF::SetFont('helvetica','',11);
		PDF::Cell(45,4,'Fecha:',0,0,'');
		PDF::Cell(45,4,$fechaActual,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Persona:',0,0,'');
		PDF::SetFont('helvetica','B',11);
		PDF::Cell(45,4,$nombrePersona,0,0,'');
		PDF::Ln(4);
		PDF::SetFont('helvetica','',11);
		PDF::Cell(45,4,'Línea de crédito:',0,0,'');
		PDF::Cell(45,4,$lineaCredito,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Descripción:',0,0,'');
		PDF::MultiCell(130, 4, $descripcionCredito."\n", 0, 'J', 0);
		PDF::Cell(45,4,'Valor solicitado:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorSolicitado,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Plazo mensual:',0,0,'');
		PDF::Cell(45,4,$plazoMensual,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Cuota mensual:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorCuota,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Tasa nominal mensual:',0,0,'');
        PDF::Cell(45,4,number_format($tasaNominal,1,',','.').'%',0,0,'');
        PDF::Ln(4);
		PDF::Cell(45,4,'Tasa efectiva anual:',0,0,'');
		PDF::Cell(45,4,number_format($generales->calcularTasaEfectivaAnual($tasaNominal), 2,',','.').'%',0,0,'');

		PDF::Ln(8);
		PDF::SetFont('helvetica','',8);	
		PDF::Cell(180,4,'* Los valores resultantes de esta simulación, son informativos, aproximados y podrán variar de acuerdo a las políticas',0,0,'');
		PDF::Ln(12);
		PDF::SetFont('helvetica','',11);
		PDF::Cell(180,4,'Tabla de liquidación:',0,0,'');
		PDF::Ln(6);
		PDF::SetFillColor(231,231,231);//color de fondo
		PDF::SetDrawColor(0);//color linea
		PDF::SetFont('helvetica','B',11);//texto del contenido de la tabla	
		PDF::Cell(12,5,'Nº',1,0,'C',true);
		PDF::Cell(40,5,'Abono Capital',1,0,'R',true);
        PDF::Cell(40,5,'Abono Intereses',1,0,'R',true);
		PDF::Cell(40,5,'Valor Cuota',1,0,'R',true);
        PDF::Cell(40,5,'Saldo Capital',1,0,'R',true);

		PDF::Ln();
		PDF::SetFont('helvetica','',11);
        $saldoCapital = $valorSolicitado;
        for ($numeroCuota = 1; $numeroCuota <= $plazoMensual; $numeroCuota++) {
            $valorInteres = $generales->calcularValorInteresMensual($saldoCapital, $tasaNominal);
            $abonoCapital = round($valorCuota - $valorInteres, 0);

            if ($saldoCapital < $valorCuota) {
                $abonoCapital = $saldoCapital;
                $valorCuota   = $saldoCapital + $valorInteres;
            }

            $saldoCapital -= $abonoCapital;

            PDF::Cell(12, 5, $numeroCuota, 1, 0, 'C', false);
            PDF::Cell(40, 5, '$' . number_format($abonoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(40, 5, '$' . number_format($valorInteres, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(40, 5, '$' . number_format($valorCuota, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(40, 5, '$' . number_format($saldoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Ln();
        }

		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		} else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function solicitudCredito($arrayDatos, $colocacionLiquidacion){

		$fechaDesembolso     = $arrayDatos['fechaDesembolso'];
		$lineaCredito        = $arrayDatos['lineaCredito'];
		$nombrePersona       = $arrayDatos['nombrePersona'];
		$descripcionCredito  = $arrayDatos['descripcionCredito'];
		$valorSolicitado     = $arrayDatos['valorSolicitado'];
		$tasaNominal       	 = $arrayDatos['tasaNominal'];
		$plazoMensual        = $arrayDatos['plazoMensual'];
		$numeroColocacion    = $arrayDatos['numeroColocacion'];
		$fechaRegistro       = $arrayDatos['fechaRegistro'];
		$metodo              = $arrayDatos['metodo'];

		$empresa            = $this->consultarEmpresa();
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

		$titulo             = 'Tabla de liquidación de la colocación número '.$numeroColocacion;
		$generales          = new generales();
		$valorCuota         = $generales->calculcularValorCuotaMensual($valorSolicitado, $tasaNominal, $plazoMensual);
		$fechaLargaDesembolso = $generales->formatearFecha($fechaRegistro);

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Colocación, Crédito, '.$siglaEmpresa.', '.$valorSolicitado.', tasa nominal mensual de '.$tasaNominal.', Número total de meses '.$plazoMensual);
        PDF::SetTitle($titulo);	

		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 36, 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(20);
		PDF::Ln(20);
		PDF::SetFont('helvetica','B',12);
        PDF::Cell(180,5,'GENERACIÓN DEL PLAN DE PAGO DE LA COLOCACIÓN NÚMERO '.$numeroColocacion,0,0,'C');
	    PDF::Ln(12); 
		PDF::SetFont('helvetica','',11);
		PDF::Cell(45,4,'Fecha:',0,0,'');
		PDF::Cell(45,4,$fechaLargaDesembolso,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Persona:',0,0,'');
		PDF::SetFont('helvetica','B',11);
		PDF::Cell(45,4,$nombrePersona,0,0,'');
		PDF::Ln(4);
		PDF::SetFont('helvetica','',11);
		PDF::Cell(45,4,'Línea de crédito:',0,0,'');
		PDF::MultiCell(130, 4, $lineaCredito."\n", 0, 'J', 0);

		PDF::Cell(45,4,'Descripción:',0,0,'');
		PDF::MultiCell(130, 4, $descripcionCredito."\n", 0, 'J', 0);
		PDF::Cell(45,4,'Valor solicitado:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorSolicitado,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Plazo mensual:',0,0,'');
		PDF::Cell(45,4,$plazoMensual,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Cuota mensual:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorCuota,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Tasa nominal mensual:',0,0,'');
        PDF::Cell(45,4,number_format($tasaNominal,1,',','.').'%',0,0,'');
        PDF::Ln(4);
		PDF::Cell(45,4,'Tasa efectiva anual:',0,0,'');
		PDF::Cell(45,4,number_format($generales->calcularTasaEfectivaAnual($tasaNominal), 2,',','.').'%',0,0,'');

		PDF::Ln(12);
		PDF::SetFont('helvetica','',11);
		PDF::Cell(180,4,'Tabla de liquidación:',0,0,'');
		PDF::Ln(6);
		PDF::SetFillColor(231,231,231);//color de fondo
		PDF::SetDrawColor(0);//color linea
		PDF::SetFont('helvetica','B',11);//texto del contenido de la tabla	
		PDF::Cell(12,5,'Nº',1,0,'C',true);
		PDF::Cell(32,5,'Fecha Cuota',1,0,'R',true);
		PDF::Cell(32,5,'Valor Cuota',1,0,'R',true);
		PDF::Cell(32,5,'Valor Intereses',1,0,'R',true);
		PDF::Cell(32,5,'Abono Capital',1,0,'R',true);
        PDF::Cell(32,5,'Saldo Capital',1,0,'R',true);

		PDF::Ln();
		PDF::SetFont('helvetica','',11);

		$fechaVencimiento      = $generales->obtenerFechaMesSiguiente($fechaDesembolso);
		$numeroDiasCambioFecha = $generales->calcularDiasCambiosFechaDesembolso($fechaRegistro, $fechaDesembolso);
		$arrayInteresMensual   = $generales->calcularValorInteresDiario($valorSolicitado, $tasaNominal, $fechaVencimiento, 0, $numeroDiasCambioFecha);
        $valorInteres          = $arrayInteresMensual['valorIntereses'];
        $saldoCapital          = $valorSolicitado;

		foreach($colocacionLiquidacion as $dato){
			$numeroCuota      = $dato->colliqnumerocuota;
			$fechaVencimiento = $dato->colliqfechavencimiento;
			$valorCuota       = $dato->colliqvalorcuota;
            $abonoCapital     = round($valorCuota - $valorInteres, 0);

            if ($saldoCapital < $valorCuota) {
                $abonoCapital = $saldoCapital;
                $valorCuota   = $saldoCapital + $valorInteres;
            }

            $saldoCapital -= $abonoCapital;	

			PDF::Cell(12, 5, $numeroCuota, 1, 0, 'C', false);
			PDF::Cell(32, 5, $fechaVencimiento, 1, 0, 'R');
			PDF::Cell(32, 5, '$' . number_format($valorCuota, 0, '.', ','), 1, 0, 'R');
			PDF::Cell(32, 5, '$' . number_format($valorInteres, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(32, 5, '$' . number_format($abonoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(32, 5, '$' . number_format($saldoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Ln();
			$valorInteres           = $generales->calcularValorInteresMensual($saldoCapital, $tasaNominal);
		}

		PDF::Ln(12);
		PDF::Cell(130, 4, '', '', 0, 'L');
		PDF::MultiCell(30, 30, '', 1, 'C', false, 1);
		PDF::Cell(80, 4, $nombrePersona, 'T', 0, 'L');
		PDF::Cell(50, 4, '', '', 0, 'L');
		PDF::Cell(30, 4, 'HUELLA', '', 0, 'L');
		PDF::Ln(4);
		PDF::Cell(80, 4, 'C.C. ', 0, 0, 'L');

		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		} else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function contratoVehiculo($arrayDatos, $contenido, $tipoContrato, $firmasContrato){

		$titulo                 = $arrayDatos['titulo'];
		$numeroContrato         = $arrayDatos['numeroContrato'];
		$placaVehiculo          = $arrayDatos['placaVehiculo'];
		$numeroInterno          = $arrayDatos['numeroInterno'];
		$nombreAsociado         = $arrayDatos['nombreAsociado'];
		$documentoAsociado      = $arrayDatos['documentoAsociado'];
		$direccionAsociado      = $arrayDatos['direccionAsociado'];
		$telefonoAsociado       = $arrayDatos['telefonoAsociado'];
		$correoAsociado         = $arrayDatos['correoAsociado'];
		$firmadoElectonicamente = $arrayDatos['firmadoElectonicamente'];
		$mensajeAsuntoPdf       = $arrayDatos['mensajeAsuntoPdf'];
		$metodo                 = $arrayDatos['metodo'];

		$empresa                 = $this->consultarEmpresa();
		$direccionEmpresa 	     = $empresa->emprdireccion;
		$ciudadEmpresa    	     = $empresa->muninombre;
		$barrioEmpresa    	     = $empresa->emprbarrio;
		$telefonoEmpresa  	     = $empresa->emprtelefonofijo;
		$celularEmpresa   	     = $empresa->emprtelefonocelular;
		$urlEmpresa       	     = $empresa->emprurl;
		$nombreEmpresa           = $empresa->emprnombre;
		$lemaEmpresa             = $empresa->emprlema;
		$siglaEmpresa            = $empresa->emprsigla;
		$nit                     = $empresa->nit;
		$personeriaJuridica      = $empresa->emprpersoneriajuridica;
		$logoEmpresa             = $empresa->emprlogo;

		if($tipoContrato === 'E'){
		   $nombreTipoContrato = 'Especial';
		}else if($tipoContrato === 'I'){
			$nombreTipoContrato = 'Intermunicipal';
		}else if($tipoContrato === 'C'){
			$nombreTipoContrato = 'Colectivo';
		}else {
			$nombreTipoContrato = 'Mixto';
		}

        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Contrato, Vehículo, '.$nombreTipoContrato.', '.$siglaEmpresa.' , '.$mensajeAsuntoPdf.' Contrato número '.$numeroContrato );
        PDF::SetTitle($titulo);	

		//Encabezado y pie de pagina del pdf
		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 36, 15);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 26);
		PDF::SetY(16);
		PDF::Ln(20);

		PDF::SetFont('helvetica', 'B', 12);
		if($tipoContrato === 'E'){
			PDF::Cell(176, 4, 'CONTRATO DE ADMINISTRACIÓN Y/O VINCULACION POR AFILIACIÓN:', 0, 0, 'C');
			PDF::Ln(4);
			PDF::SetFont('helvetica', 'B', 11);
			PDF::Cell(176, 4, 'Decreto Único Reglamentario del Sector Transporte', 0, 0, 'C');
			PDF::Ln(4);
			PDF::Cell(176, 4, 'Servicio Público de Pasajeros en la Modalidad de Transporte Especial', 0, 0, 'C');
		}else if($tipoContrato === 'I'){
			PDF::Cell(176, 4, 'CONTRATO DE VINCULACIÓN POR AFILIACIÓN ', 0, 0, 'C');
			PDF::Ln(4);
			PDF::SetFont('helvetica', 'B', 11);
			PDF::Cell(176, 4, 'MODALIDAD TRANSPORTE INTERMUNICIPAL ', 0, 0, 'C');
		}else if($tipoContrato === 'C'){
			PDF::Cell(176, 4, 'CONTRATO DE VINCULACIÓN POR AFILIACIÓN ', 0, 0, 'C');
			PDF::Ln(4);
			PDF::SetFont('helvetica', 'B', 11);
			PDF::Cell(176, 4, 'MODALIDAD TRANSPORTE COLECTIVO ', 0, 0, 'C');
		}else {
			PDF::Cell(176, 4, 'CONTRATO DE VINCULACIÓN POR AFILIACIÓN ', 0, 0, 'C');
			PDF::Ln(4);
			PDF::SetFont('helvetica', 'B', 11);
			PDF::Cell(176, 4, 'MODALIDAD TRANSPORTE MIXTO ', 0, 0, 'C');
		}

		PDF::Ln(12);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'No. Contrato:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $numeroContrato, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Placa:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $placaVehiculo, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'No móvil:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $numeroInterno, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Propietario:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $nombreAsociado, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Identificación:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $documentoAsociado, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Dirección:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $direccionAsociado, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Teléfono:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $telefonoAsociado, 0, 0, 'L');
		PDF::Ln(5);
		PDF::SetFont('helvetica', '', 11);
		PDF::Cell(40, 4, 'Correo Electrónico:', 0, 0, 'L');
		PDF::SetFont('helvetica', 'B', 11);
		PDF::Cell(136, 4, $correoAsociado, 0, 0, 'L');
		PDF::Ln(12);
		PDF::SetFont('helvetica', '', 9);
		PDF::writeHTML($contenido, true, false, true, false, '');
		PDF::Ln(4);
		PDF::SetFont('helvetica', 'B', 10);
		PDF::Cell(80, 4, 'La EMPRESA', 0, 0, 'L');
		PDF::Cell(16, 4, '', 0, 0, 'L');
		PDF::Cell(80, 4, 'El PROPIETARIO', 0, 0, 'L');
		PDF::Ln(20);

		if($firmadoElectonicamente){
			$posicionY      = PDF::GetY();
			$contadorImagen = 0;
			foreach($firmasContrato as $firmaElectronica){
				$mensajeFirma      = $firmaElectronica['mensajeFirma'];
				if($contadorImagen == 0){
					PDF::Image('images/logoFirmaElectronica.png', 20, $posicionY - 7,20,20);
					PDF::SetFont('helvetica', '', 8);
					PDF::Cell(20, 4, '', 0, 0, 'L');
					$posicionY1 = PDF::GetY();
					$posicionX1 = PDF::GetX();
					PDF::MultiCell(60, 4, $mensajeFirma."\n", 0, 'J', false, 1);
					$contadorImagen += 1;
				}else{
					PDF::Image('images/logoFirmaElectronica.png', 110, $posicionY -7,20,20);
					PDF::SetFont('helvetica', '', 8);
					PDF::Cell(20, 4, '', 0, 0, 'L');
					PDF::SetXY($posicionX1 + 90, $posicionY1);
					PDF::MultiCell(60, 4, $mensajeFirma."\n", 0, 'J', false, 1);
					$contadorImagen = 0;
				}
			}
		}

		//Creamos el xml para adjuntarlo al documento
		$xml              = new \DomDocument('1.0', 'UTF-8'); 
		$raiz             = $xml->appendChild($xml->createElement('firmaDocumento'));
		$datosPersona     = $raiz->appendChild($xml->createElement('datosPersona'));
		$informacionFirma = $raiz->appendChild($xml->createElement('informacionFirma'));

		$contadorFirma = 0;
		foreach($firmasContrato as $firmaContrato){
			$nombrePersona    = $firmaContrato['nombrePersona'];
		    $documentoPersona = $firmaContrato['documentoPersona'];
			if($contadorFirma == 0){
				PDF::Cell(80, 4, '', 'T', 0, 'L');
				PDF::SetFont('helvetica', 'B', 10);
			    PDF::writeHTMLCell(86, 4, 24, '', "<b>".$nombrePersona."</b><br>".$documentoPersona."<br>", 0, 0, 0, true, 'J');
				$contadorFirma += 1;
			}else{
				PDF::Cell(80, 4, '', 'T', 0, 'L');
				PDF::SetFont('helvetica', 'B', 10);	
			    PDF::writeHTMLCell(86, 4, 112, '', "<b>".$nombrePersona."</b><br>".$documentoPersona."<br>", 0, 0, 0, true, 'J');
				$contadorFirma = 0;
			}

			$datosPersona->appendChild($xml->createElement('documento',$documentoPersona));
			$datosPersona->appendChild($xml->createElement('nombre',$nombrePersona));
			$datosPersona->appendChild($xml->createElement('correo',$firmaContrato['correoPersona']));
			$datosPersona->appendChild($xml->createElement('celular',$firmaContrato['celularPersona']));

			$informacionFirma->appendChild($xml->createElement('numeroContrato',$numeroContrato));
			$informacionFirma->appendChild($xml->createElement('fechaNotificacion',$firmaContrato['fechaNotificacion']));
			$informacionFirma->appendChild($xml->createElement('fechaFirma',$firmaContrato['fechaFirmado']));
			$informacionFirma->appendChild($xml->createElement('tokenFirma',$firmaContrato['tokenFirma']));
			$informacionFirma->appendChild($xml->createElement('ipFirma',$firmaContrato['ipFirma']));
			$informacionFirma->appendChild($xml->createElement('medioCorreo',$firmaContrato['medioCorreo']));
			$informacionFirma->appendChild($xml->createElement('medioCelular',$firmaContrato['medioCelular']));
		}

		$xml->preserveWhiteSpace = false;
		$xml->formatOutput       = true;
		$xmlString               = $xml->saveXML();
		$titleXml                = 'FirmaContrato_'.$numeroContrato.'.xml';

		Storage::disk('public')->put($titleXml, $xmlString);
		$xmlFirma = public_path('storage/'.$titleXml);
		PDF::Annotation(85, 27, 5, 5, 'Informacion de la firma', array('Subtype'=>'FileAttachment', 'Name' => 'PushPin', 'T' => 'Documento firmado', 'Subj' => $siglaEmpresa, 'FS' => $xmlFirma));

		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en el servidor	
			$rutaCarpeta  = public_path().'/archivos/vehiculo/'.$placaVehiculo;
			$carpetaServe = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
			$rutaPdf      = $rutaCarpeta.'/Contrato_'.$numeroContrato.'.pdf';
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function pagareColocacion($titulo, $contenido, $numeroPagare, $documento, $metodo = 'S'){

		$empresa       = $this->consultarEmpresa();
		$nombreEmpresa = $empresa->emprnombre;
		$lemaEmpresa   = $empresa->emprlema;
		$siglaEmpresa  = $empresa->emprsigla;
	
        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo.' '.$documento);
		PDF::SetKeywords('Colocación, Vehículo, '.$siglaEmpresa.', '.$numeroPagare.', '.$documento);
        PDF::SetTitle($titulo);	

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetMargins(20, 30, 14);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 26);
		PDF::SetY(8);
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

	function cartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, $metodo = 'S'){

		$empresa            = $this->consultarEmpresa();
		$direccionEmpresa 	= $empresa->emprdireccion;	
		$barrioEmpresa    	= $empresa->emprbarrio;
		$telefonoEmpresa  	= $empresa->emprtelefonofijo;
		$celularEmpresa   	= $empresa->emprtelefonocelular;
		$urlEmpresa       	= $empresa->emprurl;
		$nombreEmpresa      = $empresa->emprnombre;
		$siglaEmpresa       = $empresa->emprsigla;
		$nit                = $empresa->nit;
		$personeriaJuridica = $empresa->emprpersoneriajuridica;
		$logoEmpresa        = $empresa->emprlogo;
	
        PDF::SetAuthor('IMPLESOFT'); 
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo.' '.$documento);
		PDF::SetKeywords('Colocación, Vehículo, '.$siglaEmpresa.', '.$numeroPagare.', '.$documento.', '.$titulo);
        PDF::SetTitle($titulo);

		$this->headerDocumento($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

		PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 36, 14);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(20);
		PDF::Ln(20);
		PDF::SetFont('helvetica', 'B', 13);
		PDF::Cell(176, 4, $titulo, 0, 0, 'L');
		PDF::Ln(16);
		PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');
		PDF::Ln(16);

		PDF::Cell(30, 4,'Firma:', 0, 0, 'L');
		PDF::Cell(80, 4,'', 'B', 0, '');
		PDF::Cell(30, 4,'', '', 0, '');
		PDF::Ln(8);
		PDF::Cell(30, 4,'Nombre:', 0, 0, 'L');
		PDF::Cell(80, 4,'', 'B', 0, '');
		PDF::Cell(30, 4,'', '', 0, '');
		PDF::Ln(8);
		PDF::Cell(30, 4,'C.C. No. ', 0, 0, 'L');
		PDF::Cell(80, 4,'', 'B', 0, '');
		PDF::Cell(30, 4,'', '', 0, '');
		PDF::Ln(8);
		PDF::Cell(30, 4,'Domiciliada en:', 0, 0, 'L');
		PDF::Cell(80, 4,'', 'B', 0, '');
		PDF::Cell(30, 4,'', '', 0, '');
		PDF::SetFillColor(255, 255, 255);
		PDF::MultiCell(30, 30, '', 1, 'J', 1, 1, 150, PDF::GetY() - 24, false, 0, false, false, 60, 'M');
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

	function formatoSolicitudCredito($arrayDatos){

		$documentoAsociado = $arrayDatos['documentoAsociado'];
		$nombrePersona     = $arrayDatos['nombrePersona'];
		$vehiculo          = $arrayDatos['vehiculo'];
		$numeroVehiculo    = $arrayDatos['numeroVehiculo'];
		$placaVehiculo     = $arrayDatos['placaVehiculo'];
		$pagareNumero      = $arrayDatos['pagareNumero'];
		$tipoCredito       = $arrayDatos['tipoCredito'];
		$montoCredito      = $arrayDatos['montoCredito'];
		$valorCuota        = $arrayDatos['valorCuota'];
		$tiempoCredito     = $arrayDatos['tiempoCredito'];
		$fechaDesembolso   = $arrayDatos['fechaDesembolso'];
		$tipoPersona       = $arrayDatos['tipoPersona'];	
		$metodo            = $arrayDatos['metodo'];	
		$tituloFormato     = 'SOLICITUD DE CRÉDITO';
        $versionFormato    = '01';
        $numeroFormato     = 'F-GAF-22';
        $fechaFormato      = '14/10/2016';
        $areaFormato       = 'GESTIÓN ADMINISTRATIVA Y FINANCIERA';
		$titulo            = "Formato solicitud crédito del pagaré número ".$pagareNumero;

		$empresa            = $this->consultarEmpresa();
		$direccionEmpresa 	= $empresa->emprdireccion;	
		$barrioEmpresa    	= $empresa->emprbarrio;
		$telefonoEmpresa  	= $empresa->emprtelefonofijo;
		$celularEmpresa   	= $empresa->emprtelefonocelular;
		$urlEmpresa       	= $empresa->emprurl;
		$nombreEmpresa      = $empresa->emprnombre;
		$siglaEmpresa       = $empresa->emprsigla;
		$nit                = $empresa->nit;
		$personeriaJuridica = $empresa->emprpersoneriajuridica;
		$logoEmpresa        = $empresa->emprlogo;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Colocación, Formato, '.$siglaEmpresa.','.$numeroVehiculo.','.$placaVehiculo.','.$pagareNumero);
        PDF::SetTitle($titulo);

		$this->headerFormato($tituloFormato, $versionFormato, $numeroFormato, $fechaFormato, $areaFormato, $siglaEmpresa, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(12, 36, 12);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(16);
		PDF::Ln(20);
		PDF::SetFont('helvetica', 'B', 13);
		PDF::Cell(188, 4, "FORMATO SOLICITUD DE CRÉDITO", 0, 0, 'C');
		PDF::Ln(12);
		PDF::SetFont('helvetica', '', 11);

        PDF::Cell(40, 4, "NOMBRE:", 0, 0, 'L');
        PDF::Cell(88, 4, $nombrePersona,0, 0, 'L');
		PDF::Cell(26, 4, "CC:", 0, 0, 'L');
        PDF::Cell(40, 4, number_format($documentoAsociado,0,',','.'), 0, 0, 'L');
        PDF::Ln(5);

		if($tipoPersona === 'A'){
			PDF::Cell(40, 4, "VEHÍCULO:", 0, 0, 'L');
			PDF::Cell(88, 4,  $vehiculo,0, 0, 'L');
			PDF::Cell(26, 4, "NÚMERO:", 0, 0, 'L');
			PDF::Cell(40, 4, $numeroVehiculo, 0, 0, 'L'); 
			PDF::Ln(5);

			PDF::Cell(40, 4, "PLACA:", 0, 0, 'L');
			PDF::Cell(76, 4, $placaVehiculo,0, 0, 'L');
		}else{
			PDF::Cell(40, 4, "RESPALDO", 0, 0, 'L');
			PDF::Cell(88, 4, "POR LIBRANZA",0, 0, 'L');
		}

        PDF::Cell(26, 4, "PAGARÉ Nº:", 0, 0, 'L');
        PDF::Cell(40, 4, $pagareNumero, 0, 0, 'L');
        PDF::Ln(5);       

        PDF::Cell(40, 4, "VALOR CUOTA:", 0, 0, 'L');
        PDF::Cell(88, 4, '$ '.number_format($valorCuota,0,',','.') ,0, 0, 'L');
        PDF::Cell(26, 4, "TIEMPO:", 0, 0, 'L');
        PDF::Cell(40, 4, $tiempoCredito.' MESES', 0, 0, 'L');
        PDF::Ln(5);

		PDF::Cell(40, 4, "TIPO DE CRÉDITO:", 0, 0, 'L');
		PDF::MultiCell(88, 4, $tipoCredito, 0, 'L', 0, 0, '', '', true);
		PDF::Cell(26, 4, "MONTO:", 0, 0, 'L');
		PDF::MultiCell(40, 5, '$ '.number_format($montoCredito,0,',','.'), 0, 'L', 0, 1, '', '', true);
		PDF::Ln(12);

        PDF::Cell(40, 4, "CONCEPTO Y/O OBJETO DEL CRÉDITO: ", 0, 0, 'L');
        PDF::Ln(12);

        PDF::MultiCell(52, 10, 'ESTADO', 1, 'C', false, 0);
        PDF::Cell(65, 5, 'AL DÍA', 1, 0, 'C');
        PDF::Cell(65, 5, 'MORA', 1, 0, 'C');
        PDF::Ln(5);
        PDF::Cell(52, 5, '', 0, 0, 'C');
        PDF::Cell(65, 5, 'X', 'LRB', 0, 'C');
        PDF::Cell(65, 5, '', 'LRB', 0, 'C');
        PDF::Ln(12);

        PDF::MultiCell(0, 10, 'NOTA: SE APRUEBA POR  ORDEN DE CONSEJO DE ADMINSITRACION Y GERENCIA.', 0, '', 0);
        PDF::MultiCell(0, 10, 'SE SUSCRIBE EN LA CIUDAD DE OCAÑA A LOS '.$fechaDesembolso, 0, '', 0);

        PDF::Ln(8);
		PDF::Cell(142, 4, '', '', 0, 'L');
		PDF::MultiCell(30, 30, '', 1, 'C', false, 1);
		PDF::Cell(80, 4, 'DEUDOR ', 'T', 0, 'L');
		PDF::Cell(62, 4, '', 0, 0, 'L');
		PDF::Cell(30, 4, 'HUELLA', 0, 0, 'C');
		PDF::Ln(4);
		PDF::Cell(80, 4, 'C.C. ', 0, 0, 'L');
        PDF::Ln(12);

        PDF::Cell(50, 4, "FECHA DE APROBACIÓN:", 0, 0, 'L');
        PDF::Cell(50, 4, "",'B', 0, 'L');
        PDF::Cell(4, 4, "",0, 0, 'L');
        PDF::Cell(32, 4, "ACTA NÚMERO:", 0, 0, 'L');
        PDF::Cell(51, 4, "", "B", 0, 'L');  
        PDF::Ln(12);

        PDF::Cell(60, 4, "APROBADO POR:", 0, 0, 'L');
        PDF::Ln(12); 
        PDF::Cell(60, 4, "GERENTE:", 0, 0, 'L');
        PDF::Cell(127, 4, "",'B', 0, 'L');
        PDF::Ln(12);
        PDF::Cell(60, 4, "PRESIDENTE DEL COMITÉ:",0, 0, 'L');
        PDF::Cell(127, 4, "",'B', 0, 'L');
        PDF::Ln(12);
        PDF::Cell(60, 4, "MIEMBRO DEL COMITÉ :", 0, 0, 'L');   
        PDF::Cell(127, 4, "",'B', 0, 'L');
        PDF::Ln(12);

        PDF::Cell(32, 4, " Observaciones:", 0, 0, 'L');
        PDF::Cell(155, 4, "",'B', 0, 'L');
        PDF::Ln(5);
        PDF::Cell(187, 4, "",'B', 0, 'L');
        PDF::Ln(5);
        PDF::Cell(187, 4, "",'B', 0, 'L');
        PDF::Ln(4);
        PDF::Cell(187, 4, "",'B', 0, 'L');
        PDF::Ln(4);

		$tituloPdf = $titulo.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $contratoVehiculos, $arrayConductores){
		$numeroContratoEspecial    = $arrayDatos['numeroContratoEspecial'];
		$numeroContrato            = $arrayDatos['numeroContrato'];
		$idCifrado                 = $arrayDatos['idCifrado'];
		$metodo                    = $arrayDatos['metodo'];
		$titulo                    = "Formato único de extracto del contrato del servicio público de transporte terrestre Nº ".$numeroContratoEspecial;
		$dataInfoPdfFichaTecnica   = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'fichaTecnica')->first();
		$dataInfoPdfContrato       = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'contratoTransporteEspecial')->first();

		$empresa                   = $this->consultarEmpresa();
		$direccionEmpresa 	       = $empresa->emprdireccion;
		$barrioEmpresa    	       = $empresa->emprbarrio;
		$telefonoEmpresa  	       = $empresa->emprtelefonofijo;
		$celularEmpresa   	       = $empresa->emprtelefonocelular;
		$urlEmpresa       	       = $empresa->emprurl;
		$nombreEmpresa             = $empresa->emprnombre;
		$siglaEmpresa              = $empresa->emprsigla;
		$nit                       = $empresa->nit;
		$personeriaJuridica        = $empresa->emprpersoneriajuridica;
		$logoEmpresa               = $empresa->emprlogo;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject("Formato único de extracto del contrato del servicio público de transporte terrestre automotor especial Nº ".$numeroContratoEspecial);
		PDF::SetKeywords('Formato, contrato, servicio público');
        PDF::SetTitle("Formato contrato del servicio público de transporte terrestre");

		foreach($contratoVehiculos as $contratoVehiculo){
			$numeroContratoCompleto = '454008302'.$contratoVehiculo->coseevextractoanio.''.$numeroContrato.''.$contratoVehiculo->coseevextractoconsecutivo;
			$arrayDatosVehiculo = [
								"numeroContratoVehiculo"       => $numeroContratoCompleto,
								"numeroExtracto"               => $contratoVehiculo->coseevextractoconsecutivo,
								"placaVehiculo"                => $contratoVehiculo->vehiplaca,
								"numeroInternoVehiculo"        => $contratoVehiculo->vehinumerointerno,
								"modeloVehiculo"               => $contratoVehiculo->vehimodelo,
								"marcaVehiculo"                => $contratoVehiculo->timavenombre,
								"claseVehiculo"                => $contratoVehiculo->tipvehnombre,
								"tarjetaOperacionVehiculo"     => $contratoVehiculo->vetaopnumero
							];
			$this->hojaGeneralContratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $arrayConductores, $arrayDatosVehiculo, $nombreEmpresa, $nit, $direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $logoEmpresa);

			$this->hojaFichaGeneral($dataInfoPdfFichaTecnica, $numeroContratoCompleto);
		}

		$this->contratoTransporteEspecial($arrayDatos, $dataInfoPdfContrato, $numeroContratoEspecial, $nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
	    $this->listaPasajeros($arrayDatos, $idCifrado, $logoEmpresa);

		$tituloPdf = 'Planilla_servicio_especial_No_'.$numeroContratoEspecial.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function headerContratoServicioEspecial($idCifrado, $logoEmpresa){
		$style = array(
			'border'        => 0,
			'vpadding'      => 'auto',
			'hpadding'      => 'auto',
			'fgcolor'       => array(0,0,0),
			'bgcolor'       => false,
			'module_width'  => 1,
			'module_height' => 1
		);

		$encrypt   = new encrypt();
		$idCifrado = $encrypt->encrypted($idCifrado);
		$url = asset('verificar/contrato/servicio/especial/'.urlencode($idCifrado));
		PDF::write2DBarcode($url, 'QRCODE,H', 10, 6, 30, 30, $style, 'N');
		PDF::Image('images/logoColombiaPotenciaVida.jpg',50,12,70,14);
		PDF::Image('images/logoSuperTransporte.png',140,12,26,14);
		PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,170,9,26,26);
	}

	function hojaGeneralContratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $arrayConductores, $arrayDatosVehiculo, $nombreEmpresa, $nit, $direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $logoEmpresa){

		$numeroContratoEspecial    = $arrayDatos['numeroContratoEspecial'];	
		$numeroContrato            = $arrayDatos['numeroContrato'];		
		$nombreContratante         = $arrayDatos['nombreContratante'];
		$documentoContratante      = $arrayDatos['documentoContratante'];
		$direccionContratante      = $arrayDatos['direccionContratante'];
		$telefonoContratante       = $arrayDatos['telefonoContratante'];
		$objetoContrato            = $arrayDatos['objetoContrato'];
		$origenContrato            = $arrayDatos['origenContrato'];
		$destinoContrato           = $arrayDatos['destinoContrato'];
		$descripcionRecorrido      = $arrayDatos['descripcionRecorrido'];
		$convenioContrato          = $arrayDatos['convenioContrato'];
		$consorcioContrato         = $arrayDatos['consorcioContrato'];
		$unionTemporal             = $arrayDatos['unionTemporal'];
		$nombreUnionTemporal       = $arrayDatos['nombreUnionTemporal'];
		$firmaGerente              = $arrayDatos['firmaGerente'];
		$observaciones             = $arrayDatos['observaciones'];
		$idCifrado                 = $arrayDatos['idCifrado'];
		$metodo                    = $arrayDatos['metodo'];

		$numeroExtracto            = $arrayDatosVehiculo['numeroExtracto'];
		$placaVehiculo             = $arrayDatosVehiculo['placaVehiculo'];
		$modeloVehiculo            = $arrayDatosVehiculo['modeloVehiculo'];
		$marcaVehiculo             = $arrayDatosVehiculo['marcaVehiculo'];
		$claseVehiculo             = $arrayDatosVehiculo['claseVehiculo'];
		$numeroInternoVehiculo     = $arrayDatosVehiculo['numeroInternoVehiculo'];
		$tarjetaOperacionVehiculo  = $arrayDatosVehiculo['tarjetaOperacionVehiculo'];
		$numeroContratoVehiculo    = $arrayDatosVehiculo['numeroContratoVehiculo'];
	
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);
		PDF::AddPage('P', 'Letter');
		PDF::SetMargins(10, 30 , 10);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(true);
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(22);
		$this->headerContratoServicioEspecial($idCifrado, $logoEmpresa);
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(4);
        PDF::Cell(26, 4,"", 0, 0,'C'); 
        PDF::MultiCell(140, 4, "FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL Nº ".$numeroContratoVehiculo, 0, 'C', 0);
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'RAZON SOCIAL DE LA EMPRESA DE TRANSPORTE ESPECIAL','LTR',0,'L'); 
        PDF::Cell(60,5,'NIT','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5, $nombreEmpresa,'LBR',0,'L');
        PDF::Cell(60,5, $nit,'LBR',0,'L');
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATO: '.$numeroContrato,'LBR',0,'L');
        PDF::Cell(60,5,'EXTRACTO: '.$numeroExtracto,'LBR',0,'L');
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATANTE','LTR',0,'L');
        PDF::Cell(60,5,'NIT/CC','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5,$nombreContratante,'LBR',0,'L');
        PDF::Cell(60,5,$documentoContratante,'LBR',0,'L');
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'OBJETO DEL CONTRATO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',8.4);
        PDF::MultiCell(190, 5, $objetoContrato."\n", 'LR', 'J', 0);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'ORIGEN','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,$origenContrato,'LTR',0,'L');
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'DESTINO','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,$destinoContrato,'LTR',0,'L');
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'DESCRIPCION DEL RECORRIDO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(190, 5, $descripcionRecorrido, 'LTR', 'L', 0);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30,5,'CONVENIO:','LTR',0,'L');
        PDF::Cell(8,5,$convenioContrato,'LTR',0,'C');
        PDF::Cell(30,5,'CONSORCIO:','LTR',0,'L');
        PDF::Cell(8,5,$consorcioContrato,'LTR',0,'C');
        PDF::Cell(34,5,'UNION TEMPORAL:','LTR',0,'L');
        PDF::Cell(8,5,$unionTemporal,'LTR',0,'C');
        PDF::Cell(72,5,'CON: '.$nombreUnionTemporal ,'LTR',0,'L');
        PDF::Ln(5);
        PDF::Cell(190,5,'VIGENCIA DEL CONTRATO','LTR',0,'C');
        PDF::Ln(5);

		foreach($arrayVigenciaContrato as $vigenciaContrato){
			PDF::SetFont('helvetica','B',9);
			PDF::Cell(47.5,5,'FECHA INICIAL','LTR',0,'C');
			PDF::Cell(47.5,5,'DÍA','LTR',0,'C');
			PDF::Cell(47.5,5,'MES','LTR',0,'C');
			PDF::Cell(47.5,5,'AÑO','LTR',0,'C');
			PDF::Ln(5);
			PDF::SetFont('helvetica','',9);
			PDF::Cell(47.5,5,'','LTR',0,'C');
			PDF::Cell(47.5,5,$vigenciaContrato['dia'],'LTR',0,'C');
			PDF::Cell(47.5,5,$vigenciaContrato['mes'],'LTR',0,'C');
			PDF::Cell(47.5,5,$vigenciaContrato['anio'],'LTR',0,'C');
			PDF::Ln(5);
		}

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(190,5,'CARACTERÍSTICAS DEL VEHÍCULO','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(47.5,5,'PLACA','LTR',0,'C');
        PDF::Cell(47.5,5,'MODELO','LTR',0,'C');
        PDF::Cell(47.5,5,'MARCA','LTR',0,'C');
        PDF::Cell(47.5,5,'CLASE','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,$placaVehiculo,'LTR',0,'C');
        PDF::Cell(47.5,5,$modeloVehiculo,'LTR',0,'C');
        PDF::Cell(47.5,5,$marcaVehiculo,'LTR',0,'C');
        PDF::Cell(47.5,5,$claseVehiculo,'LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(95,5,'NÚMERO INTERNO','LTR',0,'C');
        PDF::Cell(95,5,'TARJETA DE OPERACIÓN','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(95,5,$numeroInternoVehiculo,'LTR',0,'C');
        PDF::Cell(95,5,$tarjetaOperacionVehiculo,'LTR',0,'C');
        PDF::Ln(5);

		$numeroConductor = 1;
		foreach($arrayConductores as $conductor){
			PDF::SetFont('helvetica','B',9);
			PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
			PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
			PDF::Cell(30,5,'CEDULA ','LTR',0,'C');
			PDF::Cell(43,5,'Nº DE LICENCIA','LTR',0,'C');
			PDF::Cell(22,5,'VIGENCIA','LTR',0,'C');
			PDF::Ln(5);
			PDF::Cell(30, 5,'CONDUCTOR '.$numeroConductor,'LR',0,'L');
			PDF::SetFont('helvetica','',9);
			PDF::Cell(65,5,substr($conductor->nombreCompleto, 0, 32),'LTR',0,'L');
			PDF::Cell(30,5,number_format($conductor->documento,0,',','.'),'LTR',0,'C');
			PDF::Cell(43,5,$conductor->numeroLicencia,'LTR',0,'C');
			PDF::Cell(22,5,$conductor->vigencia,'LTR',0,'C');
			PDF::Ln(5);
			$numeroConductor ++;
		}

        PDF::Cell(190,5,'','T',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'RESPONSABLE','LTR',0,'L');
        PDF::Cell(65,5,'NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(23,5,'CÉDULA ','LTR',0,'C');
        PDF::Cell(50,5,'DIRECCIÓN','LTR',0,'C');
        PDF::Cell(22,5,'TELÉFONO','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONTRATANTE','LR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,substr($nombreContratante, 0, 32),'LTR',0,'L');
        PDF::Cell(23,5,$documentoContratante,'LTR',0,'C');
        PDF::Cell(50,5,substr($direccionContratante, 0, 25),'LTR',0,'C');
        PDF::Cell(22,5,$telefonoContratante,'LTR',0,'C');

		if($observaciones !== ''){
			PDF::Ln(5);
			PDF::Cell(190,5,'OBSERVACIONES: '.$observaciones,'1',0,'L');
			PDF::Ln(8);
		}

		$posicionY = PDF::GetY() + 7;
		//Coloco las imagenes al final del documento
		PDF::Image('images/selloCooperativa.png',140,$posicionY, 30, 18);
		PDF::Image($firmaGerente,130,$posicionY + 13, 46, 10);

        PDF::Ln(5);
        PDF::Cell(95, 14,'','LTR',0,'L');
        PDF::Cell(95, 14,'','LTR',0,'L');
        PDF::Ln(14);
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
	}

	function hojaFichaGeneral($dataInfoPdfFichaTecnica, $numeroContratoVehiculo){
	    $buscar    = Array('numeroContratoServicioEspecial');
		$remplazo  = Array($numeroContratoVehiculo);
		$contenido = str_replace($buscar,$remplazo,$dataInfoPdfFichaTecnica->ingpdfcontenido);
		PDF::AddPage('P', 'Letter');
		PDF::SetMargins(20, 30 , 20);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::Ln(4);
		PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');
	}

	function contratoTransporteEspecial($arrayDatos, $dataInfoPdfContrato, $numeroContratoServicioEspecial, $nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa){

		$firmaGerente                 = $arrayDatos['firmaGerente'];
		$nombreGerente                = $arrayDatos['nombreGerente'];
		$documentoGerente             = $arrayDatos['documentoGerente'];
		$nombreContratante            = $arrayDatos['nombreContratante'];
		$documentoContratante         = $arrayDatos['documentoContratante'];
		$valorContrato                = $arrayDatos['valorContrato'];
		$objetoContrato               = $arrayDatos['objetoContrato'];
		$origenContrato               = $arrayDatos['origenContrato'];
		$destinoContrato              = $arrayDatos['destinoContrato'];
		$fechaInicialContrato         = $arrayDatos['fechaInicialContrato'];
		$fechaFinalContrato           = $arrayDatos['fechaFinalContrato'];
		$descripcionServicoContratado = $arrayDatos['descripcionServicoContratado'];

		$buscar    = Array('numeroContratoServicioEspecial', 'nombreGerente', 'documentoGerente','nombreContratante', 'documentoContratante', 'valorContrato', 'objetoContrato', 'origenContrato', 'destinoContrato', 'fechaInicialContrato', 'fechaFinalContrato', 'descripcionServicoContratado');
		$remplazo  = Array($numeroContratoServicioEspecial, $nombreGerente, $documentoGerente, $nombreContratante, $documentoContratante, $valorContrato, $objetoContrato, $origenContrato, $destinoContrato, $fechaInicialContrato, $fechaFinalContrato, $descripcionServicoContratado);
		$titulo    = str_replace($buscar,$remplazo,$dataInfoPdfContrato->ingpdftitulo);
		$contenido = str_replace($buscar,$remplazo,$dataInfoPdfContrato->ingpdfcontenido);

		PDF::AddPage('P', 'Letter');
		PDF::SetMargins(20, 30 , 20);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(true);
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
		PDF::Cell(170,4,str_pad('',  71, "_", STR_PAD_LEFT),'0',0,'C');	
		PDF::Ln(16);
		PDF::SetFont('helvetica', 'B', 12);
		PDF::Cell(176, 4, $titulo, 0, 0, 'C');
		PDF::Ln(16);
		PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');
		$posicionY =  PDF::GetY();
		PDF::Ln(32);
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(90, 6,$nombreContratante,0,0,'L');
        PDF::Cell(10, 6,'',0,0,'L');
        PDF::Cell(90, 6,$nombreGerente,0,0,'L');
        PDF::Ln(6);
		PDF::Image('images/selloCooperativa.png', 130, $posicionY + 8, 30, 18);
		PDF::Image($firmaGerente, 120, $posicionY + 22, 46, 10);

        PDF::Cell(90, 6,'NIT/C.C: '.$documentoContratante,0,0,'L');
        PDF::Cell(10, 6,'',0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(90, 6,'EL CONTRATISTA',0,0,'L');
        PDF::Ln(6);
        PDF::Cell(90, 6,'EL CONTRATANTE',0,0,'L');
	}

	function listaPasajeros($arrayDatos, $idCifrado, $logoEmpresa){

		$firmaGerente  = $arrayDatos['firmaGerente'];
		$nombreGerente = $arrayDatos['nombreGerente'];
		PDF::AddPage('P', 'Letter');
		PDF::SetMargins(10, 30 , 10);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(true);
		PDF::SetAutoPageBreak(true, 30);
		$this->headerContratoServicioEspecial($idCifrado, $logoEmpresa);
		PDF::SetY(30);
		PDF::SetFont('helvetica','B',11);
		PDF::Ln(4);
        PDF::Cell(26, 4,"", 0, 0,'C'); 
        PDF::MultiCell(140, 4, "FORMATO UNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO", 0, 'C', 0);
		PDF::Cell(26, 4,"", 0, 0,'C'); 
		PDF::MultiCell(140, 4, "PUBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL", 0, 'C', 0);
        PDF::Ln(8);
		PDF::Cell(26, 4,"", 0, 0,'C'); 
		PDF::MultiCell(140, 4, "ANEXO LISTA DE PASAJEROS", 0, 'C', 0);
        PDF::Ln(8);

		PDF::SetFont('helvetica','B',8);
		PDF::Cell(60, 4,"NOMBRE", 'B', 0,'L');
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(1, 4,"",'L', 0,'C');
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(30, 4,"C.C", 'B', 0,'C'); 
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(1, 4,"",'L', 0,'C');
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(60, 4,"NOMBRE", 'B', 0,'L');
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(1, 4,"",'L', 0,'C');
		PDF::Cell(1, 4,"",0, 0,'C');
		PDF::Cell(30, 4,"C.C",'B', 0,'C');
		PDF::Ln(4);

		for($i = 0; $i <= 20; $i++){
			PDF::SetFont('helvetica','B',8);
			PDF::Cell(60, 7,"", 'B', 0,'L');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(1, 7,"",'L', 0,'C');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(30, 7,"", 'B', 0,'C');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(1, 7,"",'L', 0,'C');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(60, 7,"", 'B', 0,'L');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(1, 7,"",'L', 0,'C');
			PDF::Cell(1, 7,"",0, 0,'C');
			PDF::Cell(30, 7,"",'B', 0,'C');
			PDF::Ln(7);
		}

		$posicionY =  PDF::GetY();
		PDF::Ln(32);
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(90, 6,'',0,0,'L');
        PDF::Cell(10, 6,'',0,0,'L');
        PDF::Cell(90, 6,$nombreGerente,0,0,'L');
        PDF::Ln(6);
		PDF::Image('images/selloCooperativa.png', 120, $posicionY + 8, 30, 18);
		PDF::Image($firmaGerente, 110, $posicionY + 22, 46, 10);

        PDF::Cell(90, 6,'',0,0,'L');
        PDF::Cell(10, 6,'',0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(90, 6,'EL CONTRATISTA',0,0,'L');
	}

	function planillaServicioTransporte($arrayDatos, $tiquetes, $agencias){

		$fechaPlanilla        = $arrayDatos['fechaPlanilla'];
		$numeroPlanilla       = $arrayDatos['numeroPlanilla'];
		$fechaSalida          = $arrayDatos['fechaSalida'];
		$horaSalida           = $arrayDatos['horaSalida'];
		$nombreRuta           = $arrayDatos['nombreRuta'];
		$numeroVehiculo       = $arrayDatos['numeroVehiculo'];
		$placaVehiculo        = $arrayDatos['placaVehiculo'];
		$conductorVehiculo    = $arrayDatos['conductorVehiculo'];
		$documentoConductor   = $arrayDatos['documentoConductor'];
		$telefonoConductor    = $arrayDatos['telefonoConductor'];
		$valorEncomienda      = $arrayDatos['valorEncomienda'];
		$valorDomicilio       = $arrayDatos['valorDomicilio'];
		$valorComision        = $arrayDatos['valorComision'];
		$valorTotal           = $arrayDatos['valorTotal'];
		$numeroOperacion      = $arrayDatos['numeroOperacion'];
		$usuarioElabora       = $arrayDatos['usuarioElabora'];
		$usuarioDespacha      = $arrayDatos['usuarioDespacha'];
		$direccionAgencia     = $arrayDatos['direccionAgencia'];
		$telefonoAgencia      = $arrayDatos['telefonoAgencia'];
		$mensajePlanilla      = $arrayDatos['mensajePlanilla'];
		$subTotalTiquete      = $arrayDatos['subTotalTiquete'];
		$valorFondoReposicion = $arrayDatos['valorFondoReposicion'];
		$valorTotalTiquete    = $arrayDatos['valorTotalTiquete'];
		$cantidadPasajeros    = $arrayDatos['cantidadPasajeros'];
		$metodo               = $arrayDatos['metodo'];
		$linea                = str_pad('', 66, "-", STR_PAD_LEFT);
		$empresa              = $this->consultarEmpresa();
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$correEmpresa 	      = $empresa->emprcorreo;
		$urlEmpresa       	  = $empresa->emprurl;
		$personeriaJuridica	  = $empresa->emprpersoneriajuridica;
		
		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject("Formato de planilla de viaje Nº ".$numeroPlanilla);
		PDF::SetKeywords('Formato, planilla, servicio público');
        PDF::SetTitle("Formato planilla número ".$numeroPlanilla);

		//PDF::AddPage('P', array(60,196));
		PDF::AddPage('P', array(60,214));
		PDF::SetMargins(2, 4 , 2);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetAutoPageBreak(true, 2);
		PDF::SetY(2);
		PDF::SetFont('helvetica','',7);
		PDF::Ln(4);
		PDF::Cell(56, 3,$siglaEmpresa, 0, 0,'C'); 
		PDF::Ln(3);
		PDF::Cell(56, 3,"NIT: ".$nit, 0, 0,'C');
		PDF::Ln(3);
		PDF::Cell(56, 3,$personeriaJuridica, 0, 0,'C'); 
        PDF::Ln(3);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::Ln(2);
		PDF::Cell(56, 2,"PLANILLA DE VIAJE", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);
		
		PDF::Cell(12, 3,"Fecha:", 0, 0,'L'); 
		PDF::Cell(44, 3,$fechaPlanilla, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Planilla:", 0, 0,'L'); 
		PDF::Cell(44, 3,$numeroPlanilla, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Salida:", 0, 0,'L'); 
		PDF::Cell(44, 3,$fechaSalida, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Hora:", 0, 0,'L'); 
		PDF::Cell(44, 3,$horaSalida, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Ruta:", 0, 0,'L'); 
		PDF::Cell(44, 3,$nombreRuta, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Vehiculo:", 0, 0,'L'); 
		PDF::Cell(44, 3,$numeroVehiculo, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Placa:", 0, 0,'L'); 
		PDF::Cell(44, 3,$placaVehiculo, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Conductor:", 0, 0,'L');
		PDF::Cell(44, 3,$conductorVehiculo, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"C.C.:", 0, 0,'L');
		PDF::Cell(44, 3,$documentoConductor, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Telefono:", 0, 0,'L');
		PDF::Cell(44, 3,$telefonoConductor, 0, 0,'L'); 

		PDF::Ln(3);
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DE TIQUETES", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(14, 3,"Tiquete", 0, 0,'C');
		PDF::Cell(6, 3,"P", 0, 0,'C'); 
		PDF::Cell(12, 3,"Pasajero", 0, 0,'C');
		PDF::Cell(12, 3,"Destino", 0, 0,'C');
		PDF::Cell(12, 3,"Valor", 0, 0,'C');
		PDF::Ln(3);

		foreach($tiquetes as $tiquete){
			PDF::Cell(14, 3,$tiquete->numeroTiquete, 0, 0,'L');
			PDF::Cell(6, 3,$tiquete->numeroPuesto, 0, 0,'L'); 
			PDF::Cell(12, 3,substr($tiquete->nombreCliente, 0, 8) , 0, 0,'L');
			PDF::Cell(12, 3,substr($tiquete->municipioDestino, 0, 9) , 0, 0,'L');
			PDF::Cell(12, 3,'$ '.number_format($tiquete->totalTiquete,0,',','.'), 0, 0,'R');
			PDF::Ln(3);
		}

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::SetFont('helvetica','',6);
		PDF::Cell(22, 3, 'Subtotal:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($subTotalTiquete,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'Fondo de reposición:', 0, 0,'L');
		PDF::Cell(34, 3, '-$ '.number_format($valorFondoReposicion,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'Estampillas:', 0, 0,'L');
		PDF::Cell(34, 3, '-$ 0', 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'Valor total:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($valorTotalTiquete,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'Nro. pasajeros:', 0, 0,'L');
		PDF::Cell(34, 3, $cantidadPasajeros, 0, 0,'R');

		PDF::Ln(3);
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DE ENCOMIENDAS", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(22, 3, 'V. Encomiendas:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($valorEncomienda,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'V. Domicilio:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($valorDomicilio,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'V. Comisión:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($valorComision,0,',','.'), 0, 0,'R');
		PDF::Ln(3);
		PDF::Cell(22, 3, 'V. Total:', 0, 0,'L');
		PDF::Cell(34, 3, '$ '.number_format($valorTotal,0,',','.'), 0, 0,'R');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"DETALLE DEL FONDO DE REPOSICIÓN", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		foreach($agencias as $agencia){
			PDF::Cell(22, 3, $agencia->nombreAgencia, 0, 0,'L');
			PDF::Cell(34, 3, '$ '.number_format($agencia->totalFondoReposicion,0,',','.'), 0, 0,'R');
			PDF::Ln(3);
		}

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3, 'Operación:', 0, 0,'L');
		PDF::Cell(44, 3, $numeroOperacion, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Elabora:', 0, 0,'L');
		PDF::Cell(44, 3, $usuarioElabora, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Despacha:', 0, 0,'L');
		PDF::Cell(44, 3, $usuarioDespacha, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Dirección:', 0, 0,'L');
		PDF::Cell(44, 3, $direccionAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Teléfono:', 0, 0,'L');
		PDF::Cell(44, 3, $telefonoAgencia, 0, 0,'l');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);

		PDF::MultiCell(56, 3, $mensajePlanilla, 0, 'L', false, 1);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);
		PDF::MultiCell(56, 3, 'Vigilado por la superintendencia de puertos y transporte', 0, 'C', false, 1);
		PDF::Cell(56, 2, $urlEmpresa, 0, 0,'C');

		$tituloPdf = 'Planilla_viaje_No_'.$numeroPlanilla.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function facturaEncomienda($arrayDatos){

		$numeroEncomienda      = $arrayDatos['numeroEncomienda'];
		$fechaEncomienda       = $arrayDatos['fechaEncomienda'];
		$rutaEncomienda        = $arrayDatos['rutaEncomienda'];
		$tipoEncomienda        = $arrayDatos['tipoEncomienda'];
		$origenEncomienda      = $arrayDatos['origenEncomienda'];
		$destinoEncomienda     = $arrayDatos['destinoEncomienda'];
		$pagoContraentrega     = $arrayDatos['pagoContraentrega'];		
		$valorDeclarado        = $arrayDatos['valorDeclarado'];
		$valorEnvio            = $arrayDatos['valorEnvio'];
		$valorDomicilio        = $arrayDatos['valorDomicilio'];
		$valorSeguro           = $arrayDatos['valorSeguro'];
		$valorTotal            = $arrayDatos['valorTotal'];
		$nombreRemitente       = $arrayDatos['nombreRemitente'];
		$direccionRemitente    = $arrayDatos['direccionRemitente'];
		$telefonoRemitente     = $arrayDatos['telefonoRemitente'];
		$nombreDestinatario    = $arrayDatos['nombreDestinatario'];
		$direccionDestinatario = $arrayDatos['direccionDestinatario'];
		$telefonoDestinatario  = $arrayDatos['telefonoDestinatario'];
		$usuarioElabora        = $arrayDatos['usuarioElabora'];
		$nombreAgencia         = $arrayDatos['nombreAgencia'];
		$direccionAgencia      = $arrayDatos['direccionAgencia'];
		$telefonoAgencia       = $arrayDatos['telefonoAgencia'];
		$mensajePlanilla       = $arrayDatos['mensajePlanilla'];
		$metodo                = $arrayDatos['metodo'];

		$linea                 = str_pad('', 66, "-", STR_PAD_LEFT);
		$empresa               = $this->consultarEmpresa();
		$siglaEmpresa          = $empresa->emprsigla;
		$nit                   = $empresa->nit;
		$correEmpresa 	       = $empresa->emprcorreo;
		$urlEmpresa       	   = $empresa->emprurl;
		$personeriaJuridica	   = $empresa->emprpersoneriajuridica;
		
		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject("Formato de planilla de encomienda Nº ".$numeroEncomienda);
		PDF::SetKeywords('Formato, planilla, servicio público, encomienda, '. $numeroEncomienda);
        PDF::SetTitle("Formato encomienda número ".$numeroEncomienda);

		PDF::AddPage('P', array(60,136));
		PDF::SetMargins(2, 4 , 2);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetAutoPageBreak(true, 2);
		PDF::SetY(2);
		PDF::SetFont('helvetica','',7);
		PDF::Ln(4);
		PDF::Cell(56, 3,$siglaEmpresa, 0, 0,'C'); 
		PDF::Ln(3);
		PDF::Cell(56, 3,"NIT: ".$nit, 0, 0,'C');
		PDF::Ln(3);
		PDF::Cell(56, 3,$personeriaJuridica, 0, 0,'C'); 
        PDF::Ln(3);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::Ln(2);
		PDF::Cell(56, 2,"FACTURA DE ENCOMIENDA", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);
		
		PDF::Cell(20, 3,"Fecha:", 0, 0,'L'); 
		PDF::Cell(36, 3,$fechaEncomienda, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(20, 3,"Número:", 0, 0,'L'); 
		PDF::Cell(36, 3,$numeroEncomienda, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Ruta:", 0, 0,'L'); 
		PDF::Cell(36, 3,$rutaEncomienda, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Tipo encomienda:", 0, 0,'L'); 
		PDF::Cell(36, 3,$tipoEncomienda, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Origen:", 0, 0,'L'); 
		PDF::Cell(36, 3,$origenEncomienda, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Destino:", 0, 0,'L'); 
		PDF::Cell(36, 3,$destinoEncomienda, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Valor declarado:", 0, 0,'L');
		PDF::Cell(36, 3,'$ '.$valorDeclarado, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(20, 3,"Valor envío:", 0, 0,'L'); 
		PDF::Cell(36, 3,'$ '.$valorEnvio, 0, 0,'L'); 
		PDF::Ln(3);

		if($valorDomicilio > 0){
			PDF::Cell(20, 3,"Valor domicilio:", 0, 0,'L'); 
			PDF::Cell(36, 3,'$ '.$valorDomicilio, 0, 0,'L'); 
			PDF::Ln(3);
		}

		PDF::Cell(20, 3,"Valor seguro:", 0, 0,'L'); 
		PDF::Cell(36, 3,'$ '.$valorSeguro, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::Cell(20, 3,"Valor total:", 0, 0,'L');
		PDF::Cell(36, 3,'$ '.$valorTotal, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(20, 3,"Pago contra entrega:", 0, 0,'L');
		PDF::Cell(36, 3, $pagoContraentrega, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DEL REMITENTE", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3,"Nombre:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreRemitente, 0, 0,'L'); 
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Dirección:", 0, 0,'L'); 
		PDF::Cell(44, 3,$direccionRemitente, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Teléfono:", 0, 0,'L'); 
		PDF::Cell(44, 3,$telefonoRemitente, 0, 0,'L'); 
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DEL DESTINATARIO", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3,"Nombre:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreDestinatario, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Dirección:", 0, 0,'L'); 
		PDF::Cell(44, 3,$direccionDestinatario, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3,"Teléfono:", 0, 0,'L'); 
		PDF::Cell(44, 3,$telefonoDestinatario, 0, 0,'L'); 
		PDF::Ln(3);
		
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"DETALLE DE USUARIO", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3, 'Registrado:', 0, 0,'L');
		PDF::Cell(44, 3, $usuarioElabora, 0, 0,'l');
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Agencia:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreAgencia, 0, 0,'L'); 
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Dirección:', 0, 0,'L');
		PDF::Cell(44, 3, $direccionAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Teléfono:', 0, 0,'L');
		PDF::Cell(44, 3, $telefonoAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);

		PDF::MultiCell(56, 3, $mensajePlanilla, 0, 'C', false, 1);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);
		PDF::MultiCell(56, 3, 'Vigilado por la superintendencia de puertos y transporte', 0, 'C', false, 1);
		PDF::Cell(56, 2, $urlEmpresa, 0, 0,'C');

		$tituloPdf = 'Factura_encomienda_no_'.$numeroEncomienda.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function facturaTiquete($arrayDatos){

		$numeroTiquete      = $arrayDatos['numeroTiquete'];
		$fechaTiquete      	= $arrayDatos['fechaTiquete'];
		$rutaTiquete        = $arrayDatos['rutaTiquete'];
		$origenTiquete      = $arrayDatos['origenTiquete'];
		$destinoTiquete     = $arrayDatos['destinoTiquete'];
		$valorTiquete       = $arrayDatos['valorTiquete'];
		$descuentoTiquete   = $arrayDatos['descuentoTiquete'];
		$valorTotalTiquete  = $arrayDatos['valorTotalTiquete'];
		$valorSeguro        = $arrayDatos['valorSeguro'];
		$valorEstampilla    = $arrayDatos['valorEstampilla'];
		$numeroPuestos      = $arrayDatos['numeroPuestos'];
		$nombreCliente      = $arrayDatos['nombreCliente'];
		$direccionCliente   = $arrayDatos['direccionCliente'];
		$telefonoCliente    = $arrayDatos['telefonoCliente'];
		$usuarioElabora     = $arrayDatos['usuarioElabora'];
		$nombreAgencia      = $arrayDatos['nombreAgencia'];
		$direccionAgencia   = $arrayDatos['direccionAgencia'];
		$telefonoAgencia    = $arrayDatos['telefonoAgencia'];
		$mensajePlanilla    = $arrayDatos['mensajePlanilla'];
		$metodo             = $arrayDatos['metodo'];

		$linea              = str_pad('', 66, "-", STR_PAD_LEFT);
		$empresa            = $this->consultarEmpresa();
		$siglaEmpresa       = $empresa->emprsigla;
		$nit                = $empresa->nit;
		$correEmpresa 	    = $empresa->emprcorreo;
		$urlEmpresa       	= $empresa->emprurl;
		$personeriaJuridica	= $empresa->emprpersoneriajuridica;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject("Formato de planilla de Tiquete Nº ".$numeroTiquete);
		PDF::SetKeywords('Formato, Planilla, Tiquete, Servicio público,'. $numeroTiquete);
        PDF::SetTitle("Formato tiquete número ".$numeroTiquete);

		PDF::AddPage('P', array(60,130));
		PDF::SetMargins(2, 4 , 2);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetAutoPageBreak(true, 2);
		PDF::SetY(2);
		PDF::SetFont('helvetica','',7);
		PDF::Ln(4);
		PDF::Cell(56, 3,$siglaEmpresa, 0, 0,'C'); 
		PDF::Ln(3);
		PDF::Cell(56, 3,"NIT: ".$nit, 0, 0,'C');
		PDF::Ln(3);
		PDF::Cell(56, 3,$personeriaJuridica, 0, 0,'C'); 
        PDF::Ln(3);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::Ln(2);
		PDF::Cell(56, 2,"FACTURA DE TIQUETE", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(18, 3,"Fecha:", 0, 0,'L');
		PDF::Cell(38, 3,$fechaTiquete, 0, 0,'L');
		PDF::Ln(3);
		PDF::Cell(18, 3,"Número:", 0, 0,'L');
		PDF::Cell(38, 3,$numeroTiquete, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(18, 3,"Ruta:", 0, 0,'L');
		PDF::Cell(38, 3,$rutaTiquete, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(18, 3,"Origen:", 0, 0,'L');
		PDF::Cell(38, 3,$origenTiquete, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(18, 3,"Destino:", 0, 0,'L');
		PDF::Cell(38, 3,$destinoTiquete, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(18, 3,"Puesto:", 0, 0,'L');
		PDF::Cell(38, 3,$numeroPuestos, 0, 0,'L');
		PDF::Ln(3);

		PDF::Cell(18, 3,"Valor:", 0, 0,'L');
		PDF::Cell(38, 3,'$ '.$valorTiquete, 0, 0,'L'); 
		PDF::Ln(3);

		if($descuentoTiquete > 0){
			PDF::Cell(18, 3,"Descuento:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$descuentoTiquete, 0, 0,'L');
			PDF::Ln(3);
		}

		if($valorSeguro > 0){
			PDF::Cell(18, 3,"Seguro:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$valorSeguro, 0, 0,'L');
			PDF::Ln(3);
		}

		if($valorEstampilla > 0){
			PDF::Cell(18, 3,"Estampilla:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$valorEstampilla, 0, 0,'L');
			PDF::Ln(3);
		}

		PDF::Cell(18, 3,"Valor total:", 0, 0,'L'); 
		PDF::Cell(38, 3,'$ '.$valorTotalTiquete, 0, 0,'L');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DEL CLIENTE", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3,"Nombre:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreCliente, 0, 0,'L');
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Dirección:", 0, 0,'L'); 
		PDF::Cell(44, 3,$direccionCliente, 0, 0,'L');
		PDF::Ln(3);
		PDF::Cell(12, 3,"Teléfono:", 0, 0,'L'); 
		PDF::Cell(44, 3,$telefonoCliente, 0, 0,'L');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"DETALLE DE USUARIO", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3, 'Usuario:', 0, 0,'L');
		PDF::Cell(44, 3, $usuarioElabora, 0, 0,'l');
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Agencia:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreAgencia, 0, 0,'L');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Dirección:', 0, 0,'L');
		PDF::Cell(44, 3, $direccionAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Teléfono:', 0, 0,'L');
		PDF::Cell(44, 3, $telefonoAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);

		PDF::MultiCell(56, 3, $mensajePlanilla, 0, 'C', false, 1);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);
		PDF::MultiCell(56, 3, 'Vigilado por la superintendencia de puertos y transporte', 0, 'C', false, 1);
		PDF::Cell(56, 2, $urlEmpresa, 0, 0,'C');

		$tituloPdf = 'Factura_tiquete_no_'.$numeroTiquete.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function facturaPagoMensualidad($arrayDatos){

		$fechaPago      	= $arrayDatos['fechaPago'];
		$valorPago          = $arrayDatos['valorPago'];
		$descuentoPago      = $arrayDatos['descuentoPago'];
		$interesCorriente   = $arrayDatos['interesCorriente'];		
		$interesMora        = $arrayDatos['interesMora'];
		$valorTotalPago     = $arrayDatos['valorTotalPago'];
		$documentoCliente   = $arrayDatos['documentoCliente'];		
	    $nombreCliente      = $arrayDatos['nombreCliente'];
		$direccionCliente   = $arrayDatos['direccionCliente'];
		$telefonoCliente    = $arrayDatos['telefonoCliente'];
		$usuarioElabora     = $arrayDatos['usuarioElabora'];
		$nombreAgencia      = $arrayDatos['nombreAgencia'];
		$direccionAgencia   = $arrayDatos['direccionAgencia'];
		$telefonoAgencia    = $arrayDatos['telefonoAgencia'];
		$mensajePlanilla    = $arrayDatos['mensajePlanilla'];
		$tituloFactura      = $arrayDatos['tituloFactura'];		
		$metodo             = $arrayDatos['metodo'];

		$linea              = str_pad('', 66, "-", STR_PAD_LEFT);
		$empresa            = $this->consultarEmpresa();
		$siglaEmpresa       = $empresa->emprsigla;
		$nit                = $empresa->nit;
		$correEmpresa 	    = $empresa->emprcorreo;
		$urlEmpresa       	= $empresa->emprurl;
		$personeriaJuridica	= $empresa->emprpersoneriajuridica;

		PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject("Formato pago mensualidad");
		PDF::SetKeywords('Formato, Pago, Mensualidad, Servicio público');
        PDF::SetTitle("Formato pago de mensualidad");

		PDF::AddPage('P', array(60,100));
		PDF::SetMargins(2, 4 , 2);
		PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetAutoPageBreak(true, 2);
		PDF::SetY(2);
		PDF::SetFont('helvetica','',7);
		PDF::Ln(4);
		PDF::Cell(56, 3,$siglaEmpresa, 0, 0,'C'); 
		PDF::Ln(3);
		PDF::Cell(56, 3,"NIT: ".$nit, 0, 0,'C');
		PDF::Ln(3);
		PDF::Cell(56, 3,$personeriaJuridica, 0, 0,'C'); 
        PDF::Ln(3);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::Ln(2);
		PDF::Cell(56, 2, $tituloFactura, 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(18, 3,"Fecha:", 0, 0,'L');
		PDF::Cell(38, 3,$fechaPago, 0, 0,'L');
		PDF::Ln(3);

		if($valorPago > 0){
			PDF::Cell(18, 3,"Valor:", 0, 0,'L');
			PDF::Cell(38, 3,'$ '.$valorPago, 0, 0,'L'); 
			PDF::Ln(3);
		}

		if($descuentoPago > 0){
			PDF::Cell(18, 3,"Descuento:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$descuentoPago, 0, 0,'L');
			PDF::Ln(3);
		}

		if($interesCorriente > 0){
			PDF::Cell(18, 3,"Interés corriente:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$interesCorriente, 0, 0,'L');
			PDF::Ln(3);
		}

		if($interesMora > 0){
			PDF::Cell(18, 3,"Interés mora:", 0, 0,'L'); 
			PDF::Cell(38, 3,'$ '.$interesMora, 0, 0,'L');
			PDF::Ln(3);
		}

		PDF::Cell(18, 3,"Valor total:", 0, 0,'L'); 
		PDF::Cell(38, 3,'$ '.$valorTotalPago, 0, 0,'L');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"INFORMACIÓN DEL CLIENTE", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L'); 
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3,"Nombre:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreCliente, 0, 0,'L');
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Dirección:", 0, 0,'L'); 
		PDF::Cell(44, 3,$direccionCliente, 0, 0,'L');
		PDF::Ln(3);
		PDF::Cell(12, 3,"Teléfono:", 0, 0,'L'); 
		PDF::Cell(44, 3,$telefonoCliente, 0, 0,'L');
		PDF::Ln(3);

		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::Ln(2);
		PDF::Cell(56, 3,"DETALLE DE USUARIO", 0, 0,'C');
		PDF::Ln(2);
		PDF::Cell(56, 2, $linea, 0, 0,'L');
		PDF::SetFont('helvetica','',6);
		PDF::Ln(3);

		PDF::Cell(12, 3, 'Usuario:', 0, 0,'L');
		PDF::Cell(44, 3, $usuarioElabora, 0, 0,'l');
		PDF::Ln(3);	
		PDF::Cell(12, 3,"Agencia:", 0, 0,'L');
		PDF::Cell(44, 3,$nombreAgencia, 0, 0,'L');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Dirección:', 0, 0,'L');
		PDF::Cell(44, 3, $direccionAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::Cell(12, 3, 'Teléfono:', 0, 0,'L');
		PDF::Cell(44, 3, $telefonoAgencia, 0, 0,'l');
		PDF::Ln(3);
		PDF::SetFont('helvetica','',7);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);

		PDF::MultiCell(56, 3, $mensajePlanilla, 0, 'C', false, 1);
		PDF::Cell(56, 2, $linea, 0, 0,'L');	
		PDF::Ln(3);
		PDF::MultiCell(56, 3, 'Vigilado por la superintendencia de puertos y transporte', 0, 'C', false, 1);
		PDF::Cell(56, 2, $urlEmpresa, 0, 0,'C');

		$tituloPdf = 'Factura_pago_mensualidad_de_'.$documentoCliente.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function generarComprobanteContable($arrayDatos, $moviemientosContables){

		$nombreUsuario       = $arrayDatos['nombreUsuario'];
		$nuemeroComprobante  = $arrayDatos['nuemeroComprobante'];
		$fechaComprobante    = $arrayDatos['fechaComprobante'];
		$nombreAgencia       = $arrayDatos['nombreAgencia'];
		$numeroCaja          = $arrayDatos['numeroCaja'];
		$conceptoComprobante = $arrayDatos['conceptoComprobante']; 
		$mensajeImpresion    = $arrayDatos['mensajeImpresion']; 
		$metodo              = $arrayDatos['metodo'];
        $titulo              = 'Comprobante contable número '.$nuemeroComprobante;
		$tituloPdf           = 'Comprobante_contable_numero_'.$nuemeroComprobante;

		$empresa              = $this->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;	

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Comprobante contable, documento, '.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($titulo);	

		$this->headerDocumentoHorizontal($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$this->footerDocumentoHorizontal($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $mensajeImpresion);

        PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 35 , 20);
		PDF::AddPage('L', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(20); 
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(16);
		PDF::Cell(254,5,'COMPROBANTE CONTABLE',0,0,'L');
		PDF::Ln(8);
        PDF::SetFont('helvetica','',10);
		PDF::Cell(38,4,'Número comprobante: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(52,4,$nuemeroComprobante,0,0,'L'); 
        PDF::SetFont('helvetica','',10);
        PDF::Cell(15,4,'Fecha: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(45,4,$fechaComprobante,0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(18,4,'Agencia: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(68,4,$nombreAgencia,0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(10,4,'Caja: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(8,4,$numeroCaja,0,0,'L');
        PDF::Ln(6);
        PDF::SetFont('helvetica','',10); 
        PDF::Cell(38,5,'Concepto: ',0,0,'L');
        PDF::MultiCell(216, 0, $conceptoComprobante."\n", 0, 'J', 0);
		PDF::SetFont('helvetica','',12); 
		PDF::Ln(8);
        PDF::Cell(30,4,'Código ','RB',0,'L');
        PDF::Cell(166,4,'Cuenta ','RB',0,'L');
        PDF::Cell(30,4,'Débito ','RB',0,'C');
        PDF::Cell(30,4,'Crédito ','RB',0,'C');
        PDF::Ln(5);

        $valorTotalDebito  = 0;
        $valorTotalCredito = 0;
        foreach($moviemientosContables as $datos){
            $valorTotalDebito  += $datos->valorDebito;
            $valorTotalCredito += $datos->valorCredito;

            PDF::Cell(30,4,$datos->cueconcodigo,'R',0,'L');
            PDF::Cell(166,4,substr(mb_strtolower($datos->cueconnombre,'UTF-8') , 0, 83) ,'R',0,'L');
            PDF::Cell(30,4,'$'.number_format($datos->valorDebito,0,',','.'),'R',0,'R');
            PDF::Cell(30,4,'$'.number_format($datos->valorCredito,0,',','.'),'R',0,'R');
            PDF::Ln(5);
        }

        PDF::Ln(5);
        PDF::Cell(196,4,"Totales: ",0,0,'R');
        PDF::Cell(30,4,'$'.number_format($valorTotalDebito,0,',','.'),'TR',0,'R');
        PDF::Cell(30,4,'$'.number_format($valorTotalCredito,0,',','.'),'T',0,'R');

        PDF::Ln(24);
        PDF::Cell(80,4,$nombreUsuario,0,0,'L');
        PDF::Ln(6);
        PDF::Cell(80,4,"Preparó ",'T',0,'L');
        PDF::Cell(7,4,"",0,0,'C');
        PDF::Cell(80,4,"Revisó ",'T',0,'L');
        PDF::Cell(7,4,"",0,0,'R');
        PDF::Cell(80,4,"Aprobó ",'T',0,'L');

		$tituloPdf = $tituloPdf.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'F'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}

	function generarFormatoSolicitud($arrayDatos){
		$tipoIdentificacion     = $arrayDatos['tipoIdentificacion'];
		$documentoIdentidad     = $arrayDatos['documentoIdentidad'];
		$nombresSolicitante     = $arrayDatos['nombresSolicitante'];
		$apellidosSolicitante   = $arrayDatos['apellidosSolicitante'];
		$nombreCompleto         = $nombresSolicitante.' '.$apellidosSolicitante;
		$direccionSolicitante   = $arrayDatos['direccionSolicitante'];
		$telefonoSolicitante    = $arrayDatos['telefonoSolicitante'];
		$correoSolicitante      = $arrayDatos['correoSolicitante'];
		$tipoSolicitud          = $arrayDatos['tipoSolicitud'];
		$tipoMedio              = $arrayDatos['tipoMedio'];
		$fechaRegistro          = $arrayDatos['fechaRegistro'];
		$fechaIncidente         = $arrayDatos['fechaIncidente'];
		$conductorInvolucrado   = $arrayDatos['conductorInvolucrado'];
		$vehiculoInvolucrado    = $arrayDatos['vehiculoInvolucrado'];
		$motivoSolicitud        = $arrayDatos['motivoSolicitud'];
		$observacionesSolicitud = $arrayDatos['observacionesSolicitud'];
		$anioRadicado           = $arrayDatos['anioRadicado'];
		$nombreArchivo          = $arrayDatos['nombreArchivo'];		
		$metodo                 = $arrayDatos['metodo'];
	
		$empresa                = $this->consultarEmpresa();
		$direccionEmpresa 	    = $empresa->emprdireccion;	
		$barrioEmpresa    	    = $empresa->emprbarrio;
		$telefonoEmpresa  	    = $empresa->emprtelefonofijo;
		$celularEmpresa   	    = $empresa->emprtelefonocelular;
		$urlEmpresa       	    = $empresa->emprurl;
		$nombreEmpresa          = $empresa->emprnombre;
		$siglaEmpresa           = $empresa->emprsigla;
		$nit                    = $empresa->nit;
		$personeriaJuridica     = $empresa->emprpersoneriajuridica;
		$logoEmpresa            = $empresa->emprlogo;

		$mensajeFormato         = 'Con el fin de mejorar cada dia en la prestacion de nuestros servicios y/o Producto suministrado, ';
        $mensajeFormato         .= 'solicitamos diligenciar este formato con la finalidad de conocer, tramitar y dar solucion a las ';
        $mensajeFormato         .= 'peticiones, quejas, reclamos, sugerencias y/o felicitaciones presentadas por usted(s).';
        $titulo                 = 'Formato de recepción de peticiones, quejas, reclarmos, sugerencias y felicitaciones';
		$tituloPdf              = 'Formato_PQRS';
        $tituloFormato          = 'PETICIÓN, QUEJA, RECLAMO, SUGERENCIA Y/O FELICITACIÓN';
        $versionFormato         = '02'; 
        $numeroFormato          = 'F-GC-10'; 
        $fechaFormato           = '08/11/2016'; 
        $areaFormato            = 'GESTIÓN CALIDAD';

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Solicitud, Queja, Reclamo, Sugerencia, Felicitación, Formato, '.$siglaEmpresa);
        PDF::SetTitle($titulo);

		$this->headerFormato($tituloFormato, $versionFormato, $numeroFormato, $fechaFormato, $areaFormato, $siglaEmpresa, $logoEmpresa);
		$this->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::SetMargins(12, 30 , 12);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32);

        PDF::SetTextColor(179,179,180);
        PDF::SetFont('helvetica','I',9);
        PDF::MultiCell(0,3,$mensajeFormato."\n",0,'J',0);
        PDF::SetTextColor(0);

		PDF::Ln(4);
        PDF::SetFillColor(232, 231, 231);
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(188,4,'INFORMACIÓN DEL USUARIO',1,0,'',true);
        PDF::Ln(8);

        PDF::Cell(44,4,'Tipo de identificación:',0,0,'');
        PDF::SetFont('helvetica','',11);
        PDF::Cell(60,4,$tipoIdentificacion,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Documento: ',0,0,'');
        PDF::SetFont('helvetica','',11);
        PDF::Cell(58,4,$documentoIdentidad,0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Nombres (s): ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,substr($nombresSolicitante, 0, 23),0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Apellido (s): ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(58,4,substr($apellidosSolicitante, 0, 23),0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Correo electrónico: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,substr($correoSolicitante, 0, 27),0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Teléfono: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(58,4,$telefonoSolicitante,0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Dirección: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(144, 4, $direccionSolicitante, 0, 'L', false, 1);
        PDF::Ln(4);
        PDF::SetFillColor(232, 231, 231);
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(188,4,'INFORMACIÓN DE LA SOLICITUD',1,0,'',true);
        PDF::Ln(8);

        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Tipo de solicitud: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,$tipoSolicitud,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(42,4,'Tipo de medio: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(40,4,$tipoMedio,0,0,'');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Fecha de registro: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,$fechaRegistro,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(42,4,'Fecha de incidente:',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(40,4,$fechaIncidente,0,0,'');
        PDF::Ln(6);

		if($conductorInvolucrado !== null ){
			PDF::SetFont('helvetica','B',12);
			PDF::Cell(44,4,'Conductor: ',0,0,'');
			PDF::SetFont('helvetica','',12);
			PDF::MultiCell(144, 4, $conductorInvolucrado, 0, 'L', false, 1);
		}

		if($vehiculoInvolucrado !== null ){			
			PDF::SetFont('helvetica','B',12);
			PDF::Cell(44,4,'Vehículo: ',0,0,'');
			PDF::SetFont('helvetica','',12);
			PDF::MultiCell(144, 4, $vehiculoInvolucrado, 0, 'L', false, 1);
		}     

        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Motivo: ',0,0,'');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(188,4,$motivoSolicitud."\n",0,'J');

        PDF::Ln(8);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Observaciones: ',0,0,'');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(188,4,$observacionesSolicitud."\n",0,'J');

        PDF::Ln(12);
        PDF::SetFont('helvetica','I',12);
        PDF::MultiCell(188,4,$nombreCompleto."\n",0,'J');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(90,4,'FIRMA DE QUIEN PRESENTA LA SOLICITUD ','T',0,'L');

		$tituloPdf = $tituloPdf.'.pdf';
		if($metodo === 'S'){
			return base64_encode(PDF::output($tituloPdf, 'S'));
		}else if($metodo === 'T'){//Descargamos la copia en temporal
			$rutaCarpeta = sys_get_temp_dir().'/'.$tituloPdf;
			fopen($rutaCarpeta, "w+");
			PDF::output($rutaCarpeta, 'F');
			return $rutaCarpeta;
		}else if($metodo === 'F'){//Descargamos la copia en el servidor	
			$rutaCarpeta  = public_path().'/archivos/radicacion/documentoEntrante/'.$anioRadicado;
			$carpetaServe = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
			$rutaPdf      = $rutaCarpeta.'/'.$nombreArchivo;
			PDF::output($rutaPdf, 'F');
			return $rutaPdf;
		}else{
			PDF::output($tituloPdf, $metodo);
		}
	}
}