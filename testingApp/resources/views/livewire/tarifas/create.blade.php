<?php

use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cup;
use App\Models\Tarifa;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public $cliente;
    public $cups;
    public $ciudades;
    public $ciudad_id;
    public $user_id;

    public string $nombre;
    public string $codigo;
    public string $tipo;
    public string $precio;
    public float $descuento = 0;

    public int $cliente_id;
    public ?Tarifa $hayTarifas = null;

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente;
        $this->cliente_id = $cliente->id;
        $this->user_id = auth()->id();
        $this->cups = ['' => 'Seleccionar Examen'] + Cup::all()->pluck('nombre', 'nombre')->toArray();
        $this->ciudades = ['' => 'Seleccionar Ciudad'] + Ciudad::all()->pluck('nombre_ciudad', 'id')->toArray();
    }

    public function updatedNombre($nombre): void
    {
        $examen = Cup::where('nombre', $nombre)->select('codigo', 'tipo')->first();
        if ($examen) {
            $this->codigo = $examen->codigo;
            $this->tipo = $examen->tipo;
        } else {
            $this->codigo = null;
            $this->tipo = null;
        }
    }

    public function rules(): array
    {
        return [
            'codigo' => 'required|string',
            'tipo' => 'required|string',
            'nombre' => ['required', 'string',
                Rule::unique('tarifas')->where(fn($query) => $query
                    ->where('nombre', $this->nombre)
                    ->where('ciudad_id', $this->ciudad_id)
                    ->where('cliente_id', $this->cliente_id)
                ),
            ],
            'precio' => 'required|integer',
            'descuento' => 'nullable|integer|min:0|max:100',
            'cliente_id' => 'required|integer|exists:clientes,id',
            'user_id' => 'required|integer|exists:users,id',
            'ciudad_id' => 'required|integer|exists:ciudads,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'ciudad_id.required' => 'La Ciudad del Tarifario obligatoria.',
            'nombre.required' => 'El Nombre del Examen es Obligatorio',
            'nombre.unique' => 'Este Examen ya fue Registrado en esta ciudad.',
            'precio.required' => 'El Precio del Examen es Obligatorio',
            'descuento.max' => 'El descuento no puede ser mayor a 100%.',

        ];
    }

    public function crearTarifario()
    {
        $this->validate();

        Tarifa::create([
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'tipo' => $this->tipo,
            'precio' => $this->precio,
            'descuento' => $this->descuento,
            'cliente_id' => $this->cliente_id,
            'user_id' => $this->user_id,
            'ciudad_id' => $this->ciudad_id,
        ]);

        return redirect(route('clientes.index'))->with('success', 'Tarifario creado con éxito');
    }
}; ?>

<div>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">
            Creación de Tarifa para el cliente:
            <strong>{{ $cliente->razon_social }}</strong>
        </h1>
        <br>
    </x-slot>
    <div class="max-w-full mx-auto p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-md">
        <form wire:submit.prevent='crearTarifario' enctype="multipart/form-data">
            <x-select-field name="ciudad_id" label="Ciudad Tarifa" model="ciudad_id"
                            :options="$ciudades"/>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <x-select-field name="nombre" label="Nombre" model="nombre" :options="$cups"/>
                </div>

                <x-input-field name="precio" label="precio" model="precio" type="number"/>
                <x-input-field name="descuento" label="Descuento" model="descuento" type="number" min="0" max="100"/>
            </div>

            <x-action-button label="Crear Tarifa" variant="success"/>
        </form>
    </div>
</div>
