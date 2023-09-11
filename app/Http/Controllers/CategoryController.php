<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('category.index');
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);
        return view('category.create');
    }

    public function store(Request $request): View
    {
        /** @see App\Http\Livewire\Category\CreateForm */
    }

    public function show($id, CategoryService $categoryService): View
    {
        $category = $categoryService->findById($id);
        return view('category.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);
        $id = $category->id;
        return view('category.edit', compact('id'));
    }

    public function update(Request $request, Category $category)
    {
        /** @see App\Http\Livewire\Category\CreateForm */
    }

    public function destroy($id, CategoryService $categoryService): RedirectResponse
    {
        $category = Category::find($id);
        $this->authorize('delete', $category);
        $categoryService->destroyCategory($id);
        return redirect()->route('categories.index');
        /**
         * destroy from page category.index
         * @see App\Http\Livewire\Category\Table
        * */
    }
}
