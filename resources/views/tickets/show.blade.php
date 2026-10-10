@php
    $fieldNames = [
        'status' => 'Status',
        'priority' => 'Priorytet',
        'category' => 'Kategoria',
        'agent' => 'Technik',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Zgłoszenie #{{ $ticket->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="p-3 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- dane zgłoszenia --}}
            <div class="bg-white p-6 rounded shadow space-y-2">
                <h3 class="text-lg font-semibold">{{ $ticket->title }}</h3>
                <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                <hr>
                <p><strong>Status:</strong> {{ $ticket->status }}</p>
                <p><strong>Priorytet:</strong> {{ $ticket->priority }}</p>
                <p><strong>Kategoria:</strong> {{ $ticket->category->name }}</p>
                <p><strong>Autor:</strong> {{ $ticket->author->name }}</p>
                <p><strong>Technik:</strong> {{ $ticket->agent?->name ?? 'nieprzypisany' }}</p>
                <p><strong>Utworzono:</strong> {{ $ticket->created_at->format('d.m.Y H:i') }}</p>
                @if ($ticket->closed_at)
                    <p><strong>Zamknięto:</strong> {{ $ticket->closed_at->format('d.m.Y H:i') }}</p>
                @endif

                @foreach ($ticket->attachments as $attachment)
                    <p>
                        <strong>Załącznik:</strong>
                        <a href="{{ route('attachments.download', $attachment) }}" class="text-indigo-600 underline">{{ $attachment->original_name }}</a>
                        ({{ number_format($attachment->size / 1024, 1) }} KB)
                    </p>
                @endforeach
            </div>

            {{-- działania --}}
            @if (auth()->user()->can('take', $ticket) || auth()->user()->can('assign', $ticket) || auth()->user()->can('updateDetails', $ticket) || count($transitions))
                <div class="bg-white p-6 rounded shadow space-y-4">
                    <h3 class="font-semibold">Działania</h3>

                    @can('take', $ticket)
                        <form method="POST" action="{{ route('tickets.take', $ticket) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Przejmij zgłoszenie</button>
                        </form>
                    @endcan

                    @can('assign', $ticket)
                        <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="flex flex-wrap gap-2 items-center">
                            @csrf
                            <select name="agent_id" class="border-gray-300 rounded">
                                <option value="">— wybierz technika —</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" @selected((int) $ticket->agent_id === (int) $agent->id)>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Przypisz</button>
                        </form>
                    @endcan

                    @if (count($transitions))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($transitions as $next)
                                <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $next }}">
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded">Zmień status na: {{ $next }}</button>
                                </form>
                            @endforeach
                        </div>
                    @endif

                    @can('updateDetails', $ticket)
                        <form method="POST" action="{{ route('tickets.details', $ticket) }}" class="flex flex-wrap gap-2 items-end">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-sm">Priorytet</label>
                                <select name="priority" class="border-gray-300 rounded">
                                    @foreach ($priorities as $p)
                                        <option value="{{ $p }}" @selected($ticket->priority === $p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm">Kategoria</label>
                                <select name="category_id" class="border-gray-300 rounded">
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}" @selected((int) $ticket->category_id === (int) $c->id)>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-gray-200 rounded">Zapisz</button>
                        </form>
                    @endcan
                </div>
            @endif

            {{-- komentarze --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-2">Komentarze</h3>
                @forelse ($ticket->comments->sortBy('created_at') as $comment)
                    <div class="border-t py-2">
                        <div class="text-sm text-gray-500">{{ $comment->user->name }}, {{ $comment->created_at->format('d.m.Y H:i') }}</div>
                        <div class="whitespace-pre-line">{{ $comment->body }}</div>
                    </div>
                @empty
                    <p class="text-gray-500">Brak komentarzy.</p>
                @endforelse

                @can('comment', $ticket)
                    <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" class="mt-3 space-y-2">
                        @csrf
                        <textarea name="body" rows="3" class="w-full border-gray-300 rounded" placeholder="Napisz komentarz...">{{ old('body') }}</textarea>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Dodaj komentarz</button>
                    </form>
                @else
                    <p class="mt-3 text-sm text-gray-500">Zgłoszenie jest zamknięte, dodawanie komentarzy jest wyłączone.</p>
                @endcan
            </div>

            {{-- historia --}}
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold mb-2">Historia zmian</h3>
                @forelse ($ticket->history->sortBy('created_at') as $entry)
                    <div class="border-t py-2 text-sm">
                        {{ $entry->created_at->format('d.m.Y H:i') }} —
                        {{ $entry->user->name }}:
                        {{ $fieldNames[$entry->field] ?? $entry->field }}:
                        {{ $entry->old_value ?? '—' }} → {{ $entry->new_value ?? '—' }}
                    </div>
                @empty
                    <p class="text-gray-500">Brak zmian.</p>
                @endforelse
            </div>

            <a href="{{ route('tickets.index') }}" class="inline-block px-4 py-2 bg-gray-200 rounded">Wróć do listy</a>
        </div>
    </div>
</x-app-layout>