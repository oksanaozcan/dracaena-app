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

    public function storeProduct($title, $preview, $description, $content=null, $price, $amount, $category_id, $tags=[])
    {
        try {
            DB::beginTransaction();
            $pathPreview = Storage::disk('public')->put('product_previews', $preview);

            $p = Product::create([
                'title' => $title,
                'preview' => url('/storage/' . $pathPreview),
                'description' => $description,
                'content' => $content,
                'price' => $price,
                'amount' => $amount,
                'category_id' => $category_id,
            ]);

            if (isset($tags)) {
                $p->tags()->attach($tags);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateProduct($title, Product $product, $preview=null, $description, $content=null, $price, $amount, $category_id, $tags=[])
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

            if (isset($tags) && $product->tags->isEmpty()) {
                $product->tags()->attach($tags);
            }
            if (isset($tags) && $product->tags->isNotEmpty()) {
                $product->tags()->sync($tags);
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function destroyProduct(Product $product)
    {
        try {
            DB::beginTransaction();

            $product->tags()->detach();

            $deletingPreview = substr($product->preview, strlen(url('/storage/')) + 1);

            $product->delete();
            Storage::disk('public')->delete($deletingPreview);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
