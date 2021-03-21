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
            <th class="text-center">Show On Menu</th>
            <th class="text-center">Exclusive</th>
            <th class="text-center">Priority</th>
            <th width="200" class="text-center">Actions</th>
          </tr>
          </thead>
          <tbody>
          @forelse($categories as $key=>$category)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>
                <img src="{{ $category->image }}" alt="" style="width: 50px;height: 50px;">
              </td>
              <td>
                @if($category->has_sub_categories())
                  <a href="{{ route($routeType.'.show', $category) }}"
                     title="See all sub categories of {{ $category->name }}">
                    {{ $category->name }} ({{ $category->sub_categories->count() }})
                    <i class="material-icons" style="font-size:16px;">remove_red_eye</i>
                  </a>
                @else
                  {{ $category->name }}
                @endif
              </td>
              <td>
                <div class="checkbox" style="margin-left: auto;margin-right: auto;">
                  <label>
                    <input type="checkbox"
                           class="show-on-menu"
                           name="show_on_menu"
                           {{ $category->show_on_menu?'checked':'' }}
                           data-url="{{ route('category.show-on-menu', $category) }}">
                  </label>
                </div>
              </td>
              <td>
                <div class="checkbox" style="margin-left: auto;margin-right: auto;">
                  <label>
                    <input type="checkbox"
                           class="make-exclusive"
                           name="make_exclusive"
                           {{ $category->exclusive?'checked':'' }}
                           data-url="{{ route('category.make-exclusive', $category) }}">
                  </label>
                </div>
              </td>
              <td class="text-center">
                {{--<span data-toggle="#category-priority-input-{{ $category->id }}" title="Double tap to edit">{{ $category->priority }}</span>--}}
                <input type="text"
                       {{--id="category-priority-input-{{ $category->id }}"--}}
                       class="control-label category-priority"
                       style="width:50px;"
                       data-url="{{ route('category.set-priority', $category) }}"
                       value="{{ $category->priority }}">
              </td>
              <td class="asdh-edit_and_delete td-actions text-center">
                @include('extras.edit_delete', ['modal'=>$category, 'add_sub_category'=>true , 'message'=>'You will not be able to recover your data in the future.'])
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No data available</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="text-center">
      {{ $categories->links() }}
    </div>
  </div>
@endsection

@if($categories->count())
  @push('script')
    <script>
      $(document).ready(function () {
        $('.show-on-menu').on('change', function () {
          var url = $(this).data('url');

          $.ajax({
            url: url,
            success: function (response) {
              showSuccessMessage(response.message);
            },
            error: function (response) {
              console.log('Error: ', response);
            }
          })
        });

        $('.make-exclusive').on('change', function () {
          var url = $(this).data('url');

          $.ajax({
            url: url,
            success: function (response) {
              showSuccessMessage(response.message);
            },
            error: function (response) {
              console.log('Error: ', response);
            }
          })
        });

        var $category = $('.category-priority');
        var initialPriorityValue;
        $category.on('focus', function () {
          initialPriorityValue = $(this).val();
        });
        $category.on('blur', function () {
          if (initialPriorityValue !== $(this).val()) {
            $.ajax({
              url: $(this).data('url'),
              data: {
                priority: $(this).val()
              },
              success: function (response) {
                showSuccessMessage(response.message);
              },
              error: function (response) {
                console.log('Error: ', response);
              }
            })
          }
        });
      });
    </script>
  @endpush
@endif