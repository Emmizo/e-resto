@extends('layouts.client')
@section('title', 'Order #' . $order->id)

@section('style')
<style>
.od-page { background:#f7f7f5;min-height:calc(100vh - 56px);padding:24px; }
.od-header { display:flex;align-items:center;gap:14px;margin-bottom:24px; }
.od-back {
    width:38px;height:38px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;color:#374151;flex-shrink:0;
    transition:all .15s;
}
.od-back:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.od-card { background:#fff;border-radius:18px;border:1.5px solid #efefed;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.06);margin-bottom:14px; }
.od-card-head { padding:18px 20px;border-bottom:1px solid #f3f3f1;display:flex;align-items:center;justify-content:space-between;gap:12px; }
.od-section-title { font-size:.7rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px; }
.od-body { padding:18px 20px; }
.od-status-pill { display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:99px;font-size:.75rem;font-weight:800; }
.pill-pending    { background:#fffbeb;color:#92400e;border:1.5px solid #fde68a; }
.pill-processing { background:#eff6ff;color:#1d4ed8;border:1.5px solid #bfdbfe; }
.pill-completed  { background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0; }
.pill-cancelled  { background:#fef2f2;color:#991b1b;border:1.5px solid #fecaca; }
.od-item-row { display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f3f3f1; }
.od-item-row:last-child { border-bottom:none; }
.od-item-img { width:44px;height:44px;border-radius:10px;object-fit:cover;background:#f0f0ee;flex-shrink:0; }
.od-item-img-placeholder { width:44px;height:44px;border-radius:10px;background:#f0f0ee;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0; }
.od-item-name { font-size:.84rem;font-weight:600;color:#111827; }
.od-item-qty { font-size:.72rem;color:#0f3d45;font-weight:800;background:#e6f3f4;border-radius:4px;padding:1px 7px; }
.od-item-price { font-size:.8rem;color:#6b7280;margin-left:auto; }
.od-row { display:flex;align-items:center;gap:8px;font-size:.82rem;color:#374151;margin-bottom:8px; }
.od-row:last-child { margin-bottom:0; }
.od-row-icon { width:16px;text-align:center;flex-shrink:0;color:#0f3d45; }
.od-total-row { display:flex;justify-content:space-between;align-items:center;font-size:.85rem;padding:6px 0;border-top:1px dashed #e5e7eb;margin-top:10px; }
.od-total-row .label { color:#6b7280; }
.od-total-row .val { font-size:1.05rem;font-weight:800;color:#0f3d45; }
.od-delivery-box { background:#f0f9fa;border:1px solid #c5e6ea;border-radius:10px;padding:10px 14px;display:flex;align-items:flex-start;gap:8px;font-size:.8rem;color:#0f3d45; }
</style>
@endsection

@section('content')
<div class="od-page">

    <div class="od-header">
        <a href="{{ route('client.my-orders') }}" class="od-back">
            <i class="fas fa-arrow-left" style="font-size:.8rem;"></i>
        </a>
        <div>
            <div style="font-size:1.1rem;font-weight:800;color:#111827;">Order #{{ $order->id }}</div>
            <div style="font-size:.72rem;color:#9ca3af;margin-top:1px;">{{ $order->created_at->format('D, d M Y · H:i') }}</div>
        </div>
        @php
            $pillClass = ['pending'=>'pill-pending','processing'=>'pill-processing','completed'=>'pill-completed','cancelled'=>'pill-cancelled'][$order->status] ?? 'pill-pending';
            $pillIcon  = ['pending'=>'⏳','processing'=>'🍳','completed'=>'✅','cancelled'=>'✕'][$order->status] ?? '·';
        @endphp
        <span class="od-status-pill {{ $pillClass }}" style="margin-left:auto;">{{ $pillIcon }} {{ ucfirst($order->status) }}</span>
    </div>

    {{-- Restaurant --}}
    <div class="od-card">
        <div class="od-body">
            <div class="od-section-title"><i class="fas fa-store me-1"></i>Restaurant</div>
            <div class="od-row">
                <i class="fas fa-store od-row-icon"></i>
                <span style="font-weight:700;">{{ $order->restaurant->name ?? '—' }}</span>
            </div>
            @if($order->restaurant->address ?? null)
            <div class="od-row">
                <i class="fas fa-location-dot od-row-icon"></i>
                <span>{{ $order->restaurant->address }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Order details --}}
    <div class="od-card">
        <div class="od-body">
            <div class="od-section-title"><i class="fas fa-receipt me-1"></i>Items ordered</div>
            @foreach($order->orderItems as $item)
            <div class="od-item-row">
                @if($item->menuItem && $item->menuItem->image)
                    <img class="od-item-img" src="{{ $item->menuItem->image }}" alt="{{ $item->menuItem->name }}">
                @else
                    <div class="od-item-img-placeholder">🍽️</div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div class="od-item-name">{{ $item->menuItem->name ?? 'Item' }}</div>
                    <span class="od-item-qty">{{ $item->quantity }}×</span>
                </div>
                <div class="od-item-price">RWF {{ number_format($item->price, 0) }}</div>
            </div>
            @endforeach
            <div class="od-total-row">
                <span class="label">Total</span>
                <span class="val">RWF {{ number_format($order->total_amount, 0) }}</span>
            </div>
        </div>
    </div>

    {{-- Delivery / type --}}
    <div class="od-card">
        <div class="od-body">
            <div class="od-section-title"><i class="fas fa-info-circle me-1"></i>Order info</div>
            <div class="od-row">
                <i class="fas fa-{{ ['dine_in'=>'utensils','takeaway'=>'shopping-bag','delivery'=>'motorcycle'][$order->order_type] ?? 'receipt' }} od-row-icon"></i>
                <span>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
            </div>
            @if($order->scheduled_time)
            <div class="od-row">
                <i class="fas fa-clock od-row-icon" style="color:#f59e0b;"></i>
                <span>Scheduled for {{ \Carbon\Carbon::parse($order->scheduled_time)->format('D d M Y, H:i') }}</span>
            </div>
            @endif
            @if($order->order_type === 'delivery' && $order->delivery_address && $order->delivery_address !== 'N/A')
            <div style="margin-top:8px;">
                <div class="od-delivery-box">
                    <i class="fas fa-location-dot" style="margin-top:2px;flex-shrink:0;"></i>
                    <span>{{ $order->delivery_address }}</span>
                </div>
            </div>
            @endif
            @if($order->special_instructions)
            <div class="od-row" style="margin-top:8px;">
                <i class="fas fa-note-sticky od-row-icon" style="color:#9ca3af;"></i>
                <span style="color:#6b7280;">{{ $order->special_instructions }}</span>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
