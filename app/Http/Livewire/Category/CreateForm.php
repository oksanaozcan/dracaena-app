<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use App\Services\CategoryService;

class CreateForm extends Component
{
    public $category;
    public $title;

    protected $rules = [
        'title' => 'required|unique:categories|min:3',
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
            $categoryService->storeCategory($this->title);

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
