<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Favourite\StoreRequest;
use App\Http\Requests\API\Favourite\DeleteRequest;
use App\Services\FavouriteService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class FavouriteApiController extends Controller
{
    public function store(StoreRequest $request, FavouriteService $favouriteService): JsonResponse
    {
        try {
            $favouriteService->store($request);

            return response()->json(['message' => 'Favourite item added successfully'], 201);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Favourite item addition failed', 'message' => $exception->getMessage()], 500);
        }
    }

    public function delete(DeleteRequest $request, FavouriteService $favouriteService): JsonResponse
    {
        try {
            $favouriteService->delete($request);

            return response()->json(['message' => 'Favourite item deleted successfully'], 201);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Favourite item deletion failed', 'message' => $exception->getMessage()], 500);
        }
    }
}
