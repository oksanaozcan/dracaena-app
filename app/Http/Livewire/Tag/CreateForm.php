<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;

class CreateForm extends Component
{
    public $title;

    protected $rules = [
        'title' => 'required|unique:tags|min:3',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm()
    {
        $this->validate();

        $newTag = Tag::create([
            'title' => $this->title,
        ]);

        $this->emit('tagAdded');

        $this->resetForm();

        session()->flash('success_message', 'Tag successfully added.');
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
