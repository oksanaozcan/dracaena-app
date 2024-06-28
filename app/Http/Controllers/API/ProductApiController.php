<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\API\Product\IndexRequest;
use App\Models\Product;
use App\Models\Client;
use App\Models\Customer;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function favourites(Request $request): JsonResource //or JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if (!$customer) {
                abort(404);
            } else {
                $favoriteProducts = $customer->favoriteProducts;
                return ProductResource::collection($favoriteProducts);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
