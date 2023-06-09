<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CategoryService;

class Table extends Component
{
    use WithPagination;

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

    public function render()
    {
        return view('livewire.category.table', [
            'categories' => Category::search('title', $this->search)->orderBy($this->sortField, $this->sortDirection)->paginate(15),
            'count' => Category::count()
        ]);
    }

    public function selectCategory(int $categoryId)
    {
        $this->selectedCategory = $categoryId;
    }
}
