<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionGeneralPdf extends Model
{
    use HasFactory;

    protected $table      = 'informaciongeneralpdf';
	protected $primaryKey = 'ingpdfid';
    protected $fillable   = ['ingpdfnombre','ingpdftitulo','ingpdfcontenido'];
}