@php $default = $default ?? false @endphp
<ul>
  @if($default)
    <li><a href="#"
           class="search-category-individual{{ $default?'':'-na' }}"
           data-slug="">Select Categories</a></li>
  @endif
  @foreach($allCategories as $category)
    <li class="{{ $category->name == request()->query('selected_category') ? 'active' : '' }} {{ $category->has_sub_categories() ? 'has-child' : '' }}">
      <a href="{{ route('category.products', $category->slug) }}?selected_category={{ urlencode($category->name) }}"
         class="search-category-individual{{ $default?'':'-na' }}"
         data-level="1"
         data-id="{{ $category->id }}"
         data-slug="{{ $category->slug }}">{{ $category->name }}</a>

      @if($category->has_sub_categories())
        <ul class="sub-menu">
          @foreach($category->sub_categories as $subCategory)
            <li class="{{ $subCategory->name == request()->query('selected_category') ? 'active' : '' }} {{ $subCategory->has_children() ? 'has-child' : '' }}">
              <a href="{{ route('category.products', [$category->slug, $subCategory->slug]) }}?selected_category={{ urlencode($subCategory->name) }}"
                 class="search-category-individual{{ $default?'':'-na' }}"
                 data-level="2"
                 data-id="{{ $subCategory->id }}"
                 data-slug="{{ $subCategory->slug }}">{{ $subCategory->name }}</a>

              @if($subCategory->has_children())
                <ul class="sub-menu">
                  @foreach($subCategory->children as $child)
                    <li class="{{ $child->name == request()->query('selected_category') ? 'active' : '' }}">
                      <a href="{{ route('category.products', [$category->slug, $subCategory->slug, $child->slug]) }}?selected_category={{ urlencode($child->name) }}"
                         class="search-category-individual{{ $default?'':'-na' }}"
                         data-level="3"
                         data-id="{{ $child->id }}"
                         data-slug="{{ $child->slug }}">{{ $child->name }}</a>
                    </li>
                  @endforeach
                </ul>
              @endif

            </li>
          @endforeach
        </ul>
      @endif

    </li>
  @endforeach
</ul>

@push('script')
  <script>
    $(document).ready(function () {
      $('ul.sub-menu').each(function () {
        if($(this).children('li.active').length === 1) {
          $(this).parent('li').addClass('active');
        }
      })
    })
  </script>
@endpush