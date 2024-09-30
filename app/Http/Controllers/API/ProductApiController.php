<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilter;
use App\Http\Requests\API\Product\IndexRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Client;
use App\Models\Customer;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductWithCategoryAndFilterResource;
use App\Http\Resources\ProductWithImagesResource;
use App\Http\Resources\ProductWithReviewResource;
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

    public function getProductsForCareCategorySlider(): JsonResource
    {
        $careCategory = Category::where('title', 'care')->first();

        if (!$careCategory) {
            return response()->json(['error' => 'Care category not found'], 404);
        }

        $products = collect();

        foreach ($careCategory->categoryFilters as $filter) {
            $filteredProducts = Product::join('product_tags', 'products.id', '=', 'product_tags.product_id')
                ->join('tags', 'product_tags.tag_id', '=', 'tags.id')
                ->join('category_filters', 'tags.category_filter_id', '=', 'category_filters.id')
                ->where('category_filters.title', $filter->title)
                ->orderBy('products.created_at', 'desc')
                ->take(3)
                ->get([
                    'products.*',
                    'category_filters.id as category_filter_id',
                    'category_filters.title as category_filter_title'
                ]);

            $products = $products->merge($filteredProducts);
        }

        return ProductWithCategoryAndFilterResource::collection($products);
    }

    public function show($id): JsonResource|JsonResponse
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductWithImagesResource($product);
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

    public function perchased(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if (!$customer) {
                abort(404);
            } else {
                $perchasedProducts = $customer->perchasedProducts();
                return ProductWithReviewResource::collection($perchasedProducts);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function getRelatedProducts (Request $request): JsonResource|JsonResponse
    {
        $category = $request->input('category');
        $size = $request->input('size');

        if ($category === 'houseplants') {
            $relatedCat = Category::where('title', 'pots')->first();

            if ($relatedCat) {
                $relatedProducts = Product::where('category_id', $relatedCat->id)
                    ->where('size', $size)
                    ->limit(10)
                    ->get();

                return ProductResource::collection($relatedProducts);
            }

        } elseif ($category === 'pots') {
            $relatedCat = Category::where('title', 'houseplants')->first();

            if ($relatedCat) {
                $relatedProducts = Product::where('category_id', $relatedCat->id)
                    ->where('size', $size)
                    ->limit(10)
                    ->get();

                return ProductResource::collection($relatedProducts);
            }

        } else {
            return response()->json([
                'message' => 'No related products available for this category.'
            ]);
        }

        return response()->json([
            'message' => 'Related products not found.'
        ], 404);
    }
}
