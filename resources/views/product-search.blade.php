@extends('layouts.master')

@section('title', 'Products Search')

@push('style')
  <style></style>
@endpush

@section('content')
  {{--add to cart model--}}
  @include('extras.frontend.add-to-cart')
  {{--./add to cart model--}}

  @include('extras.frontend.products', ['iProducts' => $products, 'iTitle' => 'All that you were searching for.'])

  <div class="container text-center">
    {{ $products->appends(request()->input())->links() }}
  </div>
@endsection

@push('script')
  <script>
    $(document).ready(function () {

    });
  </script>
@endpush