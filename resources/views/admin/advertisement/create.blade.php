@extends('admin.layouts.app')

@section('title', 'Add a new '. ucfirst($routeType))

@section('content')

  <form action="{{$edit?route($routeType.'.update',$model):route($routeType.'.store')}}"
        method="post"
        enctype="multipart/form-data"
        id="TypeValidation">
    {{csrf_field()}}
    {{$edit?method_field('PUT'):''}}
    <div class="card">

      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">{{ $edit?'Edit '. $model->title : 'Add a New '. ucfirst($routeType) }}</h4>
      </div>

      <div class="card-content">
        {{--image--}}
        @if($edit)
          @include('extras.input_image', ['input_image'=>$model->showImageCropped(200,200)])
        @else
          @include('extras.input_image')
        @endif
        {{--./image--}}

        {{--title--}}
        <div class="form-group" {{ $errors->has('title')?'has-error is-focused':'' }}>
          <label for="title">{{ ucwords('title') }}</label>
          <input type="text"
                 class="form-control"
                 id="title"
                 name="title"
                 value="{{$edit?old('title')??$model->title:old('title')}}"/>
        </div>
        {{--./title--}}

        {{--url--}}
        <div class="form-group" {{ $errors->has('url')?'has-error is-focused':'' }}>
          <label for="url">{{ ucwords('url') }}
            <small>*</small>
          </label>
          <input type="text"
                 class="form-control"
                 id="url"
                 name="url"
                 required="true"
                 value="{{$edit?old('url')??$model->url:old('url')}}"/>
        </div>
        {{--./url--}}

        {{--home--}}
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="home" value="1" {{ $edit?($model->home?'checked':''):(old('home')?'checked':'') }}> Home
            </label>
          </div>
        </div>
        {{--./home--}}

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">{{$edit?'Update':'Add'}}</button>
        </div>
        {{--./submit--}}

      </div>

    </div>
  </form>
@endsection

@push('script')
  @include('extras.tinymce')
  <script>
    $(document).ready(function () {
      $('#title').focus();
    });
  </script>
@endpush