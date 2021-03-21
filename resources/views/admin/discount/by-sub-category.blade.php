@extends('admin.layouts.app')

@section('title', 'All Categories')

@section('content')

  <div class="card">
    <div class="card-header card-header-text" data-background-color="green">
      <h4 class="card-title">Your Sub-Categories</h4>
      {{--<p class="category">New employees on 15th September, 2016</p>--}}
    </div>

    <div class="card-content">
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th width="40">#</th>
            <th>Name</th>
            <th>Category</th>
            <th width="100">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($sub_categories as $key=>$sub_category)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $sub_category->name }}</td>
              <td>{{ $sub_category->category->name }}</td>
              <td class="asdh-edit_and_delete td-actions">
                <a href="#"
                   class="btn btn-success asdh-delete"
                   data-toggle="modal"
                   data-target="#delete_popup_{{ $sub_category->id }}"
                   title="Assign Discount">
                  <i class="material-icons">edit</i>
                </a>

                <div class="modal fade asdh-delete make-darker"
                     id="delete_popup_{{ $sub_category->id }}"
                     tabindex="-1"
                     role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                  <div class="modal-dialog">
                    <form action="{{route('discount.by-sub-category.post', $sub_category)}}"
                          method="post"
                          class="modal-content">
                      {{csrf_field()}}
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                          <i class="material-icons">clear</i>
                        </button>
                        <h4 class="modal-title">Assign Discount Percentage to all the products of
                          "{{ $sub_category->name }}
                          " sub-category</h4>
                      </div>
                      <div class="modal-body">
                        {{--discount_percentage--}}
                        <div class="form-group">
                          <label class="text-left"
                                 for="discount_percentage"
                                 style="display:block;">{{ ucwords('discount_percentage') }}
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
                      <div class="modal-footer text-center">
                        <button type="submit" class="btn btn-success">Assign</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
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
  </div>
@endsection

@if($sub_categories->count())
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
            'targets': [2]
          }]
        });*/
      });
    </script>
  @endpush
@endif