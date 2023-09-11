<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedCategory;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $checkedTitles = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'categoryAdded' => 'render',
        'deletedCategories' => 'render'
    ];

    public function updated()
    {
        $this->emit('checkedTitlesUpdated', $this->checkedTitles);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editCategory($id)
    {
        return redirect()->route('categories.edit', $id);
    }

    public function destroyCategory($id, CategoryService $categoryService)
    {
        $category = Category::find($id);
        $this->authorize('delete', $category);
        $categoryService->destroyCategory($id);
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
        $categories = $categoryService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.category.table', [
            'categories' => $categories,
            'count' => Category::count()
        ]);
    }

    public function selectCategory(int $categoryId)
    {
        $this->selectedCategory = $categoryId;
    }
}
