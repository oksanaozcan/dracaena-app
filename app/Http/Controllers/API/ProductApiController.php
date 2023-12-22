<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
// use Illuminate\Http\Request;
use App\Http\Requests\API\Product\IndexRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    public function index (IndexRequest $request): JsonResource
    {
        $validated = $request->validated();

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($validated)]);
        $products = Product::filter($filter)->paginate(8);

        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }

    public function cart($userId): JsonResource
    {
         $products = Product::whereHas('carts', function ($query) use ($userId) {
            $query->where('client_id', $userId);
        })->get();

        return ProductResource::collection($products);
    }
}
