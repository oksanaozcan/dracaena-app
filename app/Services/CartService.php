<?php

namespace App\Services;

use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

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

            $product = Product::find($validated['product_id']);
            if ($product && $product->amount == 0) {
                throw new \InvalidArgumentException('out_of_stock'); // Specific error code/message
            }

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

            if ($exception instanceof \InvalidArgumentException) {
                throw $exception; // Re-throw to handle specifically in controller
            }

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
