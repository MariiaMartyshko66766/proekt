<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket, TicketService $service): RedirectResponse
    {
        Gate::authorize('comment', $ticket);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Wpisz treść komentarza.',
            'body.max' => 'Komentarz może mieć maksymalnie 2000 znaków.',
        ]);

        $service->addComment($ticket, $request->user(), $data['body']);

        return back()->with('success', 'Komentarz został dodany.');
    }
}