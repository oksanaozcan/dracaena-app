<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Exception;
use Illuminate\Support\Facades\Route;
use App\Jobs\Tag\BulkDeleteTagJob;
use App\Jobs\Product\BulkDeleteProductJob;

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

    public function destroyCheckedProducts()
    {
        try {
            foreach($this->checkedTitles as $key=>$value) {
                BulkDeleteProductJob::dispatch($value)->onQueue('default');
            }
            $this->emit('deletedProducts');
        } catch (Exception $exception) {
            abort(500, $exception);
        }
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
