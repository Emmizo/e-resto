@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Details #{{ $order->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Order Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge rounded-pill
                                        {{
                                            $order->status === 'completed' ? 'bg-success' :
                                            ($order->status === 'cancelled' ? 'bg-danger' :
                                            ($order->status === 'processing' ? 'bg-info' :
                                            ($order->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary')))
                                        }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Customer Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $order->user->first_name }} {{ $order->user->last_name    }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $order->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $order->user->phone_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Order Items</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td>{{ $item->menuItem->name }}</td>
                                                <td>${{ number_format($item->price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total:</th>
                                            <th>${{ number_format($order->total_amount, 2) }}</th>
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
