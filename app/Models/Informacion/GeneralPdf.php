<?php

namespace App\Models\Informacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPdf extends Model
{
    use HasFactory;

    protected $table      = 'informaciongeneralpdf';
	protected $primaryKey = 'ingpdfid';
    protected $fillable   = ['ingpdfnombre','ingpdftitulo','ingpdfcontenido'];
}