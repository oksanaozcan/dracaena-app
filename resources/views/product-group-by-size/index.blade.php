<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Product groups') }}
    </h2>
  </x-slot>

  <div class="flex flex-row ">
    <div class="p-6 text-white basis-1/6 bg-cyan-900">
      @include('includes.sidebar')
    </div>
    <div class="overflow-hidden shadow-sm basis-5/6">
      <div class="p-6 text-gray-900">
        <div
            class="mb-4"
            x-data="{
                open:false,
                toggle() {
                    this.open = !this.open
                },
            }"
        >
            <button
                @click="toggle()"
                type="button"
                :class="open ? 'text-white focus:outline-none focus:ring-4 font-medium rounded-full text-lg px-5 py-2.5 text-center mr-2 mb-2 bg-red-700 hover:bg-red-800 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800' : 'text-white focus:outline-none focus:ring-4 font-medium rounded-full text-lg px-4 py-2 text-center mr-2 mb-2 bg-green-700 hover:bg-green-800 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'"
            >
            <span>
                <i :class="open ? 'fa fa-xmark' : 'fa fa-plus'"></i>
            </span>
            </button>
            <div
                x-show="open"
                class="mb-14"
                x-transition:enter="transition ease-in duration-300"
                x-transition:enter-start="transform origin-top scale-y-0 opacity-50 shadow-none"
                x-transition:leave="transition ease-out duration-200"
                x-transition:leave-start="transform origin-top scale-y-100 opacity-100 shadow-2xl"
                x-transition:leave-end="transform origin-top scale-y-0 opacity-50 shadow-none"
            >
                <livewire:product-group-by-size.create-form>
            </div>
        </div>
        <div class="h-0.5 mb-4 bg-slate-300"></div>
        <livewire:product-group-by-size.table>
      </div>
    </div>
  </div>
</x-app-layout>
