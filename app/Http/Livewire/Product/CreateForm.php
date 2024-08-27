<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ProductService;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $product;
    public $title;
    public $preview;
    public $description;
    public $content = '';
    public $price;
    public $amount;
    public $category_id;
    public $tags = [];
    public $images = [];

    protected $rules = [
        'title' => 'required|string|unique:products|min:3',
        'description'=> 'required|string|min:3|max:100',
        'preview' => 'required|image|max:1024', // 1MB Max
        'content' => 'string|max:1000',
        'price' => 'required|numeric',
        'amount' => 'required|numeric',
        'category_id' => 'required',
        'tags' => 'nullable',
        'images' => 'nullable|array',
        'images.*' => [
            'nullable',
            'image',
            'max:2048',
        ],
    ];

    public function mount($id = null)
    {
        if($id) {
            $p = Product::find($id);
            $this->product = $p;
            $this->title = $p->title;
            $this->description = $p->description;
            $this->content = $p->content;
            $this->price = $p->price;
            $this->amount = $p->amount;
            $this->category_id = $p->category_id;
            $this->tags = $p->tags;
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
            $this->authorize('create', Product::class);
            $newTags = [];
            foreach ($this->tags as $item) {
                if (isset($item["id"])) {
                    array_push($newTags, $item["id"]);
                }
            }
            $this->validate();
            $productService->storeProduct($this->title, $this->preview, $this->description, $this->content, $this->price, $this->amount, $this->category_id, $newTags, $this->images);

            $this->emit('productAdded');
            $this->reset();
            session()->flash('success_message', 'Product successfully added.');
        } else {
            $this->authorize('update', $this->product);
            $this->validate([
                'title' => 'required|string|min:3|unique:products,title,'.$this->product->id,
                'description' => 'required|string|min:3|max:100',
                'preview' => 'nullable|image|max:1024', // 1MB Max
                'content' => 'nullable|max:1000',
                'price' => 'nullable|numeric',
                'amount' => 'nullable|numeric',
                'category_id' => 'required',
                'tags' => 'nullable',
                'images' => 'nullable|array',
            ]);
            $newTags = [];
            foreach ($this->tags as $item) {
                if (isset($item["id"])) {
                    array_push($newTags, $item["id"]);
                }
            }
            $productService->updateProduct($this->title, $this->product, $this->preview, $this->description, $this->content, $this->price, $this->amount, $this->category_id, $newTags, $this->images);
            return redirect()->route('products.index');
        }
    }

    public function removeTempImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Re-index the array
        }
    }

    public function render()
    {
        return view('livewire.product.create-form', [
            'categories' => CategoryResource::collection(Category::all()),
            'tags_fore_select' => TagResource::collection(Tag::with(['categoryFilter'])->get()),
        ]);
    }
}
