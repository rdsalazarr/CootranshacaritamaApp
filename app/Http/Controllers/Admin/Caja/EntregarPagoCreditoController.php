<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use App\Models\Cartera\Colocacion;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;

class EntregarPagoCreditoController extends Controller
{
    public function index(){
        try{
            $tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')
                                        ->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();

            return response()->json(['success' => true, "tipoIdentificaciones" => $tipoIdentificaciones]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        try{
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');
            $url             = URL::to('/');

            $colocacion = DB::table('colocacion as c')
                                ->select('c.coloid', 'c.colofechadesembolso','p.persdocumento', 'p.persprimernombre',
                                'p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                'p.persgenero','p.persrutafoto','c.colovalordesembolsado as valorDesembolsado',
                                    DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->where('c.tiesclid', 'V')
                                ->where('c.colocontabilizada', false)
                                ->where('p.tipideid', $request->tipoIdentificacion)
                                ->where('p.persdocumento', $request->documento)
                                ->first();

            $success    = (!$colocacion) ? false : true;

            return response()->json(['success' =>  $success, 'message' => 'Lo siento, no se encontraron registros con la información proporcionada',
                                     "datosEncontrado" => $success,  "colocacion" => ($success) ? $colocacion : '']);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function entregarEfectivo(Request $request)
	{
        $this->validate(request(),['colocacionId' => 'required|numeric','valorDesembolsado' => 'required|numeric|between:1,999999999',]);

        $cajaAbierta = MovimientoCaja::verificarCajaAbierta();
        if(!$cajaAbierta){
            return response()->json(['success' => false, 'message'=> 'Lo sentimos, no es posible registrar la entrega de un crédito sin antes haber abierto la caja para el día de hoy']);
        }

        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $colocacion 				   = Colocacion::findOrFail($request->colocacionId);
            $colocacion->colocontabilizada = true;
            $colocacion->save();

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 11;//CXC desembolso
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->valorDesembolsado;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = - $request->valorDesembolsado;
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}