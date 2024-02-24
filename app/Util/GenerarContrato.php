<?php

namespace App\Util;

use App\Util\generarPdf;
use App\Util\generales;
use Exception, DB;
use Carbon\Carbon;

class GenerarContrato
{
    public static function vehiculo($contratoId, $metodo = 'S')
    {
        $vehiculoContrato = DB::table('vehiculocontrato as vc')
                        ->select('vc.vehconid','vc.vehconfechainicial','vc.vehconfechafinal', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),
                        'v.vehinumerointerno','v.vehiplaca','v.timoveid','tmv.timovecuotasostenimiento','tmv.timovedescuentopagoanticipado','tmv.timoverecargomora',
                        'p.persdocumento', 'p.persdireccion', 'p.perscorreoelectronico','p.persnumerocelular', 'p.perstienefirmaelectronica', 
                        'pe.persdocumento as documentoGerente','me.muninombre as nombreMunicipioExpedicion', 'pe.persdocumento as documentoGerente',
                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"),
                        DB::raw("CONCAT(pe.persprimernombre,' ',IFNULL(pe.perssegundonombre,''),' ',pe.persprimerapellido,' ',IFNULL(pe.perssegundoapellido,'')) as nombreGerente"),
                        DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 INNER JOIN vehiculocontrato as vc1 ON vc1.vehconid = vcf1.vehconid WHERE vc1.vehiid = vc.vehiid) AS totalFirmas"),
                        DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 INNER JOIN vehiculocontrato as vc2 ON vc2.vehconid = vcf2.vehconid WHERE vc2.vehiid = vc.vehiid and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                        ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                        ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                        ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->join('persona as pe', 'pe.persid', '=', 'vc.persidgerente') 
                        ->join('municipio as me', function($join)
                        {
                            $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                            $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                        })
                        ->where('vc.vehconid', $contratoId)
                        ->first();

        if($vehiculoContrato->timoveid === 'E' ){
            $idInformacionPdf = 'contratoModalidadEspecial';
        }else  if($vehiculoContrato->timoveid === 'I'){
            $idInformacionPdf = 'contratoModalidadIntermunicipal';
        }else  if($vehiculoContrato->timoveid === 'C'){
            $idInformacionPdf = 'contratoModalidadColectivo';
        } else {
            $idInformacionPdf = 'contratoModalidadMixto';
        }

        $generales                      = new generales();
        $generarPdf                     = new generarPdf();
        $informacionPDF                 = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', $idInformacionPdf)->first();
        $fechaFirmaContrato             = $generales->formatearFecha($vehiculoContrato->vehconfechainicial);
        $cuotaSostenimientoAdmon        = number_format($vehiculoContrato->timovecuotasostenimiento, 0, ',', '.') ;
        $descuentoPagoAnualAnticipado   = $vehiculoContrato->timovedescuentopagoanticipado;
        $recargoCuotaSostenimientoAdmon = $vehiculoContrato->timoverecargomora;
        $nombreGerente                  = $vehiculoContrato->nombreGerente;
        $documentoGerente               = number_format($vehiculoContrato->documentoGerente, 0, ',', '.');
        $nombrePersona                  = $vehiculoContrato->nombreAsociado;
        $documentoAsociado              = number_format($vehiculoContrato->persdocumento, 0, ',', '.');
        $ciudadExpDocumentoGerente      = $vehiculoContrato->nombreMunicipioExpedicion;;
        $numeroContrato                 = $vehiculoContrato->numeroContrato;
        $fechaContrato                  = $generales->formatearFechaContrato($vehiculoContrato->vehconfechainicial);
        $tipoContrato                   = $vehiculoContrato->timoveid;
        $documentoAsociado              = number_format($vehiculoContrato->persdocumento, 0, ',', '.');
        $totalFirmas                    = $vehiculoContrato->totalFirmas;
        $totalFirmasRealizadas          = $vehiculoContrato->totalFirmasRealizadas;
        $nombreAsociado                 = trim($vehiculoContrato->nombreAsociado);
        $direccionAsociado              = $vehiculoContrato->persdireccion;
        $telefonoAsociado               = ($vehiculoContrato->persnumerocelular !== null ) ? $vehiculoContrato->persnumerocelular: '';
        $correoAsociado                 = ($vehiculoContrato->perscorreoelectronico !== null ) ? $vehiculoContrato->perscorreoelectronico: '';

        //Determino firma del contrato
        $firmasElectronicas = [];
        $mensajeAsuntoPdf   = '';
        if($vehiculoContrato->perstienefirmaelectronica and $totalFirmas === $totalFirmasRealizadas){
            $firmasContrato    = DB::table('vehiculocontratofirma as vcf')
                                    ->select('vcf.vecofitoken','vcf.vecofifechahorafirmado','p.persdocumento',
                                     DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                                    ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                    ->where('vcf.vehconid', $contratoId)
                                    ->get();

            foreach($firmasContrato as $firmaContrato){
                $tokeFirma         = $firmaContrato->vecofitoken;
                $fechaFirmado      = $firmaContrato->vecofifechahorafirmado;
                $nombrePersona     = $firmaContrato->nombrePersona;
                $mensajeFirma      = 'Documento firmado electrónicamente el día '.$fechaFirmado.', mediante el token número '.$tokeFirma.' por '.$nombrePersona;
                $mensajeAsuntoPdf .= $mensajeFirma.', ';
                $array = [
                    "mensajeFirma"     => $mensajeFirma,
                    "nombrePersona"    => $nombrePersona,
                    "documentoPersona" => 'C.C. '.number_format($firmaContrato->persdocumento, 0, ',', '.')
                ];
                array_push($firmasElectronicas, $array); 
            }
        }else{//Se imprime las persona que deben firmar
            $array = [
                "mensajeFirma"     => '',
                "nombrePersona"    => $nombreGerente,
                "documentoPersona" => 'C.C. '.$documentoGerente
            ];
            array_push($firmasElectronicas, $array); 

            $array = [
                "mensajeFirma"     => '',
                "nombrePersona"    => $nombrePersona,
                "documentoPersona" => 'C.C. '.$documentoAsociado
            ];
            array_push($firmasElectronicas, $array); 
        }

        $arrayDatos = [ "titulo"                 => 'Contrato número '.$numeroContrato,
                        "numeroContrato"         => $numeroContrato,
                        "placaVehiculo"          => $vehiculoContrato->vehiplaca,
                        "numeroInterno"          => $vehiculoContrato->vehinumerointerno,
                        "nombreAsociado"         => $nombreAsociado,
                        "documentoAsociado"      => $documentoAsociado,
                        "direccionAsociado"      => $direccionAsociado,
                        "telefonoAsociado"       => $telefonoAsociado,
                        "correoAsociado"         => $correoAsociado,
                        "firmadoElectonicamente" => ($totalFirmas === $totalFirmasRealizadas) ? true : false,
                        "mensajeAsuntoPdf"       => $mensajeAsuntoPdf,
                        "metodo"                 => $metodo
                    ];

        $buscar      = Array('documentoGerente', 'nombreGerente', 'ciudadExpDocumentoGerente', 'cuotaSostenimientoAdmon','descuentoPagoAnualAnticipado',
                            'recargoCuotaSostenimientoAdmon','fechaFirmaContrato','fechaContrato');
        $remplazo    = Array($documentoGerente, $nombreGerente, $ciudadExpDocumentoGerente, $cuotaSostenimientoAdmon, $descuentoPagoAnualAnticipado,
                            $recargoCuotaSostenimientoAdmon, $fechaFirmaContrato, $fechaContrato); 
        $contenido   = str_replace($buscar,$remplazo,$informacionPDF->ingpdfcontenido);
        $pdfGenerado = $generarPdf->contratoVehiculo($arrayDatos, $contenido, $tipoContrato, $firmasElectronicas);

        return $pdfGenerado;
    }
}