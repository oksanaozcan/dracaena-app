<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ClientService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Client;

class ClientApiController extends Controller
{
    public function processRequest (Request $request)
    {
        if ($request->input('type') === 'user.created') {
            return $this->store($request);
        } elseif ($request->input('type') === 'user.deleted') {
            return $this->delete($request);
        }
    }
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
