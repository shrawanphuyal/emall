@if ($paginator->hasPages())
  <style>
    .pagination .nav-links .page-numbers.disabled {
      background : #888;
      color      : #ccc;
    }
  </style>

  <nav class="navigation pagination">
    <div class="nav-links">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <span class="next page-numbers disabled">@lang('pagination.previous')</span>
      @else
        <a class="next page-numbers"
           href="{{ $paginator->previousPageUrl() }}">@lang('pagination.previous')</a>
      @endif


      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
          <span>{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="page-numbers current">{{ $page }}</span>
            @else
              <a class="page-numbers"
                 href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <a class="next page-numbers"
           href="{{ $paginator->nextPageUrl() }}">@lang('pagination.next')</a>
      @else
        <a class="next page-numbers disabled">@lang('pagination.next')</a>
      @endif
    </div> <!-- .nav-links -->
  </nav> <!-- .navigation.pagination -->
@endif
