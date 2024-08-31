<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReviewService {

    public function store(Request $request, $customerId): void
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
            }

            $validated = $validator->validated();

            Review::create([
                'product_id' => $validated['product_id'],
                'customer_id' => $customerId,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            DB::commit();
        } catch (\InvalidArgumentException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::error('Failed to store review', [
                'error' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ]);

            abort(500, 'Failed to store review.');
        }
    }

    public function update(Request $request, $customerId): void
    {

    }

    public function delete(Request $request, $customerId): void
    {
        // try {
        //     DB::beginTransaction();

        //     $validator = Validator::make($request->all(), [
        //         'product_id' => 'required|exists:products,id',
        //     ]);

        //     if ($validator->fails()) {
        //         throw new \InvalidArgumentException('Validation failed: ' . $validator->errors()->first());
        //     }

        //     $validated = $validator->validated();

        //     $favourite = Favourite::where([
        //         'customer_id' => $customerId,
        //         'product_id' => $validated['product_id']
        //     ])->first();

        //     if (!$favourite) {
        //         throw new \InvalidArgumentException('Favourite item not found');
        //     }

        //     $favourite->delete();

        //     DB::commit();
        // } catch (\Throwable $exception) {
        //     DB::rollBack();

        //     Log::error('Failed to delete favourite item', [
        //         'error' => $exception->getMessage(),
        //         'stack' => $exception->getTraceAsString(),
        //     ]);

        //     abort(500, 'Failed to delete favourite item.');
        // }
    }
}
