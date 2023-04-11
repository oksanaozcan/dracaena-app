<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Tags') }}
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
                <div class="mb-2 text-xl font-bold">{{$tag->title}}</div>
                <p class="text-base text-gray-700">Id: {{$tag->id}}</p>
                <p class="text-base text-gray-700">Created at: {{$tag->created_at}}</p>
                <p class="text-base text-gray-700">Updated at: {{$tag->updated_at}}</p>
                <p class="text-base text-gray-700">Deleted at: {{$tag->deleted_at}}</p>
            </div>
            <div class="flex justify-between px-6 pt-4 pb-2">
                <a class="btn-common btn-primary" href="{{route('tags.edit', $tag->id)}}">Edit</a>
                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-common btn-danger"
                    {{ $disabled ?? false ? ' disabled' :'' }}
                    >Delete</button>
                </form>
            </div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
