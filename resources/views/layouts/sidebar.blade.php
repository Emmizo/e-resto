@php
    $currentRoute = request()->route()?->getName() ?? '';
    $userData = session('userData')['users'] ?? null;
    $restaurantId = $userData?->restaurant_id ?? null;
    $isAdmin = auth()->user()->role === 'admin';

    function sidebarActive(string $routePattern): string {
        $current = request()->route()?->getName() ?? '';
        return str_contains($current, $routePattern) ? 'active' : '';
    }
@endphp

<aside class="sidebar position-fixed top-0 start-0 h-100" id="sidebar">

    {{-- Logo / Brand --}}
    <div class="sidebar-brand d-flex align-items-center px-3 py-3 border-bottom border-white border-opacity-10">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none w-100">
            @if($isAdmin)
                <div class="sidebar-logo-wrap d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:38px;height:38px;background:rgba(255,255,255,0.15);">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="width:26px;height:26px;object-fit:contain;">
                </div>
                <div class="menu-title">
                    <span class="fw-bold text-white d-block" style="font-size:0.9rem;line-height:1.2;">Resto-Finder</span>
                    <span class="text-white-50" style="font-size:0.7rem;">Admin Panel</span>
                </div>
            @elseif($userData?->restaurant_logo)
                <div class="sidebar-logo-wrap d-flex align-items-center justify-content-center rounded-3 flex-shrink-0 overflow-hidden"
                     style="width:38px;height:38px;background:rgba(255,255,255,0.15);">
                    <img src="{{ asset($userData->restaurant_logo) }}" alt="{{ $userData->restaurant_name }}" style="width:38px;height:38px;object-fit:cover;">
                </div>
                <div class="menu-title">
                    <span class="fw-bold text-white d-block text-truncate" style="font-size:0.85rem;line-height:1.2;max-width:130px;">{{ $userData->restaurant_name }}</span>
                    <span class="text-white-50" style="font-size:0.7rem;">{{ Str::title(str_replace('_',' ', auth()->user()->role)) }}</span>
                </div>
            @else
                <div class="sidebar-logo-wrap d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:38px;height:38px;background:rgba(255,255,255,0.15);">
                    <i class="fas fa-store-alt text-white"></i>
                </div>
                <div class="menu-title">
                    <span class="fw-bold text-white d-block text-truncate" style="font-size:0.85rem;line-height:1.2;max-width:130px;">{{ $userData->restaurant_name ?? 'Restaurant' }}</span>
                    <span class="text-white-50" style="font-size:0.7rem;">{{ Str::title(str_replace('_',' ', auth()->user()->role)) }}</span>
                </div>
            @endif
        </a>
    </div>

    {{-- Scrollable nav --}}
    <div class="sidebar-menu-items custom-scroll-content" id="sidebar-menu">
        <nav class="sidebar-nav-menu-items px-2 pt-2">

            {{-- Main section --}}
            <div class="sidebar-section-label menu-title">MAIN</div>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('dashboard') }}" href="{{ route('dashboard') }}">
                        <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                @hasrestaurantpermission('Order_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('orders') }}" href="{{ route('orders.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-receipt"></i></span>
                        <span class="menu-title">Manage Orders</span>
                    </a>
                </li>
                @endhasrestaurantpermission

                @hasrestaurantpermission('Reservation_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('reservations') }}" href="{{ route('reservations.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="menu-title">Reservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('tables') }}" href="{{ route('admin.tables.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-chair"></i></span>
                        <span class="menu-title">Manage Tables</span>
                    </a>
                </li>
                @endhasrestaurantpermission

                @hasrestaurantpermission('Menu_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('manage-menu') }}" href="{{ route('manage-menu') }}">
                        <span class="sidebar-icon"><i class="fas fa-utensils"></i></span>
                        <span class="menu-title">Manage Menu</span>
                    </a>
                </li>
                @if(!$isAdmin)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('inventory') }}" href="{{ route('inventory.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-boxes"></i></span>
                        <span class="menu-title">Inventory</span>
                    </a>
                </li>
                @endif
                @endhasrestaurantpermission

                @if(!$isAdmin)
                @hasrestaurantpermission('Promo_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('promo-banners') }}" href="{{ route('promo-banners.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-image"></i></span>
                        <span class="menu-title">Promo Banners</span>
                    </a>
                </li>
                @endhasrestaurantpermission
                @endif
            </ul>

            {{-- Analytics section --}}
            <div class="sidebar-section-label menu-title">ANALYTICS</div>
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('reports') }}" href="{{ route('reports.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="menu-title">Reports</span>
                    </a>
                </li>
            </ul>

            {{-- Management section --}}
            <div class="sidebar-section-label menu-title">MANAGEMENT</div>
            <ul class="nav flex-column mb-2">
                @hasrestaurantpermission('User_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('manage-users') }}" href="{{ route('manage-users') }}">
                        <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                        <span class="menu-title">Manage Users</span>
                    </a>
                </li>
                @endhasrestaurantpermission

                @hasrestaurantpermission('Role_Management', $restaurantId)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('roles') }}" href="{{ route('roles') }}">
                        <span class="sidebar-icon"><i class="fas fa-user-shield"></i></span>
                        <span class="menu-title">Manage Roles</span>
                    </a>
                </li>
                @endhasrestaurantpermission

                @if($isAdmin)
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('admin.restaurants') }}" href="{{ route('admin.restaurants.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-store-alt"></i></span>
                        <span class="menu-title">Restaurants</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ sidebarActive('admin.terms') }}" href="{{ route('admin.terms.index') }}">
                        <span class="sidebar-icon"><i class="fas fa-file-contract"></i></span>
                        <span class="menu-title">Terms & Conditions</span>
                    </a>
                </li>
                @endif
            </ul>

        </nav>
    </div>

    {{-- User profile at bottom --}}
    <div class="sidebar-footer border-top border-white border-opacity-10 px-3 py-2 menu-title">
        <div class="d-flex align-items-center gap-2">
            @if(!empty(auth()->user()->profile_picture))
                <img src="{{ asset(auth()->user()->profile_picture) }}"
                     alt="Profile" class="rounded-circle flex-shrink-0"
                     style="width:32px;height:32px;object-fit:cover;border:2px solid rgba(255,255,255,0.2);">
            @else
                <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                     style="width:32px;height:32px;background:rgba(255,255,255,0.15);border:2px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-user text-white" style="font-size:0.8rem;"></i>
                </div>
            @endif
            <div class="overflow-hidden">
                <div class="text-white fw-semibold text-truncate" style="font-size:0.78rem;line-height:1.3;">
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                </div>
                <a href="{{ route('logout') }}" class="text-white-50 text-decoration-none" style="font-size:0.7rem;">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </div>

</aside>
