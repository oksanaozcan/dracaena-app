<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Table extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedTag;

    public $sortedColumnHeader = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['tagAdded' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showTag($id)
    {
        return redirect()->route('tags.show', $id);
    }

    public function editTag($id)
    {
        return redirect()->route('tags.edit', $id);
    }

    public function sortBy($columnHeader)
    {
        if ($this->sortedColumnHeader === $columnHeader) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortedColumnHeader = $columnHeader;
    }

    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        return view('livewire.tag.table', [
            'tags' => Tag::where('title', 'like', '%'.$this->search.'%')->orderBy($this->sortedColumnHeader, $this->sortDirection)->paginate(15),
            'count' => Tag::count()
        ]);
    }

    public function selectTag(int $tagId)
    {
        $this->selectedTag = $tagId;
    }
}
