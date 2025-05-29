@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Platform Logo" style="height:48px;">
                                @if($reservation->restaurant && $reservation->restaurant->image)
                                    <img src="{{ url($reservation->restaurant->image) }}" alt="{{ $reservation->restaurant->name }} Logo" style="height:40px; border-radius:8px; background:#fff; margin-left:8px;">
                                @endif
                                <h3 class="mt-3 mb-1" style="color:#184C55; font-weight:700;">Reservation Details</h3>
                                <div class="text-muted mb-1">{{ $reservation->restaurant->name ?? '' }}</div>
                                @if($reservation->restaurant && $reservation->restaurant->address)
                                    <div class="small text-muted">{{ $reservation->restaurant->address }}</div>
                                @endif
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Reservation ID:</strong><br>{{ $reservation->id }}
                                </div>
                                <div class="col-6">
                                    <strong>Status:</strong><br>
                                    <span class="badge rounded-pill bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Date & Time:</strong><br>
                                    {{ $reservation->reservation_time ? $reservation->reservation_time->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('M d, Y H:i') : 'N/A' }}
                                </div>
                                <div class="col-6">
                                    <strong>Created At:</strong><br>
                                    {{ $reservation->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('M d, Y H:i') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Party Size:</strong><br>{{ $reservation->number_of_people }}
                                </div>
                                <div class="col-6">
                                    <strong>Phone Number:</strong><br>{{ $reservation->phone_number ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>Customer:</strong><br>
                                {{ $reservation->user->first_name ?? '' }} {{ $reservation->user->last_name ?? '' }}<br>
                                <span class="text-muted small">{{ $reservation->user->email ?? '' }}</span>
                            </div>
                            @if($reservation->special_requests)
                                <div class="mb-3">
                                    <strong>Special Requests:</strong><br>
                                    <span>{{ $reservation->special_requests }}</span>
                                </div>
                            @endif
                            <a href="{{ route('reservations.index') }}" class="btn btn-outline-primary mt-3">Back to Reservations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
