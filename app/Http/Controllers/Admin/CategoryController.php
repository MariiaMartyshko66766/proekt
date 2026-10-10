<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private array $messages = [
        'name.required' => 'Podaj nazwę kategorii.',
        'name.max' => 'Nazwa może mieć maksymalnie 100 znaków.',
        'name.unique' => 'Kategoria o takiej nazwie już istnieje.',
        'description.max' => 'Opis może mieć maksymalnie 1000 znaków.',
    ];

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('tickets')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], $this->messages);

        Category::create($data);

        return back()->with('success', 'Kategoria została dodana.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ], $this->messages);

        $category->update($data);

        return back()->with('success', 'Kategoria została zapisana.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // kategorii z przypisanymi zgłoszeniami nie można usunąć
        if ($category->tickets()->exists()) {
            return back()->withErrors([
                'category' => 'Nie można usunąć kategorii, do której przypisane są zgłoszenia.',
            ]);
        }

        $category->delete();

        return back()->with('success', 'Kategoria została usunięta.');
    }
}