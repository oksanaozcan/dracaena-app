<?php

namespace App\Services;

use App\Models\Favourite;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FavouriteService {

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
                throw new \InvalidArgumentException('out_of_stock');
            }

            Favourite::create([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id'],
            ]);

            DB::commit();
        } catch (\InvalidArgumentException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to store favourite item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to store favourite item.');
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

            $favourite = Favourite::where([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id']
            ])->first();

            if (!$favourite) {
                throw new \InvalidArgumentException('Favourite item not found');
            }

            $favourite->delete();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to delete favourite item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to delete favourite item.');
        }
    }
}
