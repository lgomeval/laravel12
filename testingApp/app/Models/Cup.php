<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cup extends Model
{
    protected $fillable = [

        'codigo',
        'tipo',
        'nombre',
        'precio',
    ];
}
