<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumental extends Model
{
    use HasFactory;

    protected $table      = 'tipodocumental';
    protected $primaryKey = 'tipdocid';
    protected $fillable   = ['tipdoccodigo','tipdocnombre','tipdocproducedocumento','tipdocactivo'];
}