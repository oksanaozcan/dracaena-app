<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillboardResource;
use Illuminate\Http\Request;
use App\Models\Billboard;

class BillboardApiController extends Controller
{
    public function index()
    {
        return BillboardResource::collection(Billboard::all());
    }
}
