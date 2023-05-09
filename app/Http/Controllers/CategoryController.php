<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category.index');
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        /** @see App\Http\Livewire\Category\CreateForm */
    }

    public function show($id)
    {
        $category = Category::find($id);
        return view('category.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $id = $category->id;
        return view('category.edit', compact('id'));
    }

    public function update(Request $request, Category $category)
    {
        /** @see App\Http\Livewire\Category\CreateForm */
    }

    public function destroy($id, CategoryService $categoryService)
    {
        $categoryService->destroyCategory($id);
        return redirect()->route('categories.index');
        /**
         * destroy from page category.index
         * @see App\Http\Livewire\Category\Table
        * */
    }
}
