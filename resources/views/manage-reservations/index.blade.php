@extends('layouts.app')

@section('style')
<style>
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
                    <a href="{{route('reservations.index')}}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Reservations">Manage Reservations</a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-inner" data-restaurant-id="{{ session('userData')['users']->restaurant_id ?? '' }}">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Manage Reservations</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Reservations" aria-controls="manageReservationsTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0 d-flex gap-2">
                        <button id="printReservationsBtn" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1" title="Print Reservations">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                        <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 filter-btn" title="Filter">Filter</a>
                    </div>
                </div>
                <div class="table-block" style="overflow-x: auto;">
                    <table id="manageReservationsTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th><span>Reservation ID</span></th>
                                <th>
                                    <span>Customer</span>
                                </th>
                                <th>
                                    <span>Date & Time</span>
                                </th>
                                <th>
                                    <span>Party Size</span>
                                </th>
                                <th>
                                    <span>Phone Number</span>
                                </th>
                                <th>
                                    <span>Status</span>
                                </th>
                                <th>
                                    <span>Created At</span>
                                </th>
                                @if($isAdmin)
                                <th><span>Restaurant</span></th>
                                @endif
                                <th class="action-cell text-center">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservations as $key => $reservation)
                            <tr>
                                <td><span>{{ $key+1 }}</span></td>
                                <td>
                                    <span>{{ $reservation->first_name }} {{ $reservation->last_name }}</span>
                                </td>
                                <td>
                                    <span>{{ $reservation->reservation_time ? $reservation->reservation_time->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('d/m/Y H:i:s') : 'N/A' }}</span>
                                </td>
                                <td>
                                    <span>{{ $reservation->number_of_people }}</span>
                                </td>
                                <td>
                                    <span>{{ $reservation->phone_number ?? 'N/A' }}</span>
                                </td>
                                @php
                                    $statusOrder = ['pending'=>0,'confirmed'=>1,'completed'=>2,'cancelled'=>3];
                                @endphp
                                <td data-order="{{ $statusOrder[$reservation->status] ?? 9 }}">
                                    <span class="badge rounded-pill reservation-status-badge bg-{{ $reservation->status === 'confirmed' ? 'success' : ($reservation->status === 'cancelled' ? 'danger' : 'warning') }}"
                                          data-reservation-id="{{ $reservation->id }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span>{{ $reservation->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('d/m/Y H:i:s') }}</span>
                                </td>
                                @if($isAdmin)
                                <td><span class="badge rounded-pill" style="background:#e0f2fe;color:#0369a1;font-weight:500;">{{ $reservation->restaurant_name ?? '—' }}</span></td>
                                @endif
                                <td class="action-cell text-center">
                                    @if($isAdmin)
                                    <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-sm btn-info" style="width:30px;height:30px;padding:0;display:inline-flex;align-items:center;justify-content:center;" title="View">
                                        <i class="fas fa-eye" style="font-size:0.75rem;"></i>
                                    </a>
                                    @else
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                                                <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                                                <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a href="{{ route('reservations.show', $reservation->id) }}" class="dropdown-item xsmall font-dmsans">
                                                    <i class="fas fa-eye me-2 text-primary"></i>View
                                                </a>
                                            </li>
                                            @if($reservation->status === 'pending')
                                            <li>
                                                <button type="button" class="dropdown-item xsmall font-dmsans text-success approve-action"
                                                    data-reservation-id="{{ $reservation->id }}">
                                                    <i class="fas fa-check-circle me-2 text-success"></i>Approve
                                                </button>
                                            </li>
                                            @endif
                                            <li>
                                                <button type="button" class="dropdown-item xsmall font-dmsans update-status-action"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateStatus"
                                                    data-reservation-id="{{ $reservation->id }}"
                                                    data-reservation-status="{{ $reservation->status }}">
                                                    <i class="fas fa-edit me-2 text-warning"></i>Update Status
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item xsmall font-dmsans text-danger delete-action"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteReservation"
                                                    data-reservation-id="{{ $reservation->id }}">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
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

<div id="printArea"></div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="updateStatusLabel">Update Reservation Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="reservation_id" id="reservation_id">
                    <div class="form-group mb-3">
                        <label for="status" class="form-label font-dmsans fw-medium text-primary-v1">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
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

<!-- Delete Reservation Modal -->
<div class="modal fade" id="deleteReservation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteReservationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteReservationLabel">Delete Reservation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this reservation?</p>
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
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#manageReservationsTable')) {
        $('#manageReservationsTable').DataTable().destroy();
    }
    $('#manageReservationsTable').DataTable({
        responsive: true,
        order: [[5, 'asc'], [2, 'asc']],
        language: { emptyTable: "No reservations found" },
        dom: 'lrtip',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });

    // Pre-fill modal with the current status of the clicked row
    $(document).on('click', '.update-status-action', function() {
        const reservationId = $(this).data('reservation-id');
        const currentStatus  = $(this).data('reservation-status');
        $('#reservation_id').val(reservationId);
        $('#status').val(currentStatus);
    });

    // Handle status update
    $(document).on('click', '#updateStatusBtn', function() {
        const reservationId = $('#reservation_id').val();
        const status = $('#status').val();
        const btn = $(this);
        btn.prop('disabled', true).text('Updating...');

        $.ajax({
            url: `/reservations/${reservationId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                status: status
            },
            success: function() {
                toastr.success('Reservation status updated successfully');
                $('#updateStatus').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error updating reservation status');
                btn.prop('disabled', false).text('Update Status');
            }
        });
    });

    // One-click Approve (sets status to confirmed immediately)
    $(document).on('click', '.approve-action', function() {
        const reservationId = $(this).data('reservation-id');
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Approving...');

        $.ajax({
            url: `/reservations/${reservationId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                status: 'confirmed'
            },
            success: function() {
                toastr.success('Reservation approved successfully');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error approving reservation');
                btn.prop('disabled', false).html('<i class="fas fa-check-circle me-2 text-success"></i>Approve');
            }
        });
    });

    // Print reservations
    $('#printReservationsBtn').on('click', function() {
        var dt = $('#manageReservationsTable').DataTable();
        var statusColors = { pending: '#f59e0b', confirmed: '#22c55e', cancelled: '#ef4444', completed: '#0ea5e9' };
        var rowsHtml = '';
        var count = 0;
        dt.rows({ search: 'applied' }).every(function() {
            var cells = $(this.node()).find('td');
            if (cells.length < 7) return;
            var statusText = cells.eq(5).find('.badge').text().trim() || cells.eq(5).text().trim();
            var color = statusColors[statusText.toLowerCase()] || '#6b7280';
            rowsHtml += '<tr><td>' + cells.eq(0).text().trim() + '</td><td>' + cells.eq(1).text().trim() + '</td><td>' + cells.eq(2).text().trim() + '</td><td>' + cells.eq(3).text().trim() + '</td><td>' + cells.eq(4).text().trim() + '</td><td><span class="status-badge" style="background:' + color + ';">' + statusText + '</span></td><td>' + cells.eq(6).text().trim() + '</td></tr>';
            count++;
        });
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
                    '<div style="font-size:16px;font-weight:600;color:#0f3039;">Reservations List</div>' +
                    '<div style="font-size:11px;color:#64748b;">Printed: ' + now + '</div>' +
                    '<div style="font-size:11px;color:#64748b;">Total: ' + count + ' reservation(s)</div>' +
                '</div>' +
            '</div>';
        $('#printArea').html(
            headerHtml +
            '<table><thead><tr><th>#</th><th>Customer</th><th>Date &amp; Time</th><th>Party Size</th><th>Phone</th><th>Status</th><th>Created At</th></tr></thead><tbody>' + rowsHtml + '</tbody></table>'
        );
        window.print();
    });

    // Store reservation ID when delete modal triggered
    $(document).on('click', '.delete-action', function() {
        const reservationId = $(this).data('reservation-id');
        $('#deleteReservation').data('reservation-id', reservationId);
    });

    // Handle confirm delete
    $(document).on('click', '#confirmDelete', function() {
        const reservationId = $('#deleteReservation').data('reservation-id');
        if (!reservationId) return;
        const btn = $(this);
        btn.prop('disabled', true).text('Deleting...');

        $.ajax({
            url: `/reservations/${reservationId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function() {
                toastr.success('Reservation deleted successfully');
                $('#deleteReservation').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error deleting reservation');
                btn.prop('disabled', false).text('Yes');
            }
        });
    });
});
</script>

{{-- Real-time new reservation listener --}}
@if(auth()->check() && in_array(auth()->user()->role, ['restaurant_owner', 'admin']))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo === 'undefined') return;

    var statusColors = { pending: '#f59e0b', confirmed: '#22c55e', cancelled: '#ef4444', completed: '#0ea5e9' };
    var table = $('#manageUsersTable').DataTable();

    var statusBgMap = { pending: '#f59e0b', confirmed: '#22c55e', cancelled: '#ef4444', completed: '#0ea5e9' };

    window.Echo.private('owner.' + {{ auth()->id() }})
        // ── New reservation arrives ────────────────────────────
        .listen('ReservationCreated', function(e) {
            var r = e.reservation;
            if (!r) return;
            var statusColor = statusBgMap[r.status] || '#6b7280';
            var dt = r.reservation_time ? new Date(r.reservation_time).toLocaleString() : '';
            var statusBadge = '<span class="badge rounded-pill reservation-status-badge px-2" data-reservation-id="' + r.id + '" style="background:' + statusColor + ';color:#fff;font-size:.7rem;">' + (r.status.charAt(0).toUpperCase() + r.status.slice(1)) + '</span>';
            var actions = '<div class="d-flex gap-1">'
                + '<button class="btn btn-sm approve-action" data-reservation-id="' + r.id + '" style="background:#dcfce7;border:none;width:30px;height:30px;border-radius:8px;" title="Approve"><i class="fas fa-check" style="color:#16a34a;font-size:.75rem;"></i></button>'
                + '<button class="btn btn-sm update-status-action" data-reservation-id="' + r.id + '" data-reservation-status="' + r.status + '" data-bs-toggle="modal" data-bs-target="#updateStatus" style="background:#e0f2fe;border:none;width:30px;height:30px;border-radius:8px;" title="Update Status"><i class="fas fa-edit" style="color:#0284c7;font-size:.75rem;"></i></button>'
                + '</div>';
            table.row.add([
                r.id,
                r.customer ? r.customer.name : 'Guest',
                dt,
                r.number_of_people || '—',
                r.phone_number || '—',
                statusBadge,
                actions,
            ]).draw(false);
            toastr.info('New reservation #' + r.id + ' from ' + (r.customer ? r.customer.name : 'a guest'));
        })
        // ── Reservation status changed ─────────────────────────
        .listen('ReservationStatusChanged', function(e) {
            var r = e.reservation;
            if (!r) return;
            var color = statusBgMap[r.status] || '#6b7280';
            var label = r.status.charAt(0).toUpperCase() + r.status.slice(1);
            document.querySelectorAll('.reservation-status-badge[data-reservation-id="' + r.id + '"]').forEach(function(el) {
                el.style.background = color;
                el.className = 'badge rounded-pill reservation-status-badge';
                el.style.color = '#fff';
                el.textContent = label;
                el.dataset.reservationId = r.id;
            });
            document.querySelectorAll('[data-reservation-id="' + r.id + '"].update-status-action').forEach(function(btn) {
                btn.dataset.reservationStatus = r.status;
            });
        });
});
</script>
@endif
@endsection
