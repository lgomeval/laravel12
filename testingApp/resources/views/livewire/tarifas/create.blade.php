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

    public bool $todos = false;
    public $cupsDisponibles = [];
    public $cupsSeleccionados = [];
    public array $precios = [];
    public array $descuentos = [];

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
        $this->cups = ['' => 'Seleccionar Examen'] + Cup::all()->pluck('nombre', 'id')->toArray();
        $this->cupsDisponibles = Cup::all();
        $this->ciudades = ['' => 'Seleccionar Ciudad'] + Ciudad::all()->pluck('nombre_ciudad', 'id')->toArray();
    }

    public function updatedNombre($id): void
    {
        // Duk, ten en cuenta que aqui $this->nombre llega como si fuera id=1

        $examen = Cup::select('id', 'codigo', 'tipo', 'nombre')->find($id);
        if ($examen) {
            $this->id = $examen->id;
            $this->codigo = $examen->codigo;
            $this->tipo = $examen->tipo;
            $this->nombre = $examen->nombre;
        } else {
            $this->id = null;
            $this->codigo = null;
            $this->tipo = null;
            $this->nombre = null;
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

    public function agregarTodos(): void
    {
        $this->cupsDisponibles = Cup::select('id', 'codigo', 'nombre', 'tipo')->get();

        $this->todos = true;
    }

    public function crearTarifaTodos()
    {
        $this->validate([
            'ciudad_id' => 'required|exists:ciudads,id',
        ], [
            'ciudad_id.required' => 'Selecciona la ciudad de la Tarifa.',
        ]);

        foreach ($this->cupsSeleccionados as $cupId) {
            $cup = $this->cupsDisponibles->firstwhere('id', $cupId);

            Tarifa::create([
                'nombre' => $cup->nombre,
                'codigo' => $cup->codigo,
                'tipo' => $cup->tipo,
                'precio' => $this->precios[$cupId] ?? 0,
                'descuento' => $this->descuentos[$cupId] ?? 0,
                'cliente_id' => $this->cliente_id,
                'user_id' => $this->user_id,
                'ciudad_id' => $this->ciudad_id,

            ]);
        }
        return redirect(route('clientes.index'))->with('success', 'Tarifario creado con éxito');
    }

    public function crearTarifario()
    {
        $this->validate();

//        dd($this->nombre, $this->precio, $this->descuento, $this->codigo, $this->tipo );

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
        @if ($todos == false)
            <form wire:submit.prevent='crearTarifario' enctype="multipart/form-data">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-select-field name="ciudad_id" label="Ciudad Tarifa" model="ciudad_id"
                                    :options="$ciudades"/>
                    <div></div>
                    {{-- Agregar todos los CUPS disponibles --}}
                    <label class="flex items-center space-x-3">
                        <input
                            type="checkbox"
                            id="todos"
                            name="todos"
                            wire:click="agregarTodos"
                            class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out"
                        >
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300"> Agregar todos los CUPS disponibles        </span>
                    </label>

                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2">
                        <x-select-field name="nombre" label="Nombre" model="nombre" :options="$cups"/>
                    </div>

                    <x-input-field name="precio" label="Precio" model="precio" type="number"/>
                    <x-input-field name="descuento" label="Descuento" model="descuento" type="number" min="0"
                                   max="100"/>
                </div>

                <x-action-button label="Crear Tarifa" variant="success"/>
            </form>
        @else
            <form wire:submit.prevent='crearTarifaTodos' enctype="multipart/form-data">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-select-field name="ciudad_id" label="Ciudad Tarifa" model="ciudad_id"
                                    :options="$ciudades"/>
                    <div></div>
                </div>
                <div>
                    <table class="min-w-full border border-gray-300 dark:border-zinc-600 text-sm">
                        <thead class="bg-gray-100 dark:bg-zinc-700">
                        <tr>
                            <th>Seleccionar</th>
                            <th class="px-4 py-2 text-left">Código</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Precio</th>
                            <th class="px-4 py-2 text-left">Descuento</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cupsDisponibles as $cup)
                            <tr class="text-center">
                                <td>
                                    <input type="checkbox"
                                           wire:model="cupsSeleccionados"
                                           value="{{ $cup->id }}"/>
                                </td>
                                <td class="text-left">{{ $cup->codigo }}</td>
                                <td class="text-left">{{ $cup->nombre }}</td>
                                <td class="text-left">{{ $cup->tipo }}</td>
                                <td>
                                    <x-input-precio wire:model="precios.{{ $cup->id }}" min="0"/>
                                </td>
                                <td>
                                    <x-input-descuento placeholder="0" wire:model="descuentos.{{ $cup->id }}"/>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5">
                        <x-action-button label="Crear Tarifario" variant="success"/>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
