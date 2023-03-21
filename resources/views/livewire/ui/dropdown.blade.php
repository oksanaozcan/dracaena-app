<div>
  <a href="#" class="#">
    <i class="{{$faIcon}}"></i>
    <p>
      {{$title}}
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="#">
    @foreach ($links as $link)
      <li class="nav-item">
        <a href="#" class="#">
          <i class="far fa-circle"></i>
          <p>{{$link}}</p>
        </a>
      </li>
    @endforeach   
  </ul> 
</div>