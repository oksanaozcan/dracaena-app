<?php

namespace App\Http\Controllers;

use App\Models\ProductGroupBySize;
use Illuminate\Http\Request;
use App\Services\ProductGroupBySizeService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

 /** @see realization of store and update method to App\Http\Livewire\ProductGroupBySize\CreateForm */
    /**destroy from page product-group-by-size.index @see App\Http\Livewire\ProductGroupBySize\Table * */

class ProductGroupBySizeController extends Controller
{
    public function __construct(public ProductGroupBySizeService $productGroupBySizeService)
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(): View
    {
        return view('product-group-by-size.index');
    }

    public function create(): View
    {
        $this->authorize('create', ProductGroupBySize::class);
        return view('product-group-by-size.create');
    }

    public function show(ProductGroupBySize $productGroupBySize): View
    {
        return view('product-group-by-size.show', compact('productGroupBySize'));
    }

    public function edit(ProductGroupBySize $productGroupBySize): View
    {
        $this->authorize('update', $productGroupBySize);
        $id = $productGroupBySize->id;
        return view('product-group-by-size.edit', compact('id'));
    }

    public function destroy(ProductGroupBySize $productGroupBySize): RedirectResponse
    {
        $this->authorize('delete', $productGroupBySize);
        $this->productGroupBySizeService->destroyProductGroupBySize($productGroupBySize);
        return redirect()->route('product-group-by-sizes.index');
    }
}
