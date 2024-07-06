<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\RestokeSubscriptionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RestokeSubscriptionApiController extends Controller
{
    public function store(Request $request, RestokeSubscriptionService $rss): JsonResponse
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                try {
                    $rss->store($request, $customerId);
                    return response()->json(['message' => 'Restoke subscription item added successfully'], 201);
                } catch (\InvalidArgumentException $exception) {
                    return response()->json(['error' => $exception->getMessage()], 400);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Restoke subscription item addition failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function delete(Request $request, RestokeSubscriptionService $rss): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    $rss->delete($request, $customer->id);
                    return response()->json(['message' => 'Restoke subscription item deleted successfully'], 200);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Restoke subscription item deletion failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
