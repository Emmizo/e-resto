@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">

        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('promo-banners.index') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Promo Banners</a>
                </li>
            </ul>
        </div>

        <div class="content-inner">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Promo Banners</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Banners" aria-controls="promoBannersTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0">
                        @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('promo-banners.create') }}" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3">
                            <i class="fas fa-plus me-1"></i> Add Banner
                        </a>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-block mt-3">
                    <table id="promoBannersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Image</th>
                                @if(auth()->user()->role === 'admin')<th>Restaurant</th>@endif
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th class="action-cell text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $key => $banner)
                            @php $imgUrl = $banner->image_path ? asset(ltrim(str_replace(config('app.url'), '', $banner->image_path), '/')) : null; @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="fw-semibold small">{{ $banner->title }}</div>
                                    @if($banner->description)
                                        <div class="text-muted xsmall">{{ Str::limit($banner->description, 60) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $banner->title }}" class="rounded-2 banner-thumb"
                                             style="width:70px;height:45px;object-fit:cover;cursor:pointer;" data-img="{{ $imgUrl }}">
                                    @else
                                        <span class="text-muted xsmall">No image</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                <td><span class="badge rounded-pill" style="background:#e0f2fe;color:#0369a1;">{{ $banner->restaurant->name ?? '—' }}</span></td>
                                @endif
                                <td>{{ $banner->start_date ? $banner->start_date->format('d/m/Y') : '—' }}</td>
                                <td>{{ $banner->end_date ? $banner->end_date->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @if(auth()->user()->role === 'admin')
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input toggle-banner-active" type="checkbox" role="switch"
                                                data-banner-id="{{ $banner->id }}"
                                                {{ $banner->is_active ? 'checked' : '' }}
                                                title="{{ $banner->is_active ? 'Click to disable' : 'Click to enable' }}">
                                        </div>
                                    @else
                                        <span class="badge rounded-pill {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="action-cell text-center">
                                    @if(auth()->user()->role === 'admin')
                                        <span class="text-muted xsmall">View only</span>
                                    @else
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a href="{{ route('promo-banners.edit', $banner->id) }}" class="dropdown-item xsmall font-dmsans">
                                                    <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item xsmall font-dmsans text-danger delete-banner-btn"
                                                    data-banner-id="{{ $banner->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#deleteBannerModal">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $banners->links() }}</div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Image preview modal -->
<div class="modal fade" id="bannerImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title font-dmsans fw-bold text-primary-v1">Banner Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="bannerModalImg" class="img-fluid rounded-3" alt="Banner Image">
            </div>
        </div>
    </div>
</div>

<!-- Delete confirm modal -->
<div class="modal fade" id="deleteBannerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title font-dmsans fw-bold text-primary-v1">Delete Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="font-dmsans text-primary-v1 mb-4">Are you sure you want to delete this promo banner?</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-danger btn-small fw-semibold rounded-3" id="confirmDeleteBanner">Yes, Delete</button>
                    <button type="button" class="btn btn-outline-secondary btn-small fw-semibold rounded-3" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#promoBannersTable')) {
        $('#promoBannersTable').DataTable().destroy();
    }
    $('#promoBannersTable').DataTable({
        responsive: true,
        dom: 'lrtip',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        language: { emptyTable: 'No promo banners found' },
        columnDefs: [{ orderable: false, targets: [2, 6] }]
    });

    // Custom search wire-up
    $('.custom-search').on('keyup', function() {
        $('#promoBannersTable').DataTable().search($(this).val()).draw();
    });

    // Image preview modal
    $(document).on('click', '.banner-thumb', function() {
        $('#bannerModalImg').attr('src', $(this).data('img'));
        $('#bannerImageModal').modal('show');
    });

    // Admin toggle active/inactive
    $(document).on('change', '.toggle-banner-active', function() {
        const bannerId = $(this).data('banner-id');
        const checkbox = $(this);
        checkbox.prop('disabled', true);
        $.ajax({
            url: '/promo-banners/' + bannerId + '/toggle-active',
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'PATCH' },
            success: function(res) {
                checkbox.prop('disabled', false);
                const label = res.is_active ? 'Click to disable' : 'Click to enable';
                checkbox.attr('title', label);
                toastr.success('Banner ' + (res.is_active ? 'enabled' : 'disabled') + ' successfully.');
            },
            error: function() {
                checkbox.prop('checked', !checkbox.prop('checked')).prop('disabled', false);
                toastr.error('Failed to update banner status.');
            }
        });
    });

    // Delete
    let deleteBannerId = null;

    $(document).on('click', '.delete-banner-btn', function() {
        deleteBannerId = $(this).data('banner-id');
    });

    $('#confirmDeleteBanner').on('click', function() {
        if (!deleteBannerId) return;
        const btn = $(this);
        btn.prop('disabled', true).text('Deleting...');

        $.ajax({
            url: '/promo-banners/' + deleteBannerId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'DELETE'
            },
            success: function() {
                toastr.success('Promo banner deleted successfully.');
                $('#deleteBannerModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error deleting banner.');
                btn.prop('disabled', false).text('Yes, Delete');
            }
        });
    });
});
</script>
@endsection
