<?php

namespace App\Http\Livewire\ProductGroupBySize;

use Livewire\Component;
use App\Models\ProductGroupBySize;
use App\Services\ProductGroupBySizeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateForm extends Component
{
    use AuthorizesRequests;

    public $productGroupBySize;
    public $title;

    protected $rules = [
        'title' => 'required|string|unique:product_group_by_sizes|min:3',
    ];

    public function mount($id = null)
    {
        if($id) {
            $pg = ProductGroupBySize::find($id);
            $this->productGroupBySize = $pg;
            $this->title = $pg->title;
        } else {
            $this->productGroupBySize = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(ProductGroupBySizeService $productGroupBySizeService)
    {
        if ($this->productGroupBySize === null) {
            $this->authorize('create', ProductGroupBySize::class);
            $this->validate();
            $productGroupBySizeService->storeProductGroupBySize($this->title);

            $this->emit('productGroupBySizeAdded');
            $this->reset();
            session()->flash('success_message', 'ProductGroupBySize successfully added.');
        } else {
            $this->authorize('update', $this->productGroupBySize);
            $this->validate([
                'title' => 'required|string|min:3|unique:product_group_by_sizes,title,'.$this->productGroupBySize->id,
            ]);
            $productGroupBySizeService->updateProductGroupBySize($this->title, $this->productGroupBySize);
            return redirect()->route('product-group-by-sizes.index');
        }
    }

    public function render()
    {
        return view('livewire.product-group-by-size.create-form');
    }
}
