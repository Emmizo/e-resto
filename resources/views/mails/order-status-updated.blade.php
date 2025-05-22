<h2>Order Status Update</h2>
<p>Dear {{ $order->user->first_name }},</p>
<p>Your order (ID: {{ $order->id }}) at <strong>{{ $order->restaurant->name }}</strong> has been updated.</p>
<p><strong>New Status:</strong> {{ ucfirst($order->status) }}</p>
<p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
<p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
@if($order->special_instructions)
    <p><strong>Special Instructions:</strong> {{ $order->special_instructions }}</p>
@endif
<p>Thank you for your order!</p>
<p>Best regards,<br>{{ config('app.name') }} Team</p>
