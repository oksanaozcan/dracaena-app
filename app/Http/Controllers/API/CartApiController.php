<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function store(Request $request, CartService $cartService): JsonResponse
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                try {
                    $cartService->store($request, $customerId);
                    return response()->json(['message' => 'Cart item added successfully'], 201);
                } catch (\InvalidArgumentException $exception) {
                    return response()->json(['error' => 'Cart item addition failed', 'message' => $exception->getMessage()], 400);
                } catch (\Exception $exception) {
                    return response()->json(['error' => 'Cart item addition failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function delete(Request $request, CartService $cartService): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    $cartService->delete($request, $customer->id);
                    return response()->json(['message' => 'Cart item deleted successfully'], 200);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Cart item deletion failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
