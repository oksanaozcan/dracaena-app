<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;

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
        $this->authorize('show', Order::class);
        return view('order.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $this->authorize('destroy', Order::class);
        $this->orderService->destroyOrder($order);
        return redirect()->route('orders.index');
    }

    // public function restore(Order $order)
    // {
    //     $this->authorize('restore', Order::class);
    //     // $post = Post::withTrashed()->find($id);
    //     // $post->restore(); // This restores the soft-deleted post

    //     // Additional logic...
    // }

    // public function forceDelete(Order $order)
    // {
    //     $this->authorize('forceDelete', Order::class);
    //     // // If you have not deleted before
    //     // $post = Post::find($id);

    //     // // If you have soft-deleted it before
    //     // $post = Post::withTrashed()->find($id);

    //     // $post->forceDelete(); // This permanently deletes the post for ever!

    //     // Additional logic...
    // }
}
