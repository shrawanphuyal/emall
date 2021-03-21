@extends('admin.layouts.app')

@section('title', 'Add a New ' . ucfirst($routeType))

@section('content')

  <form action="{{$edit?route($routeType.'.update',$news):route($routeType.'.store')}}"
        method="post"
        enctype="multipart/form-data"
        id="TypeValidation">
    {{csrf_field()}}
    {{$edit?method_field('PUT'):''}}
    <div class="card">

      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">{{ $edit?'Edit':'Add a New' }} {{ ucfirst($routeType) }}</h4>
      </div>

      <div class="card-content">

        <div class="row">

          <div class="col-md-2">
            {{--image--}}
            @if($edit)
              @include('extras.input_image', ['input_image'=>$news->image(200,200)])
            @else
              @include('extras.input_image')
            @endif
          </div>

          <div class="col-md-10">
            {{--title--}}
            <div class="form-group label-floating">
              <label class="control-label" for="title">
                Title
                <small>*</small>
              </label>
              <input type="text"
                     class="form-control"
                     id="title"
                     name="title"
                     required="true"
                     value="{{$edit?$news->title:old('title')}}"/>
            </div>

            @if($edit)
              {{--slug--}}
              <div class="form-group">
                <label class="control-label" for="slug">Slug</label>
                <input type="text" id="slug" class="form-control" disabled value="{{ $news->slug }}"/>
              </div>
            @endif

            {{--description--}}
            <div class="form-group">
              <label for="description">Description *</label>
              <textarea class="form-control asdh-tinymce"
                        id="description"
                        name="description"
                        rows="10">{{$edit?$news->description:old('description')}}</textarea>
            </div>
          </div>
        </div>

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">{{$edit?'Update':'Add'}}</button>
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