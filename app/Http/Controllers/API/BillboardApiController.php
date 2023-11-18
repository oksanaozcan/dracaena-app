<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillboardResource;
use Illuminate\Http\Request;
use App\Models\Billboard;
use Illuminate\Http\Resources\Json\JsonResource;

class BillboardApiController extends Controller
{
    public function show($id): JsonResource
    {
        $billboard = Billboard::findOrFail($id);
        return new BillboardResource($billboard);
    }
}
