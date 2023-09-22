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
	function certificado($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->certificado($id);
		
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
		PDF::SetKeywords('Cerrtificado, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($codigoInstitucional);

		//Encabezado
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

		//Pie de pagina
		PDF::setFooterCallback(function($pdf) use ($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento){
			$linea = str_pad('',  52, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::SetFont('helvetica','I',12);
			PDF::Ln(2);
			PDF::SetY(268);	
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

			//Crypt::encrypt($id)
			$url = asset('verificar/documento/'.urlencode($idCifrado));			
			PDF::write2DBarcode($url, 'QRCODE,H', 20, 264, 30, 30, $style, 'N');

			if($estadoDocumento === 10){
				PDF::SetFont('helvetica', 'B', 70);
				PDF::SetTextColor(229, 229, 229);
				PDF::StartTransform();	
				PDF::Rotate(52);
				PDF::Text(74, 240, 'Documento anulado');
				PDF::StopTransform();
			}
		});

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

	    //Informacion para visualizar o descargar el pdf
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

	function constancia($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento) =  $visualizar->constancia($id);
		
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

		//Encabezado
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

		//Pie de pagina
		PDF::setFooterCallback(function($pdf) use ($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento){
			$linea = str_pad('',  52, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::SetFont('helvetica','I',12);
			PDF::Ln(2);
			PDF::SetY(268);	
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

			//Crypt::encrypt($id)
			$url = asset('verificar/documento/'.urlencode($idCifrado));			
			PDF::write2DBarcode($url, 'QRCODE,H', 20, 264, 30, 30, $style, 'N');

			if($estadoDocumento === 10){
				PDF::SetFont('helvetica', 'B', 70);
				PDF::SetTextColor(229, 229, 229);
				PDF::StartTransform();	
				PDF::Rotate(52);
				PDF::Text(74, 240, 'Documento anulado');
				PDF::StopTransform();
			}
		});

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

	    //Informacion para visualizar o descargar el pdf
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

    function oficio($id, $metodo = 'I')
	{
        $funcion          = new generales();
		$encrypt          = new encrypt();
        $fechaHoraActual  = Carbon::now();
		$fechaActual      = $funcion->formatearFecha($fechaHoraActual->format('Y-m-d'));   
	    $visualizar       = new showTipoDocumental();
		list($infodocumento, $firmasDocumento, $copiaDependencias, $anexosDocumento) =  $visualizar->oficio($id);

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

		$idCifrado            = $encrypt->encrypted($infodocumento->codoprid);
		$fechaActualDocumento = $infodocumento->codoprfecha;
  		$anioDocumento        = $infodocumento->codopoanio;
  		$tipoDocumento        = $infodocumento->tipdoccodigo;
  		$siglaDependencia     = $infodocumento->codoposigla;
  		$codigoInstitucional  = $tipoDocumento.'-'.$siglaDependencia.'-'.$infodocumento->codopoconsecutivo;
		$codigoDocumental     = $infodocumento->depecodigo.' '.$infodocumento->serdoccodigo.','.$infodocumento->susedocodigo;
		$estadoDocumento      = $infodocumento->tiesdoid;
			
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

		//Encabezado
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

		//Pie de pagina
		PDF::setFooterCallback(function($pdf) use ($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa,$celularEmpresa, $urlEmpresa, $idCifrado, $estadoDocumento){
			$linea = str_pad('',  52, "_", STR_PAD_LEFT); //Diibuja la linea
			PDF::SetFont('helvetica','I',12);
			PDF::Ln(2);
			PDF::SetY(268);
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

			//Crypt::encrypt($id)
			$url = asset('verificar/documento/'.urlencode($idCifrado));
			PDF::write2DBarcode($url, 'QRCODE,H', 20, 264, 30, 30, $style, 'N');

			if($estadoDocumento === 10){
				PDF::SetFont('helvetica', 'B', 70);
				PDF::SetTextColor(229, 229, 229);
				PDF::StartTransform();	
				PDF::Rotate(52);
				PDF::Text(74, 240, 'Documento anulado');
				PDF::StopTransform();
			}
		});

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

		$cont = 0;
		foreach ($firmasDocumento as $firma)
		{
			$remitente = $firma->nombrePersona;
			$cargo     = $firma->carlabnombre;
			$firmado   = $firma->codopffirmado;
			$rutaFirma = ($firmado == 1) ? $firma->firmaPersona : 'images/documentoSinFirma.png';

			if($cont == 0){
				PDF::Image($rutaFirma, 28, PDF::GetY() -7,50,8);//le quito -7 a la posicion y
			    PDF::writeHTMLCell(86, 4, 24, '', "<b>".$remitente."</b><br>".$cargo."<br>", 0, 0, 0, true, 'J');
			    $cont += 1;
			}else{
				PDF::Image($rutaFirma, 114, PDF::GetY() -7,50,8);
			    PDF::writeHTMLCell(86, 4, 112, '', "<b>".$remitente."</b><br>".$cargo."<br>", 0, 0, 0, true, 'J');
			    PDF::Ln(24);
			    $cont = 0;
			}
		}

		if(count($firmasDocumento) == 1){//Por si solo tiene una sola firma
			PDF::Ln(20);
		}

		//verifico si tiene adjunto
		if($tieneAnexo == 1){
			PDF::Cell(20, 4, 'Anexos:', 0, 0, '');

			//imprimo los adjuntos
			foreach ($anexosDocumento as $anexo)
			{
				$nombreArchivo = $anexo->codopxnombreanexooriginal;
				$nombreEditado = $anexo->codopxnombreanexoeditado;
				$rutaAdjunto   = asset('/archivos/produccionDocumental/adjuntos/'.$siglaDependencia.'/'.$anioDocumento.'/'.Crypt::decrypt($anexo->codopxrutaanexo)); 
$html = <<<EOD
		<a href="$rutaAdjunto" target="\_blank" title="$nombreArchivo" >$nombreArchivo</a>
EOD;
			    PDF::writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true); 
				PDF::Cell(20, 4, '', 0, 0, '');
			}

			if($nombreAnexo != '') {
	            PDF::MultiCell(0, 4, $nombreAnexo, 0, '', 0);
	        }
		}

		//Verifico si tiene copia
		if($tieneCopia == 1){
			PDF::Ln(4);
			PDF::Cell(20, 4, 'Copia:', 0, 0, '');
			//imprimo las depedencias a las que va dirigida la copia
			foreach ($copiaDependencias as $copiaDependencia)
			{
				PDF::MultiCell(140, 4, $copiaDependencia->depenombre, 0, '', 0);
				PDF::Cell(20, 4, '', 0, 0, '');
			}

			if ($nombreCopia != '') {
			    PDF::MultiCell(140, 4, $nombreCopia, 0, '', 0);
	        }
		}

		PDF::Ln(10);
		PDF::Cell(30,4,$transcriptor,0,0,'');

	    //Informacion para visualizar o descargar el pdf
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

	function mensajeFirmarCentro(){
		PDF::SetFont('helvetica', 'B', 12);
		PDF::SetTextColor(255, 0, 0);
		PDF::Cell(165,4,'Documento pendiente por firmar',0,0,'C');
		PDF::SetTextColor(0, 0, 0);
	}

}