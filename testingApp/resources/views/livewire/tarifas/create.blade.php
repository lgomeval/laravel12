<?php

use App\Models\Cliente;
use Livewire\Volt\Component;

new class extends Component {
    public $cliente;

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente;
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
</div>
