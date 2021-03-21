<p>ID: {{ sprintf("%04d", $order->id) }}</p>
<table border style="width:100%;border-collapse:collapse;">
  <thead>
  <tr>
    <th style="width:40px;">S.N.</th>
    <th style="text-align:left;">Title</th>
    <th style="width:80px;">Quantity</th>
  </tr>
  </thead>
  <tbody>
  @foreach($order->realProducts as $product)
    <tr>
      <td style="text-align:center;">{{ $loop->index + 1 }}</td>
      <td style="text-align:left;">{{ $product->title }}</td>
      <td style="text-align:center;">{{ $product->pivot->quantity }}</td>
    </tr>
  @endforeach
  </tbody>
</table>