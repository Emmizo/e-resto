@extends('layouts.client')
@section('title', 'My Orders')

@section('style')
<style>
.ord-page { background:#f7f7f5;min-height:calc(100vh - 56px);padding:24px; }
.ord-header { display:flex;align-items:center;gap:14px;margin-bottom:24px; }
.ord-header .back-circle {
    width:38px;height:38px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;color:#374151;flex-shrink:0;
    transition:all .15s;
}
.ord-header .back-circle:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.ord-header h5 { font-size:1.25rem;font-weight:800;color:#111827;margin:0; }
.ord-empty { text-align:center;padding:60px 20px; }
.ord-empty .empty-icon { font-size:3.5rem;opacity:.18;margin-bottom:16px;display:block; }
.ord-empty p { color:#9ca3af;font-size:.9rem;margin-bottom:20px; }

/* Order card */
.order-card {
    background:#fff;border-radius:18px;border:1.5px solid #efefed;
    overflow:hidden;margin-bottom:16px;
    box-shadow:0 2px 12px rgba(0,0,0,.05);
    transition:box-shadow .18s,transform .18s;
}
.order-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.1);transform:translateY(-1px); }
.order-card-head {
    padding:16px 18px 12px;display:flex;align-items:flex-start;justify-content:space-between;gap:12px;
    border-bottom:1px solid #f3f3f1;
}
.order-rest-name { font-size:.92rem;font-weight:800;color:#111827;margin-bottom:2px; }
.order-meta { font-size:.72rem;color:#9ca3af;display:flex;align-items:center;gap:6px;flex-wrap:wrap; }
.order-meta .sep { opacity:.4; }
.order-status-pill {
    display:inline-flex;align-items:center;gap:5px;
    padding:4px 13px;border-radius:99px;font-size:.72rem;font-weight:800;
    flex-shrink:0;
}
.pill-pending    { background:#fffbeb;color:#92400e;border:1.5px solid #fde68a; }
.pill-processing { background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe; }
.pill-completed  { background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0; }
.pill-cancelled  { background:#fef2f2;color:#991b1b;border:1.5px solid #fecaca; }
.order-card-items {
    padding:12px 18px;display:flex;flex-wrap:wrap;gap:8px;
}
.order-item-tag {
    display:inline-flex;align-items:center;gap:6px;
    background:#f8f9fa;border:1px solid #f0f0ee;border-radius:8px;
    padding:5px 10px;font-size:.75rem;
}
.order-item-qty { background:#e6f3f4;color:#0f3d45;border-radius:4px;padding:1px 6px;font-weight:800;font-size:.68rem; }
.order-item-price { color:#9ca3af;font-size:.7rem; }
.order-card-foot {
    padding:10px 18px;background:#fafaf9;border-top:1px solid #f3f3f1;
    display:flex;align-items:center;justify-content:space-between;
}
.order-total { font-size:.9rem;font-weight:800;color:#0f3d45; }
.order-type-tag {
    font-size:.68rem;font-weight:700;color:#6b7280;
    background:#f0f0ee;border-radius:99px;padding:3px 10px;
    display:inline-flex;align-items:center;gap:4px;
}
.order-special {
    padding:0 18px 10px;font-size:.74rem;color:#6b7280;
    display:flex;align-items:flex-start;gap:6px;
}
.order-delivery-addr {
    margin:0 18px 10px;background:#f0f9fa;border:1px solid #c5e6ea;
    border-radius:8px;padding:8px 12px;font-size:.75rem;color:#0f3d45;
    display:flex;align-items:flex-start;gap:7px;
}
</style>
@endsection

@section('content')
<div class="ord-page">

    <div class="ord-header">
        <a href="{{ route('client.restaurants') }}" class="back-circle">
            <i class="fas fa-arrow-left" style="font-size:.8rem;"></i>
        </a>
        <div>
            <h5>My Orders</h5>
            @if(!$orders->isEmpty())
            <div style="font-size:.76rem;color:#9ca3af;margin-top:1px;">{{ $orders->count() }} order{{ $orders->count() !== 1 ? 's' : '' }}</div>
            @endif
        </div>
    </div>

    @if($orders->isEmpty())
    <div class="ord-empty">
        <span class="empty-icon">🧾</span>
        <p>You haven't placed any orders yet.</p>
        <a href="{{ route('client.restaurants') }}" class="btn fw-700 rounded-pill px-5 py-2"
            style="background:#0f3d45;color:#fff;font-weight:700;font-size:.85rem;box-shadow:0 4px 14px rgba(15,61,69,.3);">
            Browse Restaurants
        </a>
    </div>
    @else
    @foreach($orders as $order)
    @php
        $pillClass = [
            'pending'    => 'pill-pending',
            'processing' => 'pill-processing',
            'completed'  => 'pill-completed',
            'cancelled'  => 'pill-cancelled',
        ][$order->status] ?? 'pill-pending';
        $pillIcon = [
            'pending'    => '⏳',
            'processing' => '🍳',
            'completed'  => '✅',
            'cancelled'  => '✕',
        ][$order->status] ?? '·';
        $typeIcon = [
            'dine_in'  => 'fas fa-utensils',
            'takeaway' => 'fas fa-shopping-bag',
            'delivery' => 'fas fa-motorcycle',
        ][$order->order_type] ?? 'fas fa-receipt';
    @endphp
    <div class="order-card">
        <div class="order-card-head">
            <div>
                <div class="order-rest-name">{{ $order->restaurant->name ?? 'Restaurant' }}</div>
                <div class="order-meta">
                    <span>Order #{{ $order->id }}</span>
                    <span class="sep">·</span>
                    <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                    @if($order->scheduled_time)
                    <span class="sep">·</span>
                    <span><i class="fas fa-clock me-1" style="color:#f59e0b;"></i>Scheduled {{ \Carbon\Carbon::parse($order->scheduled_time)->format('d M, H:i') }}</span>
                    @endif
                </div>
            </div>
            <span class="order-status-pill {{ $pillClass }}">
                {{ $pillIcon }} {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="order-card-items">
            @foreach($order->orderItems as $item)
            <div class="order-item-tag">
                <span class="order-item-qty">{{ $item->quantity }}×</span>
                <span style="color:#374151;font-weight:600;">{{ $item->menuItem->name ?? 'Item' }}</span>
                <span class="order-item-price">RWF {{ number_format($item->price, 0) }}</span>
            </div>
            @endforeach
        </div>

        @if($order->order_type === 'delivery' && $order->delivery_address && $order->delivery_address !== 'N/A')
        <div class="order-delivery-addr">
            <i class="fas fa-location-dot" style="margin-top:1px;flex-shrink:0;"></i>
            <span>{{ $order->delivery_address }}</span>
        </div>
        @endif

        @if($order->special_instructions)
        <div class="order-special">
            <i class="fas fa-note-sticky" style="margin-top:2px;flex-shrink:0;color:#9ca3af;"></i>
            <span>{{ $order->special_instructions }}</span>
        </div>
        @endif

        <div class="order-card-foot">
            <span class="order-type-tag">
                <i class="{{ $typeIcon }}"></i>
                {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
            </span>
            <span class="order-total">RWF {{ number_format($order->total_amount, 0) }}</span>
        </div>
    </div>
    @endforeach
    @endif

</div>
@endsection
