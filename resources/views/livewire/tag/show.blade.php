<?php
/** @see App\Http\Livewire\Tag\Show */
?>
<div class="max-w-sm overflow-hidden rounded shadow-lg">
    <div class="px-6 py-4">
        <div class="mb-2 text-xl font-bold">{{$tag->title}}</div>
        <p class="text-base text-gray-700">Id: {{$tag->id}}</p>
        <p class="text-base text-gray-700">Created at: {{$tag->created_at}}</p>
        <p class="text-base text-gray-700">Updated at: {{$tag->updated_at}}</p>
        <p class="text-base text-gray-700">Deleted at: {{$tag->deleted_at}}</p>
    </div>
    <div class="px-6 pt-4 pb-2">
        <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Button
          </button>
          <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Button
          </button>

    </div>
</div>

