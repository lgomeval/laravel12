<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tarifa extends Model
{
    protected $fillable = [
        'codigo',
        'tipo',
        'nombre',
        'precio',
        'descuento',
        'estado',
        'cliente_id',
        'user_id',
        'ciudad_id',
    ];

    public function scopeBuscar($query, $valor)
    {
        return $query->where('ciudad_id', 'LIKE', "%$valor%")
            ->orWhere('nombre', 'LIKE', "%$valor%");
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function OrdenDeServicio(): BelongsToMany
    {
        return $this->belongsToMany(OrdenDeServicio::class, 'orden_de_servicio_tarifas');
    }


}
