@extends('admin.layouts.app')

@section('title', 'All Vendors')

@section('content')
  <form action="{{ route('vendor.assign-category') }}" method="post" class="from-prevent-multiple-submit">
    {{ csrf_field() }}
    <div class="card">
      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">All Vendors</h4>
      </div>

      <div class="card-content">
        <div class="table-responsive">
          <table class="table">
            <thead>
            <tr>
              <th width="40">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="check-all">
                  </label>
                </div>
              </th>
              <th>Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Category</th>
              <th>Products</th>
            </tr>
            </thead>
            <tbody>
            @forelse($vendors as $key=>$vendor)
              <tr id="asdh-{{$vendor->id}}">
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" class="check-it" name="vendor_ids[]" value="{{ $vendor->id }}">
                    </label>
                  </div>
                </td>
                <td width="60"><img src="{{$vendor->image(50,50)}}" alt="{{$vendor->name}}"></td>
                <td>
                  <a href="{{ route('vendor.show', $vendor) }}" title="See all the products of this vendor">
                    {{ $vendor->name }} <i class="fa fa-eye"></i>
                  </a>
                </td>
                <td>{{$vendor->email}}</td>
                <td>{{$vendor->phone}}</td>
                <td>
                  @forelse($vendor->categories as $vendor_category)
                    {{ $vendor_category->name }},
                  @empty
                    Not assigned
                  @endforelse
                </td>
                <td class="text-center">
                  <a href="{{ route('vendor.show', $vendor) }}" title="See all the products">
                    {{$vendor->products->count()}}
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4">No data available</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <hr style="margin: 0;padding: 0;">

      <div class="footer" style=" padding: 15px 20px;">
        <div class="row">
          <div class="col-xs-3 col-sm-3">
            <div class="form-group">
              <label for="">Choose category:</label>
            </div>
          </div>
          <div class="col-xs-5 col-sm-7">
            {{--category--}}
            <select name="category_ids[]"
                    id="category_id"
                    class="selectpicker"
                    data-style="select-with-transition"
                    data-size="5"
                    data-live-search="true"
                    multiple="true"
                    required="true">
              {{--<option value="">Choose Category</option>--}}
              @foreach($categories as $category)
                <option value="{{$category->id}}"
                        @if($category->id==request()->category_id) selected @endif>{{$category->name}}</option>
              @endforeach
            </select>
            {{--./category--}}

            <div class="add-vendor-category">
              <p class="text-right"><a href="#" class="add-vendor-category-trigger"><i class="fa fa-plus-square"></i>
                  Add Category</a></p>
              <div class="add-vendor-category-content" style="display:none;">
                {{--category_name--}}
                <div class="form-group">
                  <input type="text"
                         class="form-control"
                         id="category_name"
                         name="category_name"
                         placeholder="Enter Category Name*"/>
                </div>
                {{--./category_name--}}
                <div class="text-right">
                  <a href="{{ route('vendor-category.store') }}"
                     class="btn btn-success btn-xs add-vendor-category-submit">Add</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-4 col-sm-2 text-right">
            <button type="submit" class="btn btn-success btn-sm btn-prevent-multiple-submit">Assign</button>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@if($vendors->count())
  @push('script')
    <script>
      $(document).ready(function () {
        /*$('table').dataTable({
          "paging": true,
          "lengthChange": true,
          "lengthMenu": [10, 15, 20],
          "searching": true,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          'columnDefs': [{
            'orderable': false,
            'targets': [0]
          }]
        });*/

        var $checkAll = $('#check-all'),
          $checkIt = $('.check-it');

        $checkAll.change(function () {
          if ($(this).is(':checked')) {
            $checkIt.prop('checked', true);
          } else {
            $checkIt.prop('checked', false);
          }
        });

        $('.add-vendor-category-trigger').on('click', function (event) {
          event.preventDefault();
          $('.add-vendor-category-content').toggle();
        });

        $('.add-vendor-category-submit').on('click', function (event) {
          event.preventDefault();
          var $categoryName = $('#category_name'),
            $category = $('#category_id');
          if ($categoryName.val() === '') {
            alert('Category name is empty.');
          } else {
            $.ajax({
              url: $(this).attr('href'),
              type: 'post',
              data: {'category_name': $categoryName.val()},
              success: function (data) {
                $category.append('<option value="' + data.category.id + '" selected>' + data.category.name + '</option>');
                $category.selectpicker('refresh');
              },
              error: function (data) {
                console.log('Error: ', data);
              }
            });
          }
        })
      });
    </script>
  @endpush
@endif