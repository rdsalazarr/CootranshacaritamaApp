<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaNodo extends Model
{
    use HasFactory;

    protected $table      = 'rutanodo';
    protected $primaryKey = 'rutnodid';
    protected $fillable   = ['rutaid','rutnoddepaid','rutnodmuniid'];
}