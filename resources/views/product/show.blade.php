<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Products') }}
    </h2>
  </x-slot>

  <div class="flex flex-row ">
    <div class="p-6 text-white basis-1/6 bg-cyan-900">
      @include('includes.sidebar')
    </div>
    <div class="overflow-hidden shadow-sm basis-5/6">
      <div class="p-6 text-gray-900">

        <div class="max-w-sm overflow-hidden rounded shadow-lg">
            <div class="px-6 py-4">
                <img src="{{url($product->preview)}}" alt="preview" class="mb-2">
                <div class="mb-2 text-xl font-bold">{{$product->title}}</div>
                <p class="text-base text-gray-700">Id: {{$product->id}}</p>
                <p class="text-base text-gray-700">Description: {{$product->description}}</p>
                <p class="text-base text-gray-700">Content: {{$product->content}}</p>
                <p class="text-base text-gray-700">Price: {{$product->price}}</p>
                <p class="text-base text-gray-700">Amount: {{$product->amount}}</p>
                <p class="text-base text-gray-700">Category: {{$product->category?->title}}</p>
                <ul class="text-base text-gray-700">Tags:
                    @foreach ($product->tags as $t)
                        <li>{{$t->title}}</li>
                    @endforeach
                </ul>
                <p class="text-base text-gray-700">Created at: {{$product->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$product->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$product->deleted_at}}</p>
                <ul>
                    @foreach ($product->images as $img)
                    <li><img src="{{$img->url}}"/></li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                <a class="btn-common btn-primary" href="{{route('products.edit', $product->id)}}">Edit</a>
                <x-form.destroy path="products.destroy" deletingId="{{$product->id}}" />
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
