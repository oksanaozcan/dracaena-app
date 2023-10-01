<?php

namespace App\Http\Livewire\CategoryFilter;

use Livewire\Component;
use App\Models\CategoryFilter;
use App\Models\Category;
use App\Services\CategoryFilterService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\CategoryResource;

class CreateForm extends Component
{
    use AuthorizesRequests;

    public $categoryFilter;
    public $title;
    public $category_id;

    protected $rules = [
        'title' => 'required|string|min:3',
    ];

    public function mount($id = null)
    {
        if($id) {
            $cf = CategoryFilter::find($id);
            $this->categoryFilter = $cf;
            $this->title = $cf->title;
            $this->category_id = $cf->category_id;
        } else {
            $this->categoryFilter = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(CategoryFilterService $categoryFilterService)
    {
        if ($this->categoryFilter === null) {
            $this->authorize('create', CategoryFilter::class);
            $this->validate();
            $categoryFilterService->storeCategoryFilter($this->title, $this->category_id);

            $this->emit('categoryFilterAdded');
            $this->reset();
            session()->flash('success_message', 'Category Filter successfully added.');
        } else {
            $this->authorize('update', $this->categoryFilter);
            $this->validate([
                'title' => 'required|string|min:3',
            ]);
            $categoryFilterService->updateCategoryFilter($this->title, $this->categoryFilter, $this->category_id);
            return redirect()->route('category-filters.index');
        }
    }

    public function render()
    {
        return view('livewire.category-filter.create-form', [
            'categories' => CategoryResource::collection(Category::all()),
        ]);
    }
}
