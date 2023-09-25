<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, DB;

class FirmarDocumentosController extends Controller
{
    public function index()
	{

        return response()->json(["exito" => true, "data" => $dataDocumento]);
    }
}