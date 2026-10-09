<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lista zgłoszeń</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @can('create', App\Models\Ticket::class)
                <a href="{{ route('tickets.create') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">Nowe zgłoszenie</a>
            @endcan

            <form method="GET" action="{{ route('tickets.index') }}" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm">Szukaj w temacie</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm">Status</label>
                    <select name="status" class="border-gray-300 rounded">
                        <option value="">Wszystkie</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">Priorytet</label>
                    <select name="priority" class="border-gray-300 rounded">
                        <option value="">Wszystkie</option>
                        @foreach ($priorities as $p)
                            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">Kategoria</label>
                    <select name="category_id" class="border-gray-300 rounded">
                        <option value="">Wszystkie</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected((string) request('category_id') === (string) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Filtruj</button>
                <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-200 rounded">Wyczyść</a>
            </form>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-2">Nr</th>
                            <th class="p-2">Temat</th>
                            <th class="p-2">Kategoria</th>
                            <th class="p-2">Priorytet</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Autor</th>
                            <th class="p-2">Technik</th>
                            <th class="p-2">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr class="border-t">
                                <td class="p-2">#{{ $ticket->id }}</td>
                                <td class="p-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 underline">{{ $ticket->title }}</a>
                                </td>
                                <td class="p-2">{{ $ticket->category->name }}</td>
                                <td class="p-2">{{ $ticket->priority }}</td>
                                <td class="p-2">{{ $ticket->status }}</td>
                                <td class="p-2">{{ $ticket->author->name }}</td>
                                <td class="p-2">{{ $ticket->agent?->name ?? '—' }}</td>
                                <td class="p-2">{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-gray-500">Brak zgłoszeń.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $tickets->links() }}</div>
        </div>
    </div>
</x-app-layout>