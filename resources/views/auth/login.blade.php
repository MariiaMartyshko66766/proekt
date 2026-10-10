<x-guest-layout>
    <h2 class="h5 mb-3">Logowanie</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adres e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Zaloguj się</button>
        </div>

        <div class="d-flex justify-content-between mt-3 small">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Nie pamiętasz hasła?</a>
            @endif
            <a href="{{ route('register') }}">Utwórz konto</a>
        </div>
    </form>
</x-guest-layout>