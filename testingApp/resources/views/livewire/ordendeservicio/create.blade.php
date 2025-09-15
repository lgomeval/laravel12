<?php

use App\Http\Requests\OrdenDeServicioRequest;
use App\Models\OrdenDeServicio;
use Livewire\Volt\Component;
use App\Models\PrestadorSalud;
use App\Models\Cliente;
use App\Models\Paciente;
use App\Models\Tarifa;

new class extends Component {
    public $search;

    public $clientes;
    public $prestadorServicio;
    public $tarifas;

    public $opcionesPrestadores;

    public $tipo_evaluacion;
    public $enfasis = [];
    public $medio_venta;
    public $prestador_saluds_id;
    public $paciente_id;
    public $cliente_id;
    public $user;
    public $estado = 'Pendiente Agendar';

    public $pacientes = [];

    public $clienteSeleccionado;
    public $tarifasSeleccionadas = [];

    public function mount()
    {
        $this->clientes = Cliente::all();
        $this->prestadorServicio = PrestadorSalud::all();

        $this->opcionesPrestadores = ['' => 'Seleccione'] + $this->prestadorServicio->pluck('razon_social', 'id')->toArray();

    }

    public function updatedSearch($value): void
    {
        $this->pacientes = Paciente::where(function ($query) use ($value) {
            $query->where('nombres', 'LIKE', '%' . $value . '%')->orWhere('numero_identificacion', 'LIKE', '%' . $value . '%');
        })->get();
    }

    public function openModalTarifas(): void
    {
        $this->clienteSeleccionado = Cliente::find($this->cliente_id);
        $this->tarifas = Tarifa::where('cliente_id', $this->cliente_id)->get();
    }

    public function crearOrdenDeServicio()
    {
        $request = new OrdenDeServicioRequest();
        $validated = $this->validate(
            $request->rules(),
            $request->messages()
        );

        $this->clienteSeleccionado = Cliente::find($this->cliente_id);
        $this->tarifas = Tarifa::where('cliente_id', $this->cliente_id)->get();

        //        session()->put('success', 'Cliente seleccionado correctamente');

        $ordenDeServicio = OrdenDeServicio::create(array(
            'orden_numero' => 'HSEQ-' . date('Ymd') . '-' . rand(1, 999999),
            'tipo_evaluacion' => $this->tipo_evaluacion,
            'enfasis' => json_encode($this->enfasis),
            'medio_venta' => $this->medio_venta,
            'prestador_saluds_id' => $this->prestador_saluds_id,
//            'usuario_solicita' => auth()->user()->id,
            'paciente_id' => $this->paciente_id,
            'cliente_id' => $this->cliente_id,
            'estado' => $this->estado,
        ));

        foreach ($this->tarifasSeleccionadas as $tarifaId => $isSelected) {
            if ($isSelected) {
                $tarifa = Tarifa::find($tarifaId);
                if ($tarifa) {
                    $ordenDeServicio->tarifas()->attach($tarifaId);
                }
            }
        }

        $this->reset();

        Flux::modals()->close();

        return redirect()->route('ordenes-de-servicio.index')->with('success', 'Orden de Servicio creada con éxito');
    }
}; ?>

<div>
    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white text-center">
            {{ __('Formulario de Creación de Ordenes de Servicio.') }}
        </h1><br>
    </x-slot>

    <div class="max-w-full mx-auto p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-md">
        <form wire:submit.prevent='crearOrdenDeServicio' enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-select-field name="tipo_evaluacion" label="Tipo de Evaluación" model="tipo_evaluacion"
                                :options="[
                        '' => 'Seleccione',
                        'cambio_de_ocupacion' => 'Cambio de Ocupación',
                        'egreso' => 'Egreso',
                        'pre_ingreso' => 'Pre-Ingreso',
                        'periodico' => 'Periódico',
                        'post_incapacidad' => 'Post-Incapacidad',
                        'reubicacion' => 'Reubicación',
                        'reintegro_laboral' => 'Reintegro Laboral',
                        'seguimiento' => 'Seguimiento',
                    ]"/>

                <x-select-field name="medio_venta" label="Medio de Venta" model="medio_venta" :options="[
                    '' => 'Seleccione',
                    'Intramural' => 'Intramural',
                    'Telemedicina' => 'Telemedicina',
                    'Extramural' => 'Extramural',
                ]"/>

                <x-select-field name="prestador_saluds_id" label="Prestador del Servicio:"
                                model="prestador_saluds_id" :options="$opcionesPrestadores"/>

            </div>
            <hr>
            <div class="mt-6">
                <x-checkbox label="Énfasis en:" name="enfasis" model="enfasis" :options="[
                    'brigadista' => 'Brigadista',
                    'conductor' => 'Conducción de Vehículo',
                    'espacios_confinados' => 'Espacios Confinados',
                    'expo_radiaciones_ionizantes' => 'Exposición a Radiaciones Ionizantes',
                    'manipulacion_de_alimentos' => 'Manipulación de Alimentos',
                    'manipulacion_de_farmacos' => 'Manipulación Productos Farmacéuticos',
                    'trabajo_en_alturas' => 'Trabajo en Alturas',
                    'trabajo_riesgo_electrico' => 'Trabajo Riesgo Eléctrico',
                    'riesgo_covid-19' => 'Riesgo para COVID-19',
                    'cardiomuscular' => 'Cardiomuscular',
                    'dermatologico' => 'Dermatológico',
                    'pruebas_psicosensometricas' => 'Pruebas Psicosensométricas',
                    'neurologico' => 'Neurologico',
                    'sistema_fonatorio' => 'Sistema Fonatorio',
                    'no_aplica' => 'No Aplica',
                ]"/>
            </div>
            <br>
            <hr>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Buscar Paciente:
                    </label>
                    <input wire:model.live="search"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white"
                           placeholder="Búsqueda por nombre o Documento"/>
                </div>
                <div wire:loading>
                    <span>Buscando Paciente ......</span>
                </div>

                @if ($search)
                    <div>
                        @if ($pacientes->isEmpty())
                            <br>
                            <x-action-button label="Crear Paciente" variant="success"
                                             href="{{ route('pacientes.create') }}" target="_blank"/>
                        @else
                            <div>
                                <x-select-field name="paciente_id" label="Paciente solicita:" model="paciente_id"
                                                :options="$pacientes
                                        ->pluck('nombre_completo', 'id')
                                        ->prepend('Seleccione...', '')
                                        ->toArray()"/>
                            </div>
                        @endif
                    </div>
                @endif
                <x-select-field name="cliente_id" label="Cliente:" model="cliente_id" :options="$clientes
                    ->pluck('razon_social', 'id')
                    ->prepend('Seleccionar el cliente.')
                    ->toArray()"/>

                <div>
                    <!-- Modal para agregar examenes -->
                    <flux:modal.trigger name="agregar-tarifas">
                        <flux:button wire:click="openModalTarifas" variant="primary">Agregar Procedimientos
                        </flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="agregar-tarifas" class="@max-[]::w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Tarifas Disponibles para el
                                    Cliente: {{ $clienteSeleccionado ? $clienteSeleccionado->razon_social : 'No tiene Cliente Seleccionado' }}</flux:heading>
                            </div>
                            @if($tarifas && $tarifas->count())
                                <div class="bg-gray-800 p-4 rounded-lg shadow-md @max-[]::w-96">
                                    <h3 class="text-lg font-semibold text-white mb-3">Selecciona las tarifas:</h3>
                                    <ul class="space-y-2">
                                        @foreach($tarifas as $tarifa)
                                            <li>
                                                <label class="flex items-center space-x-3 text-gray-200">
                                                    <input type="checkbox"
                                                           wire:model="tarifasSeleccionadas.{{ $tarifa->id }}"
                                                           class="form-checkbox h-5 w-5 text-blue-500 transition duration-150 ease-in-out">
                                                    <span class="text-sm">{{ $tarifa->nombre }} - <span
                                                            class="font-medium text-green-400">${{ number_format($tarifa->precio, 0) }}</span></span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @error('tarifasSeleccionadas')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="flex">
                                    <flux:spacer/>
                                    <flux:button type="submit" variant="primary">Agregar</flux:button>
                                </div>
                            @else
                                <div class="mt-2 text-red-400 text-sm">
                                    Este cliente no tiene tarifas registradas.
                                </div>
                            @endif
                        </div>
                    </flux:modal>
                </div>
            </div>
        </form>
    </div>
</div>
