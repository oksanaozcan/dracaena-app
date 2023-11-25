<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillboardResource;
use Illuminate\Http\Request;
use App\Models\Billboard;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class BillboardApiController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $categoryId = $request->query('category_id');
        $tagId = $request->query('tag_id');

        if ($categoryId) {
            $billboard = Billboard::where('category_id', $categoryId)->first();
        }
        if ($tagId) {
            $billboard = Billboard::whereHas('tags', function ($query) use ($tagId) {
                $query->where('tag_id', $tagId);
            })
            ->first();

            if ($billboard == null) {
                $cId = Tag::find($tagId)->category()->id;
                $bc = Billboard::where('category_id', $cId)->first();
                return new BillboardResource($bc);
            }
        }
        if (!$categoryId && !$tagId) {
            $billboard = Billboard::findOrFail(1);
        }

        return new BillboardResource($billboard);
    }
}
