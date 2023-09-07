<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoAnexo extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesoanexo';
	protected $primaryKey = 'codopxid';
    protected $fillable   = ['codoprid', 'codopxrutaanexo'];
}