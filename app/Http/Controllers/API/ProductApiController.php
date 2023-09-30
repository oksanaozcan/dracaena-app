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

        if ($categoryId) {
            $products = Product::where('category_id', $categoryId)
            // ->orderBy('price')
            ->get();
        } else {
            $products = Cache::rememberForever('products:all', function () {
                return Product::all();
            })->each(function($product) {
                Cache::put('products:'.$product->id, $product);
            });
        }

        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        $product = Cache::get('products:'.$id);
        return new ProductResource($product);
    }
}
