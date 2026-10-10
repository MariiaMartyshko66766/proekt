<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Użytkownicy</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

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

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Szukaj po imieniu lub e-mailu" class="border-gray-300 rounded">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Szukaj</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 rounded">Wyczyść</a>
            </form>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-2">Imię i nazwisko</th>
                            <th class="p-2">E-mail</th>
                            <th class="p-2">Rola</th>
                            <th class="p-2">Konto</th>
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr class="border-t">
                                <td class="p-2">{{ $u->name }}</td>
                                <td class="p-2">{{ $u->email }}</td>

                                @if ($u->id === auth()->id())
                                    <td colspan="3" class="p-2 text-gray-500">
                                        {{ $roleLabels[$u->role] }} (to Twoje konto, nie można go zmienić)
                                    </td>
                                @else
                                    <td class="p-2">
                                        <select name="role" form="user-form-{{ $u->id }}" class="border-gray-300 rounded">
                                            @foreach ($roleLabels as $value => $label)
                                                <option value="{{ $value }}" @selected($u->role === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <select name="is_active" form="user-form-{{ $u->id }}" class="border-gray-300 rounded">
                                            <option value="1" @selected($u->is_active)>Aktywne</option>
                                            <option value="0" @selected(! $u->is_active)>Zablokowane</option>
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <form id="user-form-{{ $u->id }}" method="POST" action="{{ route('admin.users.update', $u) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-gray-800 text-white rounded">Zapisz</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>{{ $users->links() }}</div>

            <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-gray-200 rounded">Wróć do panelu</a>
        </div>
    </div>
</x-app-layout>