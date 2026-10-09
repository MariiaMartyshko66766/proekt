<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel główny</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow space-y-3">
                <p>Zalogowano jako {{ auth()->user()->name }} (rola: {{ auth()->user()->role }}).</p>
                <a href="{{ route('tickets.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded">Lista zgłoszeń</a>
            </div>
        </div>
    </div>
</x-app-layout>