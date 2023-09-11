<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Services\CategoryService;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $category;
    public $title;
    public $preview;

    protected $rules = [
        'title' => 'required|string|unique:categories|min:3',
        'preview' => 'required|image|max:1024', // 1MB Max
    ];

    public function mount($id = null)
    {
        if($id) {
            $c = Category::find($id);
            $this->category = $c;
            $this->title = $c->title;
        } else {
            $this->category = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(CategoryService $categoryService)
    {
        if ($this->category === null) {
            $this->authorize('create', Category::class);
            $this->validate();
            $categoryService->storeCategory($this->title, $this->preview);

            $this->emit('categoryAdded');
            $this->reset();
            session()->flash('success_message', 'Category successfully added.');
        } else {
            $this->authorize('update', $this->category);
            $this->validate([
                'title' => 'required|string|min:3|unique:categories,title,'.$this->category->id,
                'preview' => 'nullable|image|max:1024', // 1MB Max
            ]);
            $categoryService->updateCategory($this->title, $this->category, $this->preview);
            return redirect()->route('categories.index');
        }
    }

    public function render()
    {
        return view('livewire.category.create-form');
    }
}
