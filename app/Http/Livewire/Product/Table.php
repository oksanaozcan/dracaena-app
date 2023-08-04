<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\ProductService;

class Table extends Component
{
    use WithPagination;

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
        $productService->destroyProduct($id);
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

    public function render()
    {
        return view('livewire.product.table', [
            'products' => Product::with(['category' => function ($query) {
                $query->select('id','title');
            },
            'tags' => function ($query) {
                $query->select('tag_id','title');
            }])->search('title', $this->search)->orderBy($this->sortField, $this->sortDirection)->paginate(15),
            'count' => Product::count()
        ]);
    }

    public function selectProduct(int $productId)
    {
        $this->selectedProduct = $productId;
    }
}
