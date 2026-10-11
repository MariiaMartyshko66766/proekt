<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Użytkownicy</h1>
    </x-slot>

    @include('partials.errors')

    <form method="GET" action="{{ route('admin.users.index') }}" class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <label for="q" class="form-label small mb-1">Szukaj po imieniu lub e-mailu</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" class="form-control">
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Szukaj</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Wyczyść</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imię i nazwisko</th>
                        <th>E-mail</th>
                        <th>Rola</th>
                        <th>Konto</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr>
                            <td>
                                {{ $u->name }}
                                @if (! $u->is_active)
                                    <span class="badge text-bg-danger ms-1">Zablokowany</span>
                                @endif
                            </td>
                            <td>{{ $u->email }}</td>

                            @if ($u->id === auth()->id())
                                <td colspan="3" class="text-muted">
                                    {{ $roleLabels[$u->role] }}
                                    <span class="small">(to Twoje konto, nie można go zmienić)</span>
                                </td>
                            @else
                                <td>
                                    <select name="role" form="user-form-{{ $u->id }}" class="form-select form-select-sm">
                                        @foreach ($roleLabels as $value => $label)
                                            <option value="{{ $value }}" @selected($u->role === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="is_active" form="user-form-{{ $u->id }}" class="form-select form-select-sm">
                                        <option value="1" @selected($u->is_active)>Aktywne</option>
                                        <option value="0" @selected(! $u->is_active)>Zablokowane</option>
                                    </select>
                                </td>
                                <td>
                                    <form id="user-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.update', $u) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-dark">Zapisz</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nie znaleziono użytkowników.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($users->total())
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
            <div class="text-muted small">
                Wyświetlono {{ $users->firstItem() }}–{{ $users->lastItem() }} z {{ $users->total() }}
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    @endif
</x-app-layout>