@extends('layouts.app')
@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">

        <!-- Breadcrumb -->
        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('roles') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Manage Roles</a>
                </li>
            </ul>
        </div>

        <div class="content-inner">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="font-dmsans fw-bold text-primary-v1 mb-0">Manage Roles</h4>
                        <p class="text-muted small mb-0">Define roles and assign permissions</p>
                    </div>
                    <button class="btn btn-primary rounded-3 px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>Add Role
                    </button>
                </div>

                <div class="search-container position-relative mb-3" style="max-width:320px;">
                    <i class="fas fa-search position-absolute text-muted" style="top:50%;left:12px;transform:translateY(-50%);font-size:0.8rem;"></i>
                    <input type="search" class="custom-search form-control ps-4" placeholder="Search roles…" aria-controls="rolesTable">
                </div>

                <div class="table-block">
                    <table id="rolesTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th><span>Role Name</span></th>
                                <th><span>Permissions</span></th>
                                <th class="action-cell text-center"><span>Action</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:32px;height:32px;background:#ede9fe;">
                                            <i class="fas fa-user-shield" style="font-size:0.75rem;color:#7c3aed;"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(is_array($role->permissions) ? $role->permissions : explode(',', $role->permissions ?? '') as $perm)
                                            @if(trim($perm))
                                            <span class="badge rounded-pill px-2 py-1" style="background:#f0fdf4;color:#166534;font-size:0.7rem;border:1px solid #bbf7d0;">
                                                {{ trim($perm) }}
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button class="btn btn-sm btn-light rounded-3 border edit-role-btn px-2 py-1"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-permissions="{{ is_array($role->permissions) ? implode(',', $role->permissions) : $role->permissions }}"
                                                title="Edit">
                                            <i class="fas fa-pen text-primary" style="font-size:0.75rem;"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light rounded-3 border delete-role-btn px-2 py-1"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash text-danger" style="font-size:0.75rem;"></i>
                                        </button>
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

<!-- ── Add Role Modal ─────────────────────────────────────────── -->
<div class="modal fade" id="addRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:#ede9fe;">
                        <i class="fas fa-user-shield" style="color:#7c3aed;font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="addRoleLabel">Add New Role</h5>
                        <p class="text-muted small mb-0">Define a role and assign permissions</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="addRoleMessage"></div>
            <form id="addRoleForm">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" name="name" id="addRoleName"
                               placeholder="e.g. Manager, Chef, Waiter…">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Permissions <span class="text-danger">*</span></label>
                        <div class="border rounded-3 p-3" style="background:#fafafa;">
                            <div class="row g-2">
                                @foreach ($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check d-flex align-items-center gap-2 p-2 rounded-3 perm-check-wrap"
                                         style="cursor:pointer;transition:background 0.15s;">
                                        <input class="form-check-input flex-shrink-0" type="checkbox"
                                               name="Permissions[]"
                                               value="{{ $permission->id }}"
                                               id="addPerm_{{ $permission->id }}">
                                        <label class="form-check-label small fw-medium mb-0 w-100" for="addPerm_{{ $permission->id }}" style="cursor:pointer;">
                                            <i class="fas fa-check-circle me-1" style="color:#7c3aed;font-size:0.7rem;"></i>
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-link p-0 small text-primary text-decoration-none" id="addSelectAll">Select all</button>
                            <span class="text-muted small">·</span>
                            <button type="button" class="btn btn-link p-0 small text-muted text-decoration-none" id="addClearAll">Clear</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold" id="addRoleSubmitBtn">
                        <i class="fas fa-save me-1"></i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Edit Role Modal ────────────────────────────────────────── -->
<div class="modal fade" id="editRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:#fef3c7;">
                        <i class="fas fa-pen" style="color:#d97706;font-size:1rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="editRoleLabel">Edit Role</h5>
                        <p class="text-muted small mb-0">Update role name and permissions</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="editRoleMessage"></div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="role_id" id="editRoleId">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3" name="name" id="editRoleName"
                               placeholder="Role name">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Permissions <span class="text-danger">*</span></label>
                        <div class="border rounded-3 p-3" style="background:#fafafa;">
                            <div class="row g-2">
                                @foreach ($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check d-flex align-items-center gap-2 p-2 rounded-3 perm-check-wrap"
                                         style="cursor:pointer;transition:background 0.15s;">
                                        <input class="form-check-input flex-shrink-0" type="checkbox"
                                               name="Permissions[]"
                                               value="{{ $permission->id }}"
                                               id="editPerm_{{ $permission->id }}">
                                        <label class="form-check-label small fw-medium mb-0 w-100" for="editPerm_{{ $permission->id }}" style="cursor:pointer;">
                                            <i class="fas fa-check-circle me-1" style="color:#d97706;font-size:0.7rem;"></i>
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-link p-0 small text-primary text-decoration-none" id="editSelectAll">Select all</button>
                            <span class="text-muted small">·</span>
                            <button type="button" class="btn btn-link p-0 small text-muted text-decoration-none" id="editClearAll">Clear</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-3 px-4 fw-semibold text-white" id="editRoleSubmitBtn">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Delete Role Modal ──────────────────────────────────────── -->
<div class="modal fade" id="deleteRoleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-body px-4 pt-4 pb-3 text-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:60px;height:60px;background:#fee2e2;">
                    <i class="fas fa-trash-alt" style="color:#dc2626;font-size:1.4rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">Delete Role</h5>
                <p class="text-muted small mb-1">You are about to delete the role:</p>
                <p class="fw-bold text-danger mb-3" id="deleteRoleName">—</p>
                <div class="rounded-3 px-3 py-2 small text-start mb-1" style="background:#fef2f2;color:#991b1b;">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Users assigned this role will lose its permissions.
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 justify-content-center">
                <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger rounded-3 px-4 fw-semibold" id="deleteRoleConfirmBtn">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
$(document).ready(function () {

    // ── Checkbox hover highlight ──────────────────────────
    $(document).on('mouseenter', '.perm-check-wrap', function () {
        $(this).css('background', '#f5f3ff');
    }).on('mouseleave', '.perm-check-wrap', function () {
        $(this).css('background', '');
    });

    // ── Select / Clear all helpers ────────────────────────
    $('#addSelectAll').on('click', function () { $('#addRoleModal input[type=checkbox]').prop('checked', true); });
    $('#addClearAll').on('click',   function () { $('#addRoleModal input[type=checkbox]').prop('checked', false); });
    $('#editSelectAll').on('click', function () { $('#editRoleModal input[type=checkbox]').prop('checked', true); });
    $('#editClearAll').on('click',  function () { $('#editRoleModal input[type=checkbox]').prop('checked', false); });

    // ── Add Role ──────────────────────────────────────────
    $('#addRoleForm').on('submit', function (e) {
        e.preventDefault();
        var name = $('#addRoleName').val().trim();
        if (!name) {
            $('#addRoleName').addClass('is-invalid').next('.invalid-feedback').text('Role name is required.');
            return;
        }
        $('#addRoleName').removeClass('is-invalid');

        var $btn = $('#addRoleSubmitBtn');
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving…').prop('disabled', true);

        $.ajax({
            url: "{{ route('create-role') }}",
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.status == 200) {
                    bootstrap.Modal.getInstance(document.getElementById('addRoleModal')).hide();
                    toastr.success('Role created successfully');
                    setTimeout(function () { location.reload(); }, 800);
                } else {
                    $('#addRoleMessage').html('<div class="alert alert-danger mx-4 mt-2 py-2 small">' + (res.message || 'Something went wrong.') + '</div>');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Request failed.';
                $('#addRoleMessage').html('<div class="alert alert-danger mx-4 mt-2 py-2 small">' + msg + '</div>');
            },
            complete: function () {
                $btn.html('<i class="fas fa-save me-1"></i> Create Role').prop('disabled', false);
            }
        });
    });

    // ── Open Edit modal ───────────────────────────────────
    $(document).on('click', '.edit-role-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');
        var perms = String($(this).data('permissions') || '').split(',').map(s => s.trim());

        $('#editRoleId').val(id);
        $('#editRoleName').val(name);
        $('#editRoleModal input[type=checkbox]').each(function () {
            $(this).prop('checked', perms.includes($(this).val()));
        });
        $('#editRoleMessage').html('');
        new bootstrap.Modal(document.getElementById('editRoleModal')).show();
    });

    // ── Submit Edit Role ──────────────────────────────────
    $('#editRoleForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#editRoleId').val();
        var $btn = $('#editRoleSubmitBtn');
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving…').prop('disabled', true);

        $.ajax({
            url: '/roles/' + id,
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.status == 200) {
                    bootstrap.Modal.getInstance(document.getElementById('editRoleModal')).hide();
                    toastr.success('Role updated successfully');
                    setTimeout(function () { location.reload(); }, 800);
                } else {
                    $('#editRoleMessage').html('<div class="alert alert-danger mx-4 mt-2 py-2 small">' + (res.message || 'Something went wrong.') + '</div>');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON?.message || 'Request failed.';
                $('#editRoleMessage').html('<div class="alert alert-danger mx-4 mt-2 py-2 small">' + msg + '</div>');
            },
            complete: function () {
                $btn.html('<i class="fas fa-save me-1"></i> Save Changes').prop('disabled', false);
            }
        });
    });

    // ── Open Delete modal ─────────────────────────────────
    var deleteRoleId = null;
    $(document).on('click', '.delete-role-btn', function () {
        deleteRoleId = $(this).data('id');
        $('#deleteRoleName').text($(this).data('name'));
        new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
    });

    // ── Confirm Delete ────────────────────────────────────
    $('#deleteRoleConfirmBtn').on('click', function () {
        if (!deleteRoleId) return;
        var $btn = $(this);
        $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Deleting…').prop('disabled', true);

        $.ajax({
            url: '/roles/' + deleteRoleId,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                bootstrap.Modal.getInstance(document.getElementById('deleteRoleModal')).hide();
                toastr.success('Role deleted');
                setTimeout(function () { location.reload(); }, 800);
            },
            error: function () {
                toastr.error('Failed to delete role.');
            },
            complete: function () {
                $btn.html('<i class="fas fa-trash me-1"></i> Delete').prop('disabled', false);
            }
        });
    });

    // ── Reset add modal on close ──────────────────────────
    document.getElementById('addRoleModal').addEventListener('hidden.bs.modal', function () {
        $('#addRoleForm')[0].reset();
        $('#addRoleName').removeClass('is-invalid');
        $('#addRoleMessage').html('');
    });
});
</script>
@endsection
