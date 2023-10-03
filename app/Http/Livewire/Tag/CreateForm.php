<?php

namespace App\Http\Livewire\Tag;

use Livewire\Component;
use App\Models\Tag;
use App\Models\Category;
use App\Models\CategoryFilter;
use App\Services\TagService;
use App\Jobs\Tag\StoreTagJob;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facades\Debugbar;
use Throwable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryFilterResource;

class CreateForm extends Component
{
    use AuthorizesRequests;

    public $tag;
    public $title;
    public $category_id;
    public $category_filter_id;

    protected $rules = [
        'title' => 'required|unique:tags|min:3',
    ];

    public function mount($id = null)
    {
        if($id) {
            $t = Tag::find($id);
            $this->tag = $t;
            $this->title = $t->title;
            $catFilter = CategoryFilter::find($this->tag->category_filter_id);
            $this->category_filter_id = $catFilter->id;
            $this->category_id = Category::find($catFilter->category_id)->id;
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
            $this->authorize('create', Tag::class);
            StoreTagJob::dispatch($this->title, $this->category_filter_id);

            $this->emit('tagAdded');
            $this->reset();
            session()->flash('success_message', 'Tag successfully added.');
        } else {
            $this->authorize('update', $this->tag);
            $tagService->updateTag($this->title, $this->category_filter_id, $this->tag);
            return redirect()->route('tags.index');
        }
    }

    public function render()
    {
        return view('livewire.tag.create-form', [
            'categories' => CategoryResource::collection(Category::all()),
            'category_filters' => CategoryFilterResource::collection(CategoryFilter::all()),
        ]);
    }
}
