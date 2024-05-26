<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Order') }}
    </h2>
  </x-slot>

  <div class="flex flex-row h-screen">
    <div class="p-6 text-white basis-1/6 bg-cyan-900">
      @include('includes.sidebar')
    </div>
    <div class="overflow-hidden shadow-sm basis-5/6">
      <div class="p-6 text-gray-900">

        <div class="max-w-sm overflow-hidden rounded shadow-lg">
            <div class="px-6 py-4">
                <p class="text-base text-gray-700">Id: {{$order->id}}</p>
                <p class="text-base text-gray-700">Client id: {{$order->client_id}}</p>
                <p class="text-base text-gray-700">Session id: {{$order->session_id}}</p>
                <p class="text-base text-gray-700">Customer Name: {{$order->customer_name}}</p>
                <p class="text-base text-gray-700">Customer Email: {{$order->customer_email}}</p>
                <p class="text-base text-gray-700">Payment Status: {{$order->payment_status}}</p>
                <p class="text-base text-gray-700">Total Amount: {{$order->total_amount}}</p>
                <p class="text-base text-gray-700">Payment Method: {{$order->payment_method}}</p>
                <p class="text-base text-gray-700">Discount Amount: {{$order->discount_amount}}</p>
                <p class="text-base text-gray-700">Created at: {{$order->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$order->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$order->deleted_at}}</p>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                destroy button
                {{-- <a class="btn-common btn-primary" href="{{route('billboards.edit', $billboard->id)}}">Edit</a> --}}
                {{-- <x-form.destroy path="billboards.destroy" deletingId="{{$billboard->id}}" /> --}}
            </div>

            <div>
                <h3>Products of this order:</h3>
                <ul>
                    @foreach ($order->products as $p)
                    <li><span>{{$p->title}}</span>: <a class="link-blue" href="{{route("products.show", $p->id)}}">Details</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
