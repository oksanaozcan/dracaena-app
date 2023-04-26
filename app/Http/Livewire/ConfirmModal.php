<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\TagService;

class ConfirmModal extends Component
{
    public $checkedTitles;

    protected $listeners = [
        'checkedTitlesUpdated' => 'mount',
    ];

    public function mount($checkedTitles)
    {
        $this->checkedTitles = $checkedTitles;
    }

    public function destroyCheckedTags(TagService $tagService)
    {
        try {
            foreach($this->checkedTitles as $key=>$value) {
                $tagService->destroyTagByTitle($value);
            }
            $this->emit('deletedTags');
        } catch (e) {
            //
        }
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
