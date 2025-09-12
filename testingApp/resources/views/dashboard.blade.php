<x-layouts.app :title="__('Duk Health - Dashboard')">

    <x-slot name="header">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <x-slot name="content">
        <div class="mt-2">
            <p class="text-gray-700 dark:text-gray-300">
                {{ __('Welcome to the Duk Health Admin Panel!') }}
            </p>
        </div>
        <div class="mt-6">
            <h1 class="text-gray-700 dark:text-gray-300">
                {{ __('Modificaciones a mejorar durante el desarrollo del proyecto.') }}
            </h1>
            <ul class="text-gray-600 dark:text-gray-400 list-disc ml-6">
                <li>Para las vistas create, implementar modales desde los index.</li>
            </ul>
        </div>
    </x-slot>

</x-layouts.app>
