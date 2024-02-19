<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CategoryService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $categories = Category::search('title', $search)
        ->withCount('products')
        ->withCount('categoryFilters')
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $categories;
    }

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

    public function updateCategory($title, Category $category, $preview=null)
    {
        try {
            DB::beginTransaction();

            if ($preview) {
                $pathNewPreview = Storage::disk('public')->put('category_previews', $preview);
                $oldPreview = substr($category->preview, strlen(url('/storage/')) + 1);

                $category->update([
                    'title' => $title,
                    'preview' => url('/storage/' . $pathNewPreview),
                ]);

                Storage::disk('public')->delete($oldPreview);

            } else {
                $category->update([
                    'title' => $title,
                ]);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyCategory(Category $category)
    {
        try {
            DB::beginTransaction();

            $deletingPreview = substr($category->preview, strlen(url('/storage/')) + 1);

            $category->delete();
            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
