<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompaniaAseguradora extends Model
{
    use HasFactory;

    protected $table      = 'companiaaseguradora';
    protected $primaryKey = 'comaseid';
    protected $fillable   = ['comasenombre','comasenumeropoliza'];
}
