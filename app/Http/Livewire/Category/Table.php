<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $selectedCategory;

    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $queryString = ['sortField', 'sortDirection'];

    protected $listeners = [
        'categoryAdded' => 'render',
        'deletedCategories' => 'render'
    ];

    public function editCategory($id)
    {
        return redirect()->route('categories.edit', $id);
    }

    public function destroyCategory($id, CategoryService $categoryService)
    {
        $category = Category::find($id);
        $this->authorize('delete', $category);
        if ($category->products->count() == 0 && $category->categoryFilters->count() == 0) {
            $categoryService->destroyCategory($category);
            $this->emit('deletedCategories');
        } else {
            $this->flushMessage($category);
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

    public function render(CategoryService $categoryService)
    {
        $categories = $categoryService->searchForTable(null, $this->sortField, $this->sortDirection);
        return view('livewire.category.table', [
            'categories' => $categories,
            'count' => Category::count()
        ]);
    }

    public function selectCategory(int $categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function flushMessage(Category $cat)
    {
        session()->flash('message', 'You can not delete this category right now because that has relationships with several products and filters. Please be sure to look at the details.');
        return redirect()->route("categories.show", $cat);
    }
}
