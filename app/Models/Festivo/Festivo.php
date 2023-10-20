<?php

namespace App\Models\Festivo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
{
    use HasFactory;

    protected $table      = 'festivo';
    protected $primaryKey = 'festid';
    protected $fillable   = ['festfecha'];
}