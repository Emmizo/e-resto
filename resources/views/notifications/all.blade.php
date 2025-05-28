@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">All Notifications</h2>
    <div class="card">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                    <li class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-0 small text-muted">{{ $notification->body }}</p>
                                <small class="text-muted">{{ $notification->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center">No notifications found.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
