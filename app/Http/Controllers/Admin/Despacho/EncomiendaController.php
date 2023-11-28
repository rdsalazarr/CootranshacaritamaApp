<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Despacho\PersonaServicio;
use App\Http\Controllers\Controller;
use App\Models\Despacho\Encomienda;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use Carbon\Carbon;

class EncomiendaController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required']);

        $comparador = ($request->estado === 'R') ? '=' : '<>';

        $data = DB::table('encomienda as e')->select('e.encoid','e.encofechahoraregistro as fechaHoraRegistro', 'te.tipencnombre as tipoEncomienda','tee.tiesennombre as estado',
                                    DB::raw("CONCAT(de.depanombre,' ',md.muninombre) as destinoEncomienda"),
                                    DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                                        ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombrePersonaRemitente"),
                                    DB::raw("CONCAT(ps1.perserprimernombre,' ',if(ps1.persersegundonombre is null ,'', ps1.persersegundonombre),' ',
                                            ps1.perserprimerapellido,' ',if(ps1.persersegundoapellido is null ,' ', ps1.persersegundoapellido)) as nombrePersonaDestino"))
                                    ->join('personaservicio as ps', 'ps.perserid', '=', 'e.perseridremitente')
                                    ->join('personaservicio as ps1', 'ps1.perserid', '=', 'e.perseriddestino')
                                    ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                                    ->join('tipoestadoencomienda as tee', 'tee.tiesenid', '=', 'e.tiesenid')
                                    ->join('departamento as de', 'de.depaid', '=', 'e.depaiddestino') 
                                    ->join('municipio as md', function($join)
                                    {
                                        $join->on('md.munidepaid', '=', 'e.depaiddestino');
                                        $join->on('md.muniid', '=', 'e.muniiddestino');
                                    })
                                    ->whereDate('e.tiesenid', $comparador, $request->estado)
                                    ->orderBy('e.encoid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $tiposEncomiendas     = DB::table('tipoencomienda')->select('tipencid','tipencnombre')->orderBy('tipencnombre')->get();
        $departamentos        = DB::table('departamento')->select('depaid','depanombre')->where('depahacepresencia', true)->orderBy('depanombre')->get();
        $municipios           = DB::table('municipio')->select('muniid','munidepaid','muninombre')->where('munihacepresencia', true)->orderBy('muninombre')->get();
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4', '5'])->orderBy('tipidenombre')->get();
        $encomienda           = [];

        if($request->tipo === 'U'){
            $encomienda  = DB::table('encomienda as e')
                                ->select('e.encoid','e.perseridremitente','e.perseriddestino','e.depaidorigen','e.muniidorigen','e.depaiddestino','e.muniiddestino',
                                'e.tipencid','e.encocontenido','e.encocantidad','e.encovalordeclarado','e.encovalorenvio','e.encovalordomicilio', 'e.encoobservacion',
                                'psr.tipideid','psr.perserdocumento','psr.perserprimernombre','psr.persersegundonombre','psr.perserprimerapellido',
                                'psr.persersegundoapellido','psr.perserdireccion', 'psr.persercorreoelectronico','psr.persernumerocelular',
                                'psd.tipideid as tipideidDestino','psd.perserdocumento as perserdocumentoDestino','psd.perserprimernombre as perserprimernombreDestino',
                                'psd.persersegundonombre as persersegundonombreDestino','psd.perserprimerapellido as perserprimerapellidoDestino',
                                'psd.persersegundoapellido as persersegundoapellidoDestino','psd.perserdireccion as perserdireccionDestino', 
                                'psd.persercorreoelectronico as persercorreoelectronicoDestino', 'psd.persernumerocelular as persernumerocelularDestino')
                                ->join('personaservicio as psr', 'psr.perserid', '=', 'e.perseridremitente')
                                ->join('personaservicio as psd', 'psr.perserid', '=', 'e.perseriddestino')
                                ->where('e.encoid', $request->codigo)->first();
        }

        return response()->json(["tiposEncomiendas" => $tiposEncomiendas, "tipoIdentificaciones" => $tipoIdentificaciones, "departamentos" => $departamentos, 
                                "municipios"       => $municipios,        "encomienda"    => $encomienda]);
    }

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        $data   = DB::table('personaservicio')
                            ->select('perserid','tipideid','perserdocumento','perserprimernombre','persersegundonombre','perserprimerapellido',
                            			'persersegundoapellido','perserdireccion', 'persercorreoelectronico','persernumerocelular')
                            ->where('tipideid', $request->tipoIdentificacion)
                            ->where('perserdocumento', $request->documento)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function salve(Request $request)
	{
        $encoid                   = $request->codigo;
		$personaIdRemitente       = $request->personaIdRemitente;
        $personaIdDestino         = $request->personaIdDestino;
        $encomienda               = ($encoid != 000) ? Encomienda::findOrFail($encoid) : new Encomienda();
        $personaservicioRemitente = ($personaIdRemitente != 000) ? PersonaServicio::findOrFail($personaIdRemitente) : new personaservicio();
        $personaservicioDestino   = ($personaIdDestino != 000) ? PersonaServicio::findOrFail($personaIdDestino) : new personaservicio();

	    $this->validate(request(),[
			    'tipoIdentificacionRemitente' => 'required|numeric',
				'documentoRemitente'          => 'required|string|min:6|max:15|unique:personaservicio,perserdocumento,'.$personaservicio->perserid.',perserid',
				'primerNombreRemitente'       => 'required|string|min:3|max:140',
				'segundoNombreRemitente'      => 'nullable|string|min:3|max:40',
				'primerApellidoRemitente'     => 'required|string|min:4|max:40',
				'segundoApellidoRemitente'    => 'nullable|string|min:4|max:40',
				'direccionRemitente'          => 'required|string|min:4|max:100',
				'correoRemitente'             => 'nullable|email|string|max:80',
				'telefonoCelularRemitente'    => 'nullable|string|max:20',
                'tipoIdentificacionDestino'   => 'required|numeric',
                'documentoDestino'            => 'required|string|min:6|max:15',
				'primerNombreDestino'         => 'required|string|min:3|max:140',
				'segundoNombreDestino'        => 'nullable|string|min:3|max:40',
				'primerApellidoDestino'       => 'required|string|min:4|max:40',
				'segundoApellidoDestino'      => 'nullable|string|min:4|max:40',
				'direccionDestino'            => 'required|string|min:4|max:100',
				'correoDestino'               => 'nullable|email|string|max:80',
				'telefonoCelularDestino'      => 'nullable|string|max:20',
                'departamentoOrigen'          => 'required|numeric',
                'municipioOrigen'             => 'required|numeric',
                'departamentoDestino'         => 'required|numeric',
                'municipioDestino'            => 'required|numeric',
				'tipoEncomienda'              => 'required|string',
				'cantidad'                    => 'required|numeric|between:1,999',
                'valorDeclarado'              => 'required|numeric|between:1,99999999',
                'valorEnvio'                  => 'required|numeric|between:1,99999999',
                'valorDomicilio'              => 'required|numeric|between:1,99999999',
                'contenido'                   => 'required|string|max:1000',
				'observaciones'               => 'nullable|string|max:500'
	        ]);

        DB::beginTransaction();
        try {

            $estadoEncomienda = 'R';
            $fechaHoraActual  = Carbon::now();
		
			$personaservicioRemitente->tipideid                = $request->tipoIdentificacionRemitente;
			$personaservicioRemitente->pecosedocumento         = $request->documentoRemitente;
			$personaservicioRemitente->pecoseprimernombre      = mb_strtoupper($request->primerNombreRemitente,'UTF-8');
			$personaservicioRemitente->pecosesegundonombre     = mb_strtoupper($request->segundoNombreRemitente,'UTF-8');
			$personaservicioRemitente->pecoseprimerapellido    = mb_strtoupper($request->primerApellidoRemitente,'UTF-8');
			$personaservicioRemitente->pecosesegundoapellido   = mb_strtoupper($request->segundoApellidoRemitente,'UTF-8');
			$personaservicioRemitente->pecosedireccion         = $request->direccionRemitente;
			$personaservicioRemitente->pecosecorreoelectronico = $request->correoRemitente;
			$personaservicioRemitente->pecosenumerocelular     = $request->telefonoCelularRemitente;
			$personaservicioRemitente->save();

            if($request->tipo === 'I' and $personaIdRemitente === 000){
				//Consulto el ultimo identificador de la persona de la encomienda
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaIdRemitente           = $personaservicioConsecutivo->perserid;
			}

            $personaservicioDestino->tipideid                = $request->tipoIdentificacionDestino;
			$personaservicioDestino->pecosedocumento         = $request->documentoDestino;
			$personaservicioDestino->pecoseprimernombre      = mb_strtoupper($request->primerNombreDestino,'UTF-8');
			$personaservicioDestino->pecosesegundonombre     = mb_strtoupper($request->segundoNombreDestino,'UTF-8');
			$personaservicioDestino->pecoseprimerapellido    = mb_strtoupper($request->primerApellidoDestino,'UTF-8');
			$personaservicioDestino->pecosesegundoapellido   = mb_strtoupper($request->segundoApellidoDestino,'UTF-8');
			$personaservicioDestino->pecosedireccion         = $request->direccionDestino;
			$personaservicioDestino->pecosecorreoelectronico = $request->correoDestino;
			$personaservicioDestino->pecosenumerocelular     = $request->telefonoCelularDestino;
			$personaservicioDestino->save();

            if($request->tipo === 'I' and $personaIdDestino === 000){
				//Consulto el ultimo identificador de la persona de la encomienda
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaIdDestino           = $personaservicioConsecutivo->perserid;
			}

            if($request->tipo === 'I'){
                $encomienda->tiesenid               = $estadoEncomienda;
                $encomienda->usuaid                 = Auth::id();
                $encomienda->encofechahoraregistro  = $fechaHoraActual;
            }

			$encomienda->perseridremitente  = $personaIdRemitente;
			$encomienda->perseriddestino    = $personaIdDestino;
			$encomienda->depaidorigen       = $request->departamentoOrigen;
			$encomienda->muniidorigen       = $request->municipioOrigen;
			$encomienda->depaiddestino      = $request->departamentoDestino;
            $encomienda->muniiddestino      = $request->municipioDestino;
			$encomienda->tipencid           = $request->tipoEncomienda;
            $encomienda->encocantidad       = $request->cantidad;
            $encomienda->encovalordeclarado = $request->valorDeclarado;
            $encomienda->encovalorenvio     = $request->valorEnvio;
            $encomienda->encovalordomicilio = $request->valorDomicilio;
			$encomienda->encocontenido      = mb_strtoupper($request->observaciones,'UTF-8');
            $encomienda->encoobservacion    = mb_strtoupper($request->observaciones,'UTF-8');
			$encomienda->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la encomienda
				$encomiendaConsecutivo                     = Encomienda::latest('encoid')->first();
				$encoid                                    = $encomiendaConsecutivo->encoid;
                $encomiendacambioestado 				   = new EncomiendaCambioEstado();
                $encomiendacambioestado->encoid            = $encoid;
                $encomiendacambioestado->tiesenid          = $estadoEncomienda;
                $encomiendacambioestado->encaesusuaid      = Auth::id();
                $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
                $encomiendacambioestado->encaesobservacion = 'Registro de la encomienda. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $encomiendacambioestado->save();
			}

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'encomiendaId' => $encoid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}