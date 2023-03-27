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

    public Collection $tags;

    //** @var int */
    public $selectedTag;

    public function render()
    {
        return view('livewire.tag.table');
    }

    public function mount()
    {
        $this->tags = Tag::all();
    }

    public function selectTag(int $tagId)
    {
        $this->selectedTag = $tagId;
    }
}
