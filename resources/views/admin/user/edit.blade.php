@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')

  <form action="{{route('user.update', $user->id)}}" method="post" enctype="multipart/form-data" id="TypeValidation">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="card">
      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">Edit {{ auth()->user()->hasRole('admin')?'User':'Profile' }}</h4>
      </div>

      <div class="card-content">

        <div class="row">
          <div class="col-md-2">

            {{--image--}}
            @include('extras.input_image', ['input_image'=>$user->image(200,200)])
            <hr>

            @if(auth()->user()->hasRole('admin'))
              {{--roles--}}
              <div class="form-group">
                <label for="roles">Assign roles</label>
                @foreach($roles as $role)
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"
                             name="role[]"
                             value="{{$role->id}}"
                             @if($user->has_role($role->id)) checked @endif> {{$role->name()}}
                    </label>
                  </div>
                @endforeach
              </div>
              {{--roles--}}
              <hr>
            @endif

          </div>
          <div class="col-md-10">

            <div class="row">
              {{--name--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label class="control-label" for="name">
                    Name
                    <small>*</small>
                  </label>
                  <input type="text"
                         class="form-control"
                         id="name"
                         name="name"
                         required="true"
                         value="{{ $user->getOriginal('name') }}"/>
                </div>
              </div>
              {{--email--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label class="control-label" for="email">
                    Email
                    <small>*</small>
                  </label>
                  <input type="email"
                         class="form-control"
                         id="email"
                         name="email"
                         required="true"
                         email="true"
                         value="{{$user->email}}"/>
                </div>
              </div>
            </div>

            <div class="row">
              {{--address--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label class="control-label" for="address">
                    Address
                  </label>
                  <input type="text"
                         class="form-control"
                         id="address"
                         name="address"
                         value="{{$user->getOriginal('address')}}"/>
                </div>
              </div>
              {{--phone--}}
              <div class="col-md-6">
                <div class="form-group label-floating">
                  <label class="control-label" for="phone">
                    Phone
                  </label>
                  <input type="text"
                         class="form-control"
                         id="phone"
                         name="phone"
                         number="true"
                         value="{{$user->phone}}"/>
                </div>
              </div>
            </div>

            {{--about--}}
            <div class="form-group">
              <label class="control-label" for="about">About</label>
              <textarea class="form-control asdh-tinymce" id="about" name="about" rows="10">{{$user->about}}</textarea>
            </div>

          </div>
        </div>

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">Update</button>
        </div>

      </div>
    </div>
  </form>
@endsection

@push('script')
  @include('extras.tinymce')
  <script>
    $(document).ready(function () {
      $('#name').focus();
    });
  </script>
@endpush