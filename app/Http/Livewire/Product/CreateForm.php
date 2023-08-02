<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Services\ProductService;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;

    public $product;
    public $title;
    public $preview;

    protected $rules = [
        'required|string|unique:products|min:3',
        'preview' => 'required|image|max:1024', // 1MB Max
    ];

    public function mount($id = null)
    {
        if($id) {
            $p = Product::find($id);
            $this->product = $p;
            $this->title = $p->title;
        } else {
            $this->product = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(ProductService $productService)
    {
        if ($this->product === null) {
            $this->validate();
            $productService->storeProduct($this->title, $this->preview);

            $this->emit('productAdded');
            $this->reset();
            session()->flash('success_message', 'Product successfully added.');
        } else {
            $this->validate([
                'title' => 'required|string|min:3|unique:products,title,'.$this->product->id,
                'preview' => 'nullable|image|max:1024', // 1MB Max
            ]);
            $productService->updateProduct($this->title, $this->product, $this->preview);
            return redirect()->route('products.index');
        }
    }

    public function render()
    {
        return view('livewire.product.create-form');
    }
}
