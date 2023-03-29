<?php
/** @see App\Http\Livewire\Tag\Table */
?>
<div>
    <h1 class="py-4">Tags amound: {{$count}}</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Id
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Created at
                </th>
                <th scope="col" class="px-6 py-3">
                    Products
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $item)
                <tr class="border-b cursor-pointer dark:bg-gray-900 dark:border-gray-700"
                wire:click="selectTag({{$item->id}})"
                style="background-color: {{$item->id === $selectedTag ? 'slite' : 'white'}}"
                >
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$item->id}}
                    </th>
                    <td class="px-6 py-4">
                        {{$item->title}}
                    </td>
                    <td class="px-6 py-4">
                        {{$item->created_at->format('j F, Y')}}
                    </td>
                    <td class="px-6 py-4">
                        #####
                    </td>
                    <td class="px-6 py-4">
                        <a wire:click='showTag({{$item->id}})' class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Show</a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-green-600 dark:text-green-500 hover:underline">Edit</a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-2 py-4">
        {{ $tags->links() }}
    </div>
</div>

</div>
