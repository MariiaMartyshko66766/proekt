<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategorie</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

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

            <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-sm">Nazwa</label>
                    <input type="text" name="name" value="{{ old('name') }}" maxlength="100" class="border-gray-300 rounded">
                </div>
                <div class="grow">
                    <label class="block text-sm">Opis</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="border-gray-300 rounded w-full">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Dodaj kategorię</button>
            </form>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-2">Nazwa</th>
                            <th class="p-2">Opis</th>
                            <th class="p-2">Zgłoszeń</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr class="border-t">
                                <td class="p-2">
                                    <input type="text" name="name" value="{{ $category->name }}" form="cat-form-{{ $category->id }}" maxlength="100" class="border-gray-300 rounded">
                                </td>
                                <td class="p-2">
                                    <input type="text" name="description" value="{{ $category->description }}" form="cat-form-{{ $category->id }}" class="border-gray-300 rounded w-full">
                                </td>
                                <td class="p-2">{{ $category->tickets_count }}</td>
                                <td class="p-2 flex gap-2">
                                    <form id="cat-form-{{ $category->id }}" method="POST" action="{{ route('admin.categories.update', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-gray-800 text-white rounded">Zapisz</button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Czy na pewno usunąć tę kategorię?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-gray-200 rounded">Wróć do panelu</a>
        </div>
    </div>
</x-app-layout>