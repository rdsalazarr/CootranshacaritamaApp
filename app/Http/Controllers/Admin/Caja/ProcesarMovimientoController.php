<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Http\Controllers\Controller;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProcesarMovimientoController extends Controller
{
    public function index()
    {
        $cajaId = auth()->user()->cajaid;

        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $data            = DB::table('abrircaja')->select('abrcajsaldofinal')
                                    ->whereDate('abrcajfechahoraapertura', $fechaActual)
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', $cajaId)->first();

        return response()->json(["data" => $data, "cajaId" => $cajaId]);
    }

}
