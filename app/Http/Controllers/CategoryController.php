<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

 /** @see realization of store and update method to App\Http\Livewire\Category\CreateForm */
    /**destroy from page category.index @see App\Http\Livewire\Category\Table * */

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService)
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

    public function show(Category $category): View
    {
        return view('category.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);
        $id = $category->id;
        return view('category.edit', compact('id'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);
        $this->categoryService->destroyCategory($category);
        return redirect()->route('categories.index');
    }
}
