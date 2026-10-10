<x-guest-layout>
    <h2 class="h5 mb-3">Nie pamiętasz hasła?</h2>

    <p class="text-muted small">
        Podaj adres e-mail powiązany z kontem, a wyślemy Ci link do ustawienia nowego hasła.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adres e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Wyślij link</button>
        </div>

        <div class="text-center mt-3 small">
            <a href="{{ route('login') }}">Wróć do logowania</a>
        </div>
    </form>
</x-guest-layout>