<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\PersonaServicio;
use App\Models\Despacho\TiquetePuesto;
use App\Http\Controllers\Controller;
use App\Models\Despacho\Tiquete;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generarPdf;
use Carbon\Carbon;

class TiqueteController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required']);
   
        $data   = DB::table('tiquete as t')
                    ->select('t.tiquid','pr.rutaid', 't.tiqufechahoraregistro as fechaHoraRegistro','pr.plarutfechahorasalida as fechaSalida',
                    'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino',
                    DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                    DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), t.tiquanio, t.tiquconsecutivo) as numeroTiquete"),
                    DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                            ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombreCliente") )
                    ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                    ->join('municipio as mo', function($join)
                    {
                        $join->on('mo.munidepaid', '=', 't.depaidorigen');
                        $join->on('mo.muniid', '=', 't.muniidorigen');
                    })
                    ->join('municipio as md', function($join)
                    {
                        $join->on('md.munidepaid', '=', 't.depaiddestino');
                        $join->on('md.muniid', '=', 't.muniiddestino');
                    })
                    ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                    ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                    ->where('pr.agenid', auth()->user()->agenid) 
                    ->where('pr.plarutdespachada', $request->estado)
                    ->orderBy('pr.plarutid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $municipios           = DB::table('municipio')->select('muniid','munidepaid','muninombre')->where('munihacepresencia', true)->orderBy('muninombre')->get();
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4', '5'])->orderBy('tipidenombre')->get();   
        $municipios           = DB::table('municipio as m')->select('m.muniid','m.munidepaid','m.muninombre')
                                    ->join('rutanodo as rn', 'rn.muniid', '=', 'm.muniid')
                                    ->where('m.munihacepresencia', true)->orderBy('m.muninombre')->get();

        $tarifaTiquetes         = DB::table('tarifatiquete as tt')
                                    ->select('tt.rutaid','tt.depaiddestino','tt.muniiddestino', 'tt.tartiqvalor', 'tt.tartiqfondoreposicion')
                                    ->join('ruta as r', 'r.rutaid', '=', 'tt.rutaid')->get();

        $planillaRutas        = DB::table('planillaruta as pr')
                                ->select('pr.rutaid','pr.plarutid','r.depaidorigen','r.muniidorigen','r.depaiddestino','r.muniiddestino','mo.muninombre as municipioOrigen','md.muninombre as municipioDestino',
                                DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre, ' - ', pr.plarutfechahorasalida) as nombreRuta"))
                                ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                                ->join('municipio as mo', function($join)
                                {
                                    $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                                    $join->on('mo.muniid', '=', 'r.muniidorigen');
                                })
                                ->join('municipio as md', function($join)
                                {
                                    $join->on('md.munidepaid', '=', 'r.depaiddestino');
                                    $join->on('md.muniid', '=', 'r.muniiddestino');
                                })
                                ->where('pr.plarutdespachada', false)
                                ->get();

        $tiquete = [];
        if($request->tipo === 'U'){
            $tiquete  = DB::table('tiquete as t')
                                ->select('r.tiquid','plarutid','t.perserid','t.depaidorigen','t.muniidorigen','t.depaiddestino','t.muniiddestino',
                                't.tiquvalortiquete','t.tiquvalordescuento', 't.tiquvalorfondoreposicion','t.tiquvalortotal',
                                'psr.tipideid','psr.perserdocumento','psr.perserprimernombre','psr.persersegundonombre','psr.perserprimerapellido',
                                'psr.persersegundoapellido','psr.perserdireccion', 'psr.persercorreoelectronico','psr.persernumerocelular')
                                ->join('personaservicio as psr', 'psr.perserid', '=', 't.perserid')
                                ->where('t.tiquid', $request->codigo)->first();
        }

        return response()->json([ "tipoIdentificaciones" => $tipoIdentificaciones, "planillaRutas" => $planillaRutas, "tarifaTiquetes" => $tarifaTiquetes,
                                  "municipios"           => $municipios,           "tiquete"    => $tiquete]);
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
        $tiquid          = $request->codigo;
		$personaId       = $request->personaId; 
        $tiquete         = ($tiquid != 000) ? Tiquete::findOrFail($tiquid) : new Tiquete();
        $personaservicio = ($personaId != 000) ? PersonaServicio::findOrFail($personaId) : new PersonaServicio(); 

	    $this->validate(request(),[
			    'tipoIdentificacion'   => 'required|numeric',
				'documento'            => 'required|string|min:6|max:15|unique:personaservicio,perserdocumento,'.$personaservicio->perserid.',perserid',
				'primerNombre'       => 'required|string|min:3|max:140',
				'segundoNombre'      => 'nullable|string|min:3|max:40',
				'primerApellido'     => 'nullable|string|min:4|max:40',
				'segundoApellido'    => 'nullable|string|min:4|max:40',
				'direccion'          => 'required|string|min:4|max:100',
				'correo'             => 'nullable|email|string|max:80',
				'telefonoCelular'      => 'nullable|string|max:20',
                'departamentoOrigen'   => 'required|numeric',
                'municipioOrigen'      => 'required|numeric',
                'departamentoDestino'  => 'required|numeric',
                'municipioDestino'     => 'required|numeric',
                'ruta'                 => 'required|numeric',
                'valorTiquete'         => 'required|numeric|between:1,99999999',
                'valorDescuento'       => 'required|numeric|between:1,99999999',
                'valorFondoReposicion' => 'nullable|numeric|between:1,99999999',
                'valorTotal'           => 'nullable|numeric|between:1,99999999'
	        ]);

        DB::beginTransaction();
        try {
     
            $fechaHoraActual         = Carbon::now();
      
			$personaservicio->tipideid                = $request->tipoIdentificacion;
			$personaservicio->perserdocumento         = $request->documento;
			$personaservicio->perserprimernombre      = mb_strtoupper($request->primerNombre,'UTF-8');
			$personaservicio->persersegundonombre     = mb_strtoupper($request->segundoNombre,'UTF-8');
			$personaservicio->perserprimerapellido    = mb_strtoupper($request->primerApellido,'UTF-8');
			$personaservicio->persersegundoapellido   = mb_strtoupper($request->segundoApellido,'UTF-8');
			$personaservicio->perserdireccion         = $request->direccion;
			$personaservicio->persercorreoelectronico = $request->correo;
			$personaservicio->persernumerocelular     = $request->telefonoCelular;
			$personaservicio->save();

            if($request->tipo === 'I' and $personaId === '000'){
				//Consulto el ultimo identificador de la persona de la tiquete
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaId                  = $personaservicioConsecutivo->perserid;
			}

            if($request->tipo === 'I'){
                $anioActual                     = $fechaHoraActual->year;
                $tiquete->agenid                = auth()->user()->agenid;
                $tiquete->usuaid                = Auth::id();
                $tiquete->tiqufechahoraregistro = $fechaHoraActual;
                $tiquete->tiquanio              = $anioActual;
                $tiquete->tiquconsecutivo       = $this->obtenerConsecutivo($anioActual);
            }

            $tiquete->plarutid                 = $request->ruta;
            $tiquete->perserid                 = $personaId;
			$tiquete->depaidorigen             = $request->departamentoOrigen;
			$tiquete->muniidorigen             = $request->municipioOrigen;
			$tiquete->depaiddestino            = $request->departamentoDestino;
            $tiquete->muniiddestino            = $request->municipioDestino;
            $tiquete->tiqucantidad             = 2;
            $tiquete->tiquvalortiquete         = $request->valorTiquete;
            $tiquete->tiquvalordescuento       = $request->valorDescuento;
            $tiquete->tiquvalorfondoreposicion = $request->valorFondoReposicion;
            $tiquete->tiquvalortotal           = $request->valorTotal;
			$tiquete->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la tiquete
				$tiqueteConsecutivo = Tiquete::latest('tiquid')->first();
				$tiquid             = $tiqueteConsecutivo->tiquid;

                $tiquetepuesto 		               = new TiquetePuesto();
                $tiquetepuesto->tiquid             = $tiquid;
                $tiquetepuesto->tiqpuenumeropuesto = 1;
                $tiquete->save();

                $tiquetepuesto 		               = new TiquetePuesto();
                $tiquetepuesto->tiquid             = $tiquid;
                $tiquetepuesto->tiqpuenumeropuesto = 2;
                $tiquete->save();
			}

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'tiqueteId' => $tiquid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function show(Request $request)
    {
		$this->validate(request(),['codigo'  => 'required']);
    }

    public function verPlanilla(Request $request)
    {
		$this->validate(request(),['codigo' => 'required']);
		try{

            /*$encomienda  = DB::table('encomienda as e')
                            ->select('e.encofechahoraregistro', DB::raw("CONCAT(e.encoanio,'',e.encoconsecutivo) as consecutivoEncomienda"),
                            'e.encovalortotal', 'e.encovalorcomisionseguro',
                            DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre) as nombreRuta"),
                            'te.tipencnombre','do.depanombre as deptoOrigen', 'mor.muninombre as municipioOrigen', 'dd.depanombre as deptoDestino', 'mde.muninombre as municipioDestino',
                            'e.encocontenido','e.encocantidad','e.encovalordeclarado','e.encovalorenvio','e.encovalordomicilio', 
                            DB::raw("CONCAT(psr.perserprimernombre,' ',if(psr.persersegundonombre is null ,'', psr.persersegundonombre),' ',
                            psr.perserprimerapellido,' ',if(psr.persersegundoapellido is null ,' ', psr.persersegundoapellido)) as nombrePersonaRemitente"),
                            'psr.perserdireccion', 'psr.persernumerocelular',
                            DB::raw("CONCAT(psd.perserprimernombre,' ',if(psd.persersegundonombre is null ,'', psd.persersegundonombre),' ',
                            psd.perserprimerapellido,' ',if(psd.persersegundoapellido is null ,' ', psd.persersegundoapellido)) as nombrePersonaDestinatario"),
                            'psd.perserdireccion as direccionDestino',  'psd.persernumerocelular as numeroCelularDestino',
                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                            DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"),
                            DB::raw("(SELECT menimpvalor FROM mensajeimpresion WHERE menimpnombre = 'ENCOMIENDAS') AS mensajeEncomienda"))
                            ->join('personaservicio as psr', 'psr.perserid', '=', 'e.perseridremitente')
                            ->join('personaservicio as psd', 'psd.perserid', '=', 'e.perseriddestino')
                            ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 'e.plarutid')
                            ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                            ->join('municipio as mo', function($join)
                            {
                                $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                                $join->on('mo.muniid', '=', 'r.muniidorigen');
                            })
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 'r.depaiddestino');
                                $join->on('md.muniid', '=', 'r.muniiddestino');
                            })
                            ->join('departamento as do', 'do.depaid', '=', 'e.depaidorigen')
                            ->join('municipio as mor', function($join)
                            {
                                $join->on('mor.munidepaid', '=', 'e.depaidorigen');
                                $join->on('mor.muniid', '=', 'e.muniidorigen');
                            })
                            ->join('departamento as dd', 'dd.depaid', '=', 'e.depaiddestino')
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 'e.depaiddestino');
                                $join->on('mde.muniid', '=', 'e.muniiddestino');
                            })
                            ->join('usuario as u', 'u.usuaid', '=', 'e.usuaid')
                            ->join('agencia as a', 'a.agenid', '=', 'e.agenid')
                            ->where('e.encoid', $request->codigo)->first();*/

            $arrayDatos   = [
                                "fechaEncomienda"       => $encomienda->encofechahoraregistro,
                                "numeroEncomienda"      => $encomienda->consecutivoEncomienda,
                                "rutaEncomienda"        => $encomienda->nombreRuta,
                                "tipoEncomienda"        => $encomienda->tipencnombre,
                                "origenEncomienda"      => $encomienda->municipioOrigen,
                                "destinoEncomienda"     => $encomienda->municipioDestino,
                                "contenido"             => $encomienda->encocontenido,
                                "cantidad"              => $encomienda->encocantidad,
                                "valorDeclarado"        => number_format($encomienda->encovalordeclarado, 0,',','.'),
                                "valorSeguro"           => number_format($encomienda->encovalorcomisionseguro, 0,',','.'),
                                "valorEnvio"            => number_format($encomienda->encovalorenvio, 0,',','.'),
                                "valorDomicilio"        => number_format($encomienda->encovalordomicilio, 0,',','.'),
                                "valorTotal"            => number_format($encomienda->encovalortotal, 0,',','.'),
                                "nombreRemitente"       => $encomienda->nombrePersonaRemitente,
                                "direccionRemitente"    => $encomienda->perserdireccion,
                                "telefonoRemitente"     => $encomienda->persernumerocelular,
                                "nombreDestinatario"    => $encomienda->nombrePersonaDestinatario,
                                "direccionDestinatario" => $encomienda->direccionDestino,
                                "telefonoDestinatario"  => $encomienda->numeroCelularDestino,
                                "usuarioElabora"        => $encomienda->nombreUsuario,
                                "nombreAgencia"         => $encomienda->agennombre,
                                "direccionAgencia"      => $encomienda->agendireccion,
                                "telefonoAgencia"       => $encomienda->telefonoAgencia,
                                "mensajePlanilla"       => $encomienda->mensajeEncomienda,
                                "metodo" => 'S'
                            ];

            $generarPdf   = new generarPdf();
            $data         = $generarPdf->facturaEncomienda($arrayDatos);
  			return response()->json(["data" => $data ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}

    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoTiquete = DB::table('tiquete')->select('tiquconsecutivo as consecutivo')
                                                        ->where('tiquanio', $anioActual)
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->orderBy('tiquid', 'Desc')->first();
        $consecutivo = ($consecutivoTiquete === null) ? 1 : $consecutivoTiquete->consecutivo + 1;
        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}