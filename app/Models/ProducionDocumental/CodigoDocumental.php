<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumental extends Model
{
    use HasFactory;

    protected $table      = 'codigodocumental';	 
    protected $primaryKey = 'coddocid';
    protected $fillable   = ['depeid','serdocid','susedoid','tipdocid','tipmedid','tiptraid','tipdetid','usuaid','coddocfechahora'];
}