<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Browse') — {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @yield('style')

    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f9fafb; }
        .client-topnav { height: 56px; background: #fff; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; padding: 0 24px; gap: 16px; position: sticky; top: 0; z-index: 200; }
        .client-topnav .brand { font-weight: 700; font-size: 1.1rem; color: #184C55; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .client-topnav .brand img { width: 28px; height: 28px; object-fit: contain; }
        .client-nav-link { font-size: 0.85rem; color: #6b7280; text-decoration: none; padding: 6px 12px; border-radius: 20px; transition: all .15s; display: flex; align-items: center; gap: 6px; }
        .client-nav-link:hover, .client-nav-link.active { background: #ede9fe; color: #4f46e5; }
        .client-topnav .user-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
        .client-topnav .user-initials { width: 32px; height: 32px; border-radius: 50%; background: #4f46e5; color: #fff; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; }
        * { box-sizing: border-box; }
        .client-main { min-height: calc(100vh - 56px); }
    </style>
</head>
<body>

{{-- Top nav bar --}}
<nav class="client-topnav">
    <a href="{{ route('client.restaurants') }}" class="brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
        RestoFinder
    </a>

    <div class="d-flex align-items-center gap-1 ms-2">
        <a href="{{ route('client.restaurants') }}" class="client-nav-link {{ request()->routeIs('client.restaurants') ? 'active' : '' }}">
            <i class="fas fa-store-alt"></i>
            <span>Restaurants</span>
        </a>
        <a href="{{ route('client.my-orders') }}" class="client-nav-link {{ request()->routeIs('client.my-orders') ? 'active' : '' }}">
            <i class="fas fa-receipt"></i>
            <span>My Orders</span>
        </a>
        <a href="{{ route('client.my-reservations') }}" class="client-nav-link {{ request()->routeIs('client.my-reservations') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Reservations</span>
        </a>
    </div>

    <div class="ms-auto d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn p-0 border-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset(auth()->user()->profile_picture) }}" class="user-avatar" alt="profile">
                @else
                    <div class="user-initials">{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}</div>
                @endif
                <span class="fw-semibold text-dark" style="font-size:0.85rem;">{{ auth()->user()->first_name }}</span>
                <i class="fas fa-chevron-down text-muted" style="font-size:0.65rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" style="min-width:180px;">
                <li><a class="dropdown-item small py-2" href="{{ route('manage-edit-profile') }}"><i class="fas fa-user me-2 text-muted"></i>Profile</a></li>
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item small py-2 text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="client-main">
    @yield('content')
</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://unpkg.com/laravel-echo/dist/echo.umd.js"></script>

<script>
// ── Real-time client notifications ────────────────────────────
(function () {
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } },
    });

    var userId = {{ auth()->id() }};
    var toastWrap = document.getElementById('clientToastWrap');

    function showToast(icon, title, body, type) {
        var colors = { order: '#f59e0b', reservation: '#6366f1', default: '#10b981' };
        var color  = colors[type] || colors.default;
        var id     = 'toast-' + Date.now();
        var el     = document.createElement('div');
        el.id      = id;
        el.className = 'toast align-items-center border-0 show';
        el.setAttribute('role', 'alert');
        el.style.cssText = 'background:#fff;box-shadow:0 8px 32px rgba(0,0,0,.15);border-radius:14px;overflow:hidden;min-width:300px;';
        el.innerHTML = '<div style="width:4px;background:' + color + ';position:absolute;top:0;left:0;bottom:0;"></div>'
            + '<div class="d-flex align-items-start gap-3 p-3 ps-4">'
            + '<span style="font-size:1.3rem;">' + icon + '</span>'
            + '<div style="flex:1;">'
            + '<div class="fw-bold" style="font-size:.85rem;color:#111827;">' + title + '</div>'
            + '<div class="text-muted" style="font-size:.78rem;">' + body + '</div>'
            + '</div>'
            + '<button type="button" class="btn-close btn-close-sm ms-2" onclick="document.getElementById(\'' + id + '\').remove()"></button>'
            + '</div>';
        toastWrap.appendChild(el);
        setTimeout(function () { if (document.getElementById(id)) document.getElementById(id).remove(); }, 6000);
    }

    window.Echo.private('user.' + userId)
        .listen('OrderStatusChanged', function (e) {
            var o = e.order;
            var icons = { pending:'⏳', processing:'🍳', completed:'✅', cancelled:'❌' };
            showToast(icons[o.status] || '📦',
                'Order #' + o.id + ' — ' + o.status.charAt(0).toUpperCase() + o.status.slice(1),
                'Your order at ' + (o.restaurant?.name || 'the restaurant') + ' status updated.',
                'order'
            );
        })
        .listen('ReservationStatusChanged', function (e) {
            var r = e.reservation;
            var icons = { confirmed:'✅', cancelled:'❌', completed:'🎉', pending:'⏳' };
            showToast(icons[r.status] || '📅',
                'Reservation ' + r.status.charAt(0).toUpperCase() + r.status.slice(1),
                'Your reservation at ' + (r.restaurant?.name || 'the restaurant') + ' has been updated.',
                'reservation'
            );
        });
})();
</script>

{{-- Toast container --}}
<div id="clientToastWrap" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px;"></div>

@yield('script')
</body>
</html>
