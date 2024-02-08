<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\TagService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedTag;

    public $sortField = 'products_count';
    public $sortDirection = 'desc';

    public $checkedTitles = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'tagAdded' => 'render',
        'deletedTags' => 'render',
    ];

    public function updated()
    {
        $this->emit('checkedTitlesUpdated', $this->checkedTitles);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editTag($id)
    {
        return redirect()->route('tags.edit', $id);
    }

    public function destroyTag($id, TagService $tagService)
    {
        $tag = Tag::find($id);
        $this->authorize('delete', $tag);
        $tagService->destroyTag($tag);
        $this->emit('deletedTags');
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

    public function render(TagService $tagService)
    {
        $tags = $tagService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.tag.table', [
            'tags' => $tags,
            'count' => Tag::count(),
        ]);
    }

    public function selectTag(int $tagId)
    {
        $this->selectedTag = $tagId;
    }
}
