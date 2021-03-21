@extends('admin.layouts.app')

@section('title', 'Add a new '. ucfirst($routeType))

@section('content')

  <form action="{{$edit?route($routeType.'.update',$test):route($routeType.'.store')}}"
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
        <div class="asdh-add_multiple_container">
          {{--add content--}}
          <div class="form-group label-floating">
            <label class="control-label" for="add_content">Add content</label>
            <input type="text"
                   class="form-control"
                   id="add_content"
                   name="{{$edit?'add_more':'add_more[]'}}"
                   value="{{$edit?$test->name:''}}"
                   required="true"/>
            @if(!$edit)
              <span class="asdh-add_remove_multiple add" title="Add">
                <i class="material-icons">add_circle</i>
              </span>
            @endif
          </div>
          {{--./add content--}}
        </div>

        {{--name--}}
        <div class="form-group label-floating">
          <label class="control-label" for="name">Name
            <small>*</small>
          </label>
          <input type="text"
                 class="form-control"
                 id="name"
                 name="name"
                 required="true"
                 value="{{$edit?$test->name:old('name')}}"/>
        </div>
        {{--./name--}}

        {{--image--}}
        @if($edit)
          @include('extras.input_image', ['input_image'=>$image->image(200,200)])
        @else
          @include('extras.input_image')
        @endif
        {{--./image--}}

        {{--description--}}
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control asdh-tinymce"
                    id="description"
                    name="description"
                    rows="10">{{$edit?$test->description:old('description')}}</textarea>
        </div>
        {{--./description--}}

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
      $('#name').focus();

      @if(!$edit)
      /*Add and remove multiple fields starts*/
      var html = '', multipleAddLimitStart = 1, multipleAddLimitEnd = 5;

      html += '<div class="form-group asdh-remove_margin_padding_from_add_remove_multiple">';
      html += ' <input type="text" class="form-control" name="add_more[]" placeholder="Other Add More Name" required="true" />';
      html += ' <span class="asdh-add_remove_multiple remove" title="Remove"><i class="material-icons">delete</i></span>';
      html += '</div>';

      $('.asdh-add_remove_multiple.add').on('click', function (e) {
        e.preventDefault();
        var $appendTo = $('.asdh-add_multiple_container');
        if (multipleAddLimitStart < multipleAddLimitEnd) {
          $($appendTo).append(html);
          multipleAddLimitStart++;
        } else {
          alert('Limit reached.')
        }

        $('.asdh-add_remove_multiple.remove').on('click', function (e) {
          e.preventDefault();
          $(this).parent().remove();
          // I am assigning this value to multipleAddLimitStart because when remove button is clicked,
          // the event triggers for the number of html added to the container.
          multipleAddLimitStart = $appendTo.children().length;
        });
      });
      /*Add and remove multiple fields ends*/
      @endif

    });
  </script>
@endpush