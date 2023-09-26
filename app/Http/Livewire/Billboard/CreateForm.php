<?php

namespace App\Http\Livewire\Billboard;

use Livewire\Component;
use App\Models\Billboard;
use App\Services\BillboardService;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $billboard;
    public $description;
    public $image;

    protected $rules = [
        'description' => 'required|string',
        'image' => 'required|image',
    ];

    public function mount($id = null)
    {
        if($id) {
            $b = Billboard::find($id);
            $this->billboard = $b;
            $this->description = $b->description;
        } else {
            $this->billboard = null;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm(BillboardService $billboardService)
    {
        if ($this->billboard === null) {
            $this->authorize('create', Billboard::class);
            $this->validate();
            $billboardService->storeBillboard($this->description, $this->image);

            $this->emit('billboardAdded');
            $this->reset();
            session()->flash('success_message', 'Billboard successfully added.');
        } else {
            $this->authorize('update', $this->billboard);
            $this->validate([
                'description' => 'required|string',
                'image' => 'nullable|image'
            ]);
            $billboardService->updateBillboard($this->description, $this->billboard, $this->image);
            return redirect()->route('billboards.index');
        }
    }

    public function render()
    {
        return view('livewire.billboard.create-form');
    }
}
