<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel główny</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Lista zgłoszeń</a>
                @can('create', App\Models\Ticket::class)
                    <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">Nowe zgłoszenie</a>
                @endcan
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 rounded">Użytkownicy</a>
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-200 rounded">Kategorie</a>
                @endif
            </div>

            <div>
                <h3 class="font-semibold mb-2">
                    {{ $isStaff ? 'Wszystkie zgłoszenia' : 'Twoje zgłoszenia' }} według statusu (razem: {{ $total }})
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach ($statusCounts as $label => $count)
                        <div class="bg-white p-4 rounded shadow text-center">
                            <div class="text-3xl font-bold">{{ $count }}</div>
                            <div class="text-sm text-gray-600">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Według priorytetu</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ($priorityCounts as $label => $count)
                        <div class="bg-white p-4 rounded shadow text-center">
                            <div class="text-3xl font-bold">{{ $count }}</div>
                            <div class="text-sm text-gray-600">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($isStaff)
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="font-semibold mb-2">Otwarte zgłoszenia techników</h3>
                    <p class="text-sm text-gray-600 mb-3">Wolne zgłoszenia do przejęcia: <strong>{{ $unassigned }}</strong></p>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-left">
                            <tr>
                                <th class="p-2">Technik</th>
                                <th class="p-2">Otwarte zgłoszenia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agentLoad as $agent)
                                <tr class="border-t">
                                    <td class="p-2">{{ $agent->name }}</td>
                                    <td class="p-2">{{ $agent->open_tickets }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>