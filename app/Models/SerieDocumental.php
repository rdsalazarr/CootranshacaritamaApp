<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerieDocumental extends Model
{
    use HasFactory;
    
    protected $table      = 'seriedocumental';    
    protected $primaryKey = 'serdocid';
    protected $fillable   = ['serdoccodigo','serdocnombre','serdoctiempoarchivogestion','serdoctiempoarchivocentral',
                            'serdoctiempoarchivohistorico','serdocpermiteeliminar','serdocactiva'];
}