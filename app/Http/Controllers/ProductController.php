<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function index(): View
    {
        return view('product.index');
    }

    public function create(): View
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
         /** @see App\Http\Livewire\Product\CreateForm */
    }

    public function show(Product $product): View
    {
        return view('product.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $id = $product->id;
        return view('product.edit', compact('id'));
    }

    public function update(Request $request, Product $product)
    {
         /** @see App\Http\Livewire\Product\CreateForm */
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->destroyProduct($product);
        return redirect()->route('products.index');
          /**
         * destroy from page product.index
         * @see App\Http\Livewire\Product\Table
        * */
    }
}
