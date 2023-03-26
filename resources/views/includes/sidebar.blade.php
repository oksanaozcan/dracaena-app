<aside>
  <nav>
    <ul x-data="sidebarData">
        <template x-for="item in menu">
            <li
                class="divide-y"
                x-data="{
                    open: false,
                    toggle() {
                    this.open = !this.open
                    },
                    close(focusAfter) {
                    this.open = false
                    focusAfter && focusAfter.focus()
                    }
                }"
                @keydown.escape.prevent.stop="close($refs.button)"
                @focusin.window="!$refs.panel.contains($event.target) && close()"
                x-id="['dropdown-buttton']"
            >
                <a href="#" class="flex items-center py-2"
                    x-ref="button"
                    role="button"
                    type="button"
                    @click="toggle()"
                    :aria-expanded="open"
                    :aria-controls="$id('dropdown-button')"
                >
                    <i :class="item.faIcon"></i>
                    <p class="pl-4 grow" x-text="item.title"></p>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="#"
                    x-ref="panel"
                    x-show="open"
                    x-transition:enter="transition ease-in duration-500"
                    x-transition:enter-start="transform origin-top scale-y-0 opacity-50 shadow-none"
                    x-transition:enter-end="transform origin-top scale-y-100 opacity-100 shadow-2xl"
                    x-transition:leave="transition ease-out duration-200"
                    x-transition:leave-start="transform origin-top scale-y-100 opacity-100 shadow-2xl"
                    x-transition:leave-end="transform origin-top scale-y-0 opacity-50 shadow-none"
                    @click.outside="close($refs.button)"
                    :id="$id('dropdown-button')"
                    style="display:none;"
                >
                    <template x-for="link in item.links">
                        <li class="#">
                            <a :href="link.url" class="flex flex-row items-center py-2" role="button" type="button">
                            <i class="pl-2 far fa-circle"></i>
                            <p x-text="link.title" class="pl-4 capitalize"></p>
                            </a>
                        </li>
                    </template>
                </ul>
            </li>
        </template>
    </ul>
  </nav>
</aside>
