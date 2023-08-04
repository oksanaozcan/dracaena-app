<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Categories') }}
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
                <img src="{{url($category->preview)}}" alt="preview" class="mb-2">
                <div class="mb-2 text-xl font-bold">{{$category->title}}</div>
                <p class="text-base text-gray-700">Id: {{$category->id}}</p>
                <p class="text-base text-gray-700">Created at: {{$category->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$category->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$category->deleted_at}}</p>
                <ul class="text-base text-gray-700">Products:
                    @foreach ($category->products as $p)
                        <li>{{$p->title}}</li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                <a class="btn-common btn-primary" href="{{route('categories.edit', $category->id)}}">Edit</a>
                <x-form.destroy path="categories.destroy" deletingId="{{$category->id}}" />
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
