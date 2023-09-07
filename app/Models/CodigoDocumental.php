<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumental extends Model
{
    use HasFactory;

    protected $table      = 'codigodocumental';	 
    protected $primaryKey = 'coddocid';
    protected $fillable   = ['depeid','subserid','seriid','tipdocid','tipmedid','tiptraid','tipdetid','usuaid','coddocfechahora'];
}