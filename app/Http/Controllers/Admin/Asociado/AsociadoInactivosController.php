<?php

namespace App\Http\Controllers\Admin\Asociado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class AsociadoInactivosController extends Controller
{
    public function index()
    { 
        $data = DB::table('persona as p')->select('p.persid','p.persdocumento', 'p.persdireccion','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"),'tec.tiesasnombre as estado')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                    ->join('tipoestadoasociado as tec', 'tec.tiesasid', '=', 'a.tiesasid')
                                    ->where('a.tiesasid', '!=', 'A')
                                    ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                    ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

        return response()->json(["data" => $data]);
    }
}