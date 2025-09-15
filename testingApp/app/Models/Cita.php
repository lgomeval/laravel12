<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'nombre_examen',
        'tipo_examen',
        'fecha_inicio',
        'fecha_fin',
        'paciente_id',
        'especialistas_id',
        'observaciones',
        'estado'
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
