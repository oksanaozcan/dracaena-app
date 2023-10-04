<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NavigationResource;
use App\Models\Category;

class NavigationApiController extends Controller
{
    public function getCategoryWithFiltersAndTags()
    {
        $categories = Category::with(['categoryFilters', 'tags'])->get();
        return NavigationResource::collection($categories);
    }
}
