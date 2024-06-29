<?php

namespace App\Services;

use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartService {

    public function store(Request $request, $customerId): void
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
            }

            $validated = $validator->validated();

            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id'],
            ]);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to store cart item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to store cart item.');
        }
    }

    public function delete(Request $request, $customerId): void
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
            }

            $validated = $validator->validated();

            $cartItem = Cart::where([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id']
            ])->first();

            if (!$cartItem) {
                throw new \InvalidArgumentException('Cart item not found');
            }

            $cartItem->delete();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to delete cart item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to delete cart item.');
        }
    }
}
