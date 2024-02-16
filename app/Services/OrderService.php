<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService {

    public function searchForTable($search, $sortField, $sortDirection, $includeSoftDeleted = false)
    {
        $query = Order::search('created_at', $search)
            ->orderBy($sortField, $sortDirection);

        if ($includeSoftDeleted) {
            $query->softDeleted();
        }

        $orders = $query->paginate(15);

        return $orders;
    }

    public function destroyOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function restoreOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->restore();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }

    public function forceDeleteOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->forceDelete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
    }
}
