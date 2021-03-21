@extends('admin.layouts.app')

@section('title', 'All categories of ' . $category->name)

@section('content')

  <div class="card">
    <div class="card-header card-header-text" data-background-color="green">
      <h4 class="card-title">All Sub categories of <b>{{ $category->name }}</b></h4>
      {{--<p class="category">New employees on 15th September, 2016</p>--}}
    </div>
    {{--create new--}}
    <a href="{{ route($routeType.'.create',['category_id'=>$category->id]) }}"
       class="btn btn-success btn-round btn-xs create-new">
      <i class="material-icons">add_circle_outline</i> New Sub category
    </a>


    <div class="card-content">
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th width="40">#</th>
            <th>Image</th>
            <th>Name</th>
            <th width="100">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($sub_categories as $key=>$sub_category)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>
                <img src="{{ $sub_category->image(50,50) }}" alt="" style="width: 50px;height: 50px;">
              </td>
              <td>
                @if($sub_category->has_children())
                  <a href="{{ route('sub-category.show', $sub_category) }}">
                    {{ $sub_category->name }} ({{ $sub_category->children()->count() }})
                    <i class="material-icons" style="font-size:16px;">remove_red_eye</i>
                  </a>
                @else
                  {{ $sub_category->name }}
                @endif
              </td>
              <td class="asdh-edit_and_delete td-actions">
                <a href="{{ route('sub-sub-category.create',['sub_category_id'=>$sub_category->id]) }}"
                   type="button"
                   class="btn btn-warning asdh-edit"
                   title="Add Sub category to this sub category">
                  <i class="material-icons">add</i>
                </a>
                @include('extras.edit_delete', ['modal'=>$sub_category , 'message'=>'You will not be able to recover your data in the future.'])
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
            'targets': [1,3]
          }]
        });
      });
    </script>
  @endpush
@endif