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

    public function destroyCategory($id)
    {
        try {
            DB::beginTransaction();

            $deletingCat = Category::find($id);
            $deletingPreview = substr($deletingCat->preview, strlen(url('/storage/')) + 1);

            $deletingCat->delete();
            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyCategoryByTitle($title)
    {
        try {
            DB::beginTransaction();

            $deletingPreview = DB::table('categories')->where('title', $title)->pluck('preview')->first();
            $deletingPreview = substr($deletingPreview, strlen(url('/storage/')) + 1);

            Category::where('title', $title)->delete();

            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
