<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;

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

    public function submitForm()
    {
        $this->validate();

        if ($this->tag === null) {
            Tag::create([
                'title' => $this->title,
            ]);
            $this->emit('tagAdded');
            $this->resetForm();
            session()->flash('success_message', 'Tag successfully added.');
        } else {
            Tag::find($this->tag->id)->update([
                'title' => $this->title,
            ]);
            return redirect()->route('tags.index');
        }
    }

    private function resetForm()
    {
        $this->title = '';
    }

    public function render()
    {
        return view('livewire.tag.create-form');
    }
}
