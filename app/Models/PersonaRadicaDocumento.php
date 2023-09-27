<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaRadicaDocumento extends Model
{
    use HasFactory;

    protected $table      = 'personaradicadocumento';
    protected $primaryKey = 'peradoid';
    protected $fillable   = ['tipideid','peradodocumento','peradoprimernombre','peradosegundonombre','peradoprimerapellido','peradosegundoapellido',
                            'peradodireccion','peradotelefono','peradocorreo','peradocodigodocumental'];
}