<div class="product-inner-wrapper">
  <div class="product-thumb">
    <a class="thumbnail"
       href="{{ route('product.detail', $product->slug) }}"><img src="{{ $product->getFirstImageCropped(270,160) }}"></a>
    <div class="pruduct-buttons">
      @if($product->quantity > 0)
        <a href="#"
           title="Add to Cart"
           onclick="event.preventDefault();location='{{ route('add-to-cart', $product->slug) }}'">
          <i class="fas fa-cart-plus"></i>
        </a>
      @endif
      {{--<a href="#"><i class="fas fa-search-plus"></i></a>--}}
    </div><!-- .product-buttons -->
  </div><!-- .product-thumb -->
  <div class="product-content">
    <h3><a href="{{ route('product.detail', $product->slug) }}" title="{{ $product->title }}">{{ $product->textStriped(39, 'title') }}</a></h3>
    <div class="product-pricing">
      <div class="item-price">
        <span class="fix-price">Rs. {{ $product->sellingPriceFixed() }}</span>
        @if($product->hasDiscount())
          <del class="dis-price">Rs. {{ $product->price }}</del>
        @endif
      </div>
      @if($product->hasDiscount())
        <a class="offter">{{ $product->discountPercentage() }}% OFF</a>
      @endif
    </div><!-- .product-priceing -->
  </div><!-- .product-content -->
</div><!-- .product-inner-wrapper -->