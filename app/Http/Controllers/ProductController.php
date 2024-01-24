<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

 /** @see realization of store and update method to App\Http\Livewire\Product\CreateForm */
    /**destroy from page category.index @see App\Http\Livewire\Product\Table * */

class ProductController extends Controller
{
    public function __construct(public ProductService $productService)
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('product.index');
    }

    public function create(): View
    {
        $this->authorize('create', Product::class);
        return view('product.create');
    }

    public function show(Product $product): View
    {
        return view('product.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);
        $id = $product->id;
        return view('product.edit', compact('id'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);
        $this->productService->destroyProduct($product);
        return redirect()->route('products.index');
    }
}
