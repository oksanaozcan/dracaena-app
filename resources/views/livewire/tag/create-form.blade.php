<?php
/** @see App\Http\Livewire\Tag\CreateEditForm */
?>
<form wire:submit.prevent="submitForm" class="px-6 pb-2 mb-4">
    <div class="h-10">
        @if (session()->has('success_message'))
        <div
            x-data="{show: true}"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            class="relative px-4 py-3 text-green-700 bg-green-100 border border-green-400 rounded" role="alert"
        >
            <span class="block sm:inline">{{ session('success_message') }}</span>
            <span
                type="button"
                role="button"
                @click="show=false"
                class="absolute top-0 bottom-0 right-0 px-4 py-3"
            >
              <svg class="w-6 h-6 text-green-500 fill-current" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
          </div>
        @endif
    </div>
    <div class="mt-4 mb-6">
        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
        <input
            wire:model='title'
            type="text"
            id="title"
            name="title"
            value="{{$this->title}}"
            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Enter Tag"
            required
        >
        <div class="h-2">
            @error('title') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
        </div>

    </div>
    <x-form.submit-btn/>
</form>
