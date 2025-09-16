<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'nombre_examen',
        'tipo_examen',
        'fecha_cita',
        'hora_cita',
        'observaciones',
        'estado',
        'paciente_id',
        'orden_de_servicio_id'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function orden_de_servicio()
    {
        return $this->belongsTo(OrdenDeServicio::class);
    }
}
