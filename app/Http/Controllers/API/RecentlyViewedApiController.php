<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RecentlyViewedService;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Auth;

class RecentlyViewedApiController extends Controller
{
    protected $recentlyViewedService;

    public function __construct(RecentlyViewedService $recentlyViewedService)
    {
        $this->recentlyViewedService = $recentlyViewedService;
    }

    public function store(Request $request)
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                $request->validate([
                    'product_id' => 'required|exists:products,id',
                ]);

                $this->recentlyViewedService->addRecentlyViewed($request->product_id, $customerId);

                return response()->json(['message' => 'Item added to recently viewed.']);
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function index(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $recentlyViewed = $this->recentlyViewedService->getRecentlyViewed($customer->id);
                $products = Product::whereIn('id', $recentlyViewed)->get();

                return ProductResource::collection($products);
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
