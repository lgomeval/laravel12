<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use App\Models\Cliente;

new class extends Component {
    public Collection $clientes;

    public function mount(): void
    {
        $this->clientes = Cliente::with(['tarifas.user'])->get();
    }
}; ?>

<div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-4 overflow-x-auto">
    <table class="min-w-full border border-gray-300 dark:border-zinc-600 text-sm">
        <thead class="bg-gray-100 dark:bg-zinc-700">
        <tr>
            <th class="px-4 py-2 text-left">Cliente</th>
            <th class="px-4 py-2 text-center">Cantidad Exámenes</th>
            <th class="px-4 py-2 text-left">Fecha de Creación</th>
            <th class="px-4 py-2">Creado por</th>
            <th colspan="2" class="px-4 py-2 text-center">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($clientes as $cliente)
            @if ($cliente->tarifas->isNotEmpty())
                <tr class="border-t border-gray-300 dark:border-zinc-600"></tr>
                <td class="px-4 py-2">{{ $cliente->razon_social }}</td>
                <td class="px-4 py-2 text-center">{{ $cliente->tarifas->count() }}</td>
                <td class="px-4 py-2">{{ $cliente->tarifas->first()->created_at->format('d-m-Y') }}</td>
                <td class="px-4 py-2">{{ $cliente->tarifas->first()->user->name }}</td>
                <td class="px-2 py-2 text-center">
                    <a class="text-green-600 hover:text-green-800" title="Crear Tarifa"
                       href="{{ route('tarifas.create', $cliente->id) }}">
                        <i class="fas fa-save"></i>
                    </a>
                </td>
                <td class="px-2 py-2 text-center">
                    <a class="text-blue-600 hover:text-blue-800" title="Ver Detalles"
                       href="{{ route('tarifas.index', $cliente->id) }}">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <div class="mt-4 flex justify-between items-center">
        <div class="flex gap-2">
            <a class="px-4 py-2 border rounded text-green-600 hover:bg-green-50"
               href="{{ route('ordenes-de-servicio.index') }}">
                Ordenes de Servicio
            </a>
            <a class="px-4 py-2 border rounded text-blue-600 hover:bg-blue-50"
               href="{{ route('clientes.index') }}">
                Listar Clientes
            </a>
        </div>
    </div>
</div>
