<?php
/** @see App\Http\Livewire\Tag\Table */
?>
<div>
    <div class="absolute top-16 right-10"><h1 class="py-6 text-xl font-semibold leading-tight text-gray-800">Amount: {{$count}}</h1></div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div>
            <button type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">Bulk delete</button>
        </div>
        <div>
            <form class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" required>
                </div>
                <button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="sr-only">Search</span>
                </button>
            </form>
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Id
                    <span wire:click="sortBy('id')" class="pl-4">
                        <i class="px-2 py-1 fa fa-arrow-up {{$sortedColumnHeader === 'id' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        <i class="px-2 py-1 fa fa-arrow-down {{$sortedColumnHeader === 'id' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3">
                    Title
                    <span wire:click="sortBy('title')" class="pl-4">
                        <i class="px-2 py-1 fa fa-arrow-up {{$sortedColumnHeader === 'title' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        <i class="px-2 py-1 fa fa-arrow-down {{$sortedColumnHeader === 'title' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3">
                    Created at
                    <span wire:click="sortBy('created_at')" class="pl-4">
                        <i class="px-2 py-1 fa fa-arrow-up {{$sortedColumnHeader === 'created_at' && $sortDirection === 'asc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
                        <i class="px-2 py-1 fa fa-arrow-down {{$sortedColumnHeader === 'created_at' && $sortDirection === 'desc' ? 'text-red-700 bg-red-200 rounded-md' : ''}}"></i>
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
                        <a wire:click='editTag({{$item->id}})' class="font-medium text-green-600 dark:text-green-500 hover:underline">Edit</a>
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
