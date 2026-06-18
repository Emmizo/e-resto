@extends('layouts.client')
@section('title', 'My Orders')

@section('style')
<style>
.ord-page { background:#f7f7f5;min-height:calc(100vh - 56px);padding:24px; }
.ord-page-header { display:flex;align-items:center;gap:14px;margin-bottom:24px; }
.ord-page-header .back-circle {
    width:38px;height:38px;border-radius:50%;border:1.5px solid #e5e7eb;background:#fff;
    display:flex;align-items:center;justify-content:center;text-decoration:none;color:#374151;flex-shrink:0;
    transition:all .15s;
}
.ord-page-header .back-circle:hover { background:#0f3d45;border-color:#0f3d45;color:#fff; }
.ord-page-header h5 { font-size:1.25rem;font-weight:800;color:#111827;margin:0; }
.ord-empty { text-align:center;padding:60px 20px; }
.ord-empty .empty-icon { font-size:3.5rem;opacity:.18;margin-bottom:16px;display:block; }
.ord-empty p { color:#9ca3af;font-size:.9rem;margin-bottom:20px; }
.ord-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px; }

/* Card */
.ord-card {
    background:#fff;border-radius:18px;border:1.5px solid #efefed;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,0,0,.05);transition:box-shadow .18s,transform .18s;
}
.ord-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.1);transform:translateY(-1px); }

/* Teal gradient header — same as reservations */
.ord-card-head {
    background:linear-gradient(135deg,#0f3d45,#1a5c6a);
    padding:16px 18px;display:flex;align-items:flex-start;justify-content:space-between;
}
.ord-card-rest { color:#fff;font-size:.9rem;font-weight:800;margin-bottom:2px; }
.ord-card-id   { color:rgba(255,255,255,.6);font-size:.68rem; }

/* Status pills on dark bg — same as reservations */
.ord-status-pill {
    display:inline-flex;align-items:center;
    padding:3px 11px;border-radius:99px;font-size:.68rem;font-weight:800;flex-shrink:0;
}
.opill-pending    { background:rgba(245,158,11,.25);color:#fcd34d;border:1px solid rgba(245,158,11,.4); }
.opill-processing { background:rgba(96,165,250,.25);color:#93c5fd;border:1px solid rgba(96,165,250,.4); }
.opill-completed  { background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3); }
.opill-cancelled  { background:rgba(239,68,68,.25);color:#fca5a5;border:1px solid rgba(239,68,68,.4); }

/* Body */
.ord-card-body { padding:14px 18px; }
.ord-info-row {
    display:flex;align-items:flex-start;gap:10px;
    font-size:.78rem;color:#374151;margin-bottom:8px;
}
.ord-info-row:last-child { margin-bottom:0; }
.ord-info-icon { width:16px;text-align:center;flex-shrink:0;color:#0f3d45;margin-top:1px;font-size:.78rem; }

/* Items list */
.ord-items-wrap { padding:0 18px 12px;display:flex;flex-wrap:wrap;gap:7px; }
.ord-item-tag {
    display:inline-flex;align-items:center;gap:6px;
    background:#f8f9fa;border:1px solid #f0f0ee;border-radius:8px;
    padding:4px 10px;font-size:.75rem;
}
.ord-item-qty { background:#e6f3f4;color:#0f3d45;border-radius:4px;padding:1px 6px;font-weight:800;font-size:.68rem; }

/* Delivery address box */
.ord-delivery {
    margin:0 18px 12px;background:#f0f9fa;border:1px solid #c5e6ea;
    border-radius:10px;padding:10px 12px;
    display:flex;align-items:flex-start;gap:7px;font-size:.75rem;color:#0f3d45;
}

/* Footer */
.ord-card-foot {
    padding:10px 18px;background:#fafaf9;border-top:1px solid #f3f3f1;
    display:flex;align-items:center;justify-content:space-between;
}
.ord-type-tag {
    font-size:.68rem;font-weight:700;color:#6b7280;
    background:#f0f0ee;border-radius:99px;padding:3px 10px;
    display:inline-flex;align-items:center;gap:4px;
}
.ord-total { font-size:.9rem;font-weight:800;color:#0f3d45; }
</style>
@endsection

@section('content')
<div class="ord-page">

    <div class="ord-page-header">
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
        <a href="{{ route('client.restaurants') }}" class="btn rounded-pill px-5 py-2"
            style="background:#0f3d45;color:#fff;font-weight:700;font-size:.85rem;box-shadow:0 4px 14px rgba(15,61,69,.3);">
            Browse Restaurants
        </a>
    </div>
    @else
    <div class="ord-grid">
        @foreach($orders as $order)
        @php
            $pillClass = [
                'pending'    => 'opill-pending',
                'processing' => 'opill-processing',
                'completed'  => 'opill-completed',
                'cancelled'  => 'opill-cancelled',
            ][$order->status] ?? 'opill-pending';
            $pillIcon = [
                'pending'    => '⏳',
                'processing' => '🍳',
                'completed'  => '🎉',
                'cancelled'  => '✕',
            ][$order->status] ?? '·';
            $typeIcon = [
                'dine_in'  => 'fas fa-utensils',
                'takeaway' => 'fas fa-shopping-bag',
                'delivery' => 'fas fa-motorcycle',
            ][$order->order_type] ?? 'fas fa-receipt';
        @endphp
        <div class="ord-card">

            {{-- Teal gradient header --}}
            <div class="ord-card-head">
                <div>
                    <div class="ord-card-rest">{{ $order->restaurant->name ?? 'Restaurant' }}</div>
                    <div class="ord-card-id">Order #{{ $order->id }}</div>
                </div>
                <span class="ord-status-pill {{ $pillClass }}">{{ $pillIcon }} {{ ucfirst($order->status) }}</span>
            </div>

            {{-- Info rows --}}
            <div class="ord-card-body">
                <div class="ord-info-row">
                    <i class="fas fa-calendar-day ord-info-icon"></i>
                    <span>{{ $order->created_at->format('D, d M Y · H:i') }}</span>
                </div>
                <div class="ord-info-row">
                    <i class="fas fa-{{ str_replace(['dine_in','takeaway','delivery'],['utensils','shopping-bag','motorcycle'], $order->order_type) }} ord-info-icon"></i>
                    <span>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                </div>
                @if($order->scheduled_time)
                <div class="ord-info-row">
                    <i class="fas fa-clock ord-info-icon" style="color:#f59e0b;"></i>
                    <span>Scheduled {{ \Carbon\Carbon::parse($order->scheduled_time)->format('D d M, H:i') }}</span>
                </div>
                @endif
                @if($order->special_instructions)
                <div class="ord-info-row">
                    <i class="fas fa-note-sticky ord-info-icon" style="color:#9ca3af;"></i>
                    <span style="color:#6b7280;">{{ $order->special_instructions }}</span>
                </div>
                @endif
            </div>

            {{-- Items --}}
            <div class="ord-items-wrap">
                @foreach($order->orderItems as $item)
                <div class="ord-item-tag">
                    <span class="ord-item-qty">{{ $item->quantity }}×</span>
                    <span style="color:#374151;font-weight:600;">{{ $item->menuItem->name ?? 'Item' }}</span>
                    <span style="color:#9ca3af;font-size:.7rem;">RWF {{ number_format($item->price, 0) }}</span>
                </div>
                @endforeach
            </div>

            {{-- Delivery address --}}
            @if($order->order_type === 'delivery' && $order->delivery_address && $order->delivery_address !== 'N/A')
            <div class="ord-delivery">
                <i class="fas fa-location-dot" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{{ $order->delivery_address }}</span>
            </div>
            @endif

            {{-- Footer --}}
            <div class="ord-card-foot">
                <span class="ord-type-tag">
                    <i class="{{ $typeIcon }}"></i>
                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                </span>
                <span class="ord-total">RWF {{ number_format($order->total_amount, 0) }}</span>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
