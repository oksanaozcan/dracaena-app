<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Deleted extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'orderRestored' => 'render',
        'forceDeletedOrders' => 'render'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function forceDeleteOrder($id, OrderService $orderService)
    {
        $order = Order::onlyTrashed()->find($id);
        $this->authorize('forceDelete', $order);
        $orderService->forceDeleteOrder($order);
        $this->emit('forceDeletedOrders');
    }

    public function restoreOrder($id, OrderService $orderService)
    {
        $order = Order::onlyTrashed()->find($id);
        $this->authorize('restore', $order);
        $orderService->restoreOrder($order);
        $this->emit('orderRestored');
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

    public function render(OrderService $orderService)
    {
        $orders = $orderService->searchForTable($this->search, $this->sortField, $this->sortDirection, true);
        return view('livewire.order.deleted', [
            'orders' => $orders,
            'count' => Order::onlyTrashed()->count()
        ]);
    }
}
