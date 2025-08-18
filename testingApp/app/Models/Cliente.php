<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nit',
        'razon_social',
        'fecha_inicio_relacion_comercial',
        'nombre_comercial',
        'asesor_comercial_asignado',
        'actividad_economica',
        'tipo_regimen_iva',
        'departamento',
        'responsabilidad_fiscal',
        'ciudad',
        'direccion',
        'telefono_contacto1',
        'telefono_contacto2',
        'email',
    ];

    public function scopeBuscar($query, $valor)
    {
        return $query->where('nombre_comercial', 'LIKE', "%$valor%")
            ->orWhere('email', 'LIKE', "%$valor%");
    }

    public function tarifas()
    {
        return $this->hasMany(Tarifa::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function getAntiguedadAttribute()
    {
        $fecha = $this->fecha_inicio_relacion_comercial;
        if (!$fecha) {
            return 'Sin datos';
        }

        $inicio = Carbon::parse($fecha);
        $hoy = Carbon::now();
        $diff = $inicio->diff($hoy);

        return $diff->y . ' años';
    }

    public function getTarifarioCreadoAttribute()
    {
        $ciudades = $this->tarifas()
            ->with('ciudad')
            ->get()
            ->pluck('ciudad.nombre_ciudad')
            ->unique();

        $count = $ciudades->count();

        if ($count > 0) {
            $texto = $count > 1 ? 'Ciudades' : 'Ciudad';
            $listaCiudades = $ciudades->join(' & ');

            return '<a href="' . route('tarifas.show', $this->id) . '" class="inline-flex items-center px-2 py-1 font-medium bg-blue-950 rounded hover:bg-blue-500" target="blank">'
                . " $listaCiudades"
                . '</a>';
        }

        return 'No';
    }
}
