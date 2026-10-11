<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Panel główny</h1>
    </x-slot>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('tickets.index') }}" class="btn btn-primary">Lista zgłoszeń</a>
        @can('create', App\Models\Ticket::class)
            <a href="{{ route('tickets.create') }}" class="btn btn-outline-primary">Nowe zgłoszenie</a>
        @endcan
    </div>

    <h2 class="h5">
        {{ $isStaff ? 'Wszystkie zgłoszenia' : 'Twoje zgłoszenia' }} według statusu
        <span class="text-muted fw-normal">(razem: {{ $total }})</span>
    </h2>
    <div class="row g-3 mb-4">
        @foreach ($statusCounts as $label => $count)
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('tickets.index', ['status' => $label]) }}" class="text-decoration-none">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <div class="display-6 fw-bold text-body">{{ $count }}</div>
                            <x-status-badge :status="$label" />
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <h2 class="h5">Według priorytetu</h2>
    <div class="row g-3 mb-4">
        @foreach ($priorityCounts as $label => $count)
            <div class="col-6 col-md-3">
                <a href="{{ route('tickets.index', ['priority' => $label]) }}" class="text-decoration-none">
                    <div class="card h-100 text-center shadow-sm">
                        <div class="card-body">
                            <div class="display-6 fw-bold text-body">{{ $count }}</div>
                            <x-priority-badge :priority="$label" />
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    @if ($isStaff)
        <div class="card shadow-sm">
            <div class="card-header">Otwarte zgłoszenia techników</div>
            <div class="card-body">
                <p class="text-muted small">
                    Wolne zgłoszenia do przejęcia: <strong>{{ $unassigned }}</strong>
                </p>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Technik</th>
                                <th>Otwarte zgłoszenia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agentLoad as $agent)
                                <tr>
                                    <td>{{ $agent->name }}</td>
                                    <td>{{ $agent->open_tickets }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>