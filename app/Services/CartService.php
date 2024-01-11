<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\Cart\StoreRequest;
use Illuminate\Support\Facades\Log;

class CartService {

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();

            $validated = $request->validated();

            Cart::create([
                'client_id' => $validated['client_id'],
                'product_id' => $validated['product_id'],
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            $userId = $request->input('userId');
            $productId = $request->input('productId');

            $cart = Cart::where([
                "client_id" => $userId,
                "product_id" => $productId
            ]);

            $cart->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
