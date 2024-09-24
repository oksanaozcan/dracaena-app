<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Product groups') }}
    </h2>
  </x-slot>

  <div class="flex flex-row ">
    <div class="p-6 text-white basis-1/6 bg-cyan-900">
      @include('includes.sidebar')
    </div>
    <div class="overflow-hidden shadow-sm basis-5/6">
      <div class="p-6 text-gray-900">

        <div>
            @if (session()->has('message'))
                <div
                    class="relative px-4 py-3 my-2 text-red-700 bg-red-100 border border-red-400 rounded" role="alert"
                    x-data="{show: true}"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                >
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div class="max-w-sm overflow-hidden rounded shadow-lg">
            <div class="px-6 py-4">
                <div class="mb-2 text-xl font-bold">{{$productGroupBySize->title}}</div>
                <p class="text-base text-gray-700">Id: {{$productGroupBySize->id}}</p>
                <p class="text-base text-gray-700">Created at: {{$productGroupBySize->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$productGroupBySize->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$productGroupBySize->deleted_at}}</p>
                <ul class="text-base text-gray-700">Products:
                    @foreach ($productGroupBySize->products as $p)
                        <li class=""><span>{{$p->title}}</span>: <a class="link-blue" href="{{route("products.edit", $p->id)}}">Edit</a></li>
                    @endforeach
                </ul>
                <hr/>
                <hr/>
                <hr/>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                <a class="btn-common btn-primary" href="{{route('product-group-by-sizes.edit', $productGroupBySize->id)}}">Edit</a>
                <x-form.destroy path="product-group-by-sizes.destroy" deletingId="{{$productGroupBySize->id}}" />
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
