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

{{-- begin test functional--------------------------------------------- --}}

<div class="w-full">
    {{-- Start Components --}}
    <div class="relative"
        x-data="multiselect({
            items: {{ json_encode($tags_fore_select) }},
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
                    x-ref="searchInput"
                    x-model='search'
                    @keyup.enter="addActiveItem"
                    @click='expanded = true'
                    @focusin='expanded = true'
                    @input='expanded = true'
                    @keyup.arrow-down='expanded = true; selectNextItem'
                    @keyup.arrow-up='expanded = true; selectPrevItem'
                    @keyup.esc='reset'
                    type="text"
                    class="flex-grow py-2 px-2 mx-1 my-1.5"
                    :placeholder="searchPlaceholder"
                />
                {{-- Arrow Icon  --}}
                <i
                    @click='expanded = !expanded'
                    :class="expanded && 'rotate-180'"
                    class="fa-solid fa-chevron-down"
                ></i>
            </ul>
        </div>
        {{-- End Item Tags and Input Field --}}

        {{-- Start items list --}}
        <template
            x-if='expanded'
        >
            <ul
                class="w-full overflow-auto list-none border-2 border-t-0 rounded-md"
                tabindex="0"
                :style="listBoxStyle"
                x-ref="listBox"
            >
            <template x-if="filteredItems.length">
                <template x-for='(filteredItem, idx) in filteredItems'>
                    {{-- Item element --}}
                    <li
                        @click='handleItemClick(filteredItem)'
                        :class="idx == activeIndex && 'bg-amber-200'"
                        x-text='filteredItem.title'
                        class="px-2 py-2 cursor-pointer hover:bg-amber-200"
                    ></li>
                </template>
            </template>
                <template x-if='!filteredItems.length'>
                    {{-- Empty text --}}
                    <li x-text="emptyText" class="px-2 py-2 text-gray-400 cursor-pointer"></li>
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
            selectedItems: @entangle('tags'),
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

                let lastArr = [];
                let jsonTags = {!! json_encode($this->tags) !!};
                testarray = jsonTags.map(obj => Object.entries(obj))
                .map(arr => arr.slice(0,2))
                .map(arr => {
                    let ob = Object.fromEntries(arr)
                    lastArr.push(ob)
                })
                this.selectedItems = [...lastArr]

                this.$watch("filteredItems", (newValues, oldValues) => {
                    // Reset the activeIndex whenever the filteredItems array changes
                    if (newValues.length !== oldValues.length) this.activeIndex = -1;
                });

                this.$watch('selectedItems', (newValue, oldValue) => {
                    if (this.allowDuplicates) return;

                    this.allItems = this.items.filter((item,idx,all) => {
                        return newValue.every(n => n.title !==item.title);
                    }
                    );
                })

                this.$watch("activeIndex", (newValue, oldValue) => {
                    if (
                        this.activeIndex == -1 ||
                        !this.filteredItems[this.activeIndex] ||
                        !this.expanded
                    ) return;
                    this.scrollToActiveElement();
                });
            },
            reset() {
                this.expanded = false;
            },
            handleBlure(e) {
                if (this.$el.contains(e.relatedTarget)) return;
                this.expanded = false;
            },
            handleItemClick(selectedItem) {
                this.selectedItems.push(selectedItem);

                this.search = "";
                this.$refs.searchInput.focus();
            },
            removeElByInd(idx) {
                this.selectedItems.splice(idx, 1);
            },
            selectPrevItem() {
                if (!filteredItems.length) return;
                if (this.activeIndex == 0 || this.activeIndex == -1) {
                    this.activeIndex = this.filteredItems.length - 1;
                }
                this.activeIndex--;
            },
            selectNextItem() {
                if (!filteredItems.length) return;
                if (this.filteredItems.length - 1 == this.activeIndex) return (this.activeIndex = 0);
                this.activeIndex++;
            },
            addActiveItem() {
                if (!this.filteredItems[this.activeIndex]) return;
                this.selectedItems.push(this.filteredItems[this.activeIndex]);
            },
            scrollToActiveElement() {
                const avaliableListElements = [...this.$refs.listBox.children].slice(2, -1);
                avaliableListElements[this.activeIndex].scrollIntoView({
                    block: "end",
                })
            },
            shortenedTitle (title, maxChar) {
                return !maxChars || title.length <= maxChars
                    ? title
                    : `${title.substr(0, maxChars)}...`;
            },
            get listBoxStyle() {
                return {
                    maxHeight: `${this.size * this.itemHeight + 2}px`,
                };
            },
            get filteredItems() {
                return this.allItems.filter(item =>
                item.title.toLowerCase().includes(this.search.toLowerCase())
                )
            },
        }
    }
</script>

{{-- end test functional----------------------------------------------- --}}

<x-form.submit-btn/>
</form>
