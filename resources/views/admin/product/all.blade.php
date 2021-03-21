@extends('admin.layouts.app')

@section('title', 'All Products')

@section('content')
  <form action="{{ route('product.assign-discount') }}" method="post">
    {{ csrf_field() }}
    <div class="card">
      <div class="card-header card-header-text" data-background-color="green">
        <h4 class="card-title">All Products</h4>
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
              <th>Title</th>
              <th>Profit %</th>
              <th>Discount</th>
              <th>Quantity</th>
              <th>Price</th>
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
                <td width="60"><img src="{{$product->image(50,50)}}" alt="{{$product->title}}"></td>
                <td>{{$product->title}}</td>
                <td>{{$product->admin_profit_percentage}}</td>
                <td>{{$product->discount_type?($product->discount):($product->discount.' %')}}</td>
                <td>{{$product->quantity}}</td>
                <td>{{$product->price}}</td>
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
          <div class="col-xs-8 col-sm-10">
            {{--profit_percentage--}}
            <div class="form-group label-floating">
              <label class="control-label" for="profit_percentage">Profit Percentage
                <small>*</small>
              </label>
              <input type="text"
                     class="form-control"
                     id="profit_percentage"
                     name="profit_percentage"
                     required="true"
                     value="{{old('profit_percentage')}}"/>
            </div>
            {{--./profit_percentage--}}
          </div>
          <div class="col-xs-4 col-sm-2 text-right">
            <button type="submit" class="btn btn-success btn-sm">Assign</button>
          </div>
        </div>
      </div>
    </div>
  </form>
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
            $checkIt  = $('.check-it');

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