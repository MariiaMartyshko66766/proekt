<x-guest-layout>
    <div class="text-center">
        <div class="display-4 fw-bold text-danger">403</div>
        <h2 class="h5">Brak dostępu</h2>
        <p class="text-muted">Nie masz uprawnień do wyświetlenia tej strony lub wykonania tej operacji.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Wróć na stronę główną</a>
    </div>
</x-guest-layout>