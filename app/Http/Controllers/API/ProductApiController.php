<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\API\Product\IndexRequest;
use App\Models\Product;
use App\Models\Client;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ProductService;

class ProductApiController extends Controller
{
    public function __construct(protected ProductService $productService){}

    public function index (IndexRequest $request): JsonResource
    {
        $validated = $request->validated();
        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($validated)]);

        $products = $this->productService->getCachedProducts($validated, $filter);

        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function cart($userId): JsonResource
    {
        $client = Client::where('clerk_id', $userId)->first();

        if (!$client) {
            abort(404);
        } else {
            $products = Product::whereHas('carts', function ($query) use ($userId) {
                $query->where('client_id', $userId);
            })->get();

            return ProductResource::collection($products);
        }
    }
}
