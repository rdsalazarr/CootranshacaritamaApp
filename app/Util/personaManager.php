<?php

namespace App\Util;

use Illuminate\Support\Facades\Crypt;
use App\Models\Conductor\Conductor;
use App\Util\redimencionarImagen;
use App\Models\Asociado\Asociado;
use App\Models\Persona\Persona;
use Exception, File, DB, URL;
use App\Util\generales;

class personaManager {

    public function registrar($request){
        $id      = $request->codigo;
        $persona = ($id != 000) ? Persona::findOrFail($id) : new Persona(); 
        	   
        DB::beginTransaction();
        try {

			$redimencionarImagen = new redimencionarImagen();
            $funcion 		     = new generales();
            $rutaCarpeta         = public_path().'/archivos/persona/'.$request->documento;
            $carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('firma')){
				$file           = $request->file('firma');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFirma      = 'Firma_'.$request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFirma);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFirma, 280, 140);//Se redimenciona a un solo tipo
			}else{
				$rutaFirma = $request->rutaFirmaOld;
			}

            if($request->hasFile('fotografia')){
				$file           = $request->file('fotografia');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFotografia = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFotografia);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFotografia, 210, 270);//Se redimenciona a un solo tipo
			}else{
				$rutaFotografia = $request->rutaFotoOld;
			}

            if($request->hasFile('rutaCrt')){
				$file           = $request->file('rutaCrt');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaCrt        = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaCrt);
                $rutaCrt        = Crypt::encrypt($rutaCrt);
			}else{
				$rutaCrt = $request->rutaCrtOld;
			}

            if($request->hasFile('rutaPem')){
				$file           = $request->file('rutaPem');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaPem        = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaPem);
                $rutaPem        = Crypt::encrypt($rutaPem);
			}else{
				$rutaPem = $request->rutaPemOld;
			}

            $persona->carlabid               = $request->cargo;
            $persona->tipideid               = $request->tipoIdentificacion;
            $persona->tirelaid               = $request->tipoRelacionLaboral;
            $persona->persdepaidnacimiento   = $request->departamentoNacimiento;
            $persona->persmuniidnacimiento   = $request->municipioNacimiento;
            $persona->persdepaidexpedicion   = $request->departamentoExpedicion;
            $persona->persmuniidexpedicion   = $request->municipioExpedicion;
            $persona->persdocumento          = $request->documento;
            $persona->persprimernombre       = mb_strtoupper($request->primerNombre,'UTF-8');
            $persona->perssegundonombre      = mb_strtoupper($request->segundoNombre,'UTF-8');
            $persona->persprimerapellido     = mb_strtoupper($request->primerApellido,'UTF-8');
            $persona->perssegundoapellido    = mb_strtoupper($request->segundoApellido,'UTF-8');
            $persona->persfechanacimiento    = $request->fechaNacimiento;
            $persona->persdireccion          = $request->direccion;
            $persona->perscorreoelectronico  = $request->correo;
            $persona->persfechadexpedicion   = $request->fechaExpedicion;
            $persona->persnumerotelefonofijo = $request->telefonoFijo;
            $persona->persnumerocelular      = $request->numeroCelular;
            $persona->persgenero             = $request->genero;
            $persona->persrutafoto           = $rutaFotografia;
            $persona->persrutafirma          = $rutaFirma;
            $persona->perstienefirmadigital  = $request->firmaDigital;
            $persona->persclavecertificado   = $request->claveCertificado;
            $persona->persrutacrt            = $rutaCrt;
            $persona->persrutapem            = $rutaPem;
            $persona->persactiva             = $request->estado;
            $persona->save();

            if($request->formulario === 'ASOCIADO' and $request->tipo === 'I'){
                $personaMaxConsecutio       = Persona::latest('persid')->first();
				$persid                     = $personaMaxConsecutio->persid;

                $asociado                   = new Asociado();
                $asociado->persid           = $persid;
                $asociado->tiesasid         = 'A';
                $asociado->asocfechaingreso = $request->fechaIngresoAsociado;
                $asociado->save();
            }

            if($request->formulario === 'ASOCIADO' and $request->tipo === 'U'){
                $asociado                   = DB::table('asociado')->select('asocid')->where('persid', $persona->persid)->first();
                $asociado                   = Asociado::findOrFail($asociado->asocid);
                $asociado->asocfechaingreso = $request->fechaIngresoAsociado;
                $asociado->save();
            }

            if($request->formulario === 'CONDUCTOR' and $request->tipo === 'I'){
                $personaMaxConsecutio       = Persona::latest('persid')->first();
				$persid                     = $personaMaxConsecutio->persid;

                $conductor                   = new Conductor();
                $conductor->persid           = $persid;
                $conductor->tiescoid         = 'A';
                $conductor->condfechaingreso = $request->fechaIngresoConductor;
                $conductor->save();
            }

            if($request->formulario === 'CONDUCTOR' and $request->tipo === 'U'){
                $conductor                   = DB::table('conductor')->select('condid')->where('persid', $persona->persid)->first();
                $conductor                   = Conductor::findOrFail($conductor->condid);
                $conductor->condfechaingreso = $request->fechaIngresoConductor;
                $conductor->save();
            }

        	DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}