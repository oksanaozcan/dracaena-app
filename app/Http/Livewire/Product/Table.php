<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedProduct;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $checkedTitles = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'productAdded' => 'render',
        'deletedProducts' => 'render'
    ];

    public function updated()
    {
        $this->emit('checkedTitlesUpdated', $this->checkedTitles);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editProduct($id)
    {
        return redirect()->route('products.edit', $id);
    }

    public function destroyProduct($id, ProductService $productService)
    {
        $p = Product::find($id);
        $this->authorize('delete', $p);
        $productService->destroyProduct($p);
        $this->emit('deletedProducts');
    }

    public function sortBy($columnHeader)
    {
        if ($this->sortField === $columnHeader) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $columnHeader;
    }

    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render(ProductService $productService)
    {
        $products = $productService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.product.table', [
            'products' => $products,
            'count' => Product::count()
        ]);
    }

    public function selectProduct(int $productId)
    {
        $this->selectedProduct = $productId;
    }
}
