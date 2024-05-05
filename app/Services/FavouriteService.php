<?php

namespace App\Services;

use App\Models\Favourite;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\API\Favourite\StoreRequest;
use App\Http\Requests\API\Favourite\DeleteRequest;
use Illuminate\Support\Facades\Log;

class FavouriteService {

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();

            $validated = $request->validated();

            Favourite::create([
                'client_id' => $validated['client_id'],
                'product_id' => $validated['product_id'],
            ]);

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function delete(DeleteRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $favourite = Favourite::where([
                "client_id" => $validated['userId'],
                "product_id" => $validated['productId']
            ])->first();

            if (!$favourite) {
                return abort(404, 'Favourite item not found');
            }

            $favourite->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
