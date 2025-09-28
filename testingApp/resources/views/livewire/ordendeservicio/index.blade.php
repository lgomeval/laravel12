<?php

use Livewire\Volt\Component;
use App\Models\OrdenDeServicio;

new class extends Component {
    public $search;
    protected $listeners = ['disable'];

    public function with(): array
    {
        $this->ordenes = OrdenDeServicio::with(['tarifas', 'paciente', 'cliente'])
            ->latest()
            ->get();

        return [
            'ordenes' => OrdenDeServicio::buscar($this->search)
                ->where('estado', 'Pendiente Agendar')
                ->orderBy('orden_numero', 'desc')
                ->orderBy('created_at', 'asc')
                ->paginate(7),
        ];
    }

    public function disable($ordenId)
    {
        $orden = OrdenDeServicio::findOrFail($ordenId);
        if ($orden->estado === 'Finalizado') {
            return redirect()->route('ordenes-de-servicio.index')->with('danger', 'No es posible cancelar una Orden en estado Finalizado');
        }

        $orden->update(['estado' => 'Cancelado']);
        return redirect()->route('ordenes-de-servicio.index')->with('warning', 'Orden de servicio Cancelada correctamente');
    }

}; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <x-slot name="header">
        <h1 class="text-2xl text-center font-semibold text-gray-900 dark:text-white">
            {{ __('Ordenes de Servicios.') }}
        </h1>
        <br>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
        <div>
            <p>Aqui solo se muestran las ordenes de servicio en estado <strong>Pendiente Agendar</strong></p>
        </div>
        <div class="w-full max-w-md mx-auto">
            <input wire:model.live="search"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white"
                   placeholder="Búsqueda por número de Orden"/>
            <div wire:loading>
                <span>Buscando Orden de Servicio ......</span>
            </div>
        </div>
    </div>

    @if ($ordenes->count() == 0)
        <div class="mt-4">
            <h5>{{ $search }}!</h5>
            <p>No se encontraron registros con los criterios de búsqueda ingresados.</p>
        </div>
    @else
        <div class="w-full mt-4 overflow-x-auto">
            <x-table :items="$ordenes" :columns="[
                'Número de orden',
                'Tipo Evaluación',
                //'enfasis',
                //'Medio de Venta',
                'Nombre del Paciente',
                //'Documento del Paciente',
                'Lugar de Realización',
                //'Dirección',
                //'Telefono',
                'Cliente Solicita:',
                //'Cargo a desempeñar:',
                'Fecha Solicitud',
                //'Procedimientos',
                'Estado',
            ]" :fields="[
                'orden_numero',
                'tipo_evaluacion',
                //'enfasis',
                //'medio_venta',
                'paciente.NombreCompleto',
                //'paciente.numero_identificacion',
                'prestadorSaluds.razon_social',
                //'prestadorSaluds.direccion',
                //'prestadorSaluds.telefono_celular',
                'cliente.nombre_comercial',
                //'paciente.cargo_a_desempenar',
                'created_at',
                //'procedimientos',
                'estado',
            ]" :hasActions="true"
                     :agendaRoute="'ordenes-de-servicio.show'"
                     :editRoute="'ordenes-de-servicio.edit'"
                     />
            @endif
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.on('confirmdesactivar', function (ordenId) {
                            Swal.fire({
                                title: "¿Estás seguro que deseas cambiar de estado esta Orden de Servicio?",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Sí",
                                cancelButtonText: "Cancelar"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Livewire.dispatch('disable', {
                                        ordenId: ordenId
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
