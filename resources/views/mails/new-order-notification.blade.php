<h2>New Order Received</h2>
<p>You have received a new order (ID: {{ $order->id }}) at {{ $order->created_at->format('Y-m-d H:i') }}.</p>
<p><strong>Customer:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
<p><strong>Order Details:</strong></p>
<ul>
    @foreach($order->orderItems as $item)
        <li>{{ $item->menuItem->name }} x {{ $item->quantity }}</li>
    @endforeach
</ul>
@if($order->dietary_info)
    <p><strong>Dietary Info:</strong></p>
    <pre>{{ json_encode($order->dietary_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
@endif
<p>Please log in to your dashboard to view and process this order.</p>
