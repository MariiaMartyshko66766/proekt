@php
    $fieldNames = [
        'status' => 'Status',
        'priority' => 'Priorytet',
        'category' => 'Kategoria',
        'agent' => 'Technik',
    ];
    $user = auth()->user();
    $hasActions = $user->can('take', $ticket)
        || $user->can('assign', $ticket)
        || $user->can('updateDetails', $ticket)
        || count($transitions);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h1 class="h4 mb-0">Zgłoszenie #{{ $ticket->id }}</h1>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary btn-sm">Wróć do listy</a>
        </div>
    </x-slot>

    @include('partials.errors')

    <div class="row g-3">

        {{-- lewa kolumna: opis, komentarze, historia --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h5">{{ $ticket->title }}</h2>
                    <p class="mb-0" style="white-space: pre-line;">{{ $ticket->description }}</p>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header">Komentarze ({{ $ticket->comments->count() }})</div>
                <ul class="list-group list-group-flush">
                    @forelse ($ticket->comments->sortBy('created_at') as $comment)
                        <li class="list-group-item">
                            <div class="small text-muted mb-1">
                                <strong>{{ $comment->user->name }}</strong>,
                                {{ $comment->created_at->format('d.m.Y H:i') }}
                            </div>
                            <div style="white-space: pre-line;">{{ $comment->body }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Brak komentarzy.</li>
                    @endforelse
                </ul>

                <div class="card-body border-top">
                    @can('comment', $ticket)
                        <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}">
                            @csrf
                            <label for="body" class="form-label">Dodaj komentarz</label>
                            <textarea id="body" name="body" rows="3" class="form-control mb-2 @error('body') is-invalid @enderror"
                                      placeholder="Napisz komentarz...">{{ old('body') }}</textarea>
                            <button type="submit" class="btn btn-primary">Dodaj komentarz</button>
                        </form>
                    @else
                        <p class="text-muted small mb-0">Zgłoszenie jest zamknięte, dodawanie komentarzy jest wyłączone.</p>
                    @endcan
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">Historia zmian</div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Kto</th>
                                <th>Pole</th>
                                <th>Było</th>
                                <th>Jest</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ticket->history->sortBy('created_at') as $entry)
                                <tr>
                                    <td class="text-nowrap">{{ $entry->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $entry->user->name }}</td>
                                    <td>{{ $fieldNames[$entry->field] ?? $entry->field }}</td>
                                    <td>{{ $entry->old_value ?? '—' }}</td>
                                    <td>{{ $entry->new_value ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Brak zmian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- prawa kolumna: dane i działania --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header">Szczegóły</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status</span>
                        <x-status-badge :status="$ticket->status" />
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted">Priorytet</span>
                        <x-priority-badge :priority="$ticket->priority" />
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Kategoria</span>
                        <span>{{ $ticket->category->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Autor</span>
                        <span>{{ $ticket->author->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Technik</span>
                        <span>{{ $ticket->agent?->name ?? 'nieprzypisany' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Utworzono</span>
                        <span>{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                    </li>
                    @if ($ticket->closed_at)
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Zamknięto</span>
                            <span>{{ $ticket->closed_at->format('d.m.Y H:i') }}</span>
                        </li>
                    @endif
                    @foreach ($ticket->attachments as $attachment)
                        <li class="list-group-item">
                            <div class="text-muted small">Załącznik</div>
                            <a href="{{ route('attachments.download', $attachment) }}" class="text-decoration-none">
                                {{ $attachment->original_name }}
                            </a>
                            <span class="text-muted small">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($hasActions)
                <div class="card shadow-sm">
                    <div class="card-header">Działania</div>
                    <div class="card-body vstack gap-3">

                        @can('take', $ticket)
                            <form method="POST" action="{{ route('tickets.take', $ticket) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">Przejmij zgłoszenie</button>
                            </form>
                        @endcan

                        @can('assign', $ticket)
                            <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                                @csrf
                                <label for="agent_id" class="form-label small mb-1">Przypisz technika</label>
                                <div class="input-group">
                                    <select id="agent_id" name="agent_id" class="form-select">
                                        <option value="">— wybierz —</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" @selected((int) $ticket->agent_id === (int) $agent->id)>{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Przypisz</button>
                                </div>
                            </form>
                        @endcan

                        @if (count($transitions))
                            <div>
                                <div class="small text-muted mb-1">Zmień status</div>
                                <div class="vstack gap-2">
                                    @foreach ($transitions as $next)
                                        <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $next }}">
                                            <button type="submit" class="btn btn-outline-dark w-100">{{ $next }}</button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @can('updateDetails', $ticket)
                            <form method="POST" action="{{ route('tickets.details', $ticket) }}">
                                @csrf
                                @method('PATCH')
                                <div class="mb-2">
                                    <label for="priority" class="form-label small mb-1">Priorytet</label>
                                    <select id="priority" name="priority" class="form-select">
                                        @foreach ($priorities as $p)
                                            <option value="{{ $p }}" @selected($ticket->priority === $p)>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="category_id" class="form-label small mb-1">Kategoria</label>
                                    <select id="category_id" name="category_id" class="form-select">
                                        @foreach ($categories as $c)
                                            <option value="{{ $c->id }}" @selected((int) $ticket->category_id === (int) $c->id)>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-secondary w-100">Zapisz zmiany</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>