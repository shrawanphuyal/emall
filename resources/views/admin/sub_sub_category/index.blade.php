@extends('admin.layouts.app')

@section('title', 'All '. ucwords(str_plural($routeType)))

@section('content')

  <div class="card">
    @include('extras.index_header')

    <div class="card-content">
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th width="40">#</th>
            <th>Name</th>
            <th>Category</th>
            <th width="80">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($sub_categories as $key=>$sub_category)
            <tr id="asdh-{{$sub_category->id}}">
              <td>{{$key+1}}</td>
              <td>{{$sub_category->name}}</td>
              <td>{{$sub_category->category->name}}</td>
              <td class="asdh-edit_and_delete td-actions">
                @include('extras.edit_delete', ['modal'=>$sub_category, 'message'=>'You will not be able to recover your data in the future.'])
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
            'targets': [3]
          }]
        });
      });
    </script>
  @endpush
@endif