<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nowe zgłoszenie</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
                @csrf

                <div>
                    <label for="title" class="block font-medium">Temat</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" maxlength="150" class="mt-1 block w-full border-gray-300 rounded">
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <div>
                    <label for="description" class="block font-medium">Opis</label>
                    <textarea id="description" name="description" rows="6" class="mt-1 block w-full border-gray-300 rounded">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <div>
                    <label for="category_id" class="block font-medium">Kategoria</label>
                    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded">
                        <option value="">— wybierz —</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected((string) old('category_id') === (string) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                </div>

                <div>
                    <label for="priority" class="block font-medium">Priorytet</label>
                    <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded">
                        @foreach ($priorities as $p)
                            <option value="{{ $p }}" @selected(old('priority', 'Średni') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('priority')" class="mt-1" />
                </div>

                <div>
                    <label for="attachment" class="block font-medium">Załącznik (opcjonalnie: jpg, png, pdf, docx, do 5 MB)</label>
                    <input id="attachment" name="attachment" type="file" class="mt-1 block w-full">
                    <x-input-error :messages="$errors->get('attachment')" class="mt-1" />
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Wyślij zgłoszenie</button>
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-200 rounded">Anuluj</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>