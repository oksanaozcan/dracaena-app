<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService {

    public function storeProduct($title, $preview)
    {
        try {
            DB::beginTransaction();
            $pathPreview = Storage::disk('public')->put('product_previews', $preview);

            Product::create([
                'title' => $title,
                'preview' => url('/storage/' . $pathPreview),
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateProduct($title, Product $product, $preview=null)
    {
        try {
            DB::beginTransaction();

            if ($preview) {
                $pathNewPreview = Storage::disk('public')->put('product_previews', $preview);
                $oldPreview = substr($product->preview, strlen(url('/storage/')) + 1);

                $product->update([
                    'title' => $title,
                    'preview' => url('/storage/' . $pathNewPreview),
                ]);

                Storage::disk('public')->delete($oldPreview);

            } else {
                $product->update([
                    'title' => $title,
                ]);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyProduct($id)
    {
        try {
            DB::beginTransaction();

            $deletingProd = Product::find($id);
            $deletingPreview = substr($deletingProd->preview, strlen(url('/storage/')) + 1);

            $deletingProd->delete();
            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyProductByTitle($title)
    {
        try {
            DB::beginTransaction();

            $deletingPreview = DB::table('products')->where('title', $title)->pluck('preview')->first();
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
