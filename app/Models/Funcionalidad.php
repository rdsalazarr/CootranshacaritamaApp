<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Generales;

class Funcionalidad extends Model
{
    use HasFactory;

    protected $table      = 'funcionalidad';  
    protected $primaryKey = 'funcid';
    protected $fillable   = ['moduid', 'funcnombre','functitulo','funcruta', 'funcicono', 'funcorden','funcactiva'];
   
    //Funcion que construye los menus recorriendo 
    public static function menus()
    {
        $consPadre = DB::table('funcionalidad')->select('funcid','moduid', 'funcnombre','functitulo',
                    'funcruta', 'funcicono', 'moduid')->distinct()
                    ->join('modulo','moduid','=','moduid')
                    ->join('rolfuncionalidad','rolfunfuncid','=','funcid')
                    ->join('usuariorol','usurolrolid','=','rolfunrolid')   
                    ->where('usuroluserid', Auth::id());

                if(Auth::id() != 1)
                    $consPadre = $consPadre->where('funcactiva', 1);
                
                $funPadre =  $consPadre ->orderby('funcorden')->orderBy('funcnombre')->get();

        $arrayFunPadre = collect($funPadre)->map(function($x){ 
                         return (array) $x; 
                      })->toArray();
        
        $consPadre = DB::table('funcionalidad')->select('funcid','funcidpadre', 'functitulo',
                    'funcruta', 'funcicono','modunombre','moduid')->distinct()
                    ->join('modulo','moduid','=','moduid')
                    ->join('rolfuncionalidad','rolfunfuncid','=','funcid')
                    ->join('usuariorol','usurolrolid','=','rolfunrolid')   
                    ->where('usuroluserid', Auth::id());

                if(Auth::id() != 1)
                    $consPadre = $consPadre->where('funcactiva', 1);

                $funHijo = $consPadre->orderby('funcorden')->orderBy('funcnombre')->get();

        $arrayFunHijo = collect($funHijo)->map(function($x){ 
                         return (array) $x; 
                      })->toArray();
        
        $label   = ['moduid'];
        $nombres = ['Funcionalidad'];

        $funcion = new Generales();
        return $funcion->ordenarArray($arrayFunPadre, $label, $nombres, $arrayFunHijo);
    }
}
