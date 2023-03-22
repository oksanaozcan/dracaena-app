<div
  x-data="{
    open: false,
    toggle() {
      this.open = this.open ? this.close() : true
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
  <a href="#" class="#"
    x-ref="button"
    role="button"
    type="button"
    @click="toggle()"
    :aria-expanded="open"
    :aria-controls="$id('dropdown-button')"
  >
    <i class="{{$faIcon}}"></i>
    <p>
      {{$title}}
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="#"
    x-ref="panel"
    x-show="open"
    x-transition.origin.top.left
    @click.outside="close($refs.button)"
    :id="$id('dropdown-button')"
    style="display:none;"
  >
    @foreach ($links as $key => $val)
      <li class="#">
        <a href="{{$val}}" class="#" role="button" type="button">
          <i class="far fa-circle"></i>
          <p class="capitalize">{{$key}}</p>
        </a>
      </li>
    @endforeach   
  </ul> 
</div>