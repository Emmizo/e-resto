@extends('layouts.app')

@section('style')
<style>
.btn-action { width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; }
#printArea { display: none; }
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { display: block; position: fixed; top: 0; left: 0; width: 100%; padding: 24px; font-family: Arial, sans-serif; color: #1e293b; background: #fff; }
    #printArea h2 { margin: 0 0 4px; font-size: 18px; }
    #printArea .print-meta { font-size: 12px; color: #64748b; margin-bottom: 16px; }
    #printArea table { width: 100%; border-collapse: collapse; font-size: 13px; }
    #printArea th { background: #0f3039 !important; color: #fff !important; padding: 8px 10px; text-align: left; font-weight: 600; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    #printArea td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
    #printArea tr:nth-child(even) td { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    #printArea .status-badge { padding: 2px 10px; border-radius: 12px; font-size: 11px; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endsection

@section('content')
<!-- Main Content -->
<main class="content-wrapper">
    <div class="main-content manage-users">

        <!-- Breadcrumb -->
        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="{{route('dashboard')}}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Home">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{route('orders.index')}}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Orders">Manage Orders</a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-inner" data-restaurant-id="{{ session('userData')['users']->restaurant_id ?? '' }}">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Manage Orders</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Orders" aria-controls="manageOrdersTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0 d-flex gap-2">
                        <button id="printOrdersBtn" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1" title="Print Orders">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                        <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 filter-btn" title="Filter">Filter</a>
                    </div>
                </div>
                <div class="table-block">
                    <table id="manageOrdersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                @if($isAdmin)<th>Restaurant</th>@endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="printArea"></div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="updateStatusLabel">Update Order Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" action="javascript:void(0);" method="POST"><!-- No action attribute: handled by JS only -->
                    @csrf
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="form-group mb-3">
                        <label for="status" class="form-label font-dmsans fw-medium text-primary-v1">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Order Modal -->
<div class="modal fade" id="deleteOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteOrderLabel">Delete Order</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this order?</p>
                            <div class="footer-btns">
                                <button type="button" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3" id="confirmDelete">Yes</button>
                                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
$(document).ready(function() {
    console.log('Document ready');
    if ($.fn.DataTable.isDataTable('#manageOrdersTable')) {
        $('#manageOrdersTable').DataTable().destroy();
    }
    // Initialize DataTable with the existing table data
    var table = $('#manageOrdersTable').DataTable({
        responsive: true,
        order: [[3, 'asc'], [4, 'desc']],
        language: {
            emptyTable: "No orders found"
        },
        dom: 'lrtip',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        processing: false,
        serverSide: false,
        data: {!! json_encode($orders) !!},
        columns: [
            {
                data: null,
                name: 'id',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: null,
                name: 'customer',
                render: function(data, type, row) {
                    return (row.first_name || '') + ' ' + (row.last_name || '');
                }
            },
            {
                data: 'total_amount',
                name: 'total_amount',
                render: function(data) {
                    return 'RWF ' + Math.round(parseFloat(data || 0)).toLocaleString();
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    // Return numeric sort key for ordering
                    if (type === 'sort' || type === 'type') {
                        const order = { pending: 0, processing: 1, cancelled: 2, completed: 3 };
                        return order[data] !== undefined ? order[data] : 9;
                    }
                    let badgeClass = '';
                    let textClass = '';
                    switch (data) {
                        case 'completed':
                            badgeClass = 'bg-success';
                            break;
                        case 'cancelled':
                            badgeClass = 'bg-danger';
                            break;
                        case 'processing':
                            badgeClass = 'bg-info';
                            break;
                        case 'pending':
                            badgeClass = 'bg-warning';
                            textClass = 'text-dark';
                            break;
                        default:
                            badgeClass = 'bg-secondary';
                    }
                    return `<span class="badge rounded-pill ${badgeClass} ${textClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    if (!data) return '—';
                    const d = new Date(data);
                    const pad = n => String(n).padStart(2, '0');
                    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
                }
            },
            @if($isAdmin)
            {
                data: 'restaurant_name',
                name: 'restaurant_name',
                render: function(data) {
                    return data ? `<span class="badge rounded-pill" style="background:#e0f2fe;color:#0369a1;font-weight:500;">${data}</span>` : '—';
                }
            },
            @endif
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    @if($isAdmin)
                    return `<a href="/orders/${row.id}" class="btn btn-sm btn-info btn-action" title="View"><i class="fas fa-eye"></i></a>`;
                    @else
                    const isCompleted = row.status === 'completed';
                    const viewBtn = `<a href="/orders/${row.id}" class="btn btn-sm btn-info btn-action" title="View"><i class="fas fa-eye"></i></a>`;
                    if (isCompleted) {
                        return `<div class="d-flex gap-1 justify-content-center">${viewBtn}</div>`;
                    }
                    return `<div class="d-flex gap-1 justify-content-center">
                        ${viewBtn}
                        <button class="btn btn-sm btn-warning btn-action update-status-action"
                            data-order-id="${row.id}" data-order-status="${row.status}" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-action delete-action"
                            data-order-id="${row.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`;
                    @endif
                }
            }
        ],
    });

    // Update Status — open modal pre-filled
    $(document).on('click', '.update-status-action', function() {
        $('#order_id').val($(this).data('order-id'));
        $('#status').val($(this).data('order-status'));
        $('#updateStatus').modal('show');
    });

    // Delete — open confirm modal
    $(document).on('click', '.delete-action', function() {
        $('#deleteOrder').data('order-id', $(this).data('order-id'));
        $('#deleteOrder').modal('show');
    });

    // Submit status update
    $(document).on('submit', '#updateStatusForm', function(e) {
        e.preventDefault();
        var orderId = $('#order_id').val();
        var status  = $('#status').val();
        var btn     = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).text('Updating...');
        $.ajax({
            url: '/orders/' + orderId + '/status-update',
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'PUT', status: status },
            success: function() {
                toastr.success('Order status updated successfully');
                $('#updateStatus').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error updating order status');
                btn.prop('disabled', false).text('Update Status');
            }
        });
    });

    // Print orders
    $('#printOrdersBtn').on('click', function() {
        var rows = table.rows({ search: 'applied' }).data().toArray();
        var statusColors = { pending: '#f59e0b', processing: '#0ea5e9', completed: '#22c55e', cancelled: '#ef4444' };
        var pad = function(n) { return String(n).padStart(2, '0'); };
        var rowsHtml = rows.map(function(r) {
            var color = statusColors[r.status] || '#6b7280';
            var d = new Date(r.created_at);
            var dateStr = pad(d.getDate()) + '/' + pad(d.getMonth()+1) + '/' + d.getFullYear() + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
            var label = r.status.charAt(0).toUpperCase() + r.status.slice(1);
            return '<tr><td>' + r.id + '</td><td>' + (r.first_name || '') + ' ' + (r.last_name || '') + '</td><td>RWF ' + Math.round(parseFloat(r.total_amount || 0)).toLocaleString() + '</td><td><span class="status-badge" style="background:' + color + ';">' + label + '</span></td><td>' + (r.order_type || '') + '</td><td>' + dateStr + '</td></tr>';
        }).join('');
        var now = new Date().toLocaleString();
        @php
            $u = session('userData')['users'] ?? null;
            $rName    = $u->restaurant_name    ?? '—';
            $rAddress = $u->restaurant_address ?? '';
            $rPhone   = $u->restaurant_phone   ?? '';
            $rEmail   = $u->restaurant_email   ?? '';
            $rLogo    = $u->restaurant_logo    ? asset($u->restaurant_logo) : '';
        @endphp
        var logoHtml = '{{ $rLogo }}' ? '<img src="{{ $rLogo }}" style="height:60px;object-fit:contain;display:block;margin-bottom:4px;">' : '';
        var headerHtml =
            '<div style="display:flex;align-items:center;gap:18px;border-bottom:2px solid #0f3039;padding-bottom:12px;margin-bottom:16px;">' +
                '<div style="flex-shrink:0;">' + logoHtml + '</div>' +
                '<div>' +
                    '<div style="font-size:20px;font-weight:700;color:#0f3039;">{{ $rName }}</div>' +
                    '{{ $rAddress ? "<div style=\"font-size:12px;color:#64748b;\">$rAddress</div>" : "" }}' +
                    '{{ $rPhone   ? "<div style=\"font-size:12px;color:#64748b;\">Phone: $rPhone</div>" : "" }}' +
                    '{{ $rEmail   ? "<div style=\"font-size:12px;color:#64748b;\">Email: $rEmail</div>" : "" }}' +
                '</div>' +
                '<div style="margin-left:auto;text-align:right;">' +
                    '<div style="font-size:16px;font-weight:600;color:#0f3039;">Orders List</div>' +
                    '<div style="font-size:11px;color:#64748b;">Printed: ' + now + '</div>' +
                    '<div style="font-size:11px;color:#64748b;">Total: ' + rows.length + ' order(s)</div>' +
                '</div>' +
            '</div>';
        $('#printArea').html(
            headerHtml +
            '<table><thead><tr><th>Order ID</th><th>Customer</th><th>Amount</th><th>Status</th><th>Type</th><th>Date</th></tr></thead><tbody>' + rowsHtml + '</tbody></table>'
        );
        window.print();
    });

    // Confirm delete
    $(document).on('click', '#confirmDelete', function() {
        var orderId = $('#deleteOrder').data('order-id');
        if (!orderId) return;
        var btn = $(this);
        btn.prop('disabled', true).text('Deleting...');
        $.ajax({
            url: '/orders/' + orderId,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'DELETE' },
            success: function() {
                toastr.success('Order deleted successfully');
                $('#deleteOrder').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error deleting order');
                btn.prop('disabled', false).text('Yes');
            }
        });
    });
});
</script>
@endsection
