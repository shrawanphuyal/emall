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
        <h4 class="card-title">{{ $edit?'Edit':'Add a New '. ucfirst($routeType) }}</h4>
      </div>

      <div class="card-content">
        {{--image--}}
        @if($edit)
          @include('extras.input_image', ['input_image'=>$model->image(200,200)])
        @else
          @include('extras.input_image')
        @endif
        {{--./image--}}

        {{--url--}}
        <div class="form-group" {{ $errors->has('url')?'has-error is-focused':'' }}>
          <label for="url">{{ ucwords('url') }} <small>*</small></label>
          <input type="url"
                 class="form-control"
                 id="url"
                 name="url"
                 required="true"
                 value="{{$edit?old('url')??$model->url:old('url')}}"/>

        </div>
        {{--./url--}}

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
  <script>
    $(document).ready(function () {

    });
  </script>
@endpush