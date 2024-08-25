<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Billboards') }}
    </h2>
  </x-slot>

  <div class="flex flex-row">
    <div class="p-6 text-white basis-1/6 bg-cyan-900">
      @include('includes.sidebar')
    </div>
    <div class="overflow-hidden shadow-sm basis-5/6">
      <div class="p-6 text-gray-900">

        <div class="max-w-sm overflow-hidden rounded shadow-lg">
            <div class="px-6 py-4">
                <img src="{{url($billboard->image)}}" alt="billboard-image" class="mb-2">
                <div class="mb-2 text-xl font-bold">{{$billboard->description}}</div>
                <p class="text-base text-gray-700">Id: {{$billboard->id}}</p>
                <p class="text-base text-gray-700">Created at: {{$billboard->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$billboard->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$billboard->deleted_at}}</p>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                <a class="btn-common btn-primary" href="{{route('billboards.edit', $billboard->id)}}">Edit</a>
                <x-form.destroy path="billboards.destroy" deletingId="{{$billboard->id}}" />
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
