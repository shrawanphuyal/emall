@extends('layouts.master')

@section('title', $category->name . ' Category Products')

@push('style')
  <style></style>
@endpush

@section('content')
  {{--add to cart model--}}
  @include('extras.frontend.add-to-cart')
  {{--./add to cart model--}}

  <section class="category-list-inside">
    <div class="container">
      <div class="row">

        <div class="col-sm-3 pad-list-cate hidden-sm hidden-xs">
          <a class="navbar-brand brandy-nav" href="#" style="width: 92%;">All Categories<i class="fa fa-bars"
                                                                                           aria-hidden="true"></i></a>
          <div class="wrap-category" style="margin-top: 80px; margin-bottom:50px;">
            <ul class="cate-list">
              @foreach($allCategories as $categoryIndividual)
                {{--@if(!$categoryIndividual->has_products()) @continue @endif--}}
                <li>
                  <a href="{{ route('category.products', $categoryIndividual->slug) }}"
                     @if($categoryIndividual->id==$category->id) style="color: #E7A923;"@endif>
                    {{ $categoryIndividual->name }}
                    @if($categoryIndividual->has_sub_categories())
                      <span class="pe-7s-angle-right nav-rght-arrow"></span>
                    @endif
                  </a>
                  @if($categoryIndividual->has_sub_categories())
                    <ul class="submenu">
                      @foreach($categoryIndividual->sub_categories as $sub_category)
                        <li>
                          <a href="{{ route('category.products', [$categoryIndividual->slug, $sub_category->slug]) }}"
                             @if($sub_category->id==$subCategory->id) style="color: #E7A923;"@endif>
                            {{ $sub_category->name }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endforeach

            </ul>
          </div>
          <form class="wrap-range" method="{{ request()->url() }}">
            <p>Price</p>
            <input type="text" id="range_44" name="priceRange" value="{{ request()->query('priceRange') }}"/>
            <button type="submit" class="btn btn-md btn-shop reload"><a href="">Filter by price</a></button>
          </form>
        </div>
        <div class="col-lg-9">
          <div class="cd-tabs">
            <nav>
              <ul class="cd-tabs-navigation">
                <li><a data-content="inbox" class="selected" href="#0"><i class="fa fa-th" aria-hidden="true"></i></a>
                </li>
                <li><a data-content="new" href="#0"><i class="fa fa-list-ul" aria-hidden="true"></i></a></li>
              </ul> <!-- cd-tabs-navigation -->
              {{--<ul class="result">
                <li><p>Showing 1-12 result of 8 result</li>
                <li>
                  <select class="form-control sort-frm">
                    <option>Alphabetically A-Z</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                </li>
              </ul>--}}
            </nav>

            <ul class="cd-tabs-content">
              <li data-content="inbox" class="selected">
                @foreach($products as $product)
                  <div class="col-lg-4 col-sm-6 col-xs-12 col-md-4 ">
                    <div class="insidehvr">
                      <div class="prod-item">
                        <div class="item">
                          @if($product->hasImages())
                            <img src="{{ $product->images()->first()->image(225,218) }}" alt="{{ $product->title }}">
                          @else
                            <img src="{{ $product->image(225,218) }}" alt="{{ $product->title }}">
                          @endif

                          <div class="item-wrap">
                          </div>
                          <ul class="cart-all-icon">
                            <li>
                              <button class="md-trigger add-to-cart-model-triggerer"
                                      @if($product->quantity<=0)
                                      disabled
                                      style="background: #AAAAAA !important; cursor: not-allowed; padding: 0 20px;"
                                      title="Out of Stock"
                                      @else
                                      data-modal="modal-1"
                                      data-price="{{ $product->sellingPrice() }}"
                                      data-url="{{ route('add-to-cart', $product->slug) }}"
                                      style="padding: 0 20px;"
                                  @endif>
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                              </button>
                            </li>
                            <li>
                              <a href="{{ route('product.detail', $product->slug) }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                              </a>
                            </li>
                            {{--<li><a href=""><i class="fa fa-shopping-bag" aria-hidden="true"></i></a></li>
                            <li><a href=""><i class="fa fa-eye" aria-hidden="true"></i></a></li>--}}

                          </ul>

                        </div>
                        <P class="prod-name">
                          <a href="{{ route('product.detail', $product->slug) }}"
                             style="text-decoration: none;">{{ $product->title }}</a>
                        </P>
                        <P class="price-name"><i class="fa fa-inr" aria-hidden="true"></i>{{ $product->sellingPrice() }}
                          @if($product->hasDiscount())
                            <strike>Rs. {!! $product->price !!}</strike>
                          @endif
                        </P>

                      </div>
                    </div>
                  </div>
                @endforeach
              </li>

              <li data-content="new" class="all-list">
                @foreach($products as $product)
                  <div class="wrapping-div">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-md-12 pad-hide">
                      <div class="insidehvr second-insidehvr">
                        <div class="prod-item">
                          <div class="item">
                            @if($product->hasImages())
                              <img src="{{ $product->images()->first()->image(360,360) }}" alt="{{ $product->title }}">
                            @else
                              <img src="{{ $product->image(360,360) }}" alt="{{ $product->title }}">
                            @endif

                            <div class="item-wrap">
                            </div>
                            <ul class="cart-all-icon">
                              <li>
                                <button class="md-trigger add-to-cart-model-triggerer"
                                        @if($product->quantity<=0)
                                        disabled
                                        style="background: #AAAAAA !important; cursor: not-allowed; padding: 0 20px;"
                                        title="Out of Stock"
                                        @else
                                        data-modal="modal-1"
                                        data-price="{{ $product->sellingPrice() }}"
                                        data-url="{{ route('add-to-cart', $product->slug) }}"
                                        style="padding: 0 20px;"
                                    @endif>
                                  <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                </button>
                              </li>
                              <li>
                                <a href="{{ route('product.detail', $product->slug) }}">
                                  <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                              </li>
                              {{--<li><a href=""><i class="fa fa-shopping-bag" aria-hidden="true"></i></a></li>
                              <li><a href=""><i class="fa fa-heart" aria-hidden="true"></i></a></li>--}}

                            </ul>

                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-md-12 pad-hide">
                      {{--<fieldset class="rating">
                        <input type="radio" id="star5" name="rating" value="5"/><label class="full"
                                                                                       for="star5"
                                                                                       title="Awesome - 5 stars"></label>
                        <input type="radio" id="star4half" name="rating" value="4 and a half"/><label class="half"
                                                                                                      for="star4half"
                                                                                                      title="Pretty good - 4.5 stars"></label>
                        <input type="radio" id="star4" name="rating" value="4"/><label class="full"
                                                                                       for="star4"
                                                                                       title="Pretty good - 4 stars"></label>
                        <input type="radio" id="star3half" name="rating" value="3 and a half"/><label class="half"
                                                                                                      for="star3half"
                                                                                                      title="Meh - 3.5 stars"></label>
                        <input type="radio" id="star3" name="rating" value="3"/><label class="full"
                                                                                       for="star3"
                                                                                       title="Meh - 3 stars"></label>
                        <input type="radio" id="star2half" name="rating" value="2 and a half"/><label class="half"
                                                                                                      for="star2half"
                                                                                                      title="Kinda bad - 2.5 stars"></label>
                        <input type="radio" id="star2" name="rating" value="2"/><label class="full"
                                                                                       for="star2"
                                                                                       title="Kinda bad - 2 stars"></label>
                        <input type="radio" id="star1half" name="rating" value="1 and a half"/><label class="half"
                                                                                                      for="star1half"
                                                                                                      title="Meh - 1.5 stars"></label>
                        <input type="radio" id="star1" name="rating" value="1"/><label class="full"
                                                                                       for="star1"
                                                                                       title="Sucks big time - 1 star"></label>
                        <input type="radio" id="starhalf" name="rating" value="half"/><label class="half"
                                                                                             for="starhalf"
                                                                                             title="Sucks big time - 0.5 stars"></label>
                      </fieldset>
                      <br><br><br>--}}
                      <div class="span-all">
                        <span class="deal-test"><i class="fa fa-inr"
                                                   aria-hidden="true"></i>{{ $product->sellingPrice() }}</span>
                        @if($product->hasDiscount())
                          <span><strike><i class="fa fa-inr"
                                           aria-hidden="true"></i>{{ $product->price }}</strike></span>
                        @endif
                      </div>
                      <div class="inside-all-desc-tab">
                        <h2><a href="{{ route('product.detail', $product->slug) }}"
                               style="text-decoration: none;">{{ $product->title }}</a></h2>
                        <p>{{ $product->textStriped(450) }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </li>
            </ul> <!-- cd-tabs-content -->
          </div> <!-- cd-tabs -->

        </div>
      </div>
    </div>
    </div>
  </section>

  <div class="container text-center">
    {{ $products->appends(request()->input())->links() }}
  </div>
@endsection

@push('script')
  <script>
    $(document).ready(function () {
      $("#range_44").ionRangeSlider({
        type: "double",
        min: 0,
        max: 50000,
        from: '{{ $minRange??0 }}',
        to: '{{ $maxRange??50000 }}',
        step: 100

      });
      $('.reload').click(function () {
        location.reload();
      });
    });
  </script>
@endpush