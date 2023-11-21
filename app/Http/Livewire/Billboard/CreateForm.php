<?php

namespace App\Http\Livewire\Billboard;

use Livewire\Component;
use App\Models\Billboard;
use App\Services\BillboardService;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $billboard;
    public $description;
    public $image;
    public $category_id;
    public $tags = [];

    protected $rules = [
        'description' => 'required|string',
        'image' => 'required|image',
        'category_id' => 'required',
        'tags' => 'nullable',
    ];

    public function mount($id = null)
    {
        if($id) {
            $b = Billboard::find($id);
            $this->billboard = $b;
            $this->description = $b->description;
            $this->category_id = $b->category_id;
            $this->tags = $b->tags;
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

            $newTags = [];
            foreach ($this->tags as $item) {
                if (isset($item["id"])) {
                    array_push($newTags, $item["id"]);
                }
            }

            $this->validate();
            $billboardService->storeBillboard($this->description, $this->image, $this->category_id, $newTags);

            $this->emit('billboardAdded');
            $this->reset();
            session()->flash('success_message', 'Billboard successfully added.');
        } else {
            $this->authorize('update', $this->billboard);
            $this->validate([
                'description' => 'required|string',
                'image' => 'nullable|image',
                'category_id' => 'required',
                'tags' => 'nullable',
            ]);

            $newTags = [];
            foreach ($this->tags as $item) {
                if (isset($item["id"])) {
                    array_push($newTags, $item["id"]);
                }
            }

            $billboardService->updateBillboard($this->description, $this->billboard, $this->image, $this->category_id, $newTags);
            return redirect()->route('billboards.index');
        }
    }

    public function render()
    {
        return view('livewire.billboard.create-form', [
            'categories' => CategoryResource::collection(Category::all()),
            'tags_fore_select' => TagResource::collection(Tag::with(['categoryFilter'])->get()),
        ]);
    }
}
