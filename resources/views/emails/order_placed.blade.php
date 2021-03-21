@component('mail::message')
<div style="text-align:center;margin-bottom: 20px;"><img src="{{ frontend_url('images/logo.png') }}"></div>
# Your Order
<p>ID: {{ sprintf("%04d", $order->id) }}</p>
<table border style="width:100%;border-collapse:collapse;">
  <thead>
  <tr>
    <th style="width:40px;">S.N.</th>
    <th style="text-align:left;">Title</th>
    <th style="width:80px;">Quantity</th>
    <th style="width:90px;">Rate</th>
    <th style="width:80px;">Price</th>
  </tr>
  </thead>
  <tbody>
  @foreach($products as $product)
    @php
    $sellingPrice = $product['sellingPrice'];
    if ($type == 'paypal') {
				$sellingPrice = number_format($sellingPrice / $conversionRate, 2, '.', '');
    }
    @endphp
    <tr>
      <td style="text-align:center;">{{ $loop->index + 1 }}</td>
      <td style="text-align:left;">{{ $product['title'] }}</td>
      <td style="text-align:center;">{{ $product['quantity'] }}</td>
      <td style="text-align:right;width:90px;">{{ number_format($sellingPrice, 2, '.', '') }}</td>
      <td style="text-align:right;width:90px;">{{ number_format($product['quantity'] * $sellingPrice, 2, '.', '') }}</td>
    </tr>
  @endforeach
  </tbody>
  <tfoot>
  <tr>
    <th colspan="4" style="text-align:left;">Total</th>
    <th style="text-align:right;">{{ number_format($total, 2, '.', '') }}</th>
  </tr>
  </tfoot>
</table>
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
