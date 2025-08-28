<?php

use App\Models\Cliente;
use App\Models\Tarifa;
use Illuminate\Contracts\View\View;
use Livewire\Volt\Component;

new class extends Component {
    public Cliente $cliente;
    public $search;
    protected $listeners = ['disable'];

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    public function with(): array
    {
        return [
            'tarifas' => Tarifa::buscar($this->search)->where('estado', 'activo')->orderBy('id', 'desc')->paginate(7),
        ];
    }

    public function disable($tarifaId)
    {
        Tarifa::where('id', $tarifaId)->update(['estado' => 'inactivo']);

        return redirect()->route('clientes.index')->with('success', 'Tarifario desactivado');
    }
}; ?>

<div class="px-4 sm:px-6 lg:px-8">
    <x-slot name="header">
        <h1 class="text-2xl text-center font-semibold text-gray-900 dark:text-white">
            Tarifario Disponible para : {{ $cliente->nombre_comercial }}
        </h1>
        <br>
    </x-slot>
    <div class="grid grid-cols-3 ">
        <div>
        </div>
        <div>
        </div>
        <div>
            <input wire:model.live="search"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white"
                   placeholder="Búsqueda por Ciudad del Tarifario"/>
            <div wire:loading>
                <span>Buscando Tarifario ......</span>
            </div>
        </div>
    </div>

    @if ($tarifas->count() == 0)
        <div class="mt-4">
            <h5>{{ $search }}!</h5>
            <p>No se encontraron registros con los criterios de búsqueda ingresados.</p>
            <a href="{{ route('tarifas.create', $cliente->id ) }}"
               class="inline-block px-3 py-1.5 bg-green-900 text-white rounded-md hover:bg-green-600 transition">
                <i class="fas fa-plus"></i> {{ __('Crear Nueva Tarifa') }}
            </a>
        </div>
    @else
        <div class="w-full overflow-x-auto mt-4">
            <x-table :items="$tarifas"
                     :columns="['Fecha de Creación','codigo','Tipo','Producto','Valor', 'Descuento', 'Ciudad Tarifario', 'Cliente', 'Creado por:', ]"
                     :fields="['created_at','codigo', 'tipo', 'nombre', 'precio', 'descuento', 'ciudad.nombre_ciudad', 'cliente.razon_social', 'user.nombres']"
                     :hasActions="true"
                     :editRoute="'tarifas.edit'"/>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Livewire !== 'undefined') {
                Livewire.on('confirmdesactivar', function (tarifaId) {
                    Swal.fire({
                        title: "¿Estás seguro que deseas desactivar el Tarifario?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('disable', {
                                tarifaId: tarifaId
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
