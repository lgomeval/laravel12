<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrdenDeServicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_numero',
        'tipo_evaluacion',
        'enfasis',
        'medio_venta',
        'paciente_id',
        'cliente_id',
        'prestador_saluds_id',
        'estado'
    ];

    public function scopeBuscar($query, $valor)
    {
        return $query->where('orden_numero', 'LIKE', "%$valor%")
            ->orWhere('paciente_id', 'LIKE', "%$valor%");
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function prestadorSaluds(): BelongsTo
    {
        return $this->belongsTo(PrestadorSalud::class);
    }

    public function tarifas(): BelongsToMany
    {
        return $this->belongsToMany(Tarifa::class, 'orden_de_servicio_tarifas');
    }

    public function getProcedimientosAttribute()
    {
        return $this->tarifas->pluck('nombre')->join(', ');
    }
}
