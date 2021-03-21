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
            <th>Slug</th>
            <th width="100">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($categories as $key=>$category)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>
                @if($category->has_sub_categories())
                  <a href="{{ route('category.show', $category) }}"
                     title="See all of its sub-categories.">{{ $category->name }} ({{ $category->sub_categories->count() }})</a>
                @else
                  {{ $category->name }}
                @endif
              </td>
              <td>{{ $category->slug }}</td>
              <td class="asdh-edit_and_delete td-actions">
                @include('extras.edit_delete', ['modal'=>$category, 'add_sub_category'=>true , 'message'=>'You will not be able to recover your data in the future.'])
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

@if($categories->count())
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