<?php
/** @see App\Http\Livewire\Tag\Table */
?>
<div>
    <div class="absolute top-16 right-10"><h1 class="py-6 text-xl font-semibold leading-tight text-gray-800">Amount: {{$count}}</h1></div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex justify-between">
            <div class="">
                <button type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Bulk delete</button>
            </div>
            <div class="w-1/4">
                <x-text-input wire:model='search' class="w-full px-4 py-2" placeholder="Search..."/>
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Id
                        <span wire:click="sortBy('id')" class="pl-4">
                            <i class="px-2 py-1 fa fa-arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                            <i class="px-2 py-1 fa fa-arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Title
                        <span wire:click="sortBy('title')" class="pl-4">
                            <i class="px-2 py-1 fa fa-arrow-up {{$sortField === 'title' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                            <i class="px-2 py-1 fa fa-arrow-down {{$sortField === 'title' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created at
                        <span wire:click="sortBy('created_at')" class="pl-4">
                            <i class="px-2 py-1 fa fa-arrow-up {{$sortField === 'created_at' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                            <i class="px-2 py-1 fa fa-arrow-down {{$sortField === 'created_at' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        </span>
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
                @forelse ($tags as $item)
                <tr
                wire:loading.class.delay="opacity-50"
                class="border-b cursor-pointer dark:bg-gray-900 dark:border-gray-700"
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
                    <a href="{{route('tags.show', $item->id)}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Show</a>
                </td>
                <td class="px-6 py-4">
                    <a wire:click='editTag({{$item->id}})' class="font-medium text-green-600 dark:text-green-500 hover:underline">Edit</a>
                </td>
                <td class="px-6 py-4">
                    <a wire:click='destroyTag({{$item->id}})' class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                </td>
                </tr>
                @empty
                <tr class="border-b cursor-pointer dark:bg-gray-900 dark:border-gray-700">
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4 text-lg">No results found</td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4"></td>
                    <td class="px-6 py-4"></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-2 py-4">
            {{ $tags->links() }}
        </div>
    </div>
</div>
