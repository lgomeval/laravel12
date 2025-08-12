@props([])

<!-- Modal Tarifario -->
<div x-data="{ open: @entangle('mostrarModal') }" x-show="open" x-cloak x-transition.opacity x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                Tarifario disponible para
                <span class="text-indigo-600">{{ $clienteSeleccionado?->razon_social }}</span>
            </h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                ✕
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            @if (!$tarifas || $tarifas->isEmpty())
                <div class="text-center">
                    <p class="text-red-600 mb-3">No existen tarifas para este cliente</p>
                    @if ($clienteSeleccionado)
                        <a href="{{ route('tarifario.create', $clienteSeleccionado->id) }}" target="_blank" ...>
                            Crear Tarifario
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Seleccionar</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nombre</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Precio</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Ciudad Tarifario</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">

                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
            <button @click="open = false"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
                Cancelar
            </button>

            @if ($tarifas && $tarifas->isNotEmpty())
                <button wire:click="crearOrden"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    Crear Orden de Servicio
                </button>
            @endif
        </div>
    </div>
</div>
