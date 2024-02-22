<?php

namespace App\Services;

use App\Models\CategoryFilter;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryFilterService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $categoryFilters = CategoryFilter::search('title', $search)
        ->withCount('tags')
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $categoryFilters;
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

    public function destroyCategoryFilter(CategoryFilter $categoryFilter)
    {
        try {
            DB::beginTransaction();

            $categoryFilter->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
