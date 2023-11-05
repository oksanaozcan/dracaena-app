<?php

namespace App\Services;

use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartService {

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info($request);

            $validated = $request->validate([
                'client_id' => 'required|string',
                'product_id' => 'required',
            ]);

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
