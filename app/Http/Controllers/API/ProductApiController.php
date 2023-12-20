<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    public function processRequest (Request $request)
    {
        $categoryId = $request->query('category_id');
        $tagId = $request->query('tag_id');
        $search = $request->query('search');

        if ($categoryId) {
            return $this->getProductsByCategory($categoryId);
        }
        if ($tagId) {
            return $this->getProductsByTag($tagId);
        }
        if ($search) {
            return $this->getProductsBySearch($search);
        }
        if (!$tagId && !$categoryId && !$search) {
            return $this->index($request);
        }
    }

    public function getProductsByCategory($categoryId): JsonResource
    {
        $products = Product::where('category_id', $categoryId)
        // ->orderBy('price')
        ->paginate(8);
        return ProductResource::collection($products);
    }

    public function getProductsByTag($tagId): JsonResource
    {
        $products = Product::whereHas('tags', function ($query) use ($tagId) {
            $query->where('tag_id', $tagId);
        })
        // ->orderBy('price')
        ->paginate();
        return ProductResource::collection($products);
    }

    public function getProductsBySearch($search): JsonResource
    {
        $products = Product::where('title', 'like', '%'.$search.'%')->paginate(8);
        return ProductResource::collection($products);
    }

    public function index(Request $request): JsonResource
    {
        $products = Product::paginate(8);
        return ProductResource::collection($products);
    }

    public function show($id): JsonResource
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }

    public function cart($userId)
    {
         $products = Product::whereHas('carts', function ($query) use ($userId) {
            $query->where('client_id', $userId);
        })->get();

        return ProductResource::collection($products);
    }
}
