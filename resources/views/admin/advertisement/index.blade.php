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
            <th>Title</th>
            <th>Url</th>
            <th>Home</th>
            <th width="80">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($advertisements as $key=>$advertisement)
            <tr id="asdh-{{$advertisement->id}}">
              <td>{{$key+1}}</td>
              <td width="50"><img src="{{$advertisement->showImageCropped(50,50)}}" alt="{{$advertisement->name}}"></td>
              <td>{{$advertisement->title}}</td>
              <td><a href="{{$advertisement->url}}">{{$advertisement->url}}</a></td>
              <td>{{ $advertisement->home?"Yes":"No" }}</td>
              <td class="asdh-edit_and_delete td-actions">
                @include('extras.edit_delete', ['modal'=>$advertisement, 'message'=>'You will not be able to recover your data in the future.'])
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
@endsection

@if($advertisements->count())
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
            'targets': [1,5]
          }]
        });
      });
    </script>
  @endpush
@endif