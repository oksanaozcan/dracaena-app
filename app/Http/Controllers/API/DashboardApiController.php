<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    public function myOrders (Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            try {
                $customer = Auth::guard('api')->user();

                if ($customer) {
                    $orders = $customer->orders;
                    return response()->json([
                        'orders' => $orders ? OrderResource::collection($orders) : null,
                ], 200);
                } else {
                    return response()->json(['authenticated' => false], 401);
                }
            } catch (\Exception $e) {
                return response()->json(['authenticated' => false, 'error' => $e->getMessage()], 401);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
