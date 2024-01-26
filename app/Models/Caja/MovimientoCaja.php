<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB, Auth;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table      = 'movimientocaja';
    protected $primaryKey = 'movcajid';
    protected $fillable   = ['usuaid','cajaid','movcajfechahoraapertura','movcajsaldoinicial','movcajfechahoracierre',
                            'movcajsaldofinal', 'movcajcerradaautomaticamente'];

    public static function verificarCajaAbierta()
    {
        $movimientocaja  = DB::table('movimientocaja')->select('movcajsaldofinal')
                                    ->whereDate('movcajfechahoraapertura', Carbon::now()->format('Y-m-d'))
                                    ->whereNull('movcajsaldofinal')
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', auth()->user()->cajaid)
                                    ->first();

        return ($movimientocaja) ? true : false;
    }
}