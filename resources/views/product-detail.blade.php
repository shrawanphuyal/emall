@extends('layouts.master')

@section('title', $product->title)

@section('content')

  <div id="breadcrumb">
    <div class="container">
      <div aria-label="Breadcrumbs"
           class="breadcrumbs breadcrumb-trail">
        <ul class="trail-items">
          <li class="trail-item trail-begin"><a href="/"
                                                rel="home"><span>Home</span></a></li>
          <li class="trail-item trail-end"><span>Product Details</span></li>
        </ul>
      </div> <!-- .breadcrumbs -->
    </div><!-- .container -->
  </div> <!-- #breadcrumb -->

  <div id="content"
       class="site-content single-product full-width-template">
    <div class="container">
      <div class="inner-wrapper">
        <div id="primary"
             class="content-area">
          <main id="main">
            <div class="single-product-details">
              <div class="inner-wrapper">
                <div class="col-grid-6">
                  <div class="single-thumb-detail">
                    <div class="single-mian-thumb">
                      <a><img src="{{ $product->getFirstImage() }}"></a>
                    </div>
                    <div class="pager-thumbnail">
                      @foreach($product->images as $image)
                        <div class="pager-thumb {{ $loop->first?'active':'' }}">
                          <a href="#"><img src="{{ $image->image }}"></a>
                        </div>
                      @endforeach
                    </div><!-- .pager-thumbnail"> -->
                  </div><!-- .single-thumb-detail -->
                </div><!-- col-grid-6 -->
                <div class="col-grid-6">
                  <h3 class="single-product-title">{{ $product->title }}</h3>
                  {{--<a href="#"
                     class="wish-list"><!-- <i class="fas fa-heart"></i> --><i class="far fa-heart"></i></a>--}}
                  <div class="single-rating">
                    <div class="ratings">
                      @foreach(range(1,5) as $rating)
                        @if($rating <= $product->rating())
                          <i class="fas fa-star"></i>
                        @elseif($rating > $product->rating() && (($rating-$product->rating()) > 0 && ($rating-$product->rating()) <= 0.5))
                          <i class="fas fa-star-half-alt"></i>
                        @else
                          <i class="far fa-star"></i>
                        @endif
                      @endforeach
                    </div><!-- .single-rating -->
                    <ul class="info-links">
                      <li><a> Rating: {{ $product->rating() }} </a></li>
                      <li><a> Reviews: {{ $product->reviews->count() }} </a></li>
                      <li><a> Stock: {{ $product->quantity }} </a></li>
                    </ul><!-- .info-links -->
                  {{--<div class="single-social social-links">
                    <ul>
                      <li><a href="http://facebook.com"
                             target="_blank"></a></li>
                      <li><a href="http://twitter.com"
                             target="_blank"></a></li>
                    </ul>
                  </div>--}} <!-- .social-links -->
                    <div class="products-info">
                      <div class="info-tabs">
                        <ul>
                          <li><a href="#"
                                 class="active">Description</a></li>
                          {{--<li><a href="#">Specification</a></li>--}}
                        </ul>
                      </div><!-- .info-tabs -->
                      <div class="info-details">
                        {!! $product->description !!}
                      </div>

                      {{--<p class="save-app">Save <strong>NPR 250 </strong>on eMall App with code EMALL250 Use
                        <a class="app-now"
                           href="#">App Now!</a></p>--}}

                      <div class="product-pricing single-product-pricing">
                        @if($product->quantity > 0)
                          <a href="#"
                             onclick="event.preventDefault();location='{{ route('add-to-cart', $product->slug) }}'"
                             class="custom-button">Add to Cart</a>
                        @endif
                        <div class="item-price">
                          <span class="fix-price">NRs. {{ $product->sellingPrice() }}</span>
                          @if($product->hasDiscount())
                            <span class="dis-price-wrap"><del class="dis-price">NRs. {{ $product->price }} </del>(Inclusive of all taxes)</span>
                          @endif
                        </div>
                        @if($product->hasDiscount())
                          <a class="offter">{{ $product->discountPercentage() }}% OFF</a>
                        @endif
                      </div><!-- .product-priceing -->
                    </div><!-- col-gird-6 -->
                  </div><!-- .inner-wrapper -->
                </div>
              </div>
            </div>
          </main><!-- #main -->

          <div class="section product-list related-product"
               style="padding-top: 30px;">
            <h2 class="section-title">Reviews</h2>
            <div style="background: #FFFFFF;padding: 20px;"
                 id="comments"
                 class="section comments-area">
              @if($product->reviews->count())
                <ol class="comment-list">
                  @foreach($product->reviews as $review)
                    <li class="comment">
                      <article class="comment-body">
                        <footer class="comment-meta">
                          <div class="comment-author vcard">
                            <img alt="Author"
                                 src="{{ frontend_url('images/author-1.png') }}"
                                 class="avatar">
                            <b class="fn"><a href="#"
                                             class="url">{{ $review->user->name }}</a></b>
                            <span class="says">says:</span>
                            {{--<div class="reply">--}}
                            {{--<a class="comment-reply-link"--}}
                            {{--href="#">Reply</a>--}}
                            {{--</div>--}}
                          </div><!-- .comment-author -->
                          <div class="comment-metadata">
                            <a href="#">
                              <span> {{ $review->created_at->format('M d, Y \a\t h:i a') }}</span>
                            </a>
                          </div><!-- .comment-metadata -->
                        </footer><!-- .comment-meta -->
                        <div class="comment-content">
                          <p>Rating: {{ $review->rating }}</p>
                          <p>{{ $review->review }}</p>
                        </div><!-- .comment-content -->

                      </article><!-- .comment-body -->
                    </li><!-- #comment-## -->
                  @endforeach
                </ol><!-- .comment-list -->
              @endif
              <div id="respond"
                   class="comment-respond">
                <h3 id="reply-title"
                    class="comment-reply-title">Submit your review</h3>
                <form action="{{ route('review.store', $product->slug) }}"
                      method="post"
                      id="commentform"
                      class="comment-form">
                  @csrf

                  <p class="comment-form-comment">
                    <label for="comment">Rating</label><br>
                    @foreach(range(1,5) as $value)
                      <label style="margin-right: 10px;">
                        <input type="radio"
                               name="rating"
                               required
                               {{ old('rating') == $value ? 'checked' : '' }}
                               value="{{ $value }}">{{ $value }}
                      </label>
                    @endforeach
                  </p>

                  <p class="comment-form-comment">
                    <label for="comment">Comment</label>
                    <textarea id="comment"
                              required
                              name="review">{{ old('review') }}</textarea>
                  </p>

                  <p class="form-submit">
                    <input name="submit"
                           type="submit"
                           id="submit"
                           class="submit"
                           value="Submit">
                  </p>
                </form>
              </div><!-- #respond -->
            </div>
          </div><!-- .product-list -->

          @if($relatedProducts->count())
            <div class="section product-list related-product">
              <h2 class="section-title">Related Products</h2>
              <div class="inner-wrapper">
                <div class="products-list-wrapper iteam-col-4 section-carousel-enabled">
                  @foreach($relatedProducts as $product)
                    <div class="product-item col-grid-3">
                      @include('includes.product')
                    </div><!-- .product-item -->
                  @endforeach
                </div> <!-- .products-list-wrapper -->
              </div><!-- .inner-wrapper -->
            </div><!-- .product-list -->
          @endif

        </div><!-- #primary -->
      </div> <!-- #inner-wrapper -->
    </div><!-- .container -->
  </div> <!-- #content-->

@endsection

@push('script')
  <script>
    $(document).ready(function () {
      $('.pager-thumb').on('click', function (event) {
        event.preventDefault();

        $(this).addClass('active');
        $(this).siblings().removeClass('active');

        var imageUrl = $(this).find('img').attr('src');

        $('.single-mian-thumb').find('img').attr('src', imageUrl);
      })
    })
  </script>
@endpush