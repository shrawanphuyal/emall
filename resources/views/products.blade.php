@extends('layouts.master')

@section('title', 'Products List')

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div aria-label="Breadcrumbs"
           class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/"
                                                rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Products</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->


  <div id="content"
       class="site-content product-archive">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary"
             class="content-area">
          <main id="main">
            <div class="product-listing-filter">
              <div class="sort-by-title">
                <h3>{{ $title }} </h3>
                @if($products->count())
                  <span class="pruduct-result-count">Showing {{ ($products->currentPage()-1)*$products->perPage()+1 }} to {{ ($products->currentPage()-1)*$products->perPage()+$products->count() }} of {{ $products->total() }} products</span>
                @endif
              </div>
              @if($products->count())
                <div class="sort-by">
                  <span class="sort-by-list">Sort by {{ request()->query('val') }}</span>
                  <ul>
                    @if(request()->is('search'))
                      <li>
                        <a href="{{ route($routeType, array_merge(request()->except('page'), ['type'=>'created_at','order'=>'asc','val'=>'Latest'])) }}">Sort by latest</a>
                      </li>
                      <li>
                        <a href="{{ route($routeType, array_merge(request()->except('page'), ['type'=>'created_at','order'=>'desc','val'=>'Oldest'])) }}">Sort by oldest</a>
                      </li>
                      <li>
                        <a href="{{ route($routeType, array_merge(request()->except('page'), ['type'=>'price','order'=>'asc','val'=>'Price: Low to High'])) }}">Sort by price: low to high</a>
                      </li>
                      <li>
                        <a href="{{ route($routeType, array_merge(request()->except('page'), ['type'=>'price','order'=>'desc','val'=>'Price: High to Low'])) }}">Sort by price: high to low</a>
                      </li>
                    @else
                      <li>
                        <a href="{{ $currentUrl }}?type=created_at&order=desc&val=Latest">Sort by latest</a>
                      </li>
                      <li>
                        <a href="{{ $currentUrl }}?type=created_at&order=asc&val=Oldest">Sort by oldest</a>
                      </li>
                      <li>
                        <a href="{{ $currentUrl }}?type=price&order=asc&val=Price: Low to High">Sort by price: low to high</a>
                      </li>
                      <li>
                        <a href="{{ $currentUrl }}?type=price&order=desc&val=Price: High to Low">Sort by price: high to low</a>
                      </li>
                    @endif
                  </ul>
                </div><!-- .sort-by -->
              @endif
            </div>
            <div class="product-lists products-grid">
              <div class="inner-wrapper">
                <div class="products-list-wrapper">
                  @forelse($products as $product)
                    <div class="product-item col-grid-4">
                      @include('includes.product')
                    </div><!-- .product-item -->
                  @empty
                    <div class="product-item col-grid-4">
                      0 products found
                    </div>
                  @endforelse
                </div> <!-- .products-list-wrapper -->
              </div><!-- .inner-wrapper -->
            </div><!-- .product-lists -->
          </main><!-- #main -->
          {{ $products->links('vendor.pagination.frontend') }}
        </div><!-- #primary -->

        @include('includes.sidebar')
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->

@endsection