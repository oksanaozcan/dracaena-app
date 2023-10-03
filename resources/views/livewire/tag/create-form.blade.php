<?php
/** @see App\Http\Livewire\Tag\CreateForm */
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
    <div class="mt-4"
        x-data="selectFilter({
            categories: {{ json_encode($categories) }},
            filters: {{ json_encode($category_filters) }},
        })"
        x-init='onInit()'
    >
        <fieldset>
            <legend class="pb-4 font-bold">Choose parent category for future tag:</legend>
            <div class="flex flex-row items-center justify-between w-full">
                <template x-for='category in categories' :key="category.id">
                    <div>
                        <input
                            x-model="category_id"
                            :id="category.title"
                            type="radio"
                            :value="category.id.toString()"
                        />
                        <label class="px-2"
                            :for="category.title"
                            x-text="category.title"
                        ></label><br />
                    </div>
                </template>
            </div>
        </fieldset>

        <template x-if="category_id">
            <div class="mt-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >Filters:</label>
                <select
                  x-model="selectedFilterId"
                  :value="selectedFilterId.toString()"
                  class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  required
                >
                  <option value="" x-text="activeFilterTitle"></option>
                  <template x-for='fl in filtersForSelect' :key="fl.id">
                    <option :value="fl.id.toString()" x-text="fl.title"></option>
                  </template>
                </select>
                <div class="h-2">
                    @error('category_filter_id') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
                </div>
              </div>
        </template>
        <template x-if="category_id == null">
            <div>You need choose category</div>
        </template>
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

<script>
    function selectFilter (config) {
        return {
            categories:  config.categories ?? [],
            filters: config.filters ?? [],
            filtersForSelect: [],
            category_id: @entangle('category_id'),
            selectedFilterId: @entangle('category_filter_id'),
            activeFilterTitle: 'Select',
            onInit() {

                if (this.category_id) {
                    this.filtersForSelect = this.filters.filter(f => f.category_id.toString() == this.category_id);
                }

                this.$watch("category_id", (newValue, oldValue) => {
                    if (newValue != oldValue) {
                        this.filtersForSelect = this.filters.filter(f => f.category_id.toString() == this.category_id);
                        this.activeFilterTitle = 'Select';
                    }
                });

                if (this.selectedFilterId) {
                    this.activeFilterTitle = this.filtersForSelect.find(f => f.id.toString() == this.selectedFilterId).title;
                }

                this.$watch("selectedFilterId", (newValue, oldValue) => {
                    if (newValue != oldValue) {
                        this.activeFilterTitle = this.filtersForSelect.find(f => f.id.toString() == newValue).title;
                    }
                });
            }
        }
    }
</script>
