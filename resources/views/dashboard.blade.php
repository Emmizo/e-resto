@extends('layouts.app')
@section('content')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 mt-8">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Restaurant Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
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
                                                <td>{{$user->created_at->format('M d, Y')}}</td>
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
                    <div class="col-lg-6 mb-4">
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

                    <!-- Recent Orders -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                            </div>
                            <div class="card-body">
                                @forelse($dashboardData['recent_orders'] as $order)
                                    <div class="alert alert-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }} alert-dismissible fade show" role="alert">
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

                <!-- Restaurant Ratings Section -->
                @if($dashboardData['top_restaurants']->isNotEmpty())
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                @if(auth()->user()->role == 'admin')
                                    Top Rated Restaurants
                                @else
                                    Restaurant Rating
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
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
                                            <td>{{$restaurant->name}}</td>
                                            <td>{{$restaurant->cuisine_type}}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-star text-warning me-1"></i>
                                                    <span>{{number_format($restaurant->rating ?? 0, 1)}}</span>
                                                </div>
                                            </td>
                                            <td>{{$restaurant->review_count ?? 0}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </main>

@endsection

@section('script')

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection
