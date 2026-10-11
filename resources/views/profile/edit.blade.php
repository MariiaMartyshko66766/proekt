@php
    $roleLabels = [
        'user' => 'Użytkownik',
        'agent' => 'Technik',
        'admin' => 'Administrator',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0">Profil</h1>
    </x-slot>

    <div class="row g-3">

        {{-- dane konta --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">Dane konta</div>
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">Dane zostały zapisane.</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Imię i nazwisko</label>
                            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adres e-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rola</label>
                            <input type="text" class="form-control" value="{{ $roleLabels[$user->role] ?? $user->role }}" disabled>
                            <div class="form-text">Rolę może zmienić tylko administrator.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- zmiana hasła --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">Zmiana hasła</div>
                <div class="card-body">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success">Hasło zostało zmienione.</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Obecne hasło</label>
                            <input id="current_password" type="password" name="current_password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nowe hasło</label>
                            <input id="new_password" type="password" name="password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 znaków.</div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Powtórz nowe hasło</label>
                            <input id="new_password_confirmation" type="password" name="password_confirmation"
                                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Zmień hasło</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>