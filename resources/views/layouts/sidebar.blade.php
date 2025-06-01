
    <!-- Sidebar -->
    <aside class="sidebar position-fixed top-0 start-0 h-100 p-3" id="sidebar">
        <div class="sidebar-header mb-5 text-center">

            <a href="{{ route('dashboard') }}" class="sidebar-header-logo" title="{{$data['users']->restaurant_name}}">
                @if(auth()->user()->role=="admin")
                <img src="{{asset('assets/images/logo.png')}}" alt="Resto-Finder" width="61" height="86" class="sidebar-header-logo-img">
                @elseif($data['users']->restaurant_logo)
                <img src="{{asset($data['users']->restaurant_logo)??''}}" alt="{{$data['users']->restaurant_name}}" width="61" height="86" class="sidebar-header-logo-img">
                @else
                <img src="{{asset('assets/images/logo.png')}}" alt="Resto-Finder" width="61" height="86" class="sidebar-header-logo-img">
                @endif
            </a>
        </div>
        <div class="sidebar-menu-items custom-scroll-content h-100" id="sidebar-menu">
            <nav class="sidebar-nav-menu-items">
                <ul class="nav">
                    @hasrestaurantpermission('User_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{route('manage-users')}}" title="Manage Users">
                            <i class="fas fa-users me-2"></i>
                            <span class="menu-title">Manage Users</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                    @hasrestaurantpermission('Role_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{route('roles')}}" title="Manage Role">
                            <i class="fas fa-user-shield me-2"></i>
                            <span class="menu-title">Manage Role</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                    @hasrestaurantpermission('Menu_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{route('manage-menu')}}" title="Manage Menu">
                            <i class="fas fa-utensils me-2"></i>
                            <span class="menu-title">Manage Menu</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                    @hasrestaurantpermission('Promo_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{ route('promo-banners.index') }}" title="Promo Banners">
                            <i class="fas fa-image me-2"></i>
                            <span class="menu-title">Promo Banners</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                    @hasrestaurantpermission('Order_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{route('orders.index')}}" title="Manage Order">
                            <i class="fas fa-receipt me-2"></i>
                            <span class="menu-title">Manage Order</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                    @hasrestaurantpermission('Reservation_Management', $data['users']->restaurant_id)
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{route('reservations.index')}}" title="Manage Reservation">
                            <i class="fas fa-calendar-check me-2"></i>
                            <span class="menu-title">Manage Reservation</span>
                        </a>
                    </li>
                    @endhasrestaurantpermission
                </ul>
                @if(auth()->user()->role == 'admin')
                <ul class="nav mt-4">
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{ route('admin.restaurants.index') }}" title="Restaurants">
                            <i class="fas fa-store-alt me-2"></i>
                            <span class="menu-title">Restaurants</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-dmsans fw-medium text-white" href="{{ route('admin.terms.index') }}" title="Terms and Conditions">
                            <i class="fas fa-file-contract me-2"></i>
                            <span class="menu-title">Terms & Conditions</span>
                        </a>
                    </li>
                </ul>
                @endif
            </nav>
        </div>
    </aside>

