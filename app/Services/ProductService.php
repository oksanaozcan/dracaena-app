<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Image;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Filters\ProductFilter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    public function storeProduct($title, $preview, $description, $content=null, $price, $amount, $category_id, $tags=[], $images=[], $size, $product_group_by_size_id)
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
                'size' => $size,
                'product_group_by_size_id' => $product_group_by_size_id,
            ]);

            if (isset($tags)) {
                $p->tags()->attach($tags);
            }

            if (isset($images)) {
                foreach ($images as $img) {
                    $pathImg = Storage::disk('public')->put('product_images', $img);
                    Image::create([
                        'url' => url('/storage/' . $pathImg),
                        'product_id' => $p->id,
                    ]);
                }
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function updateProduct($title, Product $product, $preview=null, $description, $content=null, $price, $amount, $category_id, $tags=[], $images=[], $size, $product_group_by_size_id)
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
                    'size' => $size,
                    'product_group_by_size_id' => $product_group_by_size_id,
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
                    'size' => $size,
                    'product_group_by_size_id' => $product_group_by_size_id,
                ]);
            }

            if (isset($tags) && $product->tags->isEmpty()) {
                $product->tags()->attach($tags);
            }
            if (isset($tags) && $product->tags->isNotEmpty()) {
                $product->tags()->sync($tags);
            }

            if (isset($images) && $product->images->isEmpty()) {
                foreach ($images as $img) {
                    $pathImg = Storage::disk('public')->put('product_images', $img);
                    Image::create([
                        'url' => url('/storage/' . $pathImg),
                        'product_id' => $product->id,
                    ]);
                }
            }

            if (isset($images) && $product->images->isNotEmpty()) {
                $oldImageIds = $product->images->pluck('id')->toArray();

                foreach ($oldImageIds as $id) {
                    $img = Image::find($id);
                    if ($img) {
                        $deletingImg = substr($img->url, strlen(url('/storage/')) + 1);
                        Storage::disk('public')->delete($deletingImg);

                        $img->delete();
                    }
                }

                foreach ($images as $img) {
                    $pathImg = Storage::disk('public')->put('product_images', $img);
                    Image::create([
                        'url' => url('/storage/' . $pathImg),
                        'product_id' => $product->id,
                    ]);
                }
            }

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    //TODO: remove images after removing its product
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

    public function getCachedProducts(array $validated, ProductFilter $filter): \Illuminate\Pagination\LengthAwarePaginator
    {   $cacheKey = $this->generateCacheKey($validated);

        return Cache::tags('products')->remember($cacheKey, now()->addHours(2), function () use ($validated, $filter) {
            return Product::filter($filter)->paginate(8, ['*'], 'page', $validated['page']);
        });
    }


    protected function generateCacheKey(array $validated): string
    {
        $page = $validated['page'] ?? '';
        $categoryId = $validated['category_id'] ?? '';
        $tagId = $validated['tag_id'] ?? '';
        $search = $validated['search'] ?? '';
        $sort = $validated['sort'] ?? '';
        $categoryFilterId = $validated['category_filter_id'] ?? '';

        $page = "page.{$page}.category_id.{$categoryId}.tag_id.{$tagId}.search.{$search}.sort.{$sort}.category_filter_id.{$categoryFilterId}";

        return $page;
    }

    public function destroyProductByTitle($title)
    {
        $product = Product::where('title', $title)->first();

        if ($product) {
            $this->destroyProduct($product);
        } else {
            // Handle case where product with given title doesn't exist
        }
    }
}
