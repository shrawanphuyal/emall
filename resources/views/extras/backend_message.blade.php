@if(session('success_message'))
  <div class="alert alert-success stay">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span class="material-icons">clear</span></button>
    <p>{!! session('success_message') !!}</p>
  </div>
@elseif(session('failure_message'))
  <div class="alert alert-warning stay">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span class="material-icons">clear</span></button>
    <p>{!! session('failure_message') !!}</p>
  </div>
@endif

@if(count($errors))
  <div class="alert stay alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span class="material-icons">clear</span></button>
    @foreach($errors->all() as $error)
      <p>{!! $error !!}</p>
    @endforeach
  </div>
@endif
