<?php
/** @see App\Http\Livewire\Product\CreateForm */
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
<div>
    @if ($this->product)
        @if ($this->preview)
            <img class="mb-4 w-36 filter grayscale blur-sm" src="{{url($this->product->preview)}}" alt="product-preview">
        @else
            <img class="mb-4 w-36" src="{{url($this->product->preview)}}" alt="product-preview">
        @endif

    @endif
    @if ($this->preview)
        <img class="mb-4 w-36" src="{{$this->preview->temporaryUrl()}}" alt="">
    @endif
    <input type="file" wire:model="preview" value="{{$this->preview}}">
    @error('preview') <span class="error">{{ $message }}</span> @enderror
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
        placeholder="Enter Product"
        required
    >
    <div class="h-2">
        @error('title') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
<div class="mt-4 mb-6">
    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
    <input
        wire:model='description'
        type="text"
        id="description"
        name="description"
        value="{{$this->description}}"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Description of product"
        required
    >
    <div class="h-2">
        @error('description') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
<div class="mt-4 mb-6">
    <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content (not required)</label>
    <textarea
        wire:model='content'
        id="content"
        name="content"
        value="{{$this->content}}"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Content of product"
        rows="4" cols="50"
    ></textarea>
    <div class="h-2">
        @error('content') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
<div class="mt-4 mb-6">
    <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
    <input
        wire:model='price'
        type="number"
        step="0.01"
        id="price"
        name="price"
        value="{{$this->price}}"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Price"
        required
    >
    <div class="h-2">
        @error('price') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
<div class="mt-4 mb-6">
    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
    <input
        wire:model='amount'
        type="number"
        step="1"
        id="amount"
        name="amount"
        value="{{$this->amount}}"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="Amount"
        required
    >
    <div class="h-2">
        @error('amount') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
<div class="mt-4 mb-6">
    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
    <select
        wire:model='category_id'
        name="category_id"
        value="{{$this->category_id}}"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        required
    >
    <option value="">Select category</option>
    @foreach($categories as $category)
        <option value="{{$category->id}}">{{$category->title}}</option>
    @endforeach
    </select>
    <div class="h-2">
        @error('category_id') <span class="h-full text-sm text-red-600 dark:text-red-500">{{ $message }}</span> @enderror
    </div>
</div>

<div class="w-full mt-4 mb-6">
    Selected tags from livewire model: @json($this->tags)
</div>

{{-- <div class="w-full mt-4 mb-6">
    <label for="tags" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tags</label>
    <select
        wire:model='tags'
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        multiple
    >
    @foreach($tags_fore_select as $t)
        <option value="{{$t->id}}">{{$t->title}}</option>
    @endforeach
    </select>
</div> --}}

{{-- begin test functional--------------------------------------------- --}}

<div class="w-full">
    {{-- Start Components --}}
    <div class="relative"
        x-data="multiselect({
            items: {{ json_encode($tags_fore_select) }}
        })"
        x-init='onInit()'
        @focusout='handleBlure'
    >
        {{-- Start Item Tgas And Input Fields  --}}
        <div class="relative flex items-center justify-between px-1 border-2 rounded-md">
            <ul class="flex flex-wrap items-center w-full">
                {{-- Tags (Selected) --}}
                <template x-for='(selectedItem,idx) in selectedItems'>
                    <li
                        @click='removeElByInd(idx)'
                        @keyup.delete="removeElByInd(idx)"
                        @keyup.backspace="removeElByInd(idx)"
                        tabindex="0"
                        x-text="selectedItem.title + ' X'"
                        class="relative px-2 py-2 m-1 border rounded-md cursor-pointer hover:bg-gray-100"
                    ></li>
                </template>

                {{-- Search Input --}}
                <input
                    x-model='search'
                    @click='expanded = true'
                    @focusin='expanded = true'
                    @input='expanded = true'
                    @keyup.arrow-down='expanded = true; selectNextItem'
                    @keyup.arrow-up='expanded = true; selectPrevItem'
                    @keyup.esc='reset'
                    type="text"
                    class="flex-grow py-2 px-2 mx-1 my-1.5"
                    placeholder="Type here..."
                />
                {{-- Arrow Icon  --}}
                <i @click='expanded = true' class="fa-solid fa-chevron-down"></i>
            </ul>
        </div>
        {{-- End Item Tags and Input Field --}}

        {{-- Start items list --}}
        <template
            x-if='expanded'
        >
            <ul class="w-full list-none border-2 border-t-0 rounded-md" tabindex="0">
                <template x-for='filteredItem in filteredItems'>
                    {{-- Item element --}}
                    <li
                        @click='handleItemClick(filteredItem)'
                        x-text='filteredItem.title'
                        class="px-2 py-2 cursor-pointer hover:bg-amber-200"
                    ></li>
                </template>

                <template x-if='!filteredItems.length'>
                    {{-- Empty text --}}
                    <li class="px-2 py-2 text-gray-400 cursor-pointer">
                        No items found...
                    </li>
                </template>

            </ul>
        </template>

        {{-- End Items List --}}
    </div>
    {{-- end Component --}}
</div>

<script>
    function multiselect (config) {
        return {
            items: config.items ?? [],
            allItems: null,
            selectedItems: null,
            search: '',
            searchPlaceholder: config.searchPlaceholder ?? 'Type here...',
            expanded: false,
            emptyText: config.emptyText ?? 'No items found',
            allowDuplicates: config.allowDuplicates ?? false,
            size: config.size ?? 6,
            itemHeight: config.itemHeight ?? 40,
            maxTagChars: config.maxTagChars ?? 25,
            activeIndex: -1,
            onInit() {
                this.allItems = [...this.items];
                this.$watch('selectedItems', (newValue, oldValue) => {
                    this.allItems = this.items.filter((item,idx,all) => {
                        return newValue.every(n => n.title !==item.title);
                    }
                    );
                })
                this.selectedItems = this.allItems.filter(i => i.selected)
            },
            reset() {
                this.expanded = false;
            },
            handleBlure(e) {
                if (this.$el.contains(e.relatedTarget)) return;
                this.expanded = false;
            },
            get filteredItems() {
                return this.allItems.filter(item =>
                item.title.toLowerCase().includes(this.search.toLowerCase())
                )
            },
            handleItemClick(selectedItem) {
                this.selectedItems.push(selectedItem);
            },
            removeElByInd(idx) {
                this.selectedItems.splice(idx, 1);
            },
            selectPrevItem() {
                if (!filteredItems.length) return;
                this.activeIndex--;
            },
            selectNextItem() {
                if (!filteredItems.length) return;
                if (this.filteredItems.length - 1 == this.activeIndex) return (this.activeIndex = 0);
                this.activeIndex++;
            }
        }
    }
</script>

{{-- end test functional----------------------------------------------- --}}

<x-form.submit-btn/>
</form>

{{-- <select id="select" class="hidden">
    @foreach($tags_fore_select as $t)
        <option value="{{$t->id}}">{{$t->title}}</option>
    @endforeach
</select>

<div x-data="dropdown()" x-init="loadOptions()" class="mt-4 mb-6">
    <input
        name="values" type="hidden" x-bind:value="selectedValues()"
    >
        <div class="relative inline-block w-full">
            <div class="relative flex flex-col items-center">
              <div x-on:click="open" class="w-full">
                  <div class="flex p-1 my-2 bg-white border border-gray-200 rounded">
                      <div class="flex flex-wrap flex-auto">
                          <template x-for="(option,index) in selected"
                          :key="options[option].value"
                          >
                              <div class="flex items-center justify-center px-4 py-1 m-2 text-white rounded-full bg-cyan-900 ">
                                  <div class="pr-4" x-model="options[option]" x-text="options[option].text"></div>
                                  <div class="flex flex-row-reverse flex-auto">
                                      <div x-on:click="remove(index,option)">
                                        <i class="fa-solid fa-xmark"></i>
                                      </div>
                                  </div>
                              </div>
                          </template>
                          <div x-show="selected.length == 0" class="flex-1">
                              <input placeholder="Select a tags" class="w-full h-full p-1 px-2 text-gray-800 bg-transparent outline-none appearance-none"
                                x-bind:value="selectedValues()"
                              >
                          </div>
                      </div>
                      <div class="flex items-center w-8 py-1 pl-2 pr-1 text-gray-300 border-l border-gray-200">
                          <button type="button" x-show="isOpen() === true" x-on:click="open" class="w-6 h-6 text-gray-600 outline-none cursor-pointer focus:outline-none">
                            <i class="fa-solid fa-chevron-up"></i>
                          </button>
                          <button type="button" x-show="isOpen() === false" @click="close" class="w-6 h-6 text-gray-600 outline-none cursor-pointer focus:outline-none">                            >
                          </button>
                      </div>
                  </div>
              </div>
              <div class="w-full px-4">
                  <div x-show.transition.origin.top="isOpen()" class="absolute z-40 w-full overflow-y-auto bg-white rounded shadow top-100 lef-0 max-h-select"
                      x-on:click.away="close">
                      <div class="flex flex-col w-full">
                          <template x-for="(option,index) in options" :key="index">
                              <div>
                                  <div class="w-full border-b border-gray-100 rounded-t cursor-pointer hover:bg-cyan-100"
                                      @click="select(index,$event)">
                                      <div x-bind:class="option.selected ? 'border-cyan-800' : ''"
                                          class="relative flex items-center w-full p-2 pl-2 border-l-2 border-transparent">
                                          <div class="flex items-center w-full">
                                              <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </template>
                      </div>
                  </div>
              </div>
          </div>
      </div>


      <script>
          function dropdown() {
              return {
                  options: [],
                  selected: [],
                  show: false,
                  open() { this.show = true },
                  close() { this.show = false },
                  isOpen() { return this.show === true },
                  select(index, event) {

                      if (!this.options[index].selected) {

                          this.options[index].selected = true;
                          this.options[index].element = event.target;
                          this.selected.push(index);

                      } else {
                          this.selected.splice(this.selected.lastIndexOf(index), 1);
                          this.options[index].selected = false
                      }
                  },
                  remove(index, option) {
                      this.options[option].selected = false;
                      this.selected.splice(index, 1);


                  },
                  loadOptions() {
                      const options = document.getElementById('select').options;
                      for (let i = 0; i < options.length; i++) {
                          this.options.push({
                              value: options[i].value,
                              text: options[i].innerText,
                              selected: options[i].getAttribute('selected') != null ? options[i].getAttribute('selected') : false
                          });
                      }


                  },
                  selectedValues(){
                      return this.selected.map((option)=>{
                          return this.options[option].value;
                      })
                  }
              }
          }
      </script>
</div> --}}
