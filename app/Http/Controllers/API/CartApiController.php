<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class CartApiController extends Controller
{
    public function store(Request $request, CartService $cartService): JsonResponse
    {
        try {
            $cartService->store($request);

            return response()->json(['message' => 'Cart item added successfully'], 201);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Cart item addition failed', 'message' => $exception->getMessage()], 500);
        }
    }
}
