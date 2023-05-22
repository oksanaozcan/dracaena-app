<?php
/** @see App\Http\Livewire\Tag\Table */
?>
<div
    x-data="{openModal: false}"
>
    <div class="absolute top-16 right-10"><h1 class="py-6 text-xl font-semibold leading-tight text-gray-800">Amount: {{$count}}</h1></div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex justify-between">
            <button
                @click="openModal=true"
                type="button" class="btn-common btn-danger"
            >Bulk delete</button>
            <div class="w-1/4">
                <x-text-input wire:model='search' class="w-full px-4 py-2" placeholder="Search..."/>
            </div>
        </div>

        @if ($checkedTitles)
            <div>Checked values:
                @foreach ($checkedTitles as $checkedItem)
                    <span>{{$checkedItem}}, </span>
                @endforeach
            </div>
        @else
        @endif

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
        wire:poll.visible
        >
            <thead>
                <tr>
                    <th scope="col">
                        Check
                    </th>
                    <th scope="col">
                        Id
                        <span wire:click="sortBy('id')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Title
                        <span wire:click="sortBy('title')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Created at
                        <span wire:click="sortBy('created_at')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Products
                    </th>
                    <th scope="col">
                        Action
                    </th>
                    <th scope="col">
                        Action
                    </th>
                    <th scope="col">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tags as $item)
                <tr
                wire:loading.class.delay="opacity-50"
                wire:click="selectTag({{$item->id}})"
                style="background-color: {{$item->id === $selectedTag ? 'slite' : 'white'}}"
                >
                <td>
                    <input wire:model='checkedTitles' type="checkbox" value="{{$item->title}}"/>
                </td>
                <td scope="row">
                    {{$item->id}}
                </td>
                <td>
                    {{$item->title}}
                </td>
                <td>
                    {{$item->created_at->format('j F, Y')}}
                </td>
                <td>
                    #####
                </td>
                <td >
                    <a href="{{route('tags.show', $item->id)}}" class="link-blue">Show</a>
                </td>
                <td>
                    <a wire:click='editTag({{$item->id}})' class="link-green">Edit</a>
                </td>
                <td>
                    <a wire:click='destroyTag({{$item->id}})' class="link-red">Delete</a>
                </td>
                </tr>
                @empty
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>No results found</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-2 py-4">
            {{ $tags->links() }}
        </div>
    </div>
    <div
        x-show="openModal"
    >
        <livewire:confirm-modal :checkedTitles='$checkedTitles' currentModel='Tag'/>
    </div>
</div>
