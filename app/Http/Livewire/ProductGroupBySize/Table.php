<?php

namespace App\Http\Livewire\ProductGroupBySize;

use Livewire\Component;
use App\Models\ProductGroupBySize;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\ProductGroupBySizeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $selectedProductGroupBySize;

    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $queryString = ['sortField', 'sortDirection'];

    protected $listeners = [
        'productGroupBySizeAdded' => 'render',
        'deletedProductGroupBySizes' => 'render'
    ];

    public function editProductGroupBySize($id)
    {
        return redirect()->route('product-group-by-sizes.edit', $id);
    }

    public function destroyProductGroupBySize($id, ProductGroupBySizeService $productGroupBySizeService)
    {
        $productGroupBySize = ProductGroupBySize::find($id);
        $this->authorize('delete', $productGroupBySize);
        if ($productGroupBySize->products->count() == 0) {
            $productGroupBySizeService->destroyProductGroupBySize($productGroupBySize);
            $this->emit('deletedProductGroupBySizes');
        } else {
            $this->flushMessage($productGroupBySize);
        }
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

    public function render(ProductGroupBySizeService $productGroupBySizeService)
    {
        $ProductGroupBySizes = $productGroupBySizeService->searchForTable(null, $this->sortField, $this->sortDirection);
        return view('livewire.product-group-by-size.table', [
            'productGroupBySizes' => $ProductGroupBySizes,
            'count' => ProductGroupBySize::count()
        ]);
    }

    public function selectProductGroupBySize(int $productGroupBySizeId)
    {
        $this->selectedProductGroupBySize = $productGroupBySizeId;
    }

    public function flushMessage(ProductGroupBySize $pg)
    {
        session()->flash('message', 'You can not delete this ProductGroupBySize right now because that has relationships with several products. Please be sure to look at the details.');
        return redirect()->route("product-group-by-sizes.show", $pg);
    }
}
