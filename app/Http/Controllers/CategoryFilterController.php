<?php

namespace App\Http\Controllers;

use App\Models\CategoryFilter;
use Illuminate\Http\Request;
use App\Services\CategoryFilterService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryFilterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('category-filter.index');
    }

    public function create(): View
    {
        $this->authorize('create', CategoryFilter::class);
        return view('category-filter.create');
    }

    public function store(Request $request): View
    {
        /** @see App\Http\Livewire\CategoryFilter\CreateForm */
    }

    public function show($id, CategoryFilterService $categoryFilterService): View
    {
        $categoryFilter = $categoryFilterService->findById($id);
        return view('category-filter.show', compact('categoryFilter'));
    }

    public function edit(CategoryFilter $categoryFilter): View
    {
        $this->authorize('update', $categoryFilter);
        $id = $categoryFilter->id;
        return view('category-filter.edit', compact('id'));
    }

    public function update(Request $request, CategoryFilter $categoryFilter)
    {
        /** @see App\Http\Livewire\CategoryFilter\CreateForm */
    }

    public function destroy($id, CategoryFilterService $categoryFilterService): RedirectResponse
    {
        $categoryFilter = CategoryFilter::find($id);
        $this->authorize('delete', $categoryFilter);
        $categoryFilterService->destroyCategoryFilter($id);
        return redirect()->route('category-filters.index');
    }
}
