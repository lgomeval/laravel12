<?php

use App\Models\Cita;
use App\Models\OrdenDeServicio;
use Carbon\Carbon;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Volt\Component;

new class extends Component {
    public $orden;
    public $fecha_cita;
    public $hora_cita;
    public $observaciones;

    public $horas_disponibles = [];
    public $nombres;
    public $tipos;

    public function mount(OrdenDeServicio $orden): void
    {
        $this->orden = $orden;
    }

    public function updatedFechaCita($value): void
    {
        $this->fecha_cita = $value;
        $this->actualizarHorasDisponibles();
    }

    public function actualizarHorasDisponibles(): void
    {
        $fecha = Carbon::parse($this->fecha_cita)->format('Y-m-d');

        if (Carbon::parse($this->fecha_cita)->isSunday() || Carbon::parse($this->fecha_cita)->isToday()) {
            $this->horas_disponibles = [];
            return;
        }


        $horas = [];
        $inicio = Carbon::createFromTime(7, 0);
        $fin = Carbon::createFromTime(16, 0);

        while ($inicio <= $fin) {
            $horas[] = $inicio->format('H:i');
            $inicio->addMinutes(30);
        }

        $ocupadas = Cita::where('fecha_cita', $this->fecha_cita)
            ->pluck('hora_cita')
            ->map(fn($hora) => Carbon::parse($hora)->format('H:i'))
            ->toArray();

        $this->horas_disponibles = array_values(array_diff($horas, $ocupadas));

    }

    public function agendar()
    {
        $nombres = $this->orden->tarifas->pluck('nombre')->join(', ');
        $tipos = $this->orden->tarifas->pluck('tipo')->join(', ');

        Cita::create([
            'nombre_examen' => $nombres,
            'tipo_examen' => $tipos,
            'fecha_cita' => $this->fecha_cita,
            'hora_cita' => $this->hora_cita,
            'observaciones' => $this->observaciones,
            'estado' => 'Agendada',
            'paciente_id' => $this->orden->paciente_id,
            'orden_de_servicio_id' => $this->orden->id,
        ]);

        // Actualizar Estado de la Orden de Servicio
        $orden = OrdenDeServicio::find($this->orden->id);
        if ($orden)
        {
            $orden->estado = 'En Proceso';
            $orden->save();
        }

        return redirect(route('ordenes-de-servicio.seguimiento'))
            ->with('success', 'Procedimientos agendados exitosamente');
    }

}; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <x-slot name="header">
        <h1 class="text-2xl text-center font-semibold text-gray-900 dark:text-white">
            {{ __('Detalles de la orden de Servicio para el paciente:') }}
            <strong>{{ $orden->paciente->NombreCompleto }}</strong>
        </h1>
        <br>
    </x-slot>

    <div class="px-4 sm:px-0">
        <h3 class="text-base/7 font-semibold text-white">Información Personal</h3>
    </div>
    <div class="mt-6 border-t border-white/10">
        <div class="divide-y divide-white/10">
            {{--Grip--}}
            <div class="grid grid-flow-col grid-rows-1 gap-6">

                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Tipo & # de Identificación</div>
                    <div class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        {{$orden->paciente->tipo_identificacion . ' # ' . $orden->paciente->numero_identificacion}}
                    </div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Fecha de Nacimiento</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->paciente->fecha_de_nacimiento}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Genero</div>
                    <div class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->paciente->genero}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Dirección de Residencia</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->paciente->direccion_residencia}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Ciudad Residencia</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->paciente->ciudad_residencia}}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Número Contacto</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->paciente->telefono}}</div>
                </div>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-100">Información de la Orden De Servicio</dt>
            </div>
            <div class="grid grid-flow-col grid-rows-1 gap-6">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Número de Orden de Servicio</div>
                    <div class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                        {{$orden->orden_numero}}
                    </div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Fecha de Solicitud</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->created_at}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Tipo de Evaluación</div>
                    <div class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->tipo_evaluacion}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Énfasis en</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->enfasis}}</div>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-2 sm:px-0">
                    <dt class="text-sm/6 font-medium text-gray-100">Prestador</dt>
                    <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->prestadorSaluds->razon_social}}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <div class="text-sm/6 font-medium text-gray-100">Estado de la Orden:</div>
                    <div
                        class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">{{$orden->estado}}</div>
                </div>
            </div>
            <div class="px-4 py-6 sm:px-0">
                <div class="px-4 py-6 sm:px-0 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-100">Procedimientos</h3>
                    {{--Boton Agendar--}}
                    <div class="mt-4 text-right">
                        <flux:modal.trigger name="agendar-cita">
                            <flux:button variant="primary">
                                Agendar
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-white/10">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-200">Examen</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-200">Tipo</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-200">Precio</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-200">Estado</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                        @foreach ($orden->tarifas as $examen)
                            <tr>
                                <td class="px-4 py-3 text-gray-300">{{ $examen->nombre }}</td>
                                <td class="px-4 py-3 text-gray-300">{{ $examen->tipo }}</td>
                                <td class="px-4 py-3 text-gray-300">$ {{ number_format($examen->precio, 0, ',', '.') }}
                                    COP
                                </td>
                                <td class="px-4 py-3 text-gray-300">{{ $examen->estado }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Modal para agendar citas --}}
                <flux:modal name="agendar-cita" class="md:w-[40rem]">
                    <div class="space-y-6">
                        {{-- Encabezado --}}
                        <div>
                            <flux:heading size="lg">Agendar Citas</flux:heading>
                            <flux:text class="mt-2">
                                Selecciona fecha y hora para los procedimientos de esta orden de servicio.
                            </flux:text>
                        </div>

                        {{-- Lista de procedimientos --}}
                        <form wire:submit.prevent='agendar' enctype="multipart/form-data">
                            <div class="space-y-4">
                                <flux:heading size="sm">Procedimientos</flux:heading>
                                <ul class="space-y-2">
                                    @foreach ($orden->tarifas as $examen)
                                        <li class="flex items-center justify-between bg-zinc-800 px-3 py-2 rounded-xl">
                                <span class="text-gray-200 text-sm">
                                    {{ $examen->nombre }} ({{ $examen->tipo }})
                                </span>
                                            <flux:badge>{{ $examen->estado }}</flux:badge>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Campos de agendamiento --}}
                            <div class="space-y-4">

                                {{-- Selección de fecha --}}
                                <flux:input wire:model="fecha_cita" wire:change="actualizarHorasDisponibles($event.target.value)" label="Fecha" type="date"
                                            class="w-full"
                                />

                                {{-- Selección de hora --}}

                                @if(count($horas_disponibles) > 0)
                                    <div class="grid grid-cols-6 gap-2">
                                        @foreach($horas_disponibles as $hora)
                                            <button
                                                type="button"
                                                wire:click="$set('hora_cita', '{{ $hora }}')"
                                                class="px-3 py-2 rounded-lg text-sm font-medium transition
                       {{ $hora_cita === $hora
                           ? 'bg-blue-600 text-white shadow-md'
                           : 'bg-gray-700 text-gray-200 hover:bg-gray-600' }}">
                                                {{ $hora }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-400">No hay horas disponibles para esta fecha.</p>
                                @endif

                                <div class="flex gap-4 mt-2 text-xs">
    <span class="flex items-center gap-1 text-gray-400">
        <span class="w-3 h-3 rounded bg-gray-700"></span> Disponible
    </span>
                                    <span class="flex items-center gap-1 text-blue-500">
        <span class="w-3 h-3 rounded bg-blue-600"></span> Seleccionado
    </span>
                                </div>


                                {{-- Observaciones --}}
                                <flux:textarea
                                    label="Observaciones"
                                    wire:model.defer="observaciones"
                                    class="w-full"
                                />
                            </div>
                            <flux:button type="submit" class="mt-2" variant="primary">
                                Guardar
                            </flux:button>
                        </form>
                    </div>
                </flux:modal>
            </div>
        </div>
    </div>
</div>
