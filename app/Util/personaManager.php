<?php

namespace App\Util;

use App\Models\Conductor\ConductorCambioEstado;
use App\Models\Conductor\ConductorCertificado;
use App\Models\Conductor\ConductorLicencia;
use Illuminate\Support\Facades\Crypt;
use App\Models\Conductor\Conductor;
use Exception, Auth, File, DB, URL;
use App\Util\redimencionarImagen;
use App\Models\Asociado\Asociado;
use App\Models\Persona\Persona;
use App\Util\generales;
use Carbon\Carbon;

class personaManager {

    public function registrar($request){
        $id      = $request->codigo;
        $persona = ($id != 000) ? Persona::findOrFail($id) : new Persona(); 

        DB::beginTransaction();
        try {

			$redimencionarImagen = new redimencionarImagen();
            $funcion 		     = new generales();
            $documentoPersona    = $request->documento;
            $rutaCarpeta         = public_path().'/archivos/persona/'.$documentoPersona;
            $carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('firma')){
				$file                = $request->file('firma');
				$nombreOriginalFirma = $file->getclientOriginalName();
				$filename            = pathinfo($nombreOriginalFirma, PATHINFO_FILENAME);
				$extension           = pathinfo($nombreOriginalFirma, PATHINFO_EXTENSION);
				$rutaFirma           = 'Firma_'.$documentoPersona."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFirma);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFirma, 280, 140);//Se redimenciona a un solo tipo
			}else{
				$rutaFirma = $request->rutaFirmaOld;
			}

            if($request->hasFile('fotografia')){
				$file               = $request->file('fotografia');
				$nombreOriginalFoto = $file->getclientOriginalName();
				$filename           = pathinfo($nombreOriginalFoto, PATHINFO_FILENAME);
				$extension          = pathinfo($nombreOriginalFoto, PATHINFO_EXTENSION);
				$rutaFotografia     = $documentoPersona."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFotografia);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFotografia, 210, 270);//Se redimenciona a un solo tipo
			}else{
				$rutaFotografia = $request->rutaFotoOld;
			}

            if($request->hasFile('rutaCrt')){
				$file              = $request->file('rutaCrt');
				$nombreOriginalCrt = $file->getclientOriginalName();
				$filename          = pathinfo($nombreOriginalCrt, PATHINFO_FILENAME);
				$extension         = pathinfo($nombreOriginalCrt, PATHINFO_EXTENSION);
				$rutaCrt           = $documentoPersona."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaCrt);
                $rutaCrt          = Crypt::encrypt($rutaCrt);
			}else{
				$rutaCrt = $request->rutaCrtOld;
			}

            if($request->hasFile('rutaPem')){
				$file              = $request->file('rutaPem');
				$nombreOriginalPem = $file->getclientOriginalName();
				$filename          = pathinfo($nombreOriginalPem, PATHINFO_FILENAME);
				$extension         = pathinfo($nombreOriginalPem, PATHINFO_EXTENSION);
				$rutaPem           = $documentoPersona."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaPem);
                $rutaPem           = Crypt::encrypt($rutaPem);
			}else{
				$rutaPem = $request->rutaPemOld;
			}

            $persona->carlabid                  = $request->cargo;
            $persona->tipideid                  = $request->tipoIdentificacion;
            $persona->tipperid                  = $request->tipoPersona;
            $persona->persdepaidnacimiento      = $request->departamentoNacimiento;
            $persona->persmuniidnacimiento      = $request->municipioNacimiento;
            $persona->persdepaidexpedicion      = $request->departamentoExpedicion;
            $persona->persmuniidexpedicion      = $request->municipioExpedicion;
            $persona->persdocumento             = $documentoPersona;
            $persona->persprimernombre          = mb_strtoupper($request->primerNombre,'UTF-8');
            $persona->perssegundonombre         = mb_strtoupper($request->segundoNombre,'UTF-8');
            $persona->persprimerapellido        = mb_strtoupper($request->primerApellido,'UTF-8');
            $persona->perssegundoapellido       = mb_strtoupper($request->segundoApellido,'UTF-8');
            $persona->persfechanacimiento       = $request->fechaNacimiento;
            $persona->persdireccion             = $request->direccion;
            $persona->perscorreoelectronico     = $request->correo;
            $persona->persfechadexpedicion      = $request->fechaExpedicion;
            $persona->persnumerotelefonofijo    = $request->telefonoFijo;
            $persona->persnumerocelular         = $request->numeroCelular;
            $persona->persgenero                = $request->genero;
            $persona->persrutafoto              = $rutaFotografia;
            $persona->persrutafirma             = $rutaFirma;
            $persona->perstienefirmaelectronica = $request->firmaElectronica;
            $persona->perstienefirmadigital     = $request->firmaDigital;
            $persona->persclavecertificado      = $request->claveCertificado;
            $persona->persrutacrt               = $rutaCrt;
            $persona->persrutapem               = $rutaPem;
            $persona->persactiva                = $request->estado;
            $persona->save();

            if($request->formulario === 'ASOCIADO'){
                if($request->hasFile('rutaCertificadoAsociado')){
                    $file                    = $request->file('rutaCertificadoAsociado');
                    $nombreOriginalPem       = $file->getclientOriginalName();
                    $filename                = pathinfo($nombreOriginalPem, PATHINFO_FILENAME);
                    $extension               = pathinfo($nombreOriginalPem, PATHINFO_EXTENSION);
                    $rutaCertificadoAsociado = $documentoPersona."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                    $file->move($rutaCarpeta, $rutaCertificadoAsociado);
                    $rutaCertificadoAsociado = Crypt::encrypt($rutaCertificadoAsociado);
                }else{
                    $rutaCertificadoAsociado = $request->rutaCertificadoAsociadoOld;
                }
            }

            if($request->formulario === 'ASOCIADO' and $request->tipo === 'I'){
                $personaMaxConsecutio          = Persona::latest('persid')->first();
				$persid                        = $personaMaxConsecutio->persid;

                $asociado                      = new Asociado();
                $asociado->persid              = $persid;
                $asociado->tiesasid            = 'A';
                $asociado->asocfechaingreso    = $request->fechaIngresoAsociado;
                $asociado->asocrutacertificado = $rutaCertificadoAsociado;
                $asociado->save();
            }

            if($request->formulario === 'ASOCIADO' and $request->tipo === 'U'){
                $asociado                      = DB::table('asociado')->select('asocid')->where('persid', $persona->persid)->first();
                $asociado                      = Asociado::findOrFail($asociado->asocid);
                $asociado->asocfechaingreso    = $request->fechaIngresoAsociado;
                $asociado->asocrutacertificado = $rutaCertificadoAsociado; 
                $asociado->save();
            }

            if($request->formulario === 'CONDUCTOR'){

                $debeActualizarImagen = false;
                if($request->hasFile('imagenLicencia')){
                    $debeActualizarImagen = true;
                    $numeroAleatorio      = rand(100, 1000);
                    $file                 = $request->file('imagenLicencia');
                    $nombreOriginalLic    = $file->getclientOriginalName();
                    $filename             = pathinfo($nombreOriginalLic, PATHINFO_FILENAME);
                    $extension            = pathinfo($nombreOriginalLic, PATHINFO_EXTENSION);
                    $rutaImagenLicencia   = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                    $file->move($rutaCarpeta, $rutaImagenLicencia);
                    $rutaArchivo          = Crypt::encrypt($rutaImagenLicencia);
                    $extension            = mb_strtoupper($extension,'UTF-8');
                    if($extension !== 'PDF')
                        $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenLicencia, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
                }

                if($request->tipo === 'I'){
                    $personaMaxConsecutio       = Persona::latest('persid')->first();
                    $persid                     = $personaMaxConsecutio->persid;

                    $conductor                   = new Conductor();
                    $conductor->persid           = $persid;
                    $conductor->tiescoid         = 'A';
                    $conductor->tipconid         = $request->tipoConductor;
                    $conductor->agenid           = $request->agencia;
                    $conductor->condfechaingreso = $request->fechaIngresoConductor;
                    $conductor->save();

                    $personaMaxConductor        = Conductor::latest('condid')->first();
                    $condid                     = $personaMaxConductor->condid;

                    $conductorlicencia                              = new ConductorLicencia();
                    $conductorlicencia->condid                      = $condid;
                    $conductorlicencia->ticaliid                    = $request->tipoCategoria;
                    $conductorlicencia->conlicnumero                = $request->numeroLicencia;
                    $conductorlicencia->conlicfechaexpedicion       = $request->fechaExpedicionLicencia;
                    $conductorlicencia->conlicfechavencimiento      = $request->fechaVencimiento;
                    if($debeActualizarImagen){
                        $conductorlicencia->conlicextension             = $extension;
                        $conductorlicencia->conlicnombrearchivooriginal = $nombreOriginalLic;
                        $conductorlicencia->conlicnombrearchivoeditado  = $rutaImagenLicencia;
                        $conductorlicencia->conlicrutaarchivo           = $rutaArchivo;
                    }
                    $conductorlicencia->save();

                }else{
                    $conductor                   = Conductor::findOrFail($request->conductor);
                    $conductor->tipconid         = $request->tipoConductor;
                    $conductor->agenid           = $request->agencia;
                    $conductor->condfechaingreso = $request->fechaIngresoConductor;
                    $conductor->save();

                    if($request->crearHistorial === 'S'){
                        $conductorlicencia                     = new ConductorLicencia();
                        $conductorlicencia->condid             = $request->conductor;

                        //Verifico el estado actual del conductor
                        if($conductor->tiescoid === 'S'){//Se encuentra suspendido por falta de licencia
                            $estado              = 'A';
                            $conductor           = Conductor::findOrFail($request->conductor);
                            $conductor->tiescoid = $estado;
                            $conductor->save();

                            $conductorcambioestado 					  = new ConductorCambioEstado();
                            $conductorcambioestado->condid            = $request->conductor;
                            $conductorcambioestado->tiescoid          = $estado;
                            $conductorcambioestado->cocaesusuaid      = Auth::id();
                            $conductorcambioestado->cocaesfechahora   = Carbon::now();
                            $conductorcambioestado->cocaesobservacion = 'Se activa el conductor por el registro de la licencia número '.$request->numeroLicencia;
                            $conductorcambioestado->save();
                        }
                    }else{
                        $conductorlicencia                     = ConductorLicencia::findOrFail($request->licencia);
                    }

                    $conductorlicencia->ticaliid               = $request->tipoCategoria;
                    $conductorlicencia->conlicnumero           = $request->numeroLicencia;
                    $conductorlicencia->conlicfechaexpedicion  = $request->fechaExpedicion;
                    $conductorlicencia->conlicfechavencimiento = $request->fechaVencimiento;

                    if($debeActualizarImagen){
                        $conductorlicencia->conlicextension             = $extension;
                        $conductorlicencia->conlicnombrearchivooriginal = $nombreOriginalLic;
                        $conductorlicencia->conlicnombrearchivoeditado  = $rutaImagenLicencia;
                        $conductorlicencia->conlicrutaarchivo           = $rutaArchivo;
                    }

                    $conductorlicencia->save();
                }

                //Registramos los certificados
                if($request->hasFile('certificadoConductor')){
                    $conductor       = DB::table('conductor')->select('condid')->where('persid', $persona->persid)->first();
                    $numeroAleatorio = rand(100, 1000);
                    $files           = $request->file('certificadoConductor');
                    foreach($files as $file){
                        $nombreOriginal = $file->getclientOriginalName();
                        $filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                        $extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                        $nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                        $file->move($rutaCarpeta, $nombreArchivo);
                        $rutaArchivo    = Crypt::encrypt($nombreArchivo);

                        $conductorcertificado                              = new ConductorCertificado();
                        $conductorcertificado->condid                      = $conductor->condid;
                        $conductorcertificado->concerextension             = $extension;
                        $conductorcertificado->concernombrearchivooriginal = $nombreOriginal;
                        $conductorcertificado->concernombrearchivoeditado  = $nombreArchivo;
                        $conductorcertificado->concerrutaarchivo           = $rutaArchivo;
                        $conductorcertificado->save();
                    }
                }
            }

        	DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}