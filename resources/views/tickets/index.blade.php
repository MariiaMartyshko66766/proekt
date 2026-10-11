<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Lista zgłoszeń</h1>
            @can('create', App\Models\Ticket::class)
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">Nowe zgłoszenie</a>
            @endcan
        </div>
    </x-slot>

    <form method="GET" action="{{ route('tickets.index') }}" class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label for="q" class="form-label small mb-1">Szukaj w temacie</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <label for="status" class="form-label small mb-1">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">Wszystkie</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="priority" class="form-label small mb-1">Priorytet</label>
                    <select id="priority" name="priority" class="form-select">
                        <option value="">Wszystkie</option>
                        @foreach ($priorities as $p)
                            <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label for="category_id" class="form-label small mb-1">Kategoria</label>
                    <select id="category_id" name="category_id" class="form-select">
                        <option value="">Wszystkie</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected((string) request('category_id') === (string) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filtruj</button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Wyczyść</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nr</th>
                        <th>Temat</th>
                        <th>Kategoria</th>
                        <th>Priorytet</th>
                        <th>Status</th>
                        <th>Autor</th>
                        <th>Technik</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}" class="fw-semibold text-decoration-none">{{ $ticket->title }}</a>
                            </td>
                            <td>{{ $ticket->category->name }}</td>
                            <td><x-priority-badge :priority="$ticket->priority" /></td>
                            <td><x-status-badge :status="$ticket->status" /></td>
                            <td>{{ $ticket->author->name }}</td>
                            <td>{{ $ticket->agent?->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Brak zgłoszeń spełniających kryteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($tickets->total())
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
            <div class="text-muted small">
                Wyświetlono {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} z {{ $tickets->total() }}
            </div>
            <div>{{ $tickets->links() }}</div>
        </div>
    @endif
</x-app-layout>