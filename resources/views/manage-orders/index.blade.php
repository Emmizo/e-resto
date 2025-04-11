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
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3 me-2" title="Create Order">Create Order</a>
                        <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 filter-btn" title="Filter">Filter</a>
                    </div>
                </div>
                <div class="table-block">
                    <table id="manageOrdersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <span>Order ID</span>
                                </th>
                                <th>
                                    <span>Customer</span>
                                </th>
                                <th>
                                    <span>Total Amount</span>
                                </th>
                                <th>
                                    <span>Status</span>
                                </th>
                                <th>
                                    <span>Order Date</span>
                                </th>
                                <th class="action-cell text-center">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <span>#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <span>{{ $order->user->name }}</span>
                                </td>
                                <td>
                                    <span>${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span>{{ $order->created_at->format('M d, Y H:i') }}</span>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-col position-relative d-inline-block">
                                        <a href="javascript:;" class="p-1" data-bs-toggle="popover" data-bs-placement="top">
                                            <svg class="action-icon cursor-pointer" width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                                                <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                                                <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                                            </svg>
                                        </a>
                                        <!-- Table Action Cell -->
                                        <div class="popover-content" data-name="table-action-btn">
                                            <div class="action-menu">
                                                <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
                                                    <li class="action-menu-item text-start">
                                                        <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 view-action" data-bs-toggle="modal" data-bs-target="#viewOrder" data-order-id="{{ $order->id }}" title="View">View</a>
                                                    </li>
                                                    <li class="action-menu-item text-start">
                                                        <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 update-status-action" data-bs-toggle="modal" data-bs-target="#updateStatus" data-order-id="{{ $order->id }}" title="Update Status">Update Status</a>
                                                    </li>
                                                    <li class="action-menu-item text-start">
                                                        <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-action" data-bs-toggle="modal" data-bs-target="#deleteOrder" data-order-id="{{ $order->id }}" title="Delete">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty

                            @endforelse
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
                <form id="updateStatusForm">
                    @csrf
                    @method('PUT')
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
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateStatusBtn">Update Status</button>
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

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#manageOrdersTable').DataTable({
        responsive: true,
        order: [[4, 'desc']], // Sort by order date descending
        language: {
            emptyTable: "No orders found"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        processing: true,
        serverSide: false,
        data: {!! json_encode($orders) !!},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                targets: 2, // Total Amount column
                render: function(data) {
                    return '$' + parseFloat(data).toFixed(2);
                }
            },
            {
                targets: 3, // Status column
                render: function(data) {
                    var badgeClass = data === 'completed' ? 'success' : (data === 'cancelled' ? 'danger' : 'warning');
                    return '<span class="badge badge-' + badgeClass + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
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

    // Handle update status button click
    $('.update-status-action').click(function() {
        const orderId = $(this).data('order-id');
        const order = {!! json_encode($orders) !!}.find(o => o.id === orderId);

        if (order) {
            $('#order_id').val(order.id);
            $('#status').val(order.status);
        }
    });

    // Handle status update
    $('#updateStatusBtn').click(function() {
        const orderId = $('#order_id').val();
        const status = $('#status').val();

        $.ajax({
            url: `/orders/${orderId}`,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                toastr.success('Order status updated successfully');
                $('#updateStatus').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error('Error updating order status');
            }
        });
    });

    // Handle delete button click
    $('.delete-action').click(function() {
        const orderId = $(this).data('order-id');
        $('#deleteOrder').data('order-id', orderId);
    });

    // Handle confirm delete
    $('#confirmDelete').click(function() {
        const orderId = $('#deleteOrder').data('order-id');

        $.ajax({
            url: `/orders/${orderId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Order deleted successfully');
                $('#deleteOrder').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error('Error deleting order');
            }
        });
    });

    // Handle view button click
    $('.view-action').click(function() {
        const orderId = $(this).data('order-id');
        window.location.href = `/orders/${orderId}`;
    });
});
</script>
@endsection
