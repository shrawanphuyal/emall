@extends('admin.layouts.app')

@section('title', 'Add a new '. ucfirst($routeType))

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.8/css/fileinput.min.css">
  <style>
    .file-thumbnail-footer {
      display : none;
    }

    .krajee-default.file-preview-frame {
      width  : 80px;
      height : 80px;
    }

    .krajee-default.file-preview-frame .kv-file-content, .krajee-default.file-preview-frame .kv-file-content img {
      width  : 100%;
      height : 100% !important;
    }

    .file-caption-name {
      color : #aaa;
    }
  </style>
@endpush

@section('content')

  <form action="{{$edit?route($routeType.'.update',$product):route($routeType.'.store')}}"
        method="post"
        enctype="multipart/form-data"
        id="TypeValidation">
    {{csrf_field()}}
    {{$edit?method_field('PUT'):''}}
    <div class="card">

      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">{!! $edit?'Edit <b>'.$product->title.'</b>':'Add a New '.ucfirst($routeType) !!}</h4>
      </div>

      <div class="card-content">

        <div class="row">
          <div class="col-md-3">
            {{--image--}}
            {{--@if($edit)
              @include('extras.input_image', ['input_image'=>$product->image(140,140)])
            @else
              @include('extras.input_image')
            @endif--}}
            {{--./image--}}
            @if($edit)
              <div class="text-center">
                @if($product->getOriginal('image'))
                  <img src="{{ $product->image }}"
                       style="width:60px;height:40px;margin-right:5px;border:1px solid #ccc;">
                @endif

                @foreach($product->images as $image)
                  <img src="{{ $image->image }}"
                       style="width:150px;height:150px;margin-right:5px;border:1px solid #ccc;">
                @endforeach

                <p style="width:100%;background:#555;padding:2px 3px;color:#fff;border-radius:0 0 8px 8px;">Previous Images</p>
              </div>
            @endif
            <div class="form-group">
              <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
            </div>

            {{--category_id--}}
            <div class="form-group" style="margin-top: 30px;">
              <label for="category_id">Category</label>
              <select name="category_id"
                      id="category_id"
                      class="selectpicker"
                      {{--data-style="btn btn-primary btn-round asdh-color-777"--}}
                      data-style="select-with-transition"
                      data-size="5"
                      data-live-search="true"
                      data-url="{{route('ajax.sub-category.show-hide')}}"
                      @if($edit) data-product-id="{{$product->id}}" @endif>
                <option value="">Choose Category</option>
                @foreach($categories as $category)
                  <option value="{{$category->id}}"
                          @if($edit) @if($category->id==$product->category_id) selected @endif @endif>
                    {{$category->name}} {{$category->has_sub_categories()?'+':''}}</option>
                @endforeach
              </select>
              <div class="material-icons select-drop-down-arrow">keyboard_arrow_down</div>
            </div>
            {{--./category_id--}}

            {{--sub_category_id--}}
            <div class="asdh-sub_category">
              <!-- add sub-categories by ajax -->
              @if($edit && !is_null($product->sub_category_id))
                <div class="form-group">
                  <label class="" for="sub_category_id">Sub-Category</label>
                  <select name="sub_category_id"
                          id="sub_category_id"
                          class="selectpicker"
                          data-style="select-with-transition"
                          data-size="5"
                          data-live-search="true">
                    <option value="">Choose Sub-Category</option>
                    @foreach($sub_categories as $sub_category)
                      <option value="{{$sub_category->id}}"
                              @if($sub_category->id==$product->sub_category_id) selected @endif>{{$sub_category->name}}</option>
                    @endforeach
                  </select>
                  <div class="material-icons select-drop-down-arrow">keyboard_arrow_down</div>
                </div>
              @endif
            </div>
            {{--./sub_category_id--}}

            {{--sub_sub_category_id--}}
            <div class="asdh-sub_sub_category">
              <!-- add sub-categories by ajax -->
            </div>
            {{--./sub_sub_category_id--}}

            {{--featured--}}
            {{--<div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="featured" value="1"> Featured
                </label>
              </div>
            </div>--}}
            {{--./featured--}}

            {{--gender--}}
            <div class="form-group" style="margin-top: 30px;">
              <label for="">
                <span class="checkbox" style="display: inline-block;">
                  <label>
                    <input type="checkbox" data-id="#gender-container" id="toggle-gender"> Gender
                  </label>
                </span>
              </label>

              <div id="gender-container" style="display: none;">
                <div class="radio" style="display:inline-block;">
                  <label>
                    <input type="radio"
                           name="gender"
                           value="1"
                        {{ $edit?($product->gender===1?'checked':null):null }}> Male
                  </label>
                </div>
                <div class="radio" style="display: inline-block;">
                  <label>
                    <input type="radio"
                           name="gender"
                           value="0"
                        {{ $edit?($product->gender===0?'checked':null):null }}> Female
                  </label>
                </div>
              </div>
            </div>
            {{--./gender--}}

            {{--sale--}}
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="sale" value="1" {{ $edit?($product->sale?'checked':''):'' }}> Sale
                </label>
              </div>
            </div>
            {{--./sale--}}

          </div>

          <div class="col-md-9">
            {{--title--}}
            <div class="form-group label-floating">
              <label class="" for="title">
                Title
                <small>*</small>
              </label>
              <input type="text"
                     class="form-control"
                     id="title"
                     name="title"
                     required="true"
                     value="{{$edit?$product->title:old('title')}}"/>
            </div>

            {{--quantity--}}
            <div class="form-group label-floating">
              <label class="" for="quantity">
                Quantity
                <small>*</small>
              </label>
              <input type="text"
                     class="form-control"
                     id="quantity"
                     name="quantity"
                     required="true"
                     value="{{$edit?$product->quantity:old('quantity')}}"/>
            </div>

            {{--price--}}
            <div class="form-group label-floating">
              <label class="" for="price">Price
                <small>*</small>
              </label>
              <div class="form-line">
                <input type="text"
                       name="price"
                       id="price"
                       class="form-control"
                       value="{{$edit?$product->price:old('price')}}"
                       required="true">
              </div>
            </div>
            {{--./price--}}

            <div class="row">
              <div class="col-md-6">
                {{--discount type--}}
                <div class="form-group">
                  <label for="">Discount Type</label><br>
                  <div class="radio" style="display: inline-block;">
                    <label>
                      <input type="radio"
                             name="discount_type"
                             value="1"
                             @if($edit) @if($product->discount_type==1) checked="true" @endif
                             @else checked="true" @endif> Amount
                    </label>
                  </div>
                  <div class="radio" style="display: inline-block;">
                    <label>
                      <input type="radio"
                             name="discount_type"
                             value="0"
                             @if($edit) @if($product->discount_type==0) checked="true" @endif @endif> Percentage
                    </label>
                  </div>
                </div>
                {{--./discount type--}}
              </div>
              <div class="col-md-6">
                {{--discount--}}
                <div class="form-group">
                  <label for="discount">Discount</label>
                  <div class="form-line">
                    <input type="number"
                           name="discount"
                           id="discount"
                           class="form-control"
                           value="{{$edit?$product->discount:old('discount')|0}}"
                           required="true">
                  </div>
                </div>
                {{--./discount--}}
              </div>
            </div>

            {{--description--}}
            <div class="form-group">
              <label class="" for="description">Description</label>
              <textarea class="form-control asdh-tinymce"
                        id="description"
                        name="description"
                        rows="10">{{$edit?$product->description:old('description')}}</textarea>
            </div>
            {{--./description--}}

            {{--specification--}}
            <div class="form-group">
              <label class="" for="specification">Specification</label>
              <textarea class="form-control asdh-tinymce"
                        id="specification"
                        name="specification"
                        rows="10">{{$edit?$product->specification:old('specification')}}</textarea>
            </div>
            {{--./specification--}}

          </div>
        </div>

        {{--submit--}}
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-success btn-fill">{{ $edit?'Update':'Save' }}</button>
        </div>
        {{--./submit--}}

      </div>

    </div>
  </form>
@endsection

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.8/js/fileinput.min.js"></script>
  @include('extras.tinymce')
  <script>
    var $subSubCategoryC = $('.asdh-sub_sub_category');

    $(document).ready(function () {
      $("#images").fileinput({
        'showUpload': false,
        'previewFileType': 'any',
        dropZoneEnabled: false,
        maxFileCount: 5,
      });

      $('.fileinput-remove.fileinput-remove-button').addClass('btn-xs');
      $('.btn.btn-primary.btn-file').addClass('btn-xs');
      $('.file-caption-name').val('Choose Images');

      var $subCategoryC = $('.asdh-sub_category');
      var $category = $('#category_id');
      var $subCategory = $('#sub_category_id');
      var $toggleGender = $('#toggle-gender');

      @if($edit)
      showIfSubCategoriesExist($category, $category.data('product-id'));
      getSubSubCategoriesOf($subCategory.val(), {{$product->sub_sub_category_id}});
      @endif

      $category.change(function () {
        $subSubCategoryC.children().remove();
        if ($(this).val() > 0) {
          showIfSubCategoriesExist($(this), $(this).data('product-id'));
        } else {
          $subCategoryC.children().remove();
        }
      });

      $toggleGender.on('change', function () {
        var $this = $(this);
        var $elementToToggle = $($this.data('id'));
        if ($this.is(':checked')) {
          $elementToToggle.show();
        } else {
          $elementToToggle.hide();
          $('input[name="gender"]').prop('checked', false);
        }
      });

      $('#name').focus();
    });

    $(document).on('change', '#sub_category_id', function () {
      var $this = $(this);
      var subCategoryId = $this.val();
      @if($edit)
      getSubSubCategoriesOf(subCategoryId, {{$product->sub_sub_category_id}});
      @else
      getSubSubCategoriesOf(subCategoryId);
      @endif
    });

    function showIfSubCategoriesExist($this, product_id) {
      var $subCategoryC = $('.asdh-sub_category');
      $.ajax({
        url: $this.data('url'),
        data: {category_id: $this.val(), product_id: product_id},
        type: 'get',
        success: function (data) {
          // if category has sub-category data.status will be true
          if (data.status) {
            // if sub-categories from previous request is present then remove them
            $subCategoryC.children().remove();
            // append sub-categories to the container
            $subCategoryC.append(data.data);
            // refresh bootstrap-select
            $('#sub_category_id').selectpicker('refresh');
          } else {
            // remove any sub-categories if present to prevent conflict while saving on database
            $subCategoryC.children().remove();
          }
        },
        error: function (data) {
          console.log('Error: ', data);
        }
      });
    }

    function getSubSubCategoriesOf(subCategoryId, subSubCategoryId) {
      $.ajax({
        url: '{{ route('api.get-sub-sub-categories') }}' + '/' + subCategoryId,
        success: function (data) {
          $subSubCategoryC.children().remove();

          if (data.length > 0) {
            var html = '<div class="form-group">' +
              '                  <label class="" for="sub_sub_category_id">Sub sub Category</label>' +
              '                  <select name="sub_sub_category_id"' +
              '                          id="sub_sub_category_id"' +
              '                          class="selectpicker"' +
              '                          data-style="select-with-transition"' +
              '                          data-size="5"' +
              '                          data-live-search="true">' +
              '                    <option value="">Choose Sub sub Category</option>';

            for (var i = 0; i < data.length; i++) {
              var selectedText = subSubCategoryId == data[i].id ? 'selected' : '';
              html += '<option value="' + data[i].id + '" ' + selectedText + '>' + data[i].name + '</option>';
            }

            html += '</select>';
            html += '<div class="material-icons select-drop-down-arrow">keyboard_arrow_down</div>';
            html += '</div>';

            $subSubCategoryC.append(html);
            $('#sub_sub_category_id').selectpicker('refresh');
          }
        }
      });
    }

  </script>
@endpush