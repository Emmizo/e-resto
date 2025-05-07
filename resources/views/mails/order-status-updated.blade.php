@component('mail::message')
# Order Status Updated

Hello {{ $customerName }},

Your order with ID: **{{ $orderId }}** has been updated to the following status: **{{ ucfirst($status) }}**.

Thank you for your order!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
