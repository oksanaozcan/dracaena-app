<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\TagService;

class Table extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedTag;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'tagAdded' => 'render',
    ];



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
        $tagService->destroyTag($id);
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
        return view('livewire.tag.table', [
            'tags' => Tag::search('title', $this->search)->orderBy($this->sortField, $this->sortDirection)->paginate(15),
            'count' => Tag::count()
        ]);
    }

    public function selectTag(int $tagId)
    {
        $this->selectedTag = $tagId;
    }
}
