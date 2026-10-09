<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Ticket::class);

        $user = $request->user();
        $query = Ticket::with(['author', 'agent', 'category'])->latest();

        // zwykły użytkownik widzi tylko własne zgłoszenia
        if (! $user->isAdmin() && ! $user->isAgent()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->input('q') . '%');
        }

        return view('tickets.index', [
            'tickets' => $query->paginate(10)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'statuses' => Ticket::statuses(),
            'priorities' => Ticket::priorities(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Ticket::class);

        return view('tickets.create', [
            'categories' => Category::orderBy('name')->get(),
            'priorities' => Ticket::priorities(),
        ]);
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'status' => Ticket::STATUS_NEW,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $size = $file->getSize();
            $mime = $file->getMimeType() ?? 'application/octet-stream';

            $ticket->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $file->store('attachments'),
                'mime' => $mime,
                'size' => $size,
            ]);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Zgłoszenie zostało utworzone.');
    }

    public function show(Ticket $ticket): View
    {
        Gate::authorize('view', $ticket);

        $ticket->load(['author', 'agent', 'category', 'attachments', 'comments.user', 'history.user']);

        return view('tickets.show', ['ticket' => $ticket]);
    }
}