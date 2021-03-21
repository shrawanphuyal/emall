@extends('admin.layouts.app')

@section('title', 'Add a new category')

@section('content')

  <form action="{{$edit?route('category.update',$category):route('category.store')}}"
        method="post"
        enctype="multipart/form-data"
        id="TypeValidation">
    {{csrf_field()}}
    {{$edit?method_field('PUT'):''}}
    <div class="card">

      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">{{$edit?'Edit':'Add a New'}} Category</h4>
      </div>

      <div class="card-content">

        <div class="asdh-add_multiple_container">
          {{--file--}}
          <div class="form-group">
            <label for="image">Image</label>
            <input type="text" class="form-control" readonly value="{{$edit?$category->getOriginal('image'):''}}">
            <input type="file" class="form-control" name="{{$edit?'image':'image[]'}}" accept="image/*">
          </div>
          {{--./file--}}

          {{--name--}}
          <div class="form-group">
            <label class="" for="name">Category Name</label>
            <input type="text"
                   class="form-control"
                   id="name"
                   name="{{$edit?'name':'name[]'}}"
                   value="{{$edit?$category->name:''}}"
                   required="true"/>
            @if(!$edit)
              <span class="asdh-add_remove_multiple add" title="Add">
                <i class="material-icons">add_circle</i>
              </span>
            @endif
          </div>
        </div>

        @if($edit)
          {{--slug--}}
          <div class="form-group">
            <label class="control-label" for="slug">Slug</label>
            <input type="text" class="form-control" id="slug" value="{{ $category->slug }}" title="Disabled" disabled/>
          </div>
        @endif

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">{{$edit?'Update':'Add'}}</button>
        </div>

      </div>

    </div>
  </form>
@endsection

@push('script')
  <script>
    $(document).ready(function () {
      $('#name').focus();

      @if(!$edit)
      /*Add and remove multiple fields starts*/
      var html = '', multipleAddLimit = 1;

      html += '<div>';
      html += '<div class="form-group" style="margin-top: 30px;">';
      html += ' <input type="text" class="form-control" readonly placeholder="Other Image">';
      html += ' <input type="file" class="form-control" name="image[]" accept="image/*" />';
      html += '</div>';

      html += '<div class="form-group asdh-remove_margin_padding_from_add_remove_multiple">';
      html += ' <input type="text" class="form-control" name="name[]" placeholder="Other Category Name" required="true" />';
      html += ' <span class="asdh-add_remove_multiple remove" title="Remove"><i class="material-icons">delete</i></span>';
      html += '</div>';
      html += '</div>';

      $('.asdh-add_remove_multiple.add').on('click', function (e) {
        e.preventDefault();
        var $appendTo = $('.asdh-add_multiple_container');
        if (multipleAddLimit < 5) {
          $($appendTo).append(html);
          multipleAddLimit++;
        } else {
          alert('Limit reached.')
        }

        $('.asdh-add_remove_multiple.remove').on('click', function (e) {
          e.preventDefault();
          $(this).parent().parent().remove();
          // I am assigning this value to multipleAddLimit because when remove button is clicked,
          // the event triggers for the number of html added to the container.
          multipleAddLimit = $appendTo.children().length;
        });
      });
      /*Add and remove multiple fields ends*/
      @endif

    });
  </script>
@endpush