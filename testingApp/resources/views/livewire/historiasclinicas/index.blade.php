<?php

use App\Models\HistoriaClinica;
use Livewire\Volt\Component;

new class extends Component {
    public $search;
    protected $listeners = ['disable'];

    public function with(): array
    {
        $this->historias = HistoriaClinica::with(['cita', 'paciente'])
            ->latest()
            ->get();

        return [
            'historias' => HistoriaClinica::buscar($this->search)
                ->where('estado', 'Activo')
                ->orderBy('created_at', 'asc')
                ->paginate(7),
        ];
    }

    public function disable($historiaId)
    {
        HistoriaClinica::where('id', $historiaId)->update(['estado' => 'Inactivo']);

        return redirect()->route('historias-clinicas.index')->with('success', 'Historia Clinica del paciente desactivada');
    }
}; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <x-slot name="header">
        <h1 class="text-2xl text-center font-semibold text-gray-900 dark:text-white">
            {{ __('Historias Clinicas.') }}
        </h1>
        <br>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
        <div>
            <p>Aqui solo se muestran las Historias Clinicas en estado <strong>Activo</strong></p>
        </div>
        <div class="w-full max-w-md mx-auto">
            <input wire:model.live="search"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white"
                   placeholder="Búsqueda Historia Clinica por Paciente"/>
            <div wire:loading>
                <span>Buscando Historia Clinica ......</span>
            </div>
        </div>
    </div>

    @if ($historias->count() == 0)
        <div class="mt-4">
            <h5>{{ $search }}!</h5>
            <p>No se encontraron registros con los criterios de búsqueda ingresados.</p>
        </div>
    @else
        <div class="w-full mt-4 overflow-x-auto">
            <x-table :items="$historias" :columns="[
                    'Fecha de Creación',
                    'Nombres & Apellidos',
                    'Tipo & # Documento',
                    'Fecha de Nacimiento',
                    'Genero',
                    'Usuario Solicitud',
                    'estado'
            ]" :fields="[
                    'created_at',
                    'paciente.NombreCompleto',
                    fn($historia) => $historia->paciente->tipo_identificacion . ' # ' . $historia->paciente->numero_identificacion,
                    'paciente.fecha_de_nacimiento',
                    'paciente.genero',
                    'user.nombres',
                    'estado',

            ]" :hasActions="true"
                     :showRoute="'historias-clinicas.show'"
                     :editRoute="'historias-clinicas.edit'"
            />
            @endif
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.on('confirmdesactivar', function (historiaId) {
                            Swal.fire({
                                title: "¿Estás seguro que deseas cambiar de estado esta Historia Clinica?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Sí",
                                cancelButtonText: "Cancelar"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Livewire.dispatch('disable', {
                                        historiaId: historiaId
                                    });
                                }
                            });
                        });
                    } else {
                        console.warn('Livewire no está definido aún. Script de confirmación omitido.');
                    }
                });
            </script>
        </div>
</div>

