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
            <th>Image</th>
            <th>Name</th>
            <th width="80">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($tests as $key=>$test)
            <tr id="asdh-{{$test->id}}">
              <td>{{$key+1}}</td>
              <td width="60"><img src="{{$test->image(60,60)}}" alt="{{$test->name}}"></td>
              <td>{{$test->name}}</td>
              <td class="asdh-edit_and_delete td-actions">
                @include('extras.edit_delete', ['modal'=>$test, 'message'=>'You will not be able to recover your data in the future.'])
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

@if($tests->count())
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
          "order": [],
          'columnDefs': [{
            'orderable': false,
            'targets': [2]
          }]
        });
      });
    </script>
  @endpush
@endif