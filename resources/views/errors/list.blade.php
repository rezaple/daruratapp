@if($errors->any())
  <ul>
    @foreach($errors->all() as $eror)
      <li>{{ $eror }}</li>
    @endforeach
  </ul>
@endif
