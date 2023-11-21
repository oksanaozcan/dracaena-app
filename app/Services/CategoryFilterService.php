<?php

namespace App\Services;

use App\Models\CategoryFilter;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryFilterService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $categoryFilters = CategoryFilter::search('title', $search)
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $categoryFilters;
    }

    public function findById($id)
    {
        return CategoryFilter::findOrFail($id);
    }

    public function storeCategoryFilter($title, $category_id)
    {
        try {
            DB::beginTransaction();

            CategoryFilter::create([
                'title' => $title,
                'category_id' => $category_id,
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateCategoryFilter($title, CategoryFilter $categoryFilter, $category_id)
    {
        try {
            DB::beginTransaction();

            $categoryFilter->update([
                'title' => $title,
                'category_id' => $category_id
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyCategoryFilter($id)
    {
        try {
            DB::beginTransaction();

            $deletingCf = CategoryFilter::find($id);

            $deletingCf->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}