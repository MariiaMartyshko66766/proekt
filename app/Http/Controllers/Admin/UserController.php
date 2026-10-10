<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private function roleLabels(): array
    {
        return [
            User::ROLE_USER => 'Użytkownik',
            User::ROLE_AGENT => 'Technik',
            User::ROLE_ADMIN => 'Administrator',
        ];
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->input('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)->orWhere('email', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roleLabels' => $this->roleLabels(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // administrator nie może zablokować ani zdegradować samego siebie
        if ($user->id === $request->user()->id) {
            return back()->withErrors([
                'user' => 'Nie możesz zmienić własnej roli ani zablokować własnego konta.',
            ]);
        }

        $data = $request->validate([
            'role' => ['required', Rule::in(array_keys($this->roleLabels()))],
            'is_active' => ['required', 'boolean'],
        ], [
            'role.required' => 'Wybierz rolę.',
            'role.in' => 'Wybrana rola jest nieprawidłowa.',
            'is_active.required' => 'Wybierz status konta.',
            'is_active.boolean' => 'Nieprawidłowy status konta.',
        ]);

        $user->update([
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Zmiany użytkownika ' . $user->name . ' zostały zapisane.');
    }
}