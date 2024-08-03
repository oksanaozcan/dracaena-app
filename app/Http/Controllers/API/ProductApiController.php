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
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    public function __construct(protected ProductService $productService){}

    public function index (IndexRequest $request): JsonResource
    {
        $validated = $request->validated();

        if (!isset($validated['sort'])) {
            $validated['sort'] = 'date-desc-rank';
        }

        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($validated)]);

        $products = $this->productService->getCachedProducts($validated, $filter);

        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function cart(Request $request): JsonResource
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if (!$customer) {
                abort(404);
            } else {
                $products = $customer->cartProducts;
                return ProductResource::collection($products);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function favourites(Request $request): JsonResource|JsonResponse
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

    public function restokeSubscriptions(Request $request): JsonResource|JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if (!$customer) {
                abort(404);
            } else {
                $restokeProducts = $customer->restokeProducts;
                return ProductResource::collection($restokeProducts);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
