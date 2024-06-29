<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Favourite\DeleteRequest;
use App\Services\FavouriteService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavouriteApiController extends Controller
{
    public function store(Request $request, FavouriteService $favouriteService): JsonResponse
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                try {
                    $favouriteService->store($request, $customerId);
                    return response()->json(['message' => 'Favourite item added successfully'], 201);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Favourite item addition failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function delete(Request $request, FavouriteService $favouriteService): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    $favouriteService->delete($request, $customer->id);
                    return response()->json(['message' => 'Favourite item deleted successfully'], 200);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Favourite item deletion failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
