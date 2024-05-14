<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('index', Order::class);

       



        return view('order.index');
    }

    public function deletedOrders()
    {
        $this->authorize('viewAnyDeleted', Order::class);
        return view('order.deleted');
    }

    public function show(Order $order)
    {
        $this->authorize('show', $order);
        return view('order.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $this->authorize('destroy', $order);
        $this->orderService->destroyOrder($order);
        return redirect()->route('orders.index');
    }

    public function restore(Order $order)
    {
        $this->authorize('restore', $order);
        $this->orderService->restoreOrder($order);
    }

    public function forceDelete(Order $order)
    {
        $this->authorize('forceDelete', $order);
        $this->orderService->forceDeleteOrder($order);
    }
}
