<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaServicio extends Model
{
    use HasFactory;

    protected $table      = 'personaservicio';
    protected $primaryKey = 'perserid';
    protected $fillable   = ['tipideid','perserdocumento','perserprimernombre','persersegundonombre','perserprimerapellido',
                            'persersegundoapellido','perserdireccion','persercorreoelectronico','persernumerocelular'];
}