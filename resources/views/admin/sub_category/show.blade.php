@extends('admin.layouts.app')

@section('title', 'All children of '. $sub_category->name)

@section('content')

  <div class="card">
    <div class="card-header card-header-text" data-background-color="green">
      <h4 class="card-title">All sub categories of <b>{{ $sub_category->name }}</b> sub category</h4>
    </div>
    <a href="{{ route($routeType.'.create',['sub_category_id'=>$sub_category->id]) }}"
       class="btn btn-success btn-round btn-xs create-new">
      <i class="material-icons">add_circle_outline</i> Add sub sub category
    </a>

    <div class="card-content">
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th width="40">#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th width="80">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($children as $key=>$child)
            <tr id="asdh-{{$child->id}}">
              <td>{{$key+1}}</td>
              <td>
                <img src="{{ $child->image(50,50) }}" alt="" style="width: 50px;height: 50px;">
              </td>
              <td>{{$child->name}}</td>
              <td>{{$child->parent->name}}</td>
              <td class="asdh-edit_and_delete td-actions">
                @include('extras.edit_delete', ['modal'=>$child, 'message'=>'You will not be able to recover your data in the future.'])
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

@if($children->count())
  @push('script')
    <script>
      $(document).ready(function () {
        $('table').dataTable({
          "paging": true,
          "lengthChange": true,
          "lengthMenu": [10, 15, 20],
          "searching": true,
          "ordering": true,
          "info": false,
          "autoWidth": false,
          'columnDefs': [{
            'orderable': false,
            'targets': [1,4]
          }]
        });
      });
    </script>
  @endpush
@endif