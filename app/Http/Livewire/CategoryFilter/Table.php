<?php

namespace App\Http\Livewire\CategoryFilter;

use Livewire\Component;
use App\Models\CategoryFilter;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CategoryFilterService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedCategoryFilter;

    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $checkedTitles = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'categoryFilterAdded' => 'render',
        'deletedCategoryFilters' => 'render'
    ];

    public function updated()
    {
        $this->emit('checkedTitlesUpdated', $this->checkedTitles);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editCategoryFilter($id)
    {
        return redirect()->route('category-filters.edit', $id);
    }

    public function destroyCategoryFilter($id, CategoryFilterService $categoryFilterService)
    {
        $categoryFilter = CategoryFilter::find($id);
        $this->authorize('delete', $categoryFilter);

        $tags = $categoryFilter->tags;

        $tagsHasNotAnyProducts = true;

        foreach ($tags as $tag) {
            if ($tag->products->count() > 0) {
                $tagsHasNotAnyProducts = false;
            }
        }

        Log::info($tagsHasNotAnyProducts);

        if ($categoryFilter->tags->count() == 0) {
            $categoryFilterService->destroyCategoryFilter($categoryFilter);
            $this->emit('deletedCategoryFilters');
        } elseif ($categoryFilter->tags->count() > 0 && $tagsHasNotAnyProducts) {
            $categoryFilterService->destroyCategoryFilter($categoryFilter);
            $this->emit('deletedCategoryFilters');
        } else {
            $this->flushMessage($categoryFilter);
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

    public function render(CategoryFilterService $categoryFilterService)
    {
        $categoryFilters = $categoryFilterService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.category-filter.table', [
            'categoryFilters' => $categoryFilters,
            'count' => CategoryFilter::count()
        ]);
    }

    public function selectCategoryFilter(int $categoryFilterId)
    {
        $this->selectedCategoryFilter = $categoryFilterId;
    }

    public function flushMessage(CategoryFilter $cf)
    {
        session()->flash('message', 'You can not delete this category filter right now because that has relationships with several tags with products. Please be sure to look at the details.');
        return redirect()->route("category-filters.show", $cf);
    }
}
