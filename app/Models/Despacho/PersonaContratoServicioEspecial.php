<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaContratoServicioEspecial extends Model
{
    use HasFactory;

    protected $table      = 'personacontratoservicioesp';
    protected $primaryKey = 'pecoseid';
    protected $fillable   = ['tipideid','pecosedocumento','pecoseprimernombre','pecosesegundonombre','pecoseprimerapellido',
                            'pecosesegundoapellido','pecosedireccion', 'pecosecorreoelectronico','pecosenumerocelular'];
}