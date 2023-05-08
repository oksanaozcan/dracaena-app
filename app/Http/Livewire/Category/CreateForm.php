<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Services\CategoryService;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;

    public $category;
    public $title;
    public $preview;

    protected $rules = [
        'title' => 'required|unique:categories|min:3',
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
        $this->validate();

        if ($this->category === null) {
            $categoryService->storeCategory($this->title, $this->preview);

            $this->emit('categoryAdded');
            $this->reset();
            session()->flash('success_message', 'Category successfully added.');
        } else {
            $categoryService->updateCategory($this->title, $this->category);
            return redirect()->route('categories.index');
        }
    }

    public function render()
    {
        return view('livewire.category.create-form');
    }
}
