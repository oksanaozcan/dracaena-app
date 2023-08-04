<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService {

    public function searchForTable($search, $sortField, $sortDirection)
    {
        $products = Product::with(['category' => function ($query) {
            $query->select('id','title');
        },
        'tags' => function ($query) {
            $query->select('tag_id','title');
        }])->search('title', $search)->orderBy($sortField, $sortDirection)->paginate(15);

        return $products;
    }

    public function findById($id)
    {
        return Product::with('category', 'tags')->findOrFail($id);
    }

    public function storeProduct($title, $preview, $description, $content=null, $price, $amount, $category_id)
    {
        try {
            DB::beginTransaction();
            $pathPreview = Storage::disk('public')->put('product_previews', $preview);

            Product::create([
                'title' => $title,
                'preview' => url('/storage/' . $pathPreview),
                'description' => $description,
                'content' => $content,
                'price' => $price,
                'amount' => $amount,
                'category_id' => $category_id,
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateProduct($title, Product $product, $preview=null, $description, $content=null, $price, $amount, $category_id)
    {
        try {
            DB::beginTransaction();

            if ($preview) {
                $pathNewPreview = Storage::disk('public')->put('product_previews', $preview);
                $oldPreview = substr($product->preview, strlen(url('/storage/')) + 1);

                $product->update([
                    'title' => $title,
                    'preview' => url('/storage/' . $pathNewPreview),
                    'description' => $description,
                    'content' => $content,
                    'price' => $price,
                    'amount' => $amount,
                    'category_id' => $category_id,
                ]);

                Storage::disk('public')->delete($oldPreview);

            } else {
                $product->update([
                    'title' => $title,
                    'description' => $description,
                    'content' => $content,
                    'price' => $price,
                    'amount' => $amount,
                    'category_id' => $category_id,
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

            Product::where('title', $title)->delete();

            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
