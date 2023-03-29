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

    //** @var int */
    public $selectedTag;

    protected $listeners = ['tagAdded' => 'render'];

    public function showTag($id)
    {
        return redirect()->route('tags.show', $id);
    }

    public function render()
    {
        return view('livewire.tag.table', [
            'tags' => Tag::paginate(15),
            'count' => Tag::count()
        ]);
    }

    public function selectTag(int $tagId)
    {
        $this->selectedTag = $tagId;
    }
}
