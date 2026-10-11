@props(['status'])

@php
    $classes = [
        'Nowe' => 'text-bg-primary',
        'W realizacji' => 'text-bg-warning',
        'Oczekuje na odpowiedź' => 'text-bg-info',
        'Rozwiązane' => 'text-bg-success',
        'Zamknięte' => 'text-bg-secondary',
    ][$status] ?? 'text-bg-light';
@endphp

<span class="badge {{ $classes }}">{{ $status }}</span>