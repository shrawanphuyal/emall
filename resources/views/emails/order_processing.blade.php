@component('mail::message')
<div style="text-align:center;margin-bottom: 20px;"><img src="{{ frontend_url('images/logo.png') }}"></div>
# Your Order has been processed and will be delivered soon.
@include('emails.order')
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
