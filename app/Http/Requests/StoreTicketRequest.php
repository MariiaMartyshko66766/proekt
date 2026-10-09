<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Ticket::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'priority' => ['required', Rule::in(Ticket::priorities())],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Podaj temat zgłoszenia.',
            'title.max' => 'Temat może mieć maksymalnie 150 znaków.',
            'description.required' => 'Opisz problem.',
            'description.max' => 'Opis może mieć maksymalnie 5000 znaków.',
            'category_id.required' => 'Wybierz kategorię.',
            'category_id.exists' => 'Wybrana kategoria nie istnieje.',
            'priority.required' => 'Wybierz priorytet.',
            'priority.in' => 'Wybrany priorytet jest nieprawidłowy.',
            'attachment.file' => 'Nie udało się przesłać pliku.',
            'attachment.mimes' => 'Dozwolone formaty załącznika: jpg, png, pdf, docx.',
            'attachment.max' => 'Załącznik może mieć maksymalnie 5 MB.',
        ];
    }
}