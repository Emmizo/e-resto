@extends('layouts.app')
@section('content')
<main class="content-wrapper">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 font-dmsans">Reports</h1>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group" id="rangeButtons" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary range-btn active" data-range="month">This Month</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary range-btn" data-range="today">Today</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary range-btn" data-range="week">This Week</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary range-btn" data-range="year">This Year</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary range-btn" data-range="custom">Custom</button>
                </div>
            </div>
        </div>

        <!-- Custom date range picker -->
        <div id="customDatePicker" class="row mb-3 d-none">
            <div class="col-auto">
                <label class="form-label fw-semibold small">From</label>
                <input type="text" id="fromDate" class="form-control form-control-sm" placeholder="dd/mm/yyyy">
            </div>
            <div class="col-auto">
                <label class="form-label fw-semibold small">To</label>
                <input type="text" id="toDate" class="form-control form-control-sm" placeholder="dd/mm/yyyy" readonly>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button class="btn btn-sm btn-primary" id="applyCustomDate">Apply</button>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="row mb-4" id="kpiCards">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:#ede9fe;">
                            <i class="fas fa-money-bill-wave fa-lg" style="color:#4f46e5;"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Revenue</div>
                            <div class="fs-5 fw-bold" id="kpiRevenue"><span class="spinner-border spinner-border-sm"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:#cffafe;">
                            <i class="fas fa-receipt fa-lg" style="color:#06b6d4;"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Orders</div>
                            <div class="fs-5 fw-bold" id="kpiOrders"><span class="spinner-border spinner-border-sm"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:#d1fae5;">
                            <i class="fas fa-calendar-check fa-lg" style="color:#10b981;"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Reservations</div>
                            <div class="fs-5 fw-bold" id="kpiReservations"><span class="spinner-border spinner-border-sm"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:#fef3c7;">
                            <i class="fas fa-chart-line fa-lg" style="color:#f59e0b;"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Avg Order Value</div>
                            <div class="fs-5 fw-bold" id="kpiAvgOrder"><span class="spinner-border spinner-border-sm"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active font-dmsans" id="orders-tab" data-bs-toggle="tab" data-bs-target="#ordersTab" type="button">
                    <i class="fas fa-receipt me-1"></i> Orders
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link font-dmsans" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservationsTab" type="button">
                    <i class="fas fa-calendar-check me-1"></i> Reservations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link font-dmsans" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menuTab" type="button">
                    <i class="fas fa-utensils me-1"></i> Menu Performance
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link font-dmsans" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customersTab" type="button">
                    <i class="fas fa-users me-1"></i> Customers
                </button>
            </li>
            @if(auth()->user()->role === 'admin')
            <li class="nav-item" role="presentation">
                <button class="nav-link font-dmsans" id="restaurants-tab" data-bs-toggle="tab" data-bs-target="#restaurantsTab" type="button">
                    <i class="fas fa-store me-1"></i> Restaurants
                </button>
            </li>
            @endif
        </ul>

        <div class="tab-content" id="reportTabContent">
            <!-- Orders Tab -->
            <div class="tab-pane fade show active" id="ordersTab" role="tabpanel">
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-type="orders">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-danger pdf-btn" data-tab="ordersTab">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Revenue Over Time</h6>
                            </div>
                            <div class="card-body">
                                <div id="revenueChartLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:280px;display:none;" id="revenueChartWrap">
                                    <canvas id="revenueLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Order Status</h6>
                            </div>
                            <div class="card-body">
                                <div id="statusDonutLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="statusDonutWrap">
                                    <canvas id="orderStatusDonut"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Order Type</h6>
                            </div>
                            <div class="card-body">
                                <div id="typeBarLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="typeBarWrap">
                                    <canvas id="orderTypeBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Payment Status Revenue</h6>
                            </div>
                            <div class="card-body">
                                <div id="paymentBarLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="paymentBarWrap">
                                    <canvas id="paymentStatusBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Top 5 Menu Items</h6>
                            </div>
                            <div class="card-body">
                                <div id="topItemsLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div class="table-responsive d-none" id="topItemsTable">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr><th>#</th><th>Item</th><th>Qty Sold</th><th>Revenue</th></tr>
                                        </thead>
                                        <tbody id="topItemsBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations Tab -->
            <div class="tab-pane fade" id="reservationsTab" role="tabpanel">
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-type="reservations">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-danger pdf-btn" data-tab="reservationsTab">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Reservations Over Time</h6>
                            </div>
                            <div class="card-body">
                                <div id="resLineLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:280px;display:none;" id="resLineWrap">
                                    <canvas id="resLineChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Status Breakdown</h6>
                            </div>
                            <div class="card-body">
                                <div id="resStatusLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="resStatusWrap">
                                    <canvas id="resStatusDonut"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Party Size Distribution</h6>
                            </div>
                            <div class="card-body">
                                <div id="partySizeLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="partySizeWrap">
                                    <canvas id="partySizeBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Peak Hours</h6>
                            </div>
                            <div class="card-body">
                                <div id="peakHoursLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:240px;display:none;" id="peakHoursWrap">
                                    <canvas id="peakHoursBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Performance Tab -->
            <div class="tab-pane fade" id="menuTab" role="tabpanel">
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-type="menu">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-danger pdf-btn" data-tab="menuTab">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Top 10 Items by Quantity Sold</h6>
                            </div>
                            <div class="card-body">
                                <div id="top10Loader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:320px;display:none;" id="top10Wrap">
                                    <canvas id="top10HorizBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Revenue by Category</h6>
                            </div>
                            <div class="card-body">
                                <div id="catPieLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:320px;display:none;" id="catPieWrap">
                                    <canvas id="categoryPie"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Menu Items Detail</h6>
                            </div>
                            <div class="card-body">
                                <div id="menuTableLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div class="table-responsive d-none" id="menuTableWrap">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr><th>Item</th><th>Category</th><th>Qty Sold</th><th>Revenue</th><th>Avg Price</th></tr>
                                        </thead>
                                        <tbody id="menuTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Tab -->
            <div class="tab-pane fade" id="customersTab" role="tabpanel">
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-type="customers">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-danger pdf-btn" data-tab="customersTab">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">New Customer Registrations</h6>
                            </div>
                            <div class="card-body">
                                <div id="newCustLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:280px;display:none;" id="newCustWrap">
                                    <canvas id="newCustLine"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Repeat vs New Customers</h6>
                            </div>
                            <div class="card-body">
                                <div id="repeatDonutLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:280px;display:none;" id="repeatDonutWrap">
                                    <canvas id="repeatDonut"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <!-- Restaurants Tab -->
            <div class="tab-pane fade" id="restaurantsTab" role="tabpanel">
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-type="restaurants">
                        <i class="fas fa-file-csv me-1"></i> Export CSV
                    </button>
                    <button class="btn btn-sm btn-outline-danger pdf-btn" data-tab="restaurantsTab">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </button>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Revenue by Restaurant</h6>
                            </div>
                            <div class="card-body">
                                <div id="rstRevLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:320px;display:none;" id="rstRevWrap">
                                    <canvas id="rstRevenueBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Orders by Restaurant</h6>
                            </div>
                            <div class="card-body">
                                <div id="rstOrdLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div style="position:relative;height:320px;display:none;" id="rstOrdWrap">
                                    <canvas id="rstOrdersBar"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold">Restaurant Detail</h6>
                            </div>
                            <div class="card-body">
                                <div id="rstTableLoader" class="text-center py-4"><span class="spinner-border"></span></div>
                                <div class="table-responsive d-none" id="rstTableWrap">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr><th>#</th><th>Restaurant</th><th>Orders</th><th>Revenue</th><th>Avg Rating</th></tr>
                                        </thead>
                                        <tbody id="rstTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
const COLORS = ['#4f46e5','#06b6d4','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1'];
let currentRange = 'month';
let currentFrom = null;
let currentTo = null;
const chartInstances = {};

flatpickr('#fromDate', {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    allowInput: false,
});
flatpickr('#toDate', {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    allowInput: false,
});

function destroyChart(id) {
    if (chartInstances[id]) {
        chartInstances[id].destroy();
        delete chartInstances[id];
    }
}

function showLoader(ids) {
    ids.forEach(id => {
        const loader = document.getElementById(id + 'Loader');
        const wrap = document.getElementById(id + 'Wrap') || document.getElementById(id + 'Table') || document.getElementById(id + 'TableWrap');
        if (loader) loader.classList.remove('d-none');
        if (wrap) wrap.style.display = 'none';
    });
}

function hideLoader(ids) {
    ids.forEach(id => {
        const loader = document.getElementById(id + 'Loader');
        const wrap = document.getElementById(id + 'Wrap') || document.getElementById(id + 'Table') || document.getElementById(id + 'TableWrap');
        if (loader) loader.classList.add('d-none');
        if (wrap) {
            wrap.style.display = '';
            wrap.classList.remove('d-none');
        }
    });
}

function buildParams() {
    const params = {};
    if (currentRange === 'custom' && currentFrom && currentTo) {
        params.from = currentFrom;
        params.to = currentTo;
        params.range = 'custom';
    } else {
        params.range = currentRange;
    }
    return params;
}

function loadOrdersReport() {
    showLoader(['revenueChart','statusDonut','typeBar','paymentBar','topItems']);
    $.ajax({
        url: '{{ route("reports.orders") }}',
        data: buildParams(),
        success: function(data) {
            hideLoader(['revenueChart','statusDonut','typeBar','paymentBar','topItems']);
            renderRevenueChart(data.revenue_by_day);
            renderStatusDonut(data.status_counts);
            renderTypeBar(data.type_counts);
            renderPaymentBar(data.payment_status);
            renderTopItemsTable(data.top_items);
            updateKpis(data.total_revenue, data.total_orders, null, data.avg_order_value);
        },
        error: function() {
            toastr.error('Failed to load orders report.');
            hideLoader(['revenueChart','statusDonut','typeBar','paymentBar','topItems']);
        }
    });
}

function loadReservationsReport() {
    showLoader(['resLine','resStatus','partySize','peakHours']);
    $.ajax({
        url: '{{ route("reports.reservations") }}',
        data: buildParams(),
        success: function(data) {
            hideLoader(['resLine','resStatus','partySize','peakHours']);
            renderResLine(data.by_day);
            renderResStatusDonut(data.status_counts);
            renderPartySizeBar(data.party_sizes);
            renderPeakHoursBar(data.peak_hours);
            updateKpis(null, null, data.total, null);
        },
        error: function() {
            toastr.error('Failed to load reservations report.');
            hideLoader(['resLine','resStatus','partySize','peakHours']);
        }
    });
}

function loadMenuReport() {
    showLoader(['top10','catPie','menuTable']);
    $.ajax({
        url: '{{ route("reports.menu") }}',
        data: buildParams(),
        success: function(data) {
            hideLoader(['top10','catPie','menuTable']);
            renderTop10Bar(data.top_items);
            renderCategoryPie(data.category_breakdown);
            renderMenuTable(data.top_items);
        },
        error: function() {
            toastr.error('Failed to load menu report.');
            hideLoader(['top10','catPie','menuTable']);
        }
    });
}

function loadCustomersReport() {
    showLoader(['newCust','repeatDonut']);
    $.ajax({
        url: '{{ route("reports.customers") }}',
        data: buildParams(),
        success: function(data) {
            hideLoader(['newCust','repeatDonut']);
            renderNewCustLine(data.new_customers_by_day);
            renderRepeatDonut(data.repeat_vs_new);
        },
        error: function() {
            toastr.error('Failed to load customers report.');
            hideLoader(['newCust','repeatDonut']);
        }
    });
}

const kpiState = { revenue: null, orders: null, reservations: null, avg: null };

function updateKpis(revenue, orders, reservations, avg) {
    if (revenue !== null) { kpiState.revenue = revenue; $('#kpiRevenue').text('RWF ' + parseFloat(revenue).toLocaleString('en-US', {minimumFractionDigits:0,maximumFractionDigits:0})); }
    if (orders !== null) { kpiState.orders = orders; $('#kpiOrders').text(orders); }
    if (reservations !== null) { kpiState.reservations = reservations; $('#kpiReservations').text(reservations); }
    if (avg !== null) { kpiState.avg = avg; $('#kpiAvgOrder').text('RWF ' + parseFloat(avg).toFixed(0)); }
}

function renderRevenueChart(data) {
    destroyChart('revenueLineChart');
    const wrap = document.getElementById('revenueChartWrap');
    const loader = document.getElementById('revenueChartLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    if (!data || !data.length) {
        if (wrap) wrap.innerHTML = '<p class="text-center text-muted py-4">No revenue data for this period.</p>';
        return;
    }
    // Restore canvas if it was replaced by empty-state message
    if (wrap && !document.getElementById('revenueLineChart')) {
        wrap.innerHTML = '<canvas id="revenueLineChart"></canvas>';
    }
    const labels = data.map(d => moment(d.date).format('MMM D'));
    const revenues = data.map(d => d.revenue);
    const ctx = document.getElementById('revenueLineChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(79,70,229,0.3)');
    gradient.addColorStop(1, 'rgba(79,70,229,0)');
    chartInstances['revenueLineChart'] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'RWF ' + v.toLocaleString() } }
            }
        }
    });
}

function renderStatusDonut(counts) {
    destroyChart('orderStatusDonut');
    const wrap = document.getElementById('statusDonutWrap');
    const loader = document.getElementById('statusDonutLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    const labels = Object.keys(counts).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    const values = Object.values(counts);
    chartInstances['orderStatusDonut'] = new Chart(document.getElementById('orderStatusDonut'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: values, backgroundColor: COLORS }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

function renderTypeBar(counts) {
    destroyChart('orderTypeBar');
    const wrap = document.getElementById('typeBarWrap');
    const loader = document.getElementById('typeBarLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    const labels = ['Dine-in', 'Takeaway', 'Delivery'];
    const values = [counts.dine_in || 0, counts.takeaway || 0, counts.delivery || 0];
    chartInstances['orderTypeBar'] = new Chart(document.getElementById('orderTypeBar'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: ['#4f46e5','#06b6d4','#10b981'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderPaymentBar(data) {
    destroyChart('paymentStatusBar');
    const wrap = document.getElementById('paymentBarWrap');
    const loader = document.getElementById('paymentBarLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    const labels = data.map(d => d.status ? (d.status.charAt(0).toUpperCase() + d.status.slice(1)) : 'Unknown');
    const values = data.map(d => d.revenue);
    chartInstances['paymentStatusBar'] = new Chart(document.getElementById('paymentStatusBar'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: values,
                backgroundColor: ['#10b981','#ef4444','#f59e0b'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'RWF ' + v.toLocaleString() } } }
        }
    });
}

function renderTopItemsTable(items) {
    const tbody = $('#topItemsBody').empty();
    items.forEach((item, i) => {
        tbody.append(`<tr>
            <td>${i+1}</td>
            <td>${item.name}</td>
            <td>${item.qty_sold}</td>
            <td>RWF ${Math.round(parseFloat(item.revenue)).toLocaleString()}</td>
        </tr>`);
    });
    if (!items.length) tbody.append('<tr><td colspan="4" class="text-center text-muted">No data</td></tr>');
    $('#topItemsTable').removeClass('d-none');
}

function renderResLine(data) {
    destroyChart('resLineChart');
    const wrap = document.getElementById('resLineWrap');
    const loader = document.getElementById('resLineLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    if (!data || !data.length) {
        if (wrap) wrap.innerHTML = '<p class="text-center text-muted py-4">No reservation data for this period.</p>';
        return;
    }
    // Restore canvas if it was replaced by empty-state message
    if (wrap && !document.getElementById('resLineChart')) {
        wrap.innerHTML = '<canvas id="resLineChart"></canvas>';
    }
    const labels = data.map(d => moment(d.date).format('MMM D'));
    const counts = data.map(d => d.count);
    const ctx = document.getElementById('resLineChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(16,185,129,0.3)');
    gradient.addColorStop(1, 'rgba(16,185,129,0)');
    chartInstances['resLineChart'] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Reservations',
                data: counts,
                borderColor: '#10b981',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderResStatusDonut(counts) {
    destroyChart('resStatusDonut');
    const wrap = document.getElementById('resStatusWrap');
    const loader = document.getElementById('resStatusLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    const labels = Object.keys(counts).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    const values = Object.values(counts);
    chartInstances['resStatusDonut'] = new Chart(document.getElementById('resStatusDonut'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: values, backgroundColor: COLORS }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

function renderPartySizeBar(sizes) {
    destroyChart('partySizeBar');
    const wrap = document.getElementById('partySizeWrap');
    const loader = document.getElementById('partySizeLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['partySizeBar'] = new Chart(document.getElementById('partySizeBar'), {
        type: 'bar',
        data: {
            labels: ['1-2 guests', '3-5 guests', '6+ guests'],
            datasets: [{
                data: [sizes['1-2'], sizes['3-5'], sizes['6+']],
                backgroundColor: ['#4f46e5','#06b6d4','#10b981'],
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderPeakHoursBar(hours) {
    destroyChart('peakHoursBar');
    const wrap = document.getElementById('peakHoursWrap');
    const loader = document.getElementById('peakHoursLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    const labels = Array.from({length: 24}, (_, i) => i + ':00');
    chartInstances['peakHoursBar'] = new Chart(document.getElementById('peakHoursBar'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: hours,
                backgroundColor: '#8b5cf6',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderTop10Bar(items) {
    destroyChart('top10HorizBar');
    const wrap = document.getElementById('top10Wrap');
    const loader = document.getElementById('top10Loader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['top10HorizBar'] = new Chart(document.getElementById('top10HorizBar'), {
        type: 'bar',
        data: {
            labels: items.map(i => i.name),
            datasets: [{
                label: 'Qty Sold',
                data: items.map(i => i.qty_sold),
                backgroundColor: COLORS,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}

function renderCategoryPie(data) {
    destroyChart('categoryPie');
    const wrap = document.getElementById('catPieWrap');
    const loader = document.getElementById('catPieLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['categoryPie'] = new Chart(document.getElementById('categoryPie'), {
        type: 'pie',
        data: {
            labels: data.map(d => d.category),
            datasets: [{
                data: data.map(d => d.revenue),
                backgroundColor: COLORS,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

function renderMenuTable(items) {
    const tbody = $('#menuTableBody').empty();
    items.forEach(item => {
        tbody.append(`<tr>
            <td>${item.name}</td>
            <td>${item.category || '-'}</td>
            <td>${item.qty_sold}</td>
            <td>RWF ${Math.round(parseFloat(item.revenue)).toLocaleString()}</td>
            <td>RWF ${Math.round(parseFloat(item.avg_price)).toLocaleString()}</td>
        </tr>`);
    });
    if (!items.length) tbody.append('<tr><td colspan="5" class="text-center text-muted">No data</td></tr>');
}

function renderNewCustLine(data) {
    destroyChart('newCustLine');
    const wrap = document.getElementById('newCustWrap');
    const loader = document.getElementById('newCustLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    if (!data || !data.length) {
        if (wrap) wrap.innerHTML = '<p class="text-center text-muted py-4">No customer registration data for this period.</p>';
        return;
    }
    // Restore canvas if it was replaced by empty-state message
    if (wrap && !document.getElementById('newCustLine')) {
        wrap.innerHTML = '<canvas id="newCustLine"></canvas>';
    }
    const labels = data.map(d => moment(d.date).format('MMM D'));
    const counts = data.map(d => d.count);
    const ctx = document.getElementById('newCustLine').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(6,182,212,0.3)');
    gradient.addColorStop(1, 'rgba(6,182,212,0)');
    chartInstances['newCustLine'] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'New Customers',
                data: counts,
                borderColor: '#06b6d4',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

function renderRepeatDonut(data) {
    destroyChart('repeatDonut');
    const wrap = document.getElementById('repeatDonutWrap');
    const loader = document.getElementById('repeatDonutLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['repeatDonut'] = new Chart(document.getElementById('repeatDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Repeat', 'New'],
            datasets: [{
                data: [data.repeat, data.new],
                backgroundColor: ['#4f46e5', '#10b981'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

@if(auth()->user()->role === 'admin')
function loadRestaurantsReport() {
    showLoader(['rstRev', 'rstOrd', 'rstTable']);
    $.ajax({
        url: '{{ route("reports.restaurants") }}',
        data: buildParams(),
        success: function(data) {
            hideLoader(['rstRev', 'rstOrd', 'rstTable']);
            renderRestaurantRevenueBar(data.stats);
            renderRestaurantOrdersBar(data.stats);
            renderRestaurantTable(data.stats);
        },
        error: function() {
            toastr.error('Failed to load restaurants report.');
            hideLoader(['rstRev', 'rstOrd', 'rstTable']);
        }
    });
}

function renderRestaurantRevenueBar(stats) {
    destroyChart('rstRevenueBar');
    const wrap = document.getElementById('rstRevWrap');
    const loader = document.getElementById('rstRevLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['rstRevenueBar'] = new Chart(document.getElementById('rstRevenueBar'), {
        type: 'bar',
        data: {
            labels: stats.map(r => r.name),
            datasets: [{
                label: 'Revenue (RWF)',
                data: stats.map(r => r.revenue),
                backgroundColor: COLORS,
                borderRadius: 5,
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

function renderRestaurantOrdersBar(stats) {
    destroyChart('rstOrdersBar');
    const wrap = document.getElementById('rstOrdWrap');
    const loader = document.getElementById('rstOrdLoader');
    if (wrap) { wrap.style.display = ''; wrap.classList.remove('d-none'); }
    if (loader) loader.classList.add('d-none');
    chartInstances['rstOrdersBar'] = new Chart(document.getElementById('rstOrdersBar'), {
        type: 'bar',
        data: {
            labels: stats.map(r => r.name),
            datasets: [{
                label: 'Orders',
                data: stats.map(r => r.orders),
                backgroundColor: 'rgba(6,182,212,0.8)',
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

function renderRestaurantTable(stats) {
    const tbody = $('#rstTableBody').empty();
    stats.forEach((r, i) => {
        tbody.append(`<tr>
            <td>${i + 1}</td>
            <td>${r.name}</td>
            <td>${r.orders}</td>
            <td>RWF ${Math.round(r.revenue).toLocaleString()}</td>
            <td>${r.avg_rating > 0 ? r.avg_rating + ' <i class="fas fa-star text-warning"></i>' : '—'}</td>
        </tr>`);
    });
    if (!stats.length) tbody.append('<tr><td colspan="5" class="text-center text-muted">No data</td></tr>');
}
@endif

function reloadActive() {
    const active = $('#reportTabs .nav-link.active').attr('id');
    if (active === 'orders-tab') loadOrdersReport();
    else if (active === 'reservations-tab') loadReservationsReport();
    else if (active === 'menu-tab') loadMenuReport();
    else if (active === 'customers-tab') loadCustomersReport();
    @if(auth()->user()->role === 'admin')
    else if (active === 'restaurants-tab') loadRestaurantsReport();
    @endif
}

// Date range buttons
$('.range-btn').on('click', function() {
    $('.range-btn').removeClass('active');
    $(this).addClass('active');
    currentRange = $(this).data('range');
    if (currentRange === 'custom') {
        $('#customDatePicker').removeClass('d-none');
        return;
    }
    $('#customDatePicker').addClass('d-none');
    reloadActive();
});

$('#applyCustomDate').on('click', function() {
    currentFrom = $('#fromDate').val();
    currentTo = $('#toDate').val();
    if (!currentFrom || !currentTo) { toastr.warning('Please select both From and To dates.'); return; }
    reloadActive();
});

// Tab switch
$('#reportTabs button').on('shown.bs.tab', function(e) {
    const id = $(e.target).attr('id');
    if (id === 'orders-tab') loadOrdersReport();
    else if (id === 'reservations-tab') loadReservationsReport();
    else if (id === 'menu-tab') loadMenuReport();
    else if (id === 'customers-tab') loadCustomersReport();
    @if(auth()->user()->role === 'admin')
    else if (id === 'restaurants-tab') loadRestaurantsReport();
    @endif
});

// CSV Export buttons
$('.export-btn').on('click', function() {
    const type = $(this).data('type');
    const params = $.param(Object.assign({type: type}, buildParams()));
    window.location.href = '{{ route("reports.export") }}?' + params;
});

// PDF Export buttons
$(document).on('click', '.pdf-btn', function() {
    const tabId = $(this).data('tab');
    const btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Generating...');

    // Map tab ID to report type
    const tabTypeMap = {
        ordersTab: 'orders',
        reservationsTab: 'reservations',
        menuTab: 'menu',
        customersTab: 'customers',
        restaurantsTab: 'restaurants',
    };
    const type = tabTypeMap[tabId] || 'orders';

    // Map tab type to AJAX endpoint
    const endpointMap = {
        orders: '{{ route("reports.orders") }}',
        reservations: '{{ route("reports.reservations") }}',
        menu: '{{ route("reports.menu") }}',
        customers: '{{ route("reports.customers") }}',
        @if(auth()->user()->role === 'admin')
        restaurants: '{{ route("reports.restaurants") }}',
        @endif
    };
    const url = endpointMap[type];
    if (!url) {
        toastr.error('Export not available for this tab.');
        btn.prop('disabled', false).html('<i class="fas fa-file-pdf me-1"></i> Export PDF');
        return;
    }

    $.ajax({
        url: url,
        data: buildParams(),
        success: function(data) {
            try {
                exportPdf(type, data, btn);
            } catch(e) {
                toastr.error('Failed to generate PDF: ' + e.message);
                btn.prop('disabled', false).html('<i class="fas fa-file-pdf me-1"></i> Export PDF');
            }
        },
        error: function() {
            toastr.error('Failed to fetch report data for PDF.');
            btn.prop('disabled', false).html('<i class="fas fa-file-pdf me-1"></i> Export PDF');
        }
    });
});

function exportPdf(type, data, btn) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const pageW = doc.internal.pageSize.getWidth(); // 297mm

    // Determine report title and restaurant label
    const typeLabels = {
        orders: 'Orders Report',
        reservations: 'Reservations Report',
        menu: 'Menu Performance Report',
        customers: 'Customers Report',
        restaurants: 'Restaurants Report',
    };
    const reportTitle = typeLabels[type] || 'Report';

    // Build period label
    let periodLabel = '';
    if (currentRange === 'custom' && currentFrom && currentTo) {
        periodLabel = currentFrom + ' to ' + currentTo;
    } else {
        const rangeLabels = { today: 'Today', week: 'This Week', month: 'This Month', year: 'This Year' };
        periodLabel = rangeLabels[currentRange] || currentRange;
    }

    const restaurantName = '{{ session("userData")["users"]->restaurant_name ?? "" }}' || 'E-Resto';

    // Header bar
    doc.setFillColor(15, 48, 57);
    doc.rect(0, 0, pageW, 28, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text(restaurantName.toUpperCase() + ' — ' + reportTitle.toUpperCase(), 14, 12);
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text('Period: ' + periodLabel + '  |  Generated: ' + new Date().toLocaleDateString(), 14, 21);

    let startY = 34;

    if (type === 'orders') {
        // KPI summary row
        const kpiData = [
            ['Total Revenue', 'RWF ' + parseFloat(data.total_revenue || 0).toLocaleString('en-US', {minimumFractionDigits: 0})],
            ['Total Orders', String(data.total_orders || 0)],
            ['Avg Order Value', 'RWF ' + parseFloat(data.avg_order_value || 0).toFixed(0)],
        ];
        doc.autoTable({
            startY: startY,
            head: [['Metric', 'Value']],
            body: kpiData,
            theme: 'grid',
            headStyles: { fillColor: [15, 48, 57], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 10 },
            columnStyles: { 0: { fontStyle: 'bold', cellWidth: 60 }, 1: { cellWidth: 60 } },
            margin: { left: 14 },
            tableWidth: 120,
        });
        startY = doc.lastAutoTable.finalY + 8;

        // Top items table
        if (data.top_items && data.top_items.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Top Menu Items', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['#', 'Item', 'Qty Sold', 'Revenue (RWF)']],
                body: data.top_items.map((item, i) => [
                    i + 1,
                    item.name,
                    item.qty_sold,
                    Math.round(parseFloat(item.revenue)).toLocaleString(),
                ]),
                theme: 'striped',
                headStyles: { fillColor: [79, 70, 229], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
            });
            startY = doc.lastAutoTable.finalY + 8;
        }

        // Revenue by day table
        if (data.revenue_by_day && data.revenue_by_day.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Revenue by Day', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['Date', 'Orders', 'Revenue (RWF)']],
                body: data.revenue_by_day.map(d => [
                    d.date,
                    d.count,
                    Math.round(parseFloat(d.revenue)).toLocaleString(),
                ]),
                theme: 'striped',
                headStyles: { fillColor: [79, 70, 229], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
            });
        }

    } else if (type === 'reservations') {
        // KPI
        const kpiData = [
            ['Total Reservations', String(data.total || 0)],
        ];
        doc.autoTable({
            startY: startY,
            head: [['Metric', 'Value']],
            body: kpiData,
            theme: 'grid',
            headStyles: { fillColor: [15, 48, 57], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 10 },
            columnStyles: { 0: { fontStyle: 'bold', cellWidth: 60 }, 1: { cellWidth: 60 } },
            margin: { left: 14 },
            tableWidth: 120,
        });
        startY = doc.lastAutoTable.finalY + 8;

        // By day table
        if (data.by_day && data.by_day.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Reservations by Day', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['Date', 'Count']],
                body: data.by_day.map(d => [d.date, d.count]),
                theme: 'striped',
                headStyles: { fillColor: [16, 185, 129], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
            });
            startY = doc.lastAutoTable.finalY + 8;
        }

        // Party sizes
        if (data.party_sizes) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Party Size Distribution', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['Party Size', 'Count']],
                body: [
                    ['1-2 guests', data.party_sizes['1-2'] || 0],
                    ['3-5 guests', data.party_sizes['3-5'] || 0],
                    ['6+ guests', data.party_sizes['6+'] || 0],
                ],
                theme: 'striped',
                headStyles: { fillColor: [16, 185, 129], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
                tableWidth: 100,
            });
        }

    } else if (type === 'menu') {
        if (data.top_items && data.top_items.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Top Menu Items', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['#', 'Item', 'Category', 'Qty Sold', 'Revenue (RWF)', 'Avg Price (RWF)']],
                body: data.top_items.map((item, i) => [
                    i + 1,
                    item.name,
                    item.category || '-',
                    item.qty_sold,
                    Math.round(parseFloat(item.revenue)).toLocaleString(),
                    Math.round(parseFloat(item.avg_price)).toLocaleString(),
                ]),
                theme: 'striped',
                headStyles: { fillColor: [245, 158, 11], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
            });
            startY = doc.lastAutoTable.finalY + 8;
        }

        if (data.category_breakdown && data.category_breakdown.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('Revenue by Category', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['Category', 'Revenue (RWF)']],
                body: data.category_breakdown.map(d => [
                    d.category,
                    Math.round(parseFloat(d.revenue)).toLocaleString(),
                ]),
                theme: 'striped',
                headStyles: { fillColor: [245, 158, 11], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
                tableWidth: 120,
            });
        }

    } else if (type === 'customers') {
        const totalNew = data.total_new_registrations || 0;
        const repeat = (data.repeat_vs_new || {}).repeat || 0;
        const newOrd = (data.repeat_vs_new || {}).new || 0;
        doc.autoTable({
            startY: startY,
            head: [['Metric', 'Value']],
            body: [
                ['Total New Registrations', String(totalNew)],
                ['Repeat Orderers', String(repeat)],
                ['New Orderers', String(newOrd)],
            ],
            theme: 'grid',
            headStyles: { fillColor: [15, 48, 57], textColor: 255, fontStyle: 'bold' },
            styles: { fontSize: 10 },
            columnStyles: { 0: { fontStyle: 'bold', cellWidth: 70 }, 1: { cellWidth: 60 } },
            margin: { left: 14 },
            tableWidth: 130,
        });
        startY = doc.lastAutoTable.finalY + 8;

        if (data.new_customers_by_day && data.new_customers_by_day.length) {
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(30, 30, 30);
            doc.text('New Registrations by Day', 14, startY);
            startY += 4;
            doc.autoTable({
                startY: startY,
                head: [['Date', 'New Registrations']],
                body: data.new_customers_by_day.map(d => [d.date, d.count]),
                theme: 'striped',
                headStyles: { fillColor: [6, 182, 212], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
                tableWidth: 120,
            });
        }

    } else if (type === 'restaurants') {
        if (data.stats && data.stats.length) {
            doc.autoTable({
                startY: startY,
                head: [['#', 'Restaurant', 'Orders', 'Revenue (RWF)', 'Avg Rating']],
                body: data.stats.map((r, i) => [
                    i + 1,
                    r.name,
                    r.orders,
                    Math.round(r.revenue).toLocaleString(),
                    r.avg_rating > 0 ? r.avg_rating.toFixed(1) : '—',
                ]),
                theme: 'striped',
                headStyles: { fillColor: [15, 48, 57], textColor: 255, fontStyle: 'bold' },
                styles: { fontSize: 10 },
                margin: { left: 14 },
            });
        }
    }

    doc.save(type + '-report-' + new Date().toISOString().slice(0, 10) + '.pdf');
    if (btn) btn.prop('disabled', false).html('<i class="fas fa-file-pdf me-1"></i> Export PDF');
}

// Load initial data
loadOrdersReport();
$.ajax({
    url: '{{ route("reports.reservations") }}',
    data: buildParams(),
    success: function(data) { updateKpis(null, null, data.total, null); }
});
</script>
@endsection
