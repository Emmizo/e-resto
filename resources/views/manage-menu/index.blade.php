@extends('layouts.app')

@section('style')
<style>
#manageUsersTable_wrapper,
#manageUsersTable_wrapper .dataTables_scroll,
#manageUsersTable_wrapper .dataTables_scrollBody {
    overflow: visible !important;
}
/* Dietary pill checkboxes */
.dietary-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    cursor: pointer;
    font-size: 0.78rem;
    font-weight: 500;
    color: #475569;
    transition: all 0.15s ease;
    user-select: none;
    margin: 0;
}
.dietary-pill:hover {
    border-color: #7c3aed;
    background: #f5f3ff;
    color: #7c3aed;
}
.dietary-pill input.dietary-cb {
    display: none;
}
.dietary-pill:has(input:checked),
.dietary-pill.dietary-pill-checked {
    border-color: #7c3aed;
    background: #ede9fe;
    color: #7c3aed;
    font-weight: 600;
}
.dietary-pill:has(input:checked)::before,
.dietary-pill.dietary-pill-checked::before {
    content: '✓ ';
    font-weight: 700;
}

/* Expanded child row */
#manageUsersTable tbody tr.menu-expandable-row:hover td {
    background-color: #f0fdf4 !important;
}
#manageUsersTable tbody tr.row-expanded > td {
    border-bottom: none !important;
}
#manageUsersTable tbody tr.row-expanded > td:first-child {
    border-left: 3px solid #16a34a !important;
}
tr.child td {
    background: #f8fafc !important;
    border-top: none !important;
    padding: 0 !important;
}
.row-toggle-icon {
    transition: transform 0.2s ease;
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
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Home">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('manage-menu') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Menu">Manage Menu</a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-inner">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Manage Menu</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Article" aria-controls="manageUsersTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0">
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#viewAllMenusModal" title="View All Menus">View All Menus</a>
                        @if(auth()->user()->role !== 'admin')
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#addUser" title="Add Menu">Add Menu</a>
                        @endif
                    </div>
                </div>
                <div class="filter-col-options">
                    <div class="filter-col-options-inner pt-2 mt-1">

                    </div>
                </div>
                <div class="table-block">
                    <table id="manageUsersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                @if(auth()->user()->role === 'admin')
                                <th><span>Restaurant</span></th>
                                @endif
                                <th>
                                    <span>Name</span>
                                </th>
                                <th>
                                    <span>Description</span>
                                </th>
                                <th>
                                   <span>Status</span>
                                </th>
                                @if(auth()->user()->role !== 'admin')
                                <th class="action-cell text-center" style="width:120px;">
                                    <span>Actions</span>
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                            <tr class="menu-expandable-row" data-menu-id="{{ $menu->id }}" style="cursor:pointer;">
                                @if(auth()->user()->role === 'admin')
                                <td><span class="badge bg-light text-dark border">{{ $menu->restaurant_name }}</span></td>
                                @endif
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"
                                             style="width:30px;height:30px;background:#f0fdf4;">
                                            <i class="fas fa-utensils" style="color:#16a34a;font-size:0.7rem;"></i>
                                        </div>
                                        <span class="fw-medium">{{$menu->menu_name}}</span>
                                        <i class="fas fa-chevron-right row-toggle-icon ms-1" style="font-size:0.65rem;color:#94a3b8;transition:transform 0.2s;"></i>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($menu->menu_description, 60) }}</span>
                                </td>
                                <td>
                                    <div class="toggle-switch d-flex align-items-center">
                                        <div class="toggle-button toggle-front d-flex align-items-center position-relative">
                                            <label for="status{{ $menu->id }}" class="form-check-label font-dmsans text-primary-v1 visually-hidden">Status</label>
                                            <label class="switch">
                                                <input type="checkbox" id="status{{ $menu->id }}" class="status-toggle" data-id="{{ $menu->id }}" {{ $menu->is_active == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                                <span class="active font-dmsans fw-medium">Active</span>
                                                <span class="inactive font-dmsans fw-medium">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                @if(auth()->user()->role !== 'admin')
                                <td class="action-cell text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button"
                                            class="btn btn-sm view-menu d-flex align-items-center justify-content-center"
                                            data-menu-id="{{ $menu->id }}"
                                            title="View Items"
                                            style="width:30px;height:30px;border-radius:8px;background:#e0f2fe;border:none;padding:0;">
                                            <i class="fas fa-eye" style="color:#0284c7;font-size:0.75rem;"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm edit-menu d-flex align-items-center justify-content-center"
                                            data-menu-id="{{ $menu->id }}"
                                            title="Edit"
                                            style="width:30px;height:30px;border-radius:8px;background:#fef9c3;border:none;padding:0;">
                                            <i class="fas fa-pencil-alt" style="color:#ca8a04;font-size:0.75rem;"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm delete-menu d-flex align-items-center justify-content-center"
                                            data-menu-id="{{ $menu->id }}"
                                            title="Delete"
                                            style="width:30px;height:30px;border-radius:8px;background:#fee2e2;border:none;padding:0;">
                                            <i class="fas fa-trash-alt" style="color:#dc2626;font-size:0.75rem;"></i>
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>


<!-- Add menu Modal -->
<div class="modal fade" id="addUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="addUserLabel">Add Menu Type</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="message-container-login"></div>
            <form action="" method="POST" id="addMenuForm">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="mealTime" class="form-label">Meal Time <span class="asterik">*</span></label>
                                    <select class="form-control rounded-3" id="mealTime" name="name" required>
                                        <option value="">Select Meal Time</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                                        <option value="Snack">Snack</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label for="restaurantDescription" class="form-label">Description<span class="asterik">*</span></label>
                                    <textarea class="form-control rounded-3" id="restaurantDescription" name="description" rows="3" placeholder="Enter Restaurant Description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">Menu Items</h4>
                            <button type="button" class="btn btn-success btn-sm" id="addMenuItem">
                                <i class="bi bi-plus-circle me-1"></i>Add Menu Item
                            </button>
                        </div>
                        <div id="menuItemsContainer">
                            <div class="menu-item row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="menu_items[0][name]" placeholder="Item Name" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="menu_items[0][price]" placeholder="Price" required>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="menu_items[0][image]" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <select class="form-control" name="menu_items[0][category]">
                                        <option value="">Select Category</option>
                                        <option value="Beverage">Beverage</option>
                                        <option value="Food">Food</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-2 food-dietary-options" style="display:none;">
                                    <label class="form-label small fw-semibold mb-2">Dietary Options</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(config('dietary.food') as $option)
                                        <label class="dietary-pill">
                                            <input type="checkbox" name="menu_items[0][suitable_for][]" value="{{ $option }}" class="dietary-cb">
                                            <span>{{ ucfirst($option) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12 mt-2 beverage-dietary-options" style="display:none;">
                                    <label class="form-label small fw-semibold mb-2">Dietary Options</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach(config('dietary.beverage') as $option)
                                        <label class="dietary-pill">
                                            <input type="checkbox" name="menu_items[0][suitable_for][]" value="{{ $option }}" class="dietary-cb">
                                            <span>{{ ucfirst($option) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 mt-2">
                                    <select class="form-control" name="menu_items[0][is_available]">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                <button type="button" class="btn btn-danger remove-item-btn" onclick="removeItem(this)">
                                    <i class="fa fa-trash"></i>
                                </button>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-start">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3" id="send_btn2">Submit</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>





<!-- Edit Menu Modal -->
<div class="modal fade" id="editMenuModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="editMenuModalLabel">Edit Menu Type</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="edit-message-container"></div>
            <form action="" method="POST" id="editMenuForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_menu_id" name="id">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_mealTime" class="form-label">Meal Time <span class="asterik">*</span></label>
                                    <select class="form-control rounded-3" id="edit_mealTime" name="name" required>
                                        <option value="">Select Meal Time</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                                        <option value="Snack">Snack</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label for="edit_menu_description" class="form-label">Description<span class="asterik">*</span></label>
                                    <textarea class="form-control rounded-3" id="edit_menu_description" name="description" rows="3" placeholder="Enter Menu Description" required></textarea>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label class="form-check-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="edit_menu_status" name="is_active">
                                        <label class="form-check-label" for="edit_menu_status">Active</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">Menu Items</h4>
                            <button type="button" class="btn btn-success btn-sm" id="editAddMenuItem">
                                <i class="bi bi-plus-circle me-1"></i>Add Menu Item
                            </button>
                        </div>
                        <div id="editMenuItemsContainer">
                            <!-- Menu items will be loaded here dynamically -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-start">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3" id="updateMenuBtn">Update</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Menu Modal -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this menu?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMenu">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- View Menu Modal -->
<div class="modal fade" id="viewMenuModal" tabindex="-1" aria-labelledby="viewMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3" style="background:linear-gradient(135deg,#0f3039 0%,#184C55 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width:42px;height:42px;background:rgba(255,255,255,0.15);">
                        <i class="fas fa-book-open text-white" style="font-size:1rem;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="viewMenuModalLabel">Menu Items</h5>
                        <p class="text-white-50 mb-0" style="font-size:0.75rem;" id="viewMenuModalSubtitle">Loading…</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4" style="background:#f8fafc;max-height:70vh;overflow-y:auto;">
                <div id="viewMenuItemsContainer" class="row g-3"></div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 bg-white px-4 py-3">
                <button type="button" class="btn btn-light rounded-3 border px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>

        </div>
    </div>
</div>

<!-- View All Menus Modal -->
<div class="modal fade" id="viewAllMenusModal" tabindex="-1" aria-labelledby="viewAllMenusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="viewAllMenusModalLabel">All Menus</h1>
                <div class="ms-auto">
                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="printAllMenus()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body" style="max-height:75vh;overflow-y:auto;">
                <div id="printableMenus">
                    <div id="viewAllMenusLoader" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-2 small">Loading menus…</p>
                    </div>
                    <div id="viewAllMenusContent"></div>
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


    // ── Row expand / collapse ──────────────────────────────────────
    // Retrieve the already-initialised DataTables instance from main.js
    var menuTable = $('#manageUsersTable').DataTable();

$(document).on('click', '.menu-expandable-row', function(e) {
        // Don't expand when clicking action buttons or toggles
        if ($(e.target).closest('.action-cell, .toggle-switch, .status-toggle').length) return;

        var $row = $(this);
        var menuId = $row.data('menu-id');
        var $icon = $row.find('.row-toggle-icon');
        var dtRow = menuTable.row($row[0]);

        // If already open, close it
        if (dtRow.child.isShown()) {
            dtRow.child.hide();
            $row.removeClass('row-expanded');
            $icon.css('transform', 'rotate(0deg)');
            return;
        }

        // Show spinner immediately
        dtRow.child(`<div class="px-4 py-3 text-center"><span class="spinner-border spinner-border-sm text-primary me-2"></span><span class="text-muted small">Loading items…</span></div>`).show();
        $row.addClass('row-expanded');
        $icon.css('transform', 'rotate(90deg)');

        $.ajax({
            url: `/menu/${menuId}/edit`,
            method: 'GET',
            success: function(response) {
                var items = (response.status === 200 && response.menu) ? response.menu.menu_items : [];
                var innerHtml = `<div style="background:#f8fafc;padding:12px 16px 16px;">
                    <div class="row g-2">${buildItemCards(items)}</div>
                </div>`;
                dtRow.child(innerHtml).show();
            },
            error: function() {
                dtRow.child('<div class="px-4 py-3 text-danger small">Failed to load items.</div>').show();
            }
        });
    });

    function buildItemCards(items) {
        if (!items || items.length === 0) {
            return `<div class="col-12"><p class="text-muted small fst-italic mb-0">No items in this menu yet.</p></div>`;
        }
        return items.map(function(item) {
            const img = item.image
                ? `<img src="${item.image}" alt="${item.name}" class="rounded-2 flex-shrink-0 object-fit-cover" style="width:48px;height:48px;">`
                : `<div class="rounded-2 flex-shrink-0 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e2e8f0;"><i class="fas fa-utensils" style="color:#94a3b8;font-size:0.75rem;"></i></div>`;
            const avail = item.is_available == 1
                ? `<span class="badge rounded-pill" style="background:#dcfce7;color:#16a34a;font-size:0.6rem;">Available</span>`
                : `<span class="badge rounded-pill bg-secondary" style="font-size:0.6rem;">Unavailable</span>`;
            const dietary = formatDietaryInfo(item.dietary_info);
            return `<div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="d-flex align-items-start gap-2 p-2 rounded-3 border bg-white">
                    ${img}
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                            <span class="fw-semibold text-dark" style="font-size:0.82rem;">${item.name}</span>
                            ${avail}
                        </div>
                        <div>
                            <span class="fw-bold text-primary" style="font-size:0.78rem;">RWF ${Math.round(parseFloat(item.price||0)).toLocaleString()}</span>
                            ${item.category ? `<span class="badge rounded-pill ms-1" style="background:#ede9fe;color:#7c3aed;font-size:0.6rem;">${item.category}</span>` : ''}
                        </div>
                        ${dietary ? `<div class="text-muted mt-1" style="font-size:0.68rem;">${dietary}</div>` : ''}
                    </div>
                </div>
            </div>`;
        }).join('');
    }
    // ── End row expand ────────────────────────────────────────────

            //Bootstrap Duallistbox
            $('#Permissions').bootstrapDualListbox({
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false
            });
// To access the non-selected list container (if needed)
var nonSelectedList = $('[id="bootstrap-duallistbox-nonselected-list_Permissions[]"]');

// To access the selected list container (if needed)
var selectedList = $('[id="bootstrap-duallistbox-selected-list_Permissions[]"]');


$('#addMenuForm').validate({
    rules: {
        name: {
            required: true
        },
        description: {
            required: true,
            minlength: 10,
            maxlength: 250
        },
        'menu_items[0][name]': {
            required: true,
            minlength: 2,
            maxlength: 50
        },
        'menu_items[0][price]': {
            required: true,
            number: true,
            min: 0,
            max: 99999999
        },
        'menu_items[0][category]': {
            maxlength: 30
        },
        'menu_items[0][dietary_info]': {
            maxlength: 50
        },
        'menu_items[0][image]': {
            accept: "image/jpeg,image/png,image/jpg,image/gif,image/webp",
            filesize: 5 * 1024 * 1024
        }
    },
    messages: {
        name: {
            required: "Please select a meal time"
        },
        description: {
            required: "Please enter a description",
            minlength: "Description must be at least 10 characters long",
            maxlength: "Description cannot exceed 250 characters"
        },
        'menu_items[0][name]': {
            required: "Please enter a menu item name",
            minlength: "Item name must be at least 2 characters long",
            maxlength: "Item name cannot exceed 50 characters"
        },
        'menu_items[0][price]': {
            required: "Please enter a price",
            number: "Price must be a valid number",
            min: "Price cannot be negative",
            max: "Price is too high"
        },
        'menu_items[0][category]': {
            maxlength: "Category cannot exceed 30 characters"
        },
        'menu_items[0][dietary_info]': {
            maxlength: "Dietary info cannot exceed 50 characters"
        },
        'menu_items[0][image]': {
            required: "Please choose an image for this item",
            accept: "Only JPG, PNG, GIF, or WebP images are allowed",
            filesize: "Image must be less than 5MB"
        }
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group, .col-md-4, .col-md-3, .col-md-5').append(error);
    },
    highlight: function(element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
        $(element).removeClass('is-invalid');
    },
    submitHandler: function(form, e) {
        e.preventDefault();

        var form_data = new FormData();

        // Top-level fields (name, description, _token)
        form_data.append('name', $('#addMenuForm [name="name"]').val());
        form_data.append('description', $('#addMenuForm [name="description"]').val());
        form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

        // Collect each menu item
        $('#menuItemsContainer .menu-item').each(function(itemIndex) {
            var $item = $(this);

            // Text/number/select fields
            $item.find('input:not([type="checkbox"]):not([type="file"]), select').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                form_data.append(name, $(this).val());
            });

            // File input
            var fileInput = $item.find('input[type="file"]')[0];
            if (fileInput && fileInput.files[0]) {
                var fileName = fileInput.getAttribute('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                form_data.append(fileName, fileInput.files[0]);
            }

            // Only checked checkboxes
            $item.find('input[type="checkbox"]:checked').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                form_data.append(name, $(this).val());
            });
        });

        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('menu-store') }}",
            type: "POST",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#send_btn2')
                    .html("<i class='fa fa-spin fa-spinner'></i> Submitting...")
                    .prop('disabled', true);
                $('#loader').show();
                $('.alert-dismissible').hide();
            },
            success: function(result) {
                if (result.status == '200') {
                    $('#message-container-login')
                        .html('<div class="alert alert-success alert-dismissible">Menu Type Added Successfully! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                    setTimeout(function() {
                        window.location.href = result.redirect || '/manage-menu';
                    }, 1500);
                } else {
                    $('#message-container-login')
                        .html('<div class="alert alert-danger alert-dismissible">' +
                            (result.message || 'An error occurred. Please try again.') +
                            ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                    $('#send_btn2')
                        .html("Submit")
                        .prop('disabled', false);
                }
                $('#loader').hide();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);

                var errorMessages = '';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        errorMessages += errors.join('<br>') + '<br>';
                    });
                } else {
                    errorMessages = 'An unexpected error occurred. Please try again.';
                }

                $('#message-container-login')
                    .html('<div class="alert alert-danger alert-dismissible">' +
                        errorMessages +
                        ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                $('#send_btn2')
                    .html("Submit")
                    .prop('disabled', false);

                $('#loader').hide();
            }
        });

        return false;
    }
});

// Add custom validation method for file size
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
});

function resetForm() {
            document.getElementById("addUserForm").reset();
            $('#Permissions').bootstrapDualListbox('destroy');
        }
        $('#userPhone').mask('(000) 000-0000');
});
document.addEventListener('DOMContentLoaded', function() {
            const menuItemsContainer = document.getElementById('menuItemsContainer');
            const addMenuItemBtn = document.getElementById('addMenuItem');
            let menuItemIndex = 1;

            addMenuItemBtn.addEventListener('click', function() {
                const newMenuItem = menuItemsContainer.children[0].cloneNode(true);

                // Reset all inputs/selects
                newMenuItem.querySelectorAll('input, select').forEach(input => {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    } else if (input.type !== 'file') {
                        input.value = '';
                    }
                    // Re-index the name from [0] to [menuItemIndex]
                    if (input.name) {
                        input.name = input.name.replace(/\[0\]/, `[${menuItemIndex}]`);
                    }
                });

                // Always hide dietary option divs — they'll show when category is chosen
                newMenuItem.querySelectorAll('.food-dietary-options, .beverage-dietary-options').forEach(function(div) {
                    div.style.display = 'none';
                });

                // Add remove button functionality
                const removeBtn = newMenuItem.querySelector('.remove-item-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        this.closest('.menu-item').remove();
                    });
                }

                menuItemsContainer.appendChild(newMenuItem);
                menuItemIndex++;
            });
        });

        function removeItem(btn) {
            // Prevent removing the last menu item
            if (document.querySelectorAll('.menu-item').length > 1) {
                btn.closest('.menu-item').remove();
            } else {
                alert('At least one menu item is required.');
            }
        }
        $(document).ready(function() {
    let menuItemCounter = 0;

    // Handle edit button click
    $(document).on('click', '.edit-menu', function(e) {
        e.preventDefault();
        const menuId = $(this).data('menu-id');

        if (!menuId) return;

        // Clear previous menu items
        $('#editMenuItemsContainer').empty();

        $.ajax({
            url: `/menu/${menuId}/edit`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    const menu = response.menu;

                    // Set main menu details
                    $('#edit_menu_id').val(menu.id);
                    $('#edit_mealTime').val(menu.name);
                    $('#edit_menu_description').val(menu.description);
                    $('#edit_menu_status').prop('checked', menu.is_active == 1);

                    // Load menu items
                    if (menu.menu_items && menu.menu_items.length > 0) {
                        menu.menu_items.forEach((item, index) => {
                            addEditMenuItem(item, index);
                        });
                    } else {
                        // Add an empty menu item if none exist
                        addEditMenuItem(null, 0);
                    }

                    // Set meal time select value
                    console.log('Meal Time value for edit:', menu.name);
                    // $('#edit_mealTime').val(menu.name);

                    $('#editMenuModal').modal('show');
                } else {
                    alert('Failed to load menu data. Please try again.');
                }
            },
            error: function(xhr) {
                console.error('Error fetching menu data:', xhr.responseText);
                alert('Failed to load menu data. Please try again.');
            }
        });
    });

    // Dietary options from PHP config
    var dietaryFood      = @json(config('dietary.food'));
    var dietaryBeverage  = @json(config('dietary.beverage'));

    function buildDietaryCheckboxes(options, index, suitableFor) {
        return options.map(function(option) {
            var checked = suitableFor.includes(option) ? 'checked' : '';
            var label = option.charAt(0).toUpperCase() + option.slice(1);
            return `<label class="dietary-pill ${checked ? 'dietary-pill-checked' : ''}">
                <input type="checkbox" name="menu_items[${index}][suitable_for][]" value="${option}" class="dietary-cb" ${checked}>
                <span>${label}</span>
            </label>`;
        }).join('');
    }

    // Function to add menu item in edit form
    function addEditMenuItem(item, index) {
        var suitableFor = [];
        if (item && item.dietary_info && item.dietary_info.suitable_for) {
            suitableFor = item.dietary_info.suitable_for;
        }

        var category   = item ? item.category : '';
        var foodDisplay = category === 'Food'     ? 'block' : 'none';
        var bevDisplay  = category === 'Beverage' ? 'block' : 'none';

        var foodCheckboxes = buildDietaryCheckboxes(dietaryFood,     index, suitableFor);
        var bevCheckboxes  = buildDietaryCheckboxes(dietaryBeverage, index, suitableFor);

        var imageHtml = '';
        if (item && item.image) {
            imageHtml = `<div class="mt-2 w-100">
                <img src="${item.image}" alt="${item.name}" class="img-thumbnail" style="height:80px;width:80px;object-fit:cover;">
                <input type="hidden" name="menu_items[${index}][existing_image]" value="${item.image}">
            </div>`;
        }

        var html = `
        <div class="menu-item row mb-3 align-items-start" data-index="${index}">
            <input type="hidden" name="menu_items[${index}][id]" value="${item ? item.id : ''}">

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Item Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-3" name="menu_items[${index}][name]"
                       placeholder="Item Name" value="${item ? item.name : ''}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Price (RWF) <span class="text-danger">*</span></label>
                <input type="number" class="form-control rounded-3" name="menu_items[${index}][price]"
                       placeholder="0" value="${item ? item.price : ''}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Image</label>
                <input type="file" class="form-control rounded-3" name="menu_items[${index}][image]"
                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                ${imageHtml}
            </div>

            <div class="col-md-4 mt-2">
                <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                <select class="form-control rounded-3" name="menu_items[${index}][category]">
                    <option value="">Select Category</option>
                    <option value="Food"     ${category === 'Food'     ? 'selected' : ''}>Food</option>
                    <option value="Beverage" ${category === 'Beverage' ? 'selected' : ''}>Beverage</option>
                </select>
            </div>

            <div class="col-12 mt-2 food-dietary-options" style="display:${foodDisplay};">
                <label class="form-label small fw-semibold mb-2">Dietary Options</label>
                <div class="d-flex flex-wrap gap-2">${foodCheckboxes}</div>
            </div>
            <div class="col-12 mt-2 beverage-dietary-options" style="display:${bevDisplay};">
                <label class="form-label small fw-semibold mb-2">Dietary Options</label>
                <div class="d-flex flex-wrap gap-2">${bevCheckboxes}</div>
            </div>
            <div class="col-md-3 mt-2">
                <label class="form-label small fw-semibold">Availability</label>
                <select class="form-control rounded-3" name="menu_items[${index}][is_available]">
                    <option value="1" ${!item || item.is_available == 1 ? 'selected' : ''}>Available</option>
                    <option value="0" ${item && item.is_available == 0 ? 'selected' : ''}>Not Available</option>
                </select>
            </div>
            <div class="col-md-2 mt-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-edit-item-btn w-100" onclick="removeEditItem(this)">
                    <i class="fas fa-trash-alt me-1"></i> Remove
                </button>
            </div>
        </div>
        <hr>`;

        $('#editMenuItemsContainer').append(html);
    }

    // Add menu item button for edit form
    $('#editAddMenuItem').click(function() {
        const index = $('#editMenuItemsContainer .menu-item').length;
        addEditMenuItem(null, index);
    });

    // Remove menu item in edit form
    window.removeEditItem = function(btn) {
        // Prevent removing the last menu item
        if ($('#editMenuItemsContainer .menu-item').length > 1) {
            $(btn).closest('.menu-item').next('hr').remove();
            $(btn).closest('.menu-item').remove();
        } else {
            alert('At least one menu item is required.');
        }
    };

    // Update Menu
    $('#editMenuForm').submit(function(e) {
        e.preventDefault();
        const menuId = $('#edit_menu_id').val();

        // Build FormData manually to avoid submitting unchecked checkboxes as empty strings
        var formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('name', $('#edit_mealTime').val());
        formData.append('description', $('#edit_menu_description').val());
        formData.append('is_active', $('#edit_menu_status').is(':checked') ? 1 : 0);

        $('#editMenuItemsContainer .menu-item').each(function(itemIndex) {
            var $item = $(this);

            // Hidden + text/number/select fields
            $item.find('input:not([type="checkbox"]):not([type="file"]), select').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                formData.append(name, $(this).val());
            });

            // File input
            var fileInput = $item.find('input[type="file"]')[0];
            if (fileInput && fileInput.files[0]) {
                var fileName = fileInput.getAttribute('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                formData.append(fileName, fileInput.files[0]);
            }

            // Only checked checkboxes
            $item.find('input[type="checkbox"]:checked').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, `[${itemIndex}]`);
                formData.append(name, $(this).val());
            });
        });

        $.ajax({
            url: `/menu/${menuId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#updateMenuBtn')
                    .html("<i class='fa fa-spin fa-spinner'></i> Updating...")
                    .prop('disabled', true);
            },
            success: function(response) {
                if (response.status === 200) {
                    $('#edit-message-container').html(
                        `<div class="alert alert-success d-flex align-items-center gap-2 mx-3 mt-2 mb-0 rounded-3">
                            <i class="fas fa-check-circle"></i>
                            <span>Menu updated successfully!</span>
                        </div>`
                    );
                    setTimeout(function() {
                        $('#editMenuModal').modal('hide');
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                console.error('Error updating menu:', xhr.responseText);
                let errorMessage = 'Failed to update menu. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }

                $('#edit-message-container').html(
                    `<div class="alert alert-danger alert-dismissible">${errorMessage}
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>`
                );

                $('#updateMenuBtn')
                    .html("Update")
                    .prop('disabled', false);
            }
        });
    });

    // Delete Menu functionality
    $(document).on('click', '.delete-menu', function() {
        const menuId = $(this).data('menu-id');
        console.log('Delete Menu ID:', menuId);

        if (!menuId) {
            console.error('No menu ID found');
            return;
        }

        // Set the current menu ID in the delete modal
        $('#deleteMenuModal').data('menu-id', menuId);
        $('#deleteMenuModal').modal('show');
    });

    // Confirm delete action
    $('#confirmDeleteMenu').click(function() {
        const menuId = $('#deleteMenuModal').data('menu-id');

        $.ajax({
            url: `/menus/${menuId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#deleteMenuModal').modal('hide');
                alert('Menu deleted successfully!');
                location.reload();
            },
            error: function(xhr) {
                console.error('Error deleting menu:', xhr.responseText);
                alert('Failed to delete menu. Please try again.');
            }
        });
    });

    // Toggle Status
    $('.status-toggle').change(function() {
        const menuId = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `/menus/${menuId}/status`,
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                is_active: isActive
            },
            success: function(response) {
                console.log('Status updated successfully');
            },
            error: function(xhr) {
                console.error('Error updating status:', xhr.responseText);
                // Revert toggle if there was an error
                $(this).prop('checked', !isActive);
            }
        });
    });

    // View Menu functionality
    $(document).on('click', '.view-menu', function(e) {
        e.preventDefault();
        const menuId = $(this).data('menu-id');
        if (!menuId) return;

        // Reset and open modal with spinner
        $('#viewMenuModalLabel').text('Menu Items');
        $('#viewMenuModalSubtitle').text('Loading…');
        $('#viewMenuItemsContainer').html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary mb-2" role="status"></div>
                <p class="text-muted small">Loading items…</p>
            </div>`);
        $('#viewMenuModal').modal('show');

        $.ajax({
            url: `/menu/${menuId}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.status !== 200) {
                    $('#viewMenuItemsContainer').html('<div class="col-12 text-center text-danger py-4">Failed to load items.</div>');
                    return;
                }
                const menu = response.menu;
                $('#viewMenuModalLabel').text(menu.name);
                $('#viewMenuModalSubtitle').text(`${menu.menu_items.length} item${menu.menu_items.length !== 1 ? 's' : ''}`);

                if (!menu.menu_items || menu.menu_items.length === 0) {
                    $('#viewMenuItemsContainer').html(`
                        <div class="col-12 text-center py-5">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                 style="width:64px;height:64px;background:#f1f5f9;">
                                <i class="fas fa-utensils text-muted fa-lg"></i>
                            </div>
                            <p class="text-muted fw-semibold mb-1">No items yet</p>
                            <p class="text-muted small">Add items to this menu using the Edit button.</p>
                        </div>`);
                    return;
                }

                let html = '';
                menu.menu_items.forEach(function(item) {
                    const img = item.image
                        ? `<img src="${item.image}" alt="${item.name}"
                               class="w-100 rounded-3 object-fit-cover"
                               style="height:160px;">`
                        : `<div class="w-100 rounded-3 d-flex align-items-center justify-content-center"
                               style="height:160px;background:#f1f5f9;">
                               <i class="fas fa-utensils fa-2x" style="color:#cbd5e1;"></i>
                           </div>`;

                    const avail = item.is_available == 1
                        ? `<span class="badge rounded-pill toggle-availability-badge"
                               data-item-id="${item.id}" data-available="1"
                               style="background:#dcfce7;color:#16a34a;cursor:pointer;font-size:0.7rem;">
                               <i class="fas fa-circle me-1" style="font-size:0.45rem;"></i>Available
                           </span>`
                        : `<span class="badge rounded-pill toggle-availability-badge"
                               data-item-id="${item.id}" data-available="0"
                               style="background:#f1f5f9;color:#64748b;cursor:pointer;font-size:0.7rem;">
                               <i class="fas fa-circle me-1" style="font-size:0.45rem;"></i>Unavailable
                           </span>`;

                    const catColor = item.category === 'Food'
                        ? 'background:#fef9c3;color:#854d0e;'
                        : item.category === 'Beverage'
                            ? 'background:#dbeafe;color:#1e40af;'
                            : 'background:#f1f5f9;color:#64748b;';

                    const catBadge = item.category
                        ? `<span class="badge rounded-pill" style="${catColor}font-size:0.7rem;">${item.category}</span>`
                        : '';

                    const dietaryOpts = parseDietaryOptions(item.dietary_info);
                    const dietaryHtml = dietaryOpts.length
                        ? `<div class="mt-2 d-flex flex-wrap gap-1">
                               ${dietaryOpts.map(d => `<span class="badge rounded-pill" style="background:#ede9fe;color:#7c3aed;font-size:0.65rem;">${d.charAt(0).toUpperCase()+d.slice(1)}</span>`).join('')}
                           </div>`
                        : '';

                    html += `
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            ${img}
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                    <h6 class="fw-bold text-dark mb-0" style="font-size:0.9rem;line-height:1.3;">${item.name}</h6>
                                    ${avail}
                                </div>
                                ${item.description ? `<p class="text-muted mb-2" style="font-size:0.76rem;line-height:1.4;">${item.description}</p>` : ''}
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold" style="color:#0f3039;font-size:0.9rem;">RWF ${Math.round(parseFloat(item.price||0)).toLocaleString()}</span>
                                    ${catBadge}
                                </div>
                                ${dietaryHtml}
                            </div>
                        </div>
                    </div>`;
                });

                $('#viewMenuItemsContainer').html(html);
            },
            error: function() {
                $('#viewMenuItemsContainer').html('<div class="col-12 text-center text-danger py-4"><i class="fas fa-exclamation-circle me-1"></i>Failed to load items. Please try again.</div>');
            }
        });
    });

    // Add click handler for toggling availability
    $(document).on('click', '.toggle-availability-badge', function() {
        var btn = $(this);
        var itemId = btn.data('item-id');
        var currentStatus = btn.data('available');
        var newStatus = currentStatus == 1 ? 0 : 1;
        $.ajax({
            url: `/menu-items/${itemId}/toggle-status`,
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { is_available: newStatus },
            success: function(response) {
                if (response.status === 200) {
                    btn.data('available', newStatus);
                    if (newStatus == 1) {
                        btn.removeClass('bg-secondary').addClass('bg-success').text('Available');
                    } else {
                        btn.removeClass('bg-success').addClass('bg-secondary').text('Not Available');
                    }
                } else {
                    alert('Failed to update status.');
                }
            },
            error: function() {
                alert('Failed to update status.');
            }
        });
    });
});

// Load all menus with items when View All Menus modal is opened
$('#viewAllMenusModal').on('show.bs.modal', function () {
    $('#viewAllMenusLoader').show();
    $('#viewAllMenusContent').empty();

    $.ajax({
        url: '{{ route("menus.allWithItems") }}',
        method: 'GET',
        success: function(response) {
            $('#viewAllMenusLoader').hide();
            if (response.status !== 200 || !response.menus.length) {
                $('#viewAllMenusContent').html('<p class="text-muted text-center py-4">No menus found.</p>');
                return;
            }

            let html = '';
            response.menus.forEach(function(menu) {
                const statusBadge = menu.is_active
                    ? '<span class="badge bg-success ms-2" style="font-size:0.65rem;">Active</span>'
                    : '<span class="badge bg-secondary ms-2" style="font-size:0.65rem;">Inactive</span>';

                let itemsHtml = '';
                if (menu.menu_items.length === 0) {
                    itemsHtml = '<p class="text-muted small fst-italic">No items in this menu yet.</p>';
                } else {
                    menu.menu_items.forEach(function(item) {
                        const img = item.image
                            ? `<img src="${item.image}" alt="${item.name}" class="rounded-3 flex-shrink-0 object-fit-cover" style="width:56px;height:56px;">`
                            : `<div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:#f1f5f9;"><i class="fas fa-utensils text-muted"></i></div>`;

                        const avail = item.is_available == 1
                            ? '<span class="badge rounded-pill" style="background:#dcfce7;color:#16a34a;font-size:0.6rem;">Available</span>'
                            : '<span class="badge rounded-pill bg-secondary" style="font-size:0.6rem;">Unavailable</span>';

                        const dietary = formatDietaryInfo(item.dietary_info);

                        itemsHtml += `
                            <div class="col-md-6 col-xl-4">
                                <div class="d-flex align-items-start gap-3 p-3 rounded-3 border bg-white h-100">
                                    ${img}
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-semibold text-dark" style="font-size:0.85rem;">${item.name}</span>
                                            ${avail}
                                        </div>
                                        ${item.description ? `<p class="text-muted mb-1" style="font-size:0.75rem;line-height:1.3;">${item.description}</p>` : ''}
                                        <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                                            <span class="fw-bold text-primary" style="font-size:0.8rem;">RWF ${Math.round(parseFloat(item.price||0)).toLocaleString()}</span>
                                            ${item.category ? `<span class="badge rounded-pill" style="background:#ede9fe;color:#7c3aed;font-size:0.6rem;">${item.category}</span>` : ''}
                                            ${dietary ? `<span class="text-muted" style="font-size:0.7rem;">${dietary}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });
                }

                html += `
                    <div class="menu-section mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3 me-2 flex-shrink-0"
                                 style="width:36px;height:36px;background:#f0fdf4;">
                                <i class="fas fa-book-open" style="color:#16a34a;font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">${menu.name} ${statusBadge}</h5>
                                ${menu.description ? `<p class="text-muted mb-0" style="font-size:0.78rem;">${menu.description}</p>` : ''}
                            </div>
                        </div>
                        <div class="row g-3">${itemsHtml}</div>
                        <hr class="my-4">
                    </div>`;
            });

            $('#viewAllMenusContent').html(html);
        },
        error: function() {
            $('#viewAllMenusLoader').hide();
            $('#viewAllMenusContent').html('<p class="text-danger text-center py-4">Failed to load menus. Please try again.</p>');
        }
    });
});

function printAllMenus() {
    const printContent = document.getElementById('printableMenus').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = `
        <div class="container p-4">
            <h1 class="text-center mb-4">Restaurant Menu</h1>
            ${printContent}
        </div>
    `;

    window.print();
    document.body.innerHTML = originalContent;

    // Reinitialize necessary JavaScript after restoring content
    location.reload();
}

// Returns a flat array of dietary option strings from the dietary_info object
function parseDietaryOptions(dietaryInfo) {
    if (!dietaryInfo) return [];
    try {
        const obj = typeof dietaryInfo === 'string' ? JSON.parse(dietaryInfo) : dietaryInfo;
        if (Array.isArray(obj)) return obj;
        if (obj.suitable_for && Array.isArray(obj.suitable_for)) return obj.suitable_for;
        // flatten any arrays in the object
        return Object.values(obj).reduce((acc, v) => acc.concat(Array.isArray(v) ? v : [v]), []);
    } catch (e) {
        return [];
    }
}

// Legacy helper kept for backward compat (row expand cards etc.)
function formatDietaryInfo(dietaryInfo) {
    const opts = parseDietaryOptions(dietaryInfo);
    return opts.length ? opts.map(o => o.charAt(0).toUpperCase() + o.slice(1)).join(', ') : '-';
}

$(document).on('change', 'select[name^="menu_items"][name$="[category]"]', function() {
    var $row = $(this).closest('.menu-item');
    var category = $(this).val();
    $row.find('.food-dietary-options').toggle(category === 'Food');
    $row.find('.beverage-dietary-options').toggle(category === 'Beverage');
    // Uncheck all dietary options when category changes
    $row.find('.dietary-cb').prop('checked', false).closest('.dietary-pill').removeClass('dietary-pill-checked');
});

$(document).on('change', '.dietary-cb', function() {
    $(this).closest('.dietary-pill').toggleClass('dietary-pill-checked', this.checked);
});

  </script>
@endsection
