<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    use HasFactory;

    protected $table      = 'agencia';	 
	protected $primaryKey = 'agenid';
    protected $fillable   = ['persidresponsable', 'agendepaid','agenmuniid', 'agennombre','agendireccion','agencorreo',
                            'agentelefonocelular','agentelefonofijo','agenactiva'];
}