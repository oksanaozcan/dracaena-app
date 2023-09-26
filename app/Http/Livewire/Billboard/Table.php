<?php

namespace App\Http\Livewire\Billboard;

use Livewire\Component;
use App\Models\Billboard;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\BillboardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedBillboard;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $checkedDescriptions = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'billboardAdded' => 'render',
        'deletedBillboards' => 'render'
    ];

    public function updated()
    {
        $this->emit('checkedDescriptionUpdated', $this->checkedDescriptions);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editBillboard($id)
    {
        return redirect()->route('billboards.edit', $id);
    }

    public function destroyBillboard($id, BillboardService $billboardService)
    {
        $billboard = Billboard::find($id);
        $this->authorize('delete', $billboard);
        $billboardService->destroyBillboard($id);
    }

    public function sortBy($columnHeader)
    {
        if ($this->sortField === $columnHeader) {
            $this->sortDirection = $this->swapSortDirection();
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $columnHeader;
    }

    public function swapSortDirection()
    {
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function render(BillboardService $billboardService)
    {
        $billboards = $billboardService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.billboard.table', [
            'billboards' => $billboards,
            'count' => Billboard::count()
        ]);
    }

    public function selectBillboard(int $billboardId)
    {
        $this->selectedBillboard = $billboardId;
    }
}
