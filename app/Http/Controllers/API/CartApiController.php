<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Cart\StoreRequest;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CartApiController extends Controller
{
    public function store(StoreRequest $request, CartService $cartService): JsonResponse
    {
        try {
            $cartService->store($request);

            return response()->json(['message' => 'Cart item added successfully'], 201);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Cart item addition failed', 'message' => $exception->getMessage()], 500);
        }
    }

    public function delete(Request $request, CartService $cartService): JsonResponse
    {
        try {
            $cartService->delete($request);

            return response()->json(['message' => 'Item removed from cart successfully'], 201);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Cart item removing failed', 'message' => $exception->getMessage()], 500);
        }
    }
}
