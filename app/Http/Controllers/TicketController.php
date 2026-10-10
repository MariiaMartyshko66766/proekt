<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(private TicketService $tickets)
    {
    }

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

    public function show(Request $request, Ticket $ticket): View
    {
        Gate::authorize('view', $ticket);

        $ticket->load(['author', 'agent', 'category', 'attachments', 'comments.user', 'history.user']);

        return view('tickets.show', [
            'ticket' => $ticket,
            'transitions' => $this->tickets->allowedTransitions($ticket, $request->user()),
            'agents' => User::where('role', User::ROLE_AGENT)->where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'priorities' => Ticket::priorities(),
        ]);
    }

    public function take(Request $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('take', $ticket);

        $this->tickets->take($ticket, $request->user());

        return back()->with('success', 'Zgłoszenie zostało przejęte.');
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('assign', $ticket);

        $data = $request->validate([
            'agent_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_AGENT)->where('is_active', true),
            ],
        ], [
            'agent_id.required' => 'Wybierz technika.',
            'agent_id.exists' => 'Wybrany technik jest niedostępny.',
        ]);

        $this->tickets->assign($ticket, $request->user(), User::findOrFail($data['agent_id']));

        return back()->with('success', 'Zgłoszenie zostało przypisane.');
    }

    public function updateStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('view', $ticket);

        $data = $request->validate([
            'status' => ['required', Rule::in(Ticket::statuses())],
        ], [
            'status.required' => 'Wybierz status.',
            'status.in' => 'Wybrany status jest nieprawidłowy.',
        ]);

        $this->tickets->changeStatus($ticket, $request->user(), $data['status']);

        return back()->with('success', 'Status zgłoszenia został zmieniony.');
    }

    public function updateDetails(Request $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('updateDetails', $ticket);

        $data = $request->validate([
            'priority' => ['required', Rule::in(Ticket::priorities())],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ], [
            'priority.required' => 'Wybierz priorytet.',
            'priority.in' => 'Wybrany priorytet jest nieprawidłowy.',
            'category_id.required' => 'Wybierz kategorię.',
            'category_id.exists' => 'Wybrana kategoria nie istnieje.',
        ]);

        $this->tickets->updateDetails(
            $ticket,
            $request->user(),
            $data['priority'],
            (int) $data['category_id']
        );

        return back()->with('success', 'Dane zgłoszenia zostały zapisane.');
    }
}