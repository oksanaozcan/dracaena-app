<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Cache::rememberForever('products:all', function () {
            return Product::all();
        })->each(function($product) {
            Cache::put('products:'.$product->id, $product);
        });
    }

    public function show($id)
    {
        $product = Cache::get('products:'.$id);
    }
}
