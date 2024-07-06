<?php

namespace App\Services;

use App\Models\RestokeSubscription;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RestokeSubscriptionService {

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
            if ($product && $product->amount > 0) {
                throw new \InvalidArgumentException('in_stock');
            }

            RestokeSubscription::create([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id'],
            ]);

            DB::commit();
        } catch (\InvalidArgumentException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to store restoke subscription item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to store restoke subscription item.');
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

            $rsItem = RestokeSubscription::where([
                'customer_id' => $customerId,
                'product_id' => $validated['product_id']
            ])->first();

            if (!$rsItem) {
                throw new \InvalidArgumentException('Restoke subscription item not found');
            }

            $rsItem->delete();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to delete Restoke subscription item', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to delete Restoke subscription item.');
        }
    }
}
