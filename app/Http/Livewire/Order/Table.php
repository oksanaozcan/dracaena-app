<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Table extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';

    public $selectedOrder;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    protected $listeners = [
        'orderRestored' => 'render',
        'deletedOrders' => 'render'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function destroyOrder($id, OrderService $orderService)
    {
        $order = Order::findOrFail($id);
        $this->authorize('delete', $order);
        $orderService->destroyOrder($order);
        $this->emit('deletedOrders');
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
        $orders = $orderService->searchForTable($this->search, $this->sortField, $this->sortDirection);
        return view('livewire.order.table', [
            'orders' => $orders,
            'count' => Order::count()
        ]);
    }

    public function selectOrder(int $orderId)
    {
        $this->selectedOrder = $orderId;
    }
}
