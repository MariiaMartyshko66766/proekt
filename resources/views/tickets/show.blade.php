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
                    <p><strong>Załącznik:</strong> {{ $attachment->original_name }} ({{ number_format($attachment->size / 1024, 1) }} KB)</p>
                @endforeach
            </div>

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
            </div>

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