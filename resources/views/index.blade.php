@extends('layouts.master')

@section('title', 'Home')

@section('content')

  <div id="front-page-section-area">
    <aside class="section section-featured-slider">
      <div class="container">
        <div class="inner-wrapper">
          <div class="main-featured-slider col-grid-8">
            <div id="main-slider"
                 class="cycle-slideshow overlay-enabled featrued-slider"
                 data-cycle-fx="fadeout"
                 data-cycle-speed="1000"
                 data-cycle-pause-on-hover="true"
                 data-cycle-loader="true"
                 data-cycle-log="false"
                 data-cycle-swipe="true"
                 data-cycle-auto-height="container"
                 data-cycle-timeout="3000"
                 data-cycle-slides="article"
                 data-pager-template='<span class="pager-box"></span>'>
              <!-- prev/next links -->
              <div class="cycle-prev"><i class="fas fa-angle-left"
                                         aria-hidden="true"></i></div>
              <div class="cycle-next"><i class="fas fa-angle-right"
                                         aria-hidden="true"></i></div>
              <!-- pager -->
              <div class="cycle-pager"></div>
              @foreach($sliders as $slider)
                <article class="{{ $loop->first ? 'first' : '' }}">
                  <a href="{{ $slider->url }}">
                    <img src="{{ $slider->image }}"
                         alt="{{ $slider->url }}"/>
                  </a>
                </article>  <!-- article -->
              @endforeach
            </div><!-- #main-slider -->
            <h2 class="section-title">Recent Products</h2>
            <div class="inner-wrapper">
              <div class="products-list-wrapper iteam-col-3 section-carousel-enabled">
                @foreach($recentProducts as $product)
                  <div class="product-item col-grid-4">
                    @include('includes.product')
                  </div><!-- .product-item -->
                @endforeach
              </div> <!-- .products-list-wrapper -->
            </div><!-- .inner-wrapper -->
          </div> <!-- .main-featured-slider -->
          <div class="main-category-list col-grid-4">
            <div class="main-category-list-wrapper">
              <h2 class="cat-title">Top Categories</h2>
              <ul>
                @foreach($allCategories as $category)
                  <li class="beauty-health-link">
                    <a href="{{ route('category.products', $category->slug) }}?selected_category={{ urlencode($category->name) }}"
                       style="background-image: url('{{ $category->image }}');background-size:32px 32px;">{{ $category->name }}</a>
                  </li>
                @endforeach
              </ul>
            </div><!-- .main-category-list-wrapper -->
          </div> <!-- .main-category-list -->
        </div><!-- .inner-wrapper -->
      </div><!-- .container -->
    </aside> <!-- .section-featured-slider -->

    @if($advertisement)
      <aside class="section section-ads-banner">
        <div class="container">
          <div class="ads-banner">
            <a href="#"><img src="{{ $advertisement->showImage() }}"></a>
          </div><!-- .ads-banner -->
        </div><!-- .container -->
      </aside>
    @endif

    @if($hotProducts->count())
      <aside class="section product-list">
        <div class="container">
          <h2 class="section-title">Hot Products</h2>
          <div class="inner-wrapper">
            <div class="products-list-wrapper iteam-col-4 section-carousel-enabled">
              @foreach($hotProducts as $product)
                <div class="product-item col-grid-3">
                  @include('includes.product')
                </div><!-- .product-item -->
              @endforeach
            </div> <!-- .products-list-wrapper -->
          </div><!-- .inner-wrapper -->
        </div><!-- .container -->
      </aside><!-- .product-list -->
    @endif

    @if($featuredProducts->count())
      <aside class="section product-list">
        <div class="container">
          <h2 class="section-title" style="margin-top: 15px;">Featured Products</h2>
          <div class="inner-wrapper">
            <div class="products-list-wrapper iteam-col-4 section-carousel-enabled">
              @foreach($featuredProducts as $product)
                <div class="product-item col-grid-3">
                  @include('includes.product')
                </div><!-- .product-item -->
              @endforeach
            </div> <!-- .products-list-wrapper -->
          </div><!-- .inner-wrapper -->
        </div><!-- .container -->
      </aside><!-- .product-list -->
    @endif

    <aside class="section section-associate-logo">
      <div class="container">
        <h2 class="section-title">Top Brands</h2>
      </div><!-- .container -->
      <div class="associate-inner-wrapper">
        <div class="container">
          <div class="associate-logo-section associate-logo-col-7">
            <div class="inner-wrapper iteam-col-7 section-carousel-enabled">
              @foreach($brands as $brand)
                <div class="associate-logo-item">
                  <a href="{{ $brand->url }}"
                     target="_blank">
                    <img alt="Our Brand"
                         src="{{ $brand->image }}">
                  </a>
                </div> <!-- .plan-item  -->
              @endforeach
            </div> <!-- .inner-wrapper -->
          </div> <!-- .plan-section -->
        </div> <!-- .container -->
      </div><!-- .associate-inner-wrapper -->
    </aside> <!-- .section associate-logo -->
  </div><!-- front-page-section-area -->

@endsection