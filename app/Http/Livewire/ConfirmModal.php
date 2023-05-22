<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\CategoryService;
use Exception;
use Illuminate\Support\Facades\Route;
use App\Jobs\Tag\BulkDeleteTagJob;

class ConfirmModal extends Component
{
    public $checkedTitles;
    public string $currentModel;

    protected $listeners = [
        'checkedTitlesUpdated' => 'mount',
    ];

    public function mount($checkedTitles)
    {
        $this->checkedTitles = $checkedTitles;
    }

    public function destroyCheckedTags()
    {
        try {
            foreach($this->checkedTitles as $key=>$value) {
                BulkDeleteTagJob::dispatch($value)->onQueue('default');
            }
            $this->emit('deletedTags');
        } catch (Exception $exception) {
            abort(500, $exception);
        }

    }

    public function destroyCheckedCategories(CategoryService $categoryService)
    {
        try {
            foreach($this->checkedTitles as $key=>$value) {
                $categoryService->destroyCategoryByTitle($value);
            }
            $this->emit('deletedCategories');
        } catch (Exception $exception) {
            abort(500, $exception);
        }
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
