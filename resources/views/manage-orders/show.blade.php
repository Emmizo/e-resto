@extends('layouts.app')

@section('content')
<main class="content-wrapper" style="font-size: 0.97rem;">
    <div class="main-content manage-users">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-12 col-xl-11">
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom-0 pb-0">
                        <h3 class="card-title mb-0 fs-4 fw-bold">
                            <i class="fas fa-receipt me-2 text-primary"></i>Order Details <span class="text-muted">#{{ $order->id }}</span>
                        </h3>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-4 mt-2 mb-4">
                            <div class="col-md-4">
                                <div class="border rounded-4 p-4 h-100 bg-light shadow-sm">
                                    <h6 class="mb-3 text-primary fw-bold"><i class="fas fa-info-circle me-2"></i>Order Info</h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><strong>Order ID:</strong> {{ $order->id }}</li>
                                        <li class="mb-2"><strong>Status:</strong> <span class="badge fs-6 px-3 py-2 rounded-pill text-uppercase fw-semibold
                                            {{
                                                $order->status === 'completed' ? 'bg-success' :
                                                ($order->status === 'cancelled' ? 'bg-danger' :
                                                ($order->status === 'processing' ? 'bg-info text-white' :
                                                ($order->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')))
                                            }}">
                                            <i class="fas fa-circle me-1"></i>{{ ucfirst($order->status) }}
                                        </span></li>
                                        <li class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</li>
                                        <li class="mb-2"><strong>Total Amount:</strong> <span class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span></li>
                                        <li class="mb-2"><strong>Payment Status:</strong> <span class="badge bg-secondary text-uppercase">{{ ucfirst($order->payment_status ?? 'N/A') }}</span></li>
                                        <li class="mb-2"><strong>Order Type:</strong> <span class="badge bg-primary text-uppercase">{{ ucfirst($order->order_type ?? 'N/A') }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-4 p-4 h-100 bg-light shadow-sm">
                                    <h6 class="mb-3 text-primary fw-bold"><i class="fas fa-user me-2"></i>Customer Info</h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><strong>Name:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</li>
                                        <li class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a></li>
                                        <li class="mb-2"><strong>Phone:</strong> <a href="tel:{{ $order->user->phone_number }}">{{ $order->user->phone_number ?? 'N/A' }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-4 p-4 h-100 bg-light shadow-sm">
                                    @if($order->order_type === 'delivery')
                                        <h6 class="mb-3 text-success fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Delivery Info</h6>
                                        <ul class="list-unstyled mb-0 small">
                                            <li class="mb-2"><strong>Address:</strong> {{ $order->delivery_address ?? 'N/A' }}</li>
                                            @if($order->special_instructions)
                                                <li class="mb-2"><strong>Special Instructions:</strong> {{ $order->special_instructions }}</li>
                                            @endif
                                            @if($order->dietary_info)
                                                <li class="mb-2"><strong>Dietary Info:</strong>
                                                    @php
                                                        $dietary = $order->dietary_info;
                                                        if (is_string($dietary)) {
                                                            $dietary = json_decode($dietary, true);
                                                        }
                                                    @endphp
                                                    @if(is_array($dietary))
                                                        {{ implode(', ', $dietary) }}
                                                    @else
                                                        {{ $dietary ?? 'N/A' }}
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <h6 class="mb-3 text-success fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Location At Restaurant</h6>
                                        <ul class="list-unstyled mb-0 small">
                                            <li class="mb-2"><strong>Restaurant Name:</strong> {{ $order->restaurant->name ?? 'N/A' }}</li>
                                            <li class="mb-2"><strong>Address:</strong> {{ $order->restaurant->address ?? 'N/A' }}</li>
                                            @if($order->special_instructions)
                                                <li class="mb-2"><strong>Special Instructions:</strong> {{ $order->special_instructions }}</li>
                                            @endif
                                            @if($order->dietary_info)
                                                <li class="mb-2"><strong>Dietary Info:</strong>
                                                    @php
                                                        $dietary = $order->dietary_info;
                                                        if (is_string($dietary)) {
                                                            $dietary = json_decode($dietary, true);
                                                        }
                                                    @endphp
                                                    @if(is_array($dietary))
                                                        {{ implode(', ', $dietary) }}
                                                    @else
                                                        {{ $dietary ?? 'N/A' }}
                                                    @endif
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($order->restaurant)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="border rounded-4 p-4 bg-white shadow-sm">
                                    <h6 class="mb-3 text-info fw-bold"><i class="fas fa-store me-2"></i>Restaurant Info</h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li class="mb-2"><strong>Name:</strong> {{ $order->restaurant->name }}</li>
                                        <li class="mb-2"><strong>Address:</strong> {{ $order->restaurant->address ?? 'N/A' }}</li>
                                        <li class="mb-2"><strong>Email:</strong> {{ $order->restaurant->email ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <h6 class="mb-3 text-warning fw-bold"><i class="fas fa-utensils me-2"></i>Order Items</h6>
                                    <table class="table table-bordered table-striped align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                                <th>Instructions</th>
                                                <th>Dietary Info</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderItems as $item)
                                                <tr>
                                                    <td>{{ $item->menuItem->name }}</td>
                                                    <td>${{ number_format($item->price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                                    <td>{{ $item->special_instructions ?? 'N/A' }}</td>
                                                    <td>
                                                        @if(!empty($item->dietary_info))
                                                            @php
                                                                $dietary = $item->dietary_info;
                                                                if (is_string($dietary)) {
                                                                    $dietary = json_decode($dietary, true);
                                                                }
                                                            @endphp
                                                            @if(is_array($dietary))
                                                                {{ implode(', ', $dietary) }}
                                                            @else
                                                                {{ $dietary ?? 'N/A' }}
                                                            @endif
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="5" class="text-end">Total:</th>
                                                <th class="text-success fs-5">${{ number_format($order->total_amount, 2) }}</th>
                                                {{-- <th colspan="1"></th> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
