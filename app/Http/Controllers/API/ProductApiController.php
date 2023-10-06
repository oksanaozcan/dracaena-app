<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $categoryId = $request->query('category_id');
        $tagId = $request->query('tag_id');

        if ($categoryId) {
            $products = Product::where('category_id', $categoryId)
            // ->orderBy('price')
            ->get();
        }

        if ($tagId) {
            $products = Product::whereHas('tags', function ($query) use ($tagId) {
                $query->where('tag_id', $tagId);
            })
            // ->orderBy('price')
            ->get();
        }

        if (!$tagId && !$categoryId) {
            $products = Product::all();
        }

        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        // $product = Cache::get('products:'.$id);
        $product = Product::find($id);
        return new ProductResource($product);
    }
}
