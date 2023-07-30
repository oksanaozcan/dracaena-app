<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagApiController extends Controller
{
    public function index()
    {
        $tag = Tag::find(1);
        return response()->json(['tag' => $tag], 200);
    }
}
