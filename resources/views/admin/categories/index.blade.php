<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Kategorie</h1>
    </x-slot>

    @include('partials.errors')

    <form method="POST" action="{{ route('admin.categories.store') }}" class="card shadow-sm mb-3">
        @csrf
        <div class="card-header">Nowa kategoria</div>
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="new-name" class="form-label small mb-1">Nazwa</label>
                    <input id="new-name" type="text" name="name" value="{{ old('name') }}" maxlength="100" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <label for="new-description" class="form-label small mb-1">Opis (opcjonalnie)</label>
                    <input id="new-description" type="text" name="description" value="{{ old('description') }}" class="form-control">
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nazwa</th>
                        <th>Opis</th>
                        <th>Zgłoszeń</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                <input type="text" name="name" value="{{ $category->name }}" form="cat-form-{{ $category->id }}"
                                       maxlength="100" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="text" name="description" value="{{ $category->description }}" form="cat-form-{{ $category->id }}"
                                       class="form-control form-control-sm">
                            </td>
                            <td><span class="badge text-bg-secondary">{{ $category->tickets_count }}</span></td>
                            <td class="text-nowrap">
                                <form id="cat-form-{{ $category->id }}" method="POST" action="{{ route('admin.categories.update', $category) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-dark">Zapisz</button>
                                </form>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
                                      onsubmit="return confirm('Czy na pewno usunąć tę kategorię?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Brak kategorii.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>