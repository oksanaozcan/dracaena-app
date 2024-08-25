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
        <livewire:billboard.create-form :id="$id">
      </div>
    </div>
  </div>
</x-app-layout>
