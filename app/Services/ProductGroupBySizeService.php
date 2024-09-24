<?php

namespace App\Services;

use App\Models\ProductGroupBySize;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductGroupBySizeService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $productGroupBySizes = ProductGroupBySize::search('title', $search)
        ->withCount('products')
        ->orderBy($sortField, $sortDirection)
        ->paginate(15);

        return $productGroupBySizes;
    }

    public function storeProductGroupBySize($title)
    {
        try {
            DB::beginTransaction();

            ProductGroupBySize::create([
                'title' => $title,
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateProductGroupBySize($title, ProductGroupBySize $productGroupBySize)
    {
        try {
            DB::beginTransaction();
                $productGroupBySize->update([
                    'title' => $title,
                ]);
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyProductGroupBySize(ProductGroupBySize $productGroupBySize)
    {
        try {
            DB::beginTransaction();
            $productGroupBySize->delete();
            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
