<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;
use App\Services\TagService;

class CreateForm extends Component
{
    public $tag;
    public $title;

    protected $rules = [
        'title' => 'required|unique:tags|min:3',
    ];

    public function mount($id = null)
    {
        if($id) {
            $t = Tag::find($id);
            $this->tag = $t;
            $this->title = $t->title;
        } else {
            $this->tag = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(TagService $tagService)
    {
        $this->validate();

        if ($this->tag === null) {
            $tagService->storeTag($this->title);

            $this->emit('tagAdded');
            $this->reset();
            session()->flash('success_message', 'Tag successfully added.');
        } else {
            $tagService->updateTag($this->title, $this->tag);
            return redirect()->route('tags.index');
        }
    }

    public function render()
    {
        return view('livewire.tag.create-form');
    }
}
