@extends('layouts.app')
@section('content')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 mt-8" data-restaurant-id="{{ session('userData')['users']->restaurant_id ?? '' }}">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Restaurant Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group ms-2 align-items-center">
                            @if(isset($restaurant))
                                <button id="toggle-reservations" class="btn btn-sm {{ $restaurant->accepts_reservations ? 'btn-success' : 'btn-danger' }}" data-state="{{ $restaurant->accepts_reservations ? '1' : '0' }}">
                                    Reservations: {{ $restaurant->accepts_reservations ? 'Open' : 'Close' }}
                                </button>
                                <button id="toggle-delivery" class="btn btn-sm ms-2 {{ $restaurant->accepts_delivery ? 'btn-success' : 'btn-danger' }}" data-state="{{ $restaurant->accepts_delivery ? '1' : '0' }}">
                                    Delivery: {{ $restaurant->accepts_delivery ? 'Open' : 'Close' }}
                                </button>
                            @endif
                        </div>
                        <div class="btn-group me-2">
                            <a href="{{ route('dashboard', ['range' => 'today']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Today
                            </a>
                            <a href="{{ route('dashboard', ['range' => 'week']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'week' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Week
                            </a>
                            <a href="{{ route('dashboard', ['range' => 'month']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'month' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Month
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboardData['total_orders']}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Revenue</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($dashboardData['total_revenue'], 2)}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Daily Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboardData['daily_recommendations']}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Today's Reservations</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboardData['reservations_today']}}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <!-- Order Activity Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Order Activity - {{ ucfirst($dashboardData['current_range']) }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="position: relative; height: 300px;">
                                    <canvas id="userActivityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Types -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Order Distribution</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2" style="position: relative; height: 300px;">
                                    <canvas id="recommendationPieChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-primary"></i> Dine-in
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-success"></i> Takeaway
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-info"></i> Delivery
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    {{ Auth::user()->role == 'admin' ? 'All Users' : 'Restaurant Employees' }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dashboardData['users'] as $user)
                                            <tr>
                                                <td>{{$user->first_name}} {{$user->last_name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{ucfirst($user->role)}}</td>
                                                <td>
                                                    <span class="badge {{$user->status == 'active' ? 'bg-success' : 'bg-danger'}}">
                                                        {{ucfirst($user->status)}}
                                                    </span>
                                                </td>
                                                <td>{{$user->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('M d, Y')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Details -->
                <div class="row">
                    <!-- Top Menu Items -->
                    <div class="col-lg-12 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Top Menu Items</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Category</th>
                                                <th>Orders</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dashboardData['top_menu_items'] as $item)
                                            <tr>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->category}}</td>
                                                <td>{{$item->total_orders}}</td>
                                                <td>${{number_format($item->total_revenue ?? 0, 2)}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
<!-- Restaurant Ratings and Reviewer Comments Section -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Restaurant Rating</div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Restaurant</th>
                                <th>Cuisine Type</th>
                                <th>Rating</th>
                                <th>Reviews</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dashboardData['top_restaurants'] as $restaurant)
                            <tr>
                                <td>{{ $restaurant->name }}</td>
                                <td>{{ optional($restaurant->cuisine)->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span>{{ number_format($restaurant->rating ?? 0, 1) }}</span>
                                    </div>
                                </td>
                                <td>{{ $restaurant->review_count ?? 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Reviewer Comments for Each Restaurant</div>
            <div class="card-body p-2">
                @foreach($dashboardData['top_restaurants'] as $restaurant)
                    <div class="mb-4">
                        <h5>{{ $restaurant->name }}</h5>
                        @if($restaurant->reviews && $restaurant->reviews->isNotEmpty())
                            <ul class="list-group mb-3">
                                @foreach($restaurant->reviews as $review)
                                    <li class="list-group-item">
                                        <strong>
                                            {{ $review->user->first_name ?? 'Anonymous' }}
                                            {{ $review->user->last_name ?? '' }}:
                                        </strong>
                                        <span class="text-warning">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                ★
                                            @endfor
                                        </span>
                                        <br>
                                        <span>{{ $review->comment }}</span>
                                        <br>
                                        <small class="text-muted">{{ $review->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('M d, Y H:i') }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
                    <!-- Recent Orders -->
                    <div class="col-lg-12 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                            </div>
                            <div class="card-body">
                            <div class="row col-12">
                                @forelse($dashboardData['recent_orders'] as $order)
                                    <div class="alert alert-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} alert-dismissible fade show col-6 p-2" role="alert">
                                        <strong>Order #{{ $order->id }}</strong>
                                        @if(auth()->user()->role == 'admin')
                                            - {{ $order->restaurant->name }}
                                        @endif
                                        <br>
                                        <small>
                                            Status: {{ ucfirst($order->status) }} |
                                            Type: {{ ucfirst($order->order_type) }} |
                                            Amount: ${{ number_format($order->total_amount, 2) }}
                                        </small>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @empty
                                    <div class="alert alert-info">
                                        No recent orders found.
                                    </div>
                                @endforelse
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promo Banners Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Active Promo Banners</h6>
                                <a href="{{ route('promo-banners.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add New Banner
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Image</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="promo-banners-table">
                                            @foreach($dashboardData['promo_banners'] ?? [] as $banner)
                                            <tr id="banner-{{ $banner->id }}">
                                                <td>{{ $banner->title }}</td>
                                                <td>{{ $banner->description }}</td>
                                                <td>
                                                    @if($banner->image_path)
                                                        <img src="{{ $banner->image_path }}" alt="{{ $banner->title }}" style="max-width: 100px;">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>{{ $banner->start_date ? $banner->start_date->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $banner->end_date ? $banner->end_date->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('promo-banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('promo-banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </main>

@endsection

@section('script')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        // Subscribe to the orders channel
        const ordersChannel = pusher.subscribe('orders');

        // Listen for new orders
        ordersChannel.bind('OrderCreated', function(data) {
            const order = data.order;
            const orderHtml = `
                <div class="alert alert-${order.status === 'completed' ? 'success' : (order.status === 'pending' ? 'warning' : 'info')} alert-dismissible fade show col-6 p-2" role="alert">
                    <strong>Order #${order.id}</strong>
                    ${order.restaurant ? `- ${order.restaurant.name}` : ''}
                    <br>
                    <small>
                        Status: ${order.status.charAt(0).toUpperCase() + order.status.slice(1)} |
                        Type: ${order.order_type.charAt(0).toUpperCase() + order.order_type.slice(1)} |
                        Amount: $${parseFloat(order.total_amount).toFixed(2)}
                    </small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            // Add the new order to the top of the recent orders section
            const recentOrdersContainer = document.querySelector('.card-body .row');
            if (recentOrdersContainer) {
                recentOrdersContainer.insertAdjacentHTML('afterbegin', orderHtml);

                // Remove the last order if there are more than 10 orders
                const alerts = recentOrdersContainer.querySelectorAll('.alert');
                if (alerts.length > 10) {
                    alerts[alerts.length - 1].remove();
                }
            }

            // Update order activity chart
            updateOrderActivityChart();
        });

        // Subscribe to restaurant channel for service updates
        const restaurantChannel = pusher.subscribe('restaurant.{{ $restaurant->id ?? "" }}');

        // Listen for service status updates
        restaurantChannel.bind('ServiceStatusUpdated', function(data) {
            const serviceType = data.service_type;
            const status = data.status;
            const button = document.getElementById(`toggle-${serviceType}`);

            if (button) {
                button.classList.toggle('btn-success', status);
                button.classList.toggle('btn-danger', !status);
                button.setAttribute('data-state', status ? '1' : '0');
                button.textContent = `${serviceType.charAt(0).toUpperCase() + serviceType.slice(1)}: ${status ? 'Open' : 'Close'}`;
            }
        });

        // Listen for menu item updates
        restaurantChannel.bind('MenuItemUpdated', function(data) {
            const menuItem = data.menu_item;
            const action = data.action;

            // Update top menu items table
            const menuItemsTable = document.querySelector('.table-responsive table tbody');
            if (menuItemsTable) {
                if (action === 'created' || action === 'updated') {
                    updateMenuItemInTable(menuItem);
                } else if (action === 'deleted') {
                    removeMenuItemFromTable(menuItem.id);
                }
            }
        });

        // Listen for reservation updates
        restaurantChannel.bind('ReservationCreated', function(data) {
            const reservation = data.reservation;

            // Update reservations count in the stats card
            const reservationsCard = document.querySelector('.stat-card .h5.mb-0.font-weight-bold.text-gray-800');
            if (reservationsCard) {
                const currentCount = parseInt(reservationsCard.textContent);
                if (!isNaN(currentCount)) {
                    reservationsCard.textContent = currentCount + 1;
                }
            }

            // Update reservation activity chart
            updateOrderActivityChart();
        });

        // Function to update menu item in table
        function updateMenuItemInTable(menuItem) {
            const existingRow = document.querySelector(`tr[data-menu-item-id="${menuItem.id}"]`);
            const rowHtml = `
                <tr data-menu-item-id="${menuItem.id}">
                    <td>${menuItem.name}</td>
                    <td>${menuItem.category}</td>
                    <td>${menuItem.total_orders || 0}</td>
                    <td>$${parseFloat(menuItem.total_revenue || 0).toFixed(2)}</td>
                </tr>
            `;

            if (existingRow) {
                existingRow.outerHTML = rowHtml;
            } else {
                menuItemsTable.insertAdjacentHTML('beforeend', rowHtml);
            }
        }

        // Function to remove menu item from table
        function removeMenuItemFromTable(menuItemId) {
            const row = document.querySelector(`tr[data-menu-item-id="${menuItemId}"]`);
            if (row) {
                row.remove();
            }
        }

        // Function to update order activity chart
        function updateOrderActivityChart() {
            // Fetch updated chart data
            fetch('/dashboard/chart-data')
                .then(response => response.json())
                .then(data => {
                    activityChart.data.labels = data.activity_labels;
                    activityChart.data.datasets[0].data = data.order_activity_data;
                    activityChart.data.datasets[1].data = data.reservation_activity_data;
                    activityChart.update();
                });
        }

        // Order Activity Chart
        const userActivityCtx = document.getElementById('userActivityChart');
        if (userActivityCtx) {
            const activityChart = new Chart(userActivityCtx, {
                type: 'line',
                data: {
                    labels: @json($dashboardData['activity_labels']),
                    datasets: [
                        {
                            label: 'Orders',
                            data: @json($dashboardData['order_activity_data']),
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.1)',
                            tension: 0.1,
                            fill: true
                        },
                        {
                            label: 'Reservations',
                            data: @json($dashboardData['reservation_activity_data']),
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            tension: 0.1,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Order Types Chart
        const recommendationCtx = document.getElementById('recommendationPieChart');
        if (recommendationCtx) {
            const pieChart = new Chart(recommendationCtx, {
                type: 'pie',
                data: {
                    labels: ['Dine-in', 'Takeaway', 'Delivery'],
                    datasets: [{
                        data: @json($dashboardData['recommendation_data']),
                        backgroundColor: [
                            'rgb(54, 162, 235)',
                            'rgb(75, 192, 192)',
                            'rgb(255, 205, 86)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function toggleService(type, btn) {
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/dashboard/toggle-service', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // Update button state
                    btn.classList.toggle('btn-success');
                    btn.classList.toggle('btn-danger');
                    btn.setAttribute('data-state', data.value ? '1' : '0');
                    btn.textContent = (type.charAt(0).toUpperCase() + type.slice(1)) + ': ' + (data.value ? 'Open' : 'Close');
                }
            });
        }
        var resBtn = document.getElementById('toggle-reservations');
        var delBtn = document.getElementById('toggle-delivery');
        if(resBtn) {
            resBtn.addEventListener('click', function() {
                toggleService('reservations', this);
            });
        }
        if(delBtn) {
            delBtn.addEventListener('click', function() {
                toggleService('delivery', this);
            });
        }

        // Listen for promo banner updates
        Echo.channel('restaurant.{{ session('userData')['users']->restaurant_id }}')
            .listen('PromoBannerUpdated', (e) => {
                const banner = e.promo_banner;
                const action = e.action;
                const tableBody = document.getElementById('promo-banners-table');
                const bannerRow = document.getElementById(`banner-${banner.id}`);

                if (action === 'created') {
                    const newRow = createBannerRow(banner);
                    tableBody.insertBefore(newRow, tableBody.firstChild);
                } else if (action === 'updated') {
                    if (bannerRow) {
                        bannerRow.replaceWith(createBannerRow(banner));
                    }
                } else if (action === 'deleted') {
                    if (bannerRow) {
                        bannerRow.remove();
                    }
                }
            });

        function createBannerRow(banner) {
            const row = document.createElement('tr');
            row.id = `banner-${banner.id}`;
            row.innerHTML = `
                <td>${banner.title}</td>
                <td>${banner.description || ''}</td>
                <td>
                    ${banner.image_url ?
                        `<img src="${banner.image_url}" alt="${banner.title}" style="max-width: 100px;">` :
                        'No Image'}
                </td>
                <td>${banner.start_date ? new Date(banner.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                <td>${banner.end_date ? new Date(banner.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                <td>
                    <span class="badge ${banner.is_active ? 'bg-success' : 'bg-danger'}">
                        ${banner.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td>
                    <a href="/promo-banners/${banner.id}/edit" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="/promo-banners/${banner.id}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            `;
            return row;
        }
    });
</script>
@endsection
