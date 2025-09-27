<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'paciente_id',
        'especialista_id',
        'orden_servicio_id',
        'tarifa_id',
        'title',
        'start',
        'end',
        'estado',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
    public function especialista()
    {
        return $this->belongsTo(Especialista::class);
    }
}
