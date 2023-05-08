<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CategoryService {

    public function storeCategory($title, $preview)
    {
        try {
            DB::beginTransaction();
            $pathPreview = Storage::disk('public')->put('category_previews', $preview);

            Category::create([
                'title' => $title,
                'preview' => url('/storage/' . $pathPreview),
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
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
