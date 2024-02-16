<?php
/** @see App\Http\Livewire\Order\Table */
?>
<div
    x-data="{openModal: false}"
>
    <div class="absolute top-16 right-10"><h1 class="py-6 text-xl font-semibold leading-tight text-gray-800">Amount: {{$count}}</h1></div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex justify-between">
        </div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead>
                <tr>
                    <th scope="col">
                        Id
                        <span wire:click="sortBy('id')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Customer Name
                        <span wire:click="sortBy('customer_name')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Payment Status
                        <span wire:click="sortBy('payment_status')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Products amount
                    </th>
                    <th scope="col">
                        Total amount
                        <span wire:click="sortBy('total_amount')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Payment method
                        <span wire:click="sortBy('payment_method')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Discount
                        <span wire:click="sortBy('discount_amount')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Created at
                        <span wire:click="sortBy('created_at')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Action
                    </th>
                    <th scope="col">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $item)
                <tr
                wire:loading.class.delay="opacity-50"
                wire:click="selectOrder({{$item->id}})"
                style="background-color: {{$item->id === $selectedOrder ? 'slite' : 'white'}}"
                >
                <td scope="row">
                    {{$item->id}}
                </td>
                <td>
                    {{$item->customer_name}}
                </td>
                <td>
                    {{$item->payment_status}}
                </td>
                <td>
                    {{$item->products->count()}}
                </td>
                <td>
                    {{$item->total_amount}}
                </td>
                <td>
                    {{$item->payment_method}}
                </td>
                <td>
                    {{$item->discount_amount}}
                </td>
                <td>
                    {{$item->created_at->format('j F, Y')}}
                </td>
                <td >
                    <a href="{{route('orders.show', $item->id)}}" class="link-blue">Show</a>
                </td>
                <td>
                    <a wire:click='destroyOrder({{$item->id}})' class="link-red">Destroy</a>
                </td>
                </tr>
                @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>No results found</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-2 py-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
