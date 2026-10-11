<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Nowe zgłoszenie</h1>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="card shadow-sm">
                @csrf

                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Temat</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" maxlength="150"
                               class="form-control @error('title') is-invalid @enderror">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Opis problemu</label>
                        <textarea id="description" name="description" rows="6"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategoria</label>
                            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">— wybierz —</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" @selected((string) old('category_id') === (string) $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priorytet</label>
                            <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror">
                                @foreach ($priorities as $p)
                                    <option value="{{ $p }}" @selected(old('priority', 'Średni') === $p)>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attachment" class="form-label">Załącznik (opcjonalnie)</label>
                        <input id="attachment" name="attachment" type="file"
                               class="form-control @error('attachment') is-invalid @enderror">
                        <div class="form-text">Dozwolone formaty: jpg, png, pdf, docx. Maksymalny rozmiar: 5 MB.</div>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Wyślij zgłoszenie</button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>