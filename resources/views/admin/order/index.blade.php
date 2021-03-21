@extends('admin.layouts.app')

@section('title', 'All '. ucwords(str_plural($routeType)))

@push('css')
  <style>
    #processing-cover {
      position   : fixed;
      left       : 0;
      right      : 0;
      top        : 0;
      bottom     : 0;
      background : rgba(0, 0, 0, 0.9);
      z-index    : 9999;
      display    : none;
    }

    #processing {
      position      : fixed;
      top           : 50%;
      left          : 50%;
      background    : #555;
      color         : #fff;
      padding       : 10px 15px;
      border-radius : 25px;
    }
  </style>
@endpush

@section('content')

  <div id="vue-instance">
    <div class="card">
      <div class="card-header card-header-text"
           data-background-color="green">
        <h4 class="card-title">All {{str_plural(ucwords($routeType))}}</h4>
      </div>

      <div class="card-content">
        <div class="order-status-indicator order-pending"></div>
        Pending
        <div class="order-status-indicator order-processing"></div>
        Processing
        <div class="order-status-indicator order-delivered"></div>
        Delivered

        <div class="table-responsive">
          <table class="table datatable">
            <thead>
            <tr>
              <th width="60">Id</th>
              <th>Ordered At</th>
              <th>Customer Name</th>
              <th>Customer Phone</th>
              <th>Customer Address</th>
              <th>Status</th>
              <th width="80">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($models as $key=>$model)
              <tr id="asdh-{{$model->id}}"
                  class="order-{{ $model->status }}">
                <td>{{ $loop->index+1 }}</td>
                <td>{{$model->created_at->format('M d, h:i a')}}</td>
                <td>{{ $model->user->name }}</td>
                <td>{{$model->user->phone}}</td>
                <td>{{$model->user->address}}</td>
                <td>
                  <select name="status"
                          id="order-status"
                          @change="changeStatus('{{ route('order.change-status', $model) }}', {{ $model->id }}, $event)">
                    @foreach(['pending', 'processing', 'delivered'] as $orderStatus)
                      <option value="{{ $orderStatus }}" {{ $orderStatus==$model->status?'selected':'' }}>{{ $orderStatus }}</option>
                    @endforeach
                  </select>
                </td>
                <td class="asdh-edit_and_delete td-actions">
                  <button type="button"
                          class="btn btn-warning"
                          data-toggle="modal"
                          data-target="#view-products"
                          @click="getProducts({{ $model->id }})"
                          title="View Detail">
                    <i class="material-icons">remove_red_eye</i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">No data available</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade asdh-delete make-darker"
         id="view-products"
         tabindex="-1"
         role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"
               style="padding: 20px 24px">
            <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-hidden="true">
              <i class="material-icons">clear</i>
            </button>
            <h4 class="modal-title"
                v-if="products.length">All products of order with
              <b>ID: @{{ products[0].order_id }}</b></h4>
          </div>
          <div class="modal-body"
               style="padding: 10px 24px">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th class="text-center">Size</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-right">Rate</th>
                  <th class="text-right">Price</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in products">
                  <td><a :href="'/product/'+product.product.slug"
                         v-text="product.product.title"
                         target="_blank"></a></td>
                  <td class="text-center"
                      v-text="product.size"></td>
                  <td class="text-center"
                      v-text="product.quantity"></td>
                  <td class="text-right"
                      v-text="product.rate"></td>
                  <td class="text-right"
                      v-text="product.quantity*product.rate"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                  <th>Total</th>
                  <th class="text-center"></th>
                  <th class="text-center"
                      v-text="totalQuantity"></th>
                  <th class="text-right"></th>
                  <th class="text-right"
                      v-text="totalPrice"></th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="processing-cover">
    <div id="processing"><i class="fa fa-spinner fa-spin"></i> Sending Email</div>
  </div>

@endsection

@if($models->count())
  @push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

    <script>
      function showProcessing() {
        document.getElementById('processing-cover').style.display = 'block';
      }

      function hideProcessing() {
        document.getElementById('processing-cover').style.display = 'none';
      }

      new Vue({
        el: '#vue-instance',

        data: {
          products: []
        },

        methods: {
          getProducts: function (orderId) {
            this.products = [];

            axios.get('{{ url('admin/order') }}' + '/' + orderId + '/products')
                 .then(function (response) {
                   this.products = response.data;
                 }.bind(this))
                 .catch(function (reason) {
                   console.log(reason);
                 });
          },

          changeStatus: function (url, id, event) {
            showProcessing();
            axios.get(url + "?status=" + event.target.value)
                 .then(function (response) {
                   hideProcessing();
                   showSuccessMessage(response.data);

                   var $currentRow = $('#asdh-' + id);
                   $currentRow.removeClass(function (index, className) {
                     return (className.match(/(^|\s)order-\S+/g) || []).join(' ');
                   });
                   $currentRow.addClass("order-" + event.target.value);
                 })
                 .catch(function (reason) {
                   hideProcessing();
                   console.log(reason);
                 })
          }
        },

        computed: {
          totalQuantity: function () {
            return this.products.reduce(function (sum, currentValue) {
              return sum + currentValue.quantity;
            }, 0);
          },

          totalPrice: function () {
            return this.products.reduce(function (sum, currentValue) {
              return sum + currentValue.rate * currentValue.quantity;
            }, 0);
          }
        }
      });

      $(document).ready(function () {
        $('.datatable').dataTable({
          "paging": true,
          "lengthChange": true,
          "lengthMenu": [10, 15, 20],
          "searching": true,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          "order": [],
          'columnDefs': [{
            'orderable': false,
            'targets': [0, 1, 5, 6]
          }]
        });
      });
    </script>
  @endpush
@endif