<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    public function store(Request $request, ReviewService $reviewService): JsonResponse
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                try {
                    $reviewService->store($request, $customerId);
                    return response()->json(['message' => 'Review added successfully'], 201);
                } catch (\InvalidArgumentException $exception) {
                    return response()->json(['error' => $exception->getMessage()], 400);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Review addition failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function update(Request $request, ReviewService $reviewService): JsonResponse
    {
        $token = $request->bearerToken();
        if($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                $customerId = $customer->id;

                try {
                    $reviewService->update($request, $customerId);
                    return response()->json(['message' => 'Review updated successfully'], 201);
                } catch (\InvalidArgumentException $exception) {
                    return response()->json(['error' => $exception->getMessage()], 400);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Review updating failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function delete(Request $request, ReviewService $reviewService): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            $customer = Auth::guard('api')->user();
            if ($customer) {
                try {
                    $reviewService->delete($request, $customer->id);
                    return response()->json(['message' => 'Review deleted successfully'], 200);
                } catch (Exception $exception) {
                    return response()->json(['error' => 'Review deletion failed', 'message' => $exception->getMessage()], 500);
                }
            } else {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            return response()->json(['authenticated' => false], 401);
        }
    }
}
