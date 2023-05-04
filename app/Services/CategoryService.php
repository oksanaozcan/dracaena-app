<?php

namespace App\Services;

use App\Models\Category;

class CategoryService {

    public function storeCategory($title)
    {
        Category::create([
            'title' => $title,
        ]);
    }

    public function updateCategory($title, Category $category)
    {
        Category::find($category->id)->update([
            'title' => $title,
        ]);
    }

    public function destroyCategory($id)
    {
        Category::find($id)->delete();
    }

    public function destroyCategoryByTitle($title)
    {
        Category::where('title', $title)->delete();
    }
}
