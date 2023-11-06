<?php

namespace App\Services;

use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientService {

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Client::create([
                'clerk_id' => $request->input("data.id"),
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

            $client = Client::where(["clerk_id" => $request->input("data.id")]);

            $client->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
