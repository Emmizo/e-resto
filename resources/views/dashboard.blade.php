@extends('layouts.app')
@section('content')

<main class="content-wrapper">
    <div class="main-content" data-restaurant-id="{{ session('userData')['users']->restaurant_id ?? '' }}">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Restaurant Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @if(isset($restaurant))
                        <div class="d-flex align-items-center gap-2 me-3">
                            <!-- Reservations Toggle -->
                            <button id="toggle-reservations"
                                data-state="{{ $restaurant->accepts_reservations ? '1' : '0' }}"
                                class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-semibold shadow-sm border-0 toggle-service-btn {{ $restaurant->accepts_reservations ? 'btn-toggle-open' : 'btn-toggle-closed' }}"
                                style="{{ $restaurant->accepts_reservations ? 'background:linear-gradient(135deg,#10b981,#059669);color:#fff;' : 'background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;' }}">
                                <span class="status-dot rounded-circle d-inline-block" style="width:8px;height:8px;background:{{ $restaurant->accepts_reservations ? 'rgba(255,255,255,0.7)' : 'rgba(255,255,255,0.7)' }};"></span>
                                <i class="fas fa-calendar-check" style="font-size:0.8rem;"></i>
                                <span class="btn-label">Reservations</span>
                                <span class="badge rounded-pill ms-1 px-2" style="font-size:0.65rem;background:rgba(255,255,255,0.25);">
                                    {{ $restaurant->accepts_reservations ? 'OPEN' : 'CLOSED' }}
                                </span>
                            </button>
                            <!-- Delivery Toggle -->
                            <button id="toggle-delivery"
                                data-state="{{ $restaurant->accepts_delivery ? '1' : '0' }}"
                                class="btn btn-sm d-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-semibold shadow-sm border-0 toggle-service-btn {{ $restaurant->accepts_delivery ? 'btn-toggle-open' : 'btn-toggle-closed' }}"
                                style="{{ $restaurant->accepts_delivery ? 'background:linear-gradient(135deg,#10b981,#059669);color:#fff;' : 'background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;' }}">
                                <span class="status-dot rounded-circle d-inline-block" style="width:8px;height:8px;background:rgba(255,255,255,0.7);"></span>
                                <i class="fas fa-motorcycle" style="font-size:0.8rem;"></i>
                                <span class="btn-label">Delivery</span>
                                <span class="badge rounded-pill ms-1 px-2" style="font-size:0.65rem;background:rgba(255,255,255,0.25);">
                                    {{ $restaurant->accepts_delivery ? 'OPEN' : 'CLOSED' }}
                                </span>
                            </button>
                        </div>
                        @endif
                        <div class="btn-group me-2">
                            <a href="{{ route('dashboard', ['range' => 'today']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'today' ? 'btn-primary' : 'btn-outline-secondary' }}">Today</a>
                            <a href="{{ route('dashboard', ['range' => 'week']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'week' ? 'btn-primary' : 'btn-outline-secondary' }}">Week</a>
                            <a href="{{ route('dashboard', ['range' => 'month']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'month' ? 'btn-primary' : 'btn-outline-secondary' }}">Month</a>
                            <a href="{{ route('dashboard', ['range' => 'year']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === 'year' ? 'btn-primary' : 'btn-outline-secondary' }}">Year</a>
                            <a href="{{ route('dashboard', ['range' => '2_years']) }}"
                               class="btn btn-sm {{ $dashboardData['current_range'] === '2_years' ? 'btn-primary' : 'btn-outline-secondary' }}">2 Years</a>
                        </div>

                    </div>
                </div>

                <!-- KPI Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 p-3" style="background:#ede9fe;">
                                    <i class="fas fa-receipt fa-lg" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-semibold">Total Orders</div>
                                    <div class="fs-5 fw-bold">{{$dashboardData['total_orders']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 p-3" style="background:#d1fae5;">
                                    <i class="fas fa-money-bill-wave fa-lg" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-semibold">Total Revenue</div>
                                    <div class="fs-5 fw-bold">RWF {{number_format($dashboardData['total_revenue'], 0)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 p-3" style="background:#cffafe;">
                                    <i class="fas fa-calendar-check fa-lg" style="color:#06b6d4;"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-semibold">Reservations</div>
                                    <div class="fs-5 fw-bold">{{$dashboardData['reservations_today']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 p-3" style="background:#fef3c7;">
                                    <i class="fas fa-utensils fa-lg" style="color:#f59e0b;"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-semibold">Active Menu Items</div>
                                    <div class="fs-5 fw-bold">{{$dashboardData['active_menu_items']}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl col-md-4 col-sm-6 mb-3">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="rounded-3 p-3" style="background:#fee2e2;">
                                    <i class="fas fa-star fa-lg" style="color:#ef4444;"></i>
                                </div>
                                <div>
                                    <div class="text-muted small text-uppercase fw-semibold">Avg Rating</div>
                                    <div class="fs-5 fw-bold">{{number_format($dashboardData['avg_rating'], 1)}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Overview Chart -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Activity Overview - {{ $dashboardData['current_range'] === '2_years' ? '2 Years' : ucfirst($dashboardData['current_range']) }}
                                </h6>
                                <span class="small text-muted">Orders and reservations trend</span>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="position: relative; height: 320px;">
                                    <canvas id="activityOverviewChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <!-- Order Activity Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">
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

                    <!-- Order Types Breakdown -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow-sm border-0 rounded-4 mb-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Order Types</h6>
                                <span class="badge bg-primary rounded-pill px-3" style="font-size:0.7rem;">
                                    {{ array_sum($dashboardData['recommendation_data']) }} total
                                </span>
                            </div>
                            <div class="card-body pt-2">
                                <!-- Donut chart -->
                                <div style="position:relative;height:180px;">
                                    <canvas id="recommendationPieChart"></canvas>
                                </div>

                                @php
                                    $types = [
                                        ['key'=>'dine_in',  'label'=>'Dine-in',  'icon'=>'fa-utensils',    'color'=>'#4f46e5', 'bg'=>'#ede9fe'],
                                        ['key'=>'takeaway', 'label'=>'Takeaway', 'icon'=>'fa-shopping-bag','color'=>'#06b6d4', 'bg'=>'#cffafe'],
                                        ['key'=>'delivery', 'label'=>'Delivery', 'icon'=>'fa-motorcycle',  'color'=>'#10b981', 'bg'=>'#d1fae5'],
                                    ];
                                    $totalOrders = array_sum($dashboardData['recommendation_data']) ?: 1;
                                    $totalRevenue = array_sum($dashboardData['order_type_revenue']) ?: 1;
                                    $counts  = $dashboardData['order_types_raw'];
                                    $revenues = $dashboardData['order_type_revenue_raw'];
                                    $topKey = array_search(max($counts), $counts);
                                @endphp

                                <!-- Per-type stat rows -->
                                <div class="mt-3 d-flex flex-column gap-2">
                                    @foreach($types as $t)
                                    @php
                                        $cnt = $counts[$t['key']] ?? 0;
                                        $rev = $revenues[$t['key']] ?? 0;
                                        $pct = round($cnt / $totalOrders * 100);
                                        $revPct = round($rev / $totalRevenue * 100);
                                        $isBest = ($t['key'] === $topKey);
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 {{ $isBest ? 'border border-2' : '' }}"
                                         style="{{ $isBest ? 'border-color:'.$t['color'].'!important;background:'.str_replace(')', ',0.04)', str_replace('rgb','rgba',$t['bg'])).';' : '' }}">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:34px;height:34px;background:{{ $t['bg'] }};">
                                            <i class="fas {{ $t['icon'] }}" style="color:{{ $t['color'] }};font-size:0.8rem;"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-semibold small">{{ $t['label'] }}
                                                    @if($isBest)<span class="badge ms-1 rounded-pill" style="font-size:0.6rem;background:{{ $t['color'] }};color:#fff;">TOP</span>@endif
                                                </span>
                                                <span class="fw-bold small" style="color:{{ $t['color'] }};">{{ $cnt }}</span>
                                            </div>
                                            <div class="progress rounded-pill" style="height:5px;">
                                                <div class="progress-bar rounded-pill" role="progressbar"
                                                     style="width:{{ $pct }}%;background:{{ $t['color'] }};" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span class="text-muted" style="font-size:0.7rem;">{{ $pct }}% of orders</span>
                                                <span class="text-muted" style="font-size:0.7rem;">RWF {{ number_format($rev, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue vs Orders + Reservation Status -->
                <div class="row mb-4">
                    <div class="col-xl-8">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Revenue vs Orders (Last 7 Days)</h6>
                            </div>
                            <div class="card-body">
                                <div style="position:relative;height:300px;">
                                    <canvas id="revenueVsOrdersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Reservation Status</h6>
                            </div>
                            <div class="card-body">
                                <div style="position:relative;height:300px;">
                                    <canvas id="reservationStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin' && $dashboardData['restaurant_stats']->count())
                <!-- Restaurant Performance Charts (admin only) -->
                <div class="row mb-4">
                    <div class="col-xl-8">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Restaurant Revenue Comparison</h6>
                            </div>
                            <div class="card-body">
                                <div style="position:relative;height:300px;">
                                    <canvas id="restaurantRevenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card shadow-sm border-0 rounded-4 mb-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Orders per Restaurant</h6>
                            </div>
                            <div class="card-body">
                                <div style="position:relative;height:300px;">
                                    <canvas id="restaurantOrdersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">All Restaurants Summary</h6>
                                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Full Report</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="px-4">Restaurant</th>
                                                <th class="text-center">Orders</th>
                                                <th class="text-end pe-4">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dashboardData['restaurant_stats'] as $rs)
                                            <tr>
                                                <td class="px-4">{{ $rs['name'] }}</td>
                                                <td class="text-center">{{ $rs['orders'] }}</td>
                                                <td class="text-end pe-4">RWF {{ number_format($rs['revenue'], 0) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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
                                                <td>{{$user->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('d/m/Y')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Menu Items -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Top Menu Items</h6>
                                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Full Report</a>
                            </div>
                            <div class="card-body">
                                <div style="position:relative;height:220px;" class="mb-4">
                                    <canvas id="topMenuItemsChart"></canvas>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th>Category</th>
                                                <th class="text-center">Orders</th>
                                                <th class="text-end">Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($dashboardData['top_menu_items'] as $item)
                                            <tr>
                                                <td>{{$item->name}}</td>
                                                <td><span class="badge bg-light text-dark">{{$item->category ?? '—'}}</span></td>
                                                <td class="text-center">{{$item->total_orders}}</td>
                                                <td class="text-end">RWF {{number_format($item->total_revenue ?? 0, 0)}}</td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="4" class="text-center text-muted">No data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Ratings -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold mb-0">Restaurant Ratings</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @forelse($dashboardData['top_restaurants'] as $rest)
                                    <li class="list-group-item px-4 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold small">{{ $rest->name }}</div>
                                                <div class="text-muted xsmall">{{ optional($rest->cuisine)->name ?? 'N/A' }}</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-warning">
                                                    <i class="fas fa-star me-1"></i>{{ number_format($rest->rating ?? 0, 1) }}
                                                </div>
                                                <div class="text-muted xsmall">{{ $rest->review_count ?? 0 }} reviews</div>
                                            </div>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="list-group-item text-center text-muted py-4">No ratings yet</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders + Promo Banners -->
                <div class="row mb-4">
                    <div class="col-lg-7 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Recent Orders</h6>
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="px-4">#</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                @if(auth()->user()->role == 'admin')<th>Restaurant</th>@endif
                                            </tr>
                                        </thead>
                                        <tbody id="recent-orders-tbody">
                                            @forelse($dashboardData['recent_orders'] as $order)
                                            <tr>
                                                <td class="px-4">#{{ $order->id }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
                                                <td>RWF {{ number_format($order->total_amount, 0) }}</td>
                                                <td>
                                                    <span class="badge rounded-pill
                                                        {{ $order->status === 'completed' ? 'bg-success' :
                                                           ($order->status === 'cancelled' ? 'bg-danger' :
                                                           ($order->status === 'processing' ? 'bg-info' : 'bg-warning text-dark')) }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                @if(auth()->user()->role == 'admin')<td>{{ $order->restaurant->name ?? '—' }}</td>@endif
                                            </tr>
                                            @empty
                                            <tr><td colspan="5" class="text-center text-muted py-4">No recent orders</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->role !== 'admin')
                    <div class="col-lg-5 mb-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Active Promo Banners</h6>
                                <a href="{{ route('promo-banners.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i>Add
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush" id="promo-banners-list">
                                    @forelse($dashboardData['promo_banners'] ?? [] as $banner)
                                    <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center" id="banner-{{ $banner->id }}">
                                        <div>
                                            <div class="fw-semibold small">{{ $banner->title }}</div>
                                            <div class="text-muted xsmall">
                                                {{ $banner->start_date ? $banner->start_date->format('M d') : '' }}
                                                @if($banner->end_date) – {{ $banner->end_date->format('d/m/Y') }} @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <a href="{{ route('promo-banners.edit', $banner->id) }}" class="btn btn-xs btn-outline-secondary py-0 px-1">
                                                <i class="fas fa-edit fa-xs"></i>
                                            </a>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="list-group-item text-center text-muted py-4">No promo banners</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

    </div>
</main>

<!-- Service Toggle Confirm Modal -->
<div class="modal fade" id="serviceToggleModal" tabindex="-1" aria-labelledby="serviceToggleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 px-4 pt-4" id="serviceToggleModalHeader">
                <h5 class="modal-title fw-bold" id="serviceToggleModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div id="serviceToggleModalIcon" class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i id="serviceToggleModalIconInner" class="fas fa-lg"></i>
                    </div>
                    <p class="mb-0 fs-6" id="serviceToggleModalBody">Are you sure?</p>
                </div>
                <div id="serviceToggleModalNote" class="rounded-3 px-3 py-2 small" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn rounded-pill px-4 fw-semibold" id="serviceToggleConfirmBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
const dashboardRange = @json($dashboardData['current_range']);

// ── Charts: run immediately, no Echo dependency ──────────────────────────────

    // Order Activity Chart
    const userActivityCtx = document.getElementById('userActivityChart');
    let activityChart = null;
    if (userActivityCtx) {
        const uaGradient1 = userActivityCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        uaGradient1.addColorStop(0, 'rgba(79,70,229,0.3)');
        uaGradient1.addColorStop(1, 'rgba(79,70,229,0)');
        const uaGradient2 = userActivityCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        uaGradient2.addColorStop(0, 'rgba(16,185,129,0.3)');
        uaGradient2.addColorStop(1, 'rgba(16,185,129,0)');
        activityChart = new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: @json($dashboardData['activity_labels']),
                datasets: [
                    {
                        label: 'Orders',
                        data: @json($dashboardData['order_activity_data']),
                        borderColor: '#4f46e5',
                        backgroundColor: uaGradient1,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                    },
                    {
                        label: 'Reservations',
                        data: @json($dashboardData['reservation_activity_data']),
                        borderColor: '#10b981',
                        backgroundColor: uaGradient2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'top' } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    // Activity Overview Chart
    const activityOverviewCtx = document.getElementById('activityOverviewChart');
    if (activityOverviewCtx) {
        window.activityOverviewChart = new Chart(activityOverviewCtx, {
            type: 'bar',
            data: {
                labels: @json($dashboardData['activity_labels']),
                datasets: [
                    {
                        label: 'Orders',
                        data: @json($dashboardData['order_activity_data']),
                        backgroundColor: 'rgba(79,70,229,0.85)',
                        borderRadius: 8,
                        barThickness: 14
                    },
                    {
                        label: 'Reservations',
                        data: @json($dashboardData['reservation_activity_data']),
                        backgroundColor: 'rgba(16,185,129,0.85)',
                        borderRadius: 8,
                        barThickness: 14
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#6b7280' } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#6b7280' }, grid: { color: 'rgba(148,163,184,0.18)' } }
                }
            }
        });
    }

    // Order Types Donut Chart
    const recommendationCtx = document.getElementById('recommendationPieChart');
    if (recommendationCtx) {
        const orderTypeCounts   = @json($dashboardData['recommendation_data']);
        const orderTypeRevenues = @json($dashboardData['order_type_revenue']);
        const totalOrders = orderTypeCounts.reduce((a,b) => a+b, 0) || 1;
        new Chart(recommendationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dine-in', 'Takeaway', 'Delivery'],
                datasets: [{
                    data: orderTypeCounts,
                    backgroundColor: ['#4f46e5','#06b6d4','#10b981'],
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const pct = Math.round(ctx.parsed / totalOrders * 100);
                                const rev = orderTypeRevenues[ctx.dataIndex];
                                return [
                                    ` ${ctx.parsed} orders (${pct}%)`,
                                    ` RWF ${rev.toLocaleString()}`
                                ];
                            }
                        }
                    }
                }
            }
        });
    }

    // Revenue vs Orders dual-axis chart (last 7 days)
    const rvCtx = document.getElementById('revenueVsOrdersChart');
    if (rvCtx) {
        const revenueByDay = @json($dashboardData['revenue_by_day']);
        const last7Labels = [];
        for (let i = 6; i >= 0; i--) {
            last7Labels.push(moment().subtract(i, 'days').format('MMM D'));
        }
        new Chart(rvCtx, {
            type: 'bar',
            data: {
                labels: last7Labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Revenue (RWF)',
                        data: revenueByDay,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'yRevenue',
                        pointRadius: 4,
                    },
                    {
                        type: 'bar',
                        label: 'Orders',
                        data: @json($dashboardData['orders_by_day']),
                        backgroundColor: 'rgba(6,182,212,0.7)',
                        borderRadius: 6,
                        yAxisID: 'yOrders',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'top' } },
                scales: {
                    yRevenue: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: { callback: v => 'RWF ' + v.toLocaleString() },
                        grid: { color: 'rgba(148,163,184,0.15)' }
                    },
                    yOrders: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Reservation Status Donut
    const resStatusCtx = document.getElementById('reservationStatusChart');
    if (resStatusCtx) {
        const resStatusCounts = @json($dashboardData['reservation_status_counts']);
        new Chart(resStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Cancelled', 'Completed'],
                datasets: [{
                    data: [
                        resStatusCounts.pending,
                        resStatusCounts.confirmed,
                        resStatusCounts.cancelled,
                        resStatusCounts.completed
                    ],
                    backgroundColor: ['#f59e0b','#10b981','#ef4444','#4f46e5'],
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'bottom' } }
            }
        });
    }

    // Top Menu Items horizontal bar
    const topMenuCtx = document.getElementById('topMenuItemsChart');
    if (topMenuCtx) {
        @php
            $topItemsNames = $dashboardData['top_menu_items']->pluck('name')->toArray();
            $topItemsOrders = $dashboardData['top_menu_items']->pluck('total_orders')->toArray();
        @endphp
        new Chart(topMenuCtx, {
            type: 'bar',
            data: {
                labels: @json($topItemsNames),
                datasets: [{
                    label: 'Orders',
                    data: @json($topItemsOrders),
                    backgroundColor: ['#4f46e5','#06b6d4','#10b981','#f59e0b'],
                    borderRadius: 5,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    @if(auth()->user()->role === 'admin' && $dashboardData['restaurant_stats']->count())
    // Restaurant Revenue Chart
    const rstRevCtx = document.getElementById('restaurantRevenueChart');
    if (rstRevCtx) {
        @php
            $rstNames    = $dashboardData['restaurant_stats']->pluck('name')->toArray();
            $rstRevenues = $dashboardData['restaurant_stats']->pluck('revenue')->toArray();
            $rstOrders   = $dashboardData['restaurant_stats']->pluck('orders')->toArray();
        @endphp
        new Chart(rstRevCtx, {
            type: 'bar',
            data: {
                labels: @json($rstNames),
                datasets: [{
                    label: 'Revenue (RWF)',
                    data: @json($rstRevenues),
                    backgroundColor: ['#4f46e5','#06b6d4','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1'],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { maxRotation: 30, font: { size: 11 } } },
                    y: { beginAtZero: true, ticks: { callback: v => 'RWF ' + v.toLocaleString() } }
                }
            }
        });
    }
    // Restaurant Orders Chart
    const rstOrdCtx = document.getElementById('restaurantOrdersChart');
    if (rstOrdCtx) {
        new Chart(rstOrdCtx, {
            type: 'bar',
            data: {
                labels: @json($rstNames),
                datasets: [{
                    label: 'Orders',
                    data: @json($rstOrders),
                    backgroundColor: 'rgba(6,182,212,0.8)',
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }
    @endif

// ── Realtime (Echo/Pusher): only needs to work when Echo is available ─────────
function waitForEcho(callback) {
    if (typeof window.Echo === 'function' || typeof window.Echo === 'object') {
        callback();
    } else {
        setTimeout(() => waitForEcho(callback), 50);
    }
}

function updateOrderActivityChart() {
    fetch(`/dashboard/chart-data?range=${encodeURIComponent(dashboardRange)}`)
        .then(r => r.json())
        .then(data => {
            if (activityChart) {
                activityChart.data.labels = data.activity_labels;
                activityChart.data.datasets[0].data = data.order_activity_data;
                activityChart.data.datasets[1].data = data.reservation_activity_data;
                activityChart.update();
            }
            if (window.activityOverviewChart) {
                window.activityOverviewChart.data.labels = data.activity_labels;
                window.activityOverviewChart.data.datasets[0].data = data.order_activity_data;
                window.activityOverviewChart.data.datasets[1].data = data.reservation_activity_data;
                window.activityOverviewChart.update();
            }
        });
}

waitForEcho(function() {
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
                    Amount: RWF ${Math.round(parseFloat(order.total_amount)).toLocaleString()}
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
        var button = document.getElementById('toggle-' + data.service_type);
        if (button) {
            applyServiceBtnState(button, data.status == true || data.status == 1);
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
                <td>RWF ${Math.round(parseFloat(menuItem.total_revenue || 0)).toLocaleString()}</td>
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

    // Listen for promo banner updates
    if (window.Echo && typeof window.Echo.channel === 'function') {
        window.Echo.channel('restaurant.{{ session('userData')['users']->restaurant_id }}')
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
    } else {
        console.error('Echo is not initialized or channel is not a function');
    }

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

// ── Service toggle buttons — no Echo dependency ──────────────────────────────
function applyServiceBtnState(btn, isOpen) {
    btn.style.background = isOpen
        ? 'linear-gradient(135deg,#10b981,#059669)'
        : 'linear-gradient(135deg,#ef4444,#dc2626)';
    btn.style.color = '#fff';
    btn.setAttribute('data-state', isOpen ? '1' : '0');
    var badge = btn.querySelector('.badge');
    if (badge) badge.textContent = isOpen ? 'OPEN' : 'CLOSED';
}

function doToggleService(type, btn) {
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var confirmBtn = document.getElementById('serviceToggleConfirmBtn');
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

    fetch('/dashboard/toggle-service', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ type: type })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        bootstrap.Modal.getInstance(document.getElementById('serviceToggleModal')).hide();
        if (data.status === 'success') {
            applyServiceBtnState(btn, !!data.value);
        } else {
            alert(data.message || 'Something went wrong.');
        }
    })
    .catch(function() {
        bootstrap.Modal.getInstance(document.getElementById('serviceToggleModal')).hide();
        alert('Request failed. Please try again.');
    })
    .finally(function() {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Confirm';
    });
}

function showServiceToggleModal(type, btn) {
    var isOpen = btn.getAttribute('data-state') === '1';
    var willOpen = !isOpen;
    var label = type === 'reservations' ? 'Reservations' : 'Delivery';
    var icon  = type === 'reservations' ? 'fa-calendar-check' : 'fa-motorcycle';

    var modal      = document.getElementById('serviceToggleModal');
    var header     = document.getElementById('serviceToggleModalHeader');
    var iconWrap   = document.getElementById('serviceToggleModalIcon');
    var iconEl     = document.getElementById('serviceToggleModalIconInner');
    var bodyEl     = document.getElementById('serviceToggleModalBody');
    var noteEl     = document.getElementById('serviceToggleModalNote');
    var confirmBtn = document.getElementById('serviceToggleConfirmBtn');

    if (willOpen) {
        header.style.background     = '#f0fdf4';
        iconWrap.style.background   = '#dcfce7';
        iconEl.className            = 'fas ' + icon + ' fa-lg';
        iconEl.style.color          = '#16a34a';
        bodyEl.innerHTML            = '<strong>' + label + '</strong> will be set to <span class="text-success fw-bold">OPEN</span>. Customers will be able to place ' + (type === 'reservations' ? 'reservations' : 'delivery orders') + '.';
        noteEl.style.display        = 'block';
        noteEl.style.background     = '#f0fdf4';
        noteEl.style.color          = '#166534';
        noteEl.innerHTML            = '<i class="fas fa-check-circle me-1"></i> This will make ' + label.toLowerCase() + ' available to customers immediately.';
        confirmBtn.className        = 'btn rounded-pill px-4 fw-semibold btn-success';
        confirmBtn.textContent      = 'Yes, Open';
    } else {
        header.style.background     = '#fef2f2';
        iconWrap.style.background   = '#fee2e2';
        iconEl.className            = 'fas ' + icon + ' fa-lg';
        iconEl.style.color          = '#dc2626';
        bodyEl.innerHTML            = '<strong>' + label + '</strong> will be set to <span class="text-danger fw-bold">CLOSED</span>. Customers will <u>not</u> be able to place ' + (type === 'reservations' ? 'reservations' : 'delivery orders') + '.';
        noteEl.style.display        = 'block';
        noteEl.style.background     = '#fef2f2';
        noteEl.style.color          = '#991b1b';
        noteEl.innerHTML            = '<i class="fas fa-exclamation-circle me-1"></i> Existing ' + (type === 'reservations' ? 'reservations' : 'orders') + ' will not be affected.';
        confirmBtn.className        = 'btn rounded-pill px-4 fw-semibold btn-danger';
        confirmBtn.textContent      = 'Yes, Close';
    }

    // Wire confirm button
    confirmBtn.onclick = function() { doToggleService(type, btn); };

    new bootstrap.Modal(modal).show();
}

document.addEventListener('DOMContentLoaded', function() {
    var resBtn = document.getElementById('toggle-reservations');
    var delBtn = document.getElementById('toggle-delivery');
    if (resBtn) resBtn.addEventListener('click', function() { showServiceToggleModal('reservations', this); });
    if (delBtn) delBtn.addEventListener('click', function() { showServiceToggleModal('delivery', this); });
});
</script>
@endsection
