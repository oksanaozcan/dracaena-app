<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderResource;

class DashboardApiController extends Controller
{
    public function myOrders ($userId): JsonResource
    {
        $client = Client::where('clerk_id', $userId)->first();

        if (!$client) {
            abort(404);
        } else {
            $orders = Order::where('client_id', $userId)->get();
            return OrderResource::collection($orders);
        }
    }
}
