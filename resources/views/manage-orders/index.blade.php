@extends('layouts.app')

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
        <div class="content-inner">
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
                    <div class="btn-options mt-3 mt-xl-0">

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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $key => $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown" {{ $order->status === 'completed' ? 'disabled' : '' }}>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item view-action" href="#" data-order-id="{{ $order->id }}">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item update-status-action" href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#updateStatus"
                                                   data-order-id="{{ $order->id }}"
                                                   {{ $order->status === 'completed' ? 'style="pointer-events: none; opacity: 0.5;"' : '' }}>
                                                    <i class="fas fa-edit"></i> Update Status
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item delete-action" href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#deleteOrder"
                                                   data-order-id="{{ $order->id }}"
                                                   {{ $order->status === 'completed' ? 'style="pointer-events: none; opacity: 0.5;"' : '' }}>
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="updateStatusLabel">Update Order Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm" method="POST"><!-- No action attribute: handled by JS only -->
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

@section('script')
<script>
console.log('Script loaded');
$(document).ready(function() {
    console.log('Document ready');
    if ($.fn.DataTable.isDataTable('#manageOrdersTable')) {
        $('#manageOrdersTable').DataTable().destroy();
    }
    // Initialize DataTable with the existing table data
    var table = $('#manageOrdersTable').DataTable({
        responsive: true,
        order: [[4, 'desc']], // Sort by order date descending
        language: {
            emptyTable: "No orders found"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        processing: false,
        serverSide: false,
        data: {!! json_encode($orders) !!},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer', name: 'customer' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    const isCompleted = row.status === 'completed';
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown" ${isCompleted ? 'disabled' : ''}>
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item view-action" href="#" data-order-id="${row.id}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item update-status-action" href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#updateStatus"
                                       data-order-id="${row.id}"
                                       ${isCompleted ? 'style="pointer-events: none; opacity: 0.5;"' : ''}>
                                        <i class="fas fa-edit"></i> Update Status
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item delete-action" href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#deleteOrder"
                                       data-order-id="${row.id}"
                                       ${isCompleted ? 'style="pointer-events: none; opacity: 0.5;"' : ''}>
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        columnDefs: [
            {
                targets: 0, // Order ID column
                render: function(data) {
                    return '#' + data;
                }
            },
            {
                targets: 1, // Customer column
                render: function(data, type, row) {
                    return row.first_name + ' ' + row.last_name;
                }
            },
            {
                targets: 2, // Total Amount column
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                targets: 3, // Status column
                render: function(data, type, row) {
                    const isCompleted = row.status === 'completed';
                    const badgeClass = data === 'completed' ? 'success' : (data === 'cancelled' ? 'danger' : 'warning');
                    return `
                        <span class="badge badge-pill badge-${badgeClass} status-badge"
                              style="cursor: ${isCompleted ? 'default' : 'pointer'};"
                              data-order-id="${row.id}"
                              data-status="${data}"
                              ${isCompleted ? 'disabled' : ''}>
                            ${data.charAt(0).toUpperCase() + data.slice(1)}
                        </span>
                    `;
                }
            },
            {
                targets: 4, // Order Date column
                render: function(data) {
                    return moment(data).format('MMM D, YYYY HH:mm');
                }
            }
        ]
    });

    // Handle status badge click
    $(document).on('click', '.status-badge:not([disabled])', function() {
        const orderId = $(this).data('order-id');
        const currentStatus = $(this).data('status');
        const order = {!! json_encode($orders) !!}.find(o => o.id === parseInt(orderId));

        if (order && order.status !== 'completed') {
            $('#order_id').val(order.id);
            $('#status').val(currentStatus);
            $('#updateStatus').modal('show');
        }
    });

    // Handle update status form submission (delegated for robustness)
    $(document).on('submit', '#updateStatusForm', function(e) {
        console.log('Delegated form submit handler triggered');
        e.preventDefault();
        const orderId = $('#order_id').val();
        const status = $('#status').val();
        const order = {!! json_encode($orders) !!}.find(o => o.id === parseInt(orderId));
        alert(orderId);
        // Prevent updating if order is already completed
        if (order && order.status === 'completed') {
            toastr.error('Cannot update status of a completed order');
            return;
        }
        $.ajax({
            url: `/orders/${orderId}/status-update`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                if (response.status === 200) {
                    toastr.success('Order status updated successfully');
                    $('#updateStatus').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Error updating order status');
                }
            },
            error: function(xhr) {
                console.error('Update Status Error:', xhr);
                const errorMessage = xhr.responseJSON?.message || 'Error updating order status';
                toastr.error(errorMessage);
            }
        });
    });

    // Handle view button click (delegated)
    $(document).on('click', '.view-action', function() {
        const orderId = $(this).data('order-id');
        window.location.href = `/orders/${orderId}`;
    });
});
</script>
@endsection
