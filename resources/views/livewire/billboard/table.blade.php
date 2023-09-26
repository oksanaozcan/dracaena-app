<?php
/** @see App\Http\Livewire\Billboard\Table */
?>
<div
    x-data="{openModal: false}"
>
    <div class="absolute top-16 right-10"><h1 class="py-6 text-xl font-semibold leading-tight text-gray-800">Amount: {{$count}}</h1></div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex justify-between">

        </div>

        @if ($checkedDescriptions)
            <div>Checked values:
                @foreach ($checkedDescriptions as $checkedItem)
                    <span>{{$checkedItem}}, </span>
                @endforeach
            </div>
        @else
        @endif

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                        Description
                        <span wire:click="sortBy('title')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
                    </th>
                    <th scope="col">
                        Image
                    </th>
                    <th scope="col">
                        Created at
                        <span wire:click="sortBy('created_at')" class="pl-4">
                            <i class="arrow-up {{$sortField === 'id' && $sortDirection === 'asc' ? 'active' : ''}}"></i>
                            <i class="arrow-down {{$sortField === 'id' && $sortDirection === 'desc' ? 'active' : ''}}"></i>
                        </span>
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
                @forelse ($billboards as $item)
                <tr
                wire:loading.class.delay="opacity-50"
                wire:click="selectBillboard({{$item->id}})"
                style="background-color: {{$item->id === $selectedBillboard ? 'slite' : 'white'}}"
                >
                <td>
                    <input wire:model='checkedDescriptions' type="checkbox" value="{{$item->description}}"/>
                </td>
                <td scope="row">
                    {{$item->id}}
                </td>
                <td>
                    {{$item->description}}
                </td>
                <td>
                    <img src="{{$item->image}}"  alt="billboard-image" style="width:100px">
                </td>
                <td>
                    {{$item->created_at->format('j F, Y')}}
                </td>
                <td >
                    <a href="{{route('billboards.show', $item->id)}}" class="link-blue">Show</a>
                </td>
                <td>
                    <a wire:click='editBillboard({{$item->id}})' class="link-green">Edit</a>
                </td>
                <td>
                    <a wire:click='destroyBillboard({{$item->id}})' class="link-red">Delete</a>
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
            {{ $billboards->links() }}
        </div>
    </div>
    <div
        x-show="openModal"
    >
        {{-- <livewire:confirm-modal :checkedDescriptions='$checkedDescriptions' currentModel='Billboard'/> --}}
    </div>
</div>
