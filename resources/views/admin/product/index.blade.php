@extends('admin.layouts.app')

@section('title', 'All '. ucwords(str_plural($routeType)))

@section('content')
  <div class="card">
    @include('extras.index_header')

    <div class="card-content">

      <ul class="nav nav-pills nav-pills-warning">
        <li class="active">
          <a href="#allProducts" data-toggle="tab">All Products</a>
        </li>
        <li>
          <a href="#assignDiscountToProducts" data-toggle="tab">Assign Discount</a>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="allProducts">
          <div class="table-responsive">
            <table class="table">
              <thead>
              <tr>
                <th width="40">#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Price</th>
                <th>Category</th>
                <th width="80">Actions</th>
              </tr>
              </thead>
              <tbody>
              @forelse($products as $key=>$product)
                <tr id="asdh-{{$product->id}}">
                  <td>{{$key+1}}</td>
                  <td width="60"><img src="{{ $product->hasImages() ? $product->images()->first()->image(50,50) : $product->image(50,50) }}" alt="{{$product->title}}"></td>
                  <td>{{$product->title}}</td>
                  <td>{{$product->quantity}}</td>
                  <td>{{$product->discount_type?($product->discount):($product->discount.' %')}}</td>
                  <td>{{$product->price}}</td>
                  <td>{{optional($product->category)->name}}</td>
                  <td class="asdh-edit_and_delete td-actions">
                    @include('extras.edit_delete', ['modal'=>$product, 'message'=>'You will not be able to recover your data in the future.'])
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


        <div class="tab-pane" id="assignDiscountToProducts">
          <form action="{{ route('product.assign-discount-percentage') }}"
                method="post"
                class="from-prevent-multiple-submit">
            {{ csrf_field() }}
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
                  <th width="40">#</th>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Quantity</th>
                  <th>Discount</th>
                  <th>Price</th>
                  <th>Category</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $key=>$product)
                  <tr id="asdh-{{$product->id}}">
                    <td>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="check-it" name="product_ids[]" value="{{ $product->id }}">
                        </label>
                      </div>
                    </td>
                    <td>{{$key+1}}</td>
                    <td width="60"><img src="{{ $product->hasImages() ? $product->images()->first()->image(50,50) : $product->image(50,50) }}" alt="{{$product->title}}"></td>
                    <td>{{$product->title}}</td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->discount_type?($product->discount):($product->discount.' %')}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{optional($product->category)->name}}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4">No data available</td>
                  </tr>
                @endforelse
                </tbody>
              </table>
            </div>

            <hr style="margin: 0;padding: 0;">

            <div class="footer" style=" padding: 15px 0;">
              <div class="row">
                <div class="col-xs-8 col-sm-10">
                  {{--discount_percentage--}}
                  <div class="form-group label-floating">
                    <label class="control-label" for="discount_percentage">Discount Percentage
                      <small>*</small>
                    </label>
                    <input type="number"
                           class="form-control"
                           id="discount_percentage"
                           name="discount_percentage"
                           min="0"
                           max="100"
                           required="true"
                           value="{{old('discount_percentage')}}"/>
                  </div>
                  {{--./discount_percentage--}}
                </div>
                <div class="col-xs-4 col-sm-2 text-right">
                  <button type="submit" class="btn btn-success btn-sm btn-prevent-multiple-submit">Assign</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="text-center">
        {{ $products->links() }}
      </div>
    </div>

  </div>
@endsection

@if($products->count())
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
            'targets': [5]
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
      });
    </script>
  @endpush
@endif