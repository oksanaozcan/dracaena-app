<x-app-layout>
    <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
      </h2>
    </x-slot>

    <div class="flex flex-row">
      <div class="basis-1/6 p-6 text-white bg-cyan-900">sidebar here</div>
      <div class="basis-5/6 overflow-hidden shadow-sm">
        <div class="p-6 text-gray-900">
          {{ __("You're logged in!") }}
        </div>
      </div>
    </div>
</x-app-layout>
