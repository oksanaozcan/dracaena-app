<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
         /** @see App\Http\Livewire\Product\CreateForm */
    }

    public function show(string $id)
    {
        $product = Product::find($id);
        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $id = $product->id;
        return view('product.edit', compact('id'));
    }

    public function update(Request $request, Product $product)
    {
         /** @see App\Http\Livewire\Product\CreateForm */
    }

    public function destroy($id, ProductService $productService)
    {
        $productService->destroyProduct($id);
        return redirect()->route('products.index');
          /**
         * destroy from page product.index
         * @see App\Http\Livewire\Product\Table
        * */
    }
}
