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
                    <a href="{{route('manage-users')}}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Users">Manage Users</a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-inner">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Manage Users</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Users" aria-controls="manageUsersTable">
                        </div>
                    </div>

                    <div class="btn-options mt-3 mt-xl-0">
                        <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 filter-btn" title="Filter">Filter</a>
                        @if(auth()->user()->role !== 'admin')
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#addUser" title="Add User">Add User</a>
                        @endif
                    </div>
                </div>
                <div class="filter-col-options">
                    <div class="filter-col-options-inner pt-2 mt-1">
                        <form>
                            <div class="row">
                                <div class="col-md-6 col-xl-3">
                                    <div class="form-group m-0 mb-3 mb-xl-0 normal-select">
                                        <label for="documentType" class="form-label visually-hidden">Document Type</label>
                                        <select class="form-select" id="documentType">
                                            <option selected disabled>Select Roles</option>
                                            <option value="Document 1">Restaurant</option>
                                            <option value="Document 2">Document 2</option>
                                            <option value="Document 3">Document 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="form-group m-0 mb-3 mb-xl-0 normal-select">
                                        <label for="chapter" class="form-label visually-hidden">Chapter</label>
                                        <select class="form-select" id="chapter">
                                            <option selected disabled>Restaurant</option>
                                            <option value="Chapter 1">Chapter 1</option>
                                            <option value="Chapter 2">Chapter 2</option>
                                            <option value="Chapter 3">Chapter 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="form-group m-0 mb-3 mb-xl-0 normal-select">
                                        <label for="state" class="form-label visually-hidden">State</label>
                                        <select class="form-select" id="state">
                                            <option selected disabled>Address</option>
                                            <option value="State 1">State 1</option>
                                            <option value="State 2">State 2</option>
                                            <option value="State 3">State 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="form-group m-0 mb-3 mb-xl-0 normal-select">
                                        <label for="union" class="form-label visually-hidden">Union</label>
                                        <select class="form-select" id="union">
                                            <option selected disabled>Select by Date</option>
                                            <option value="Union 1">Union 1</option>
                                            <option value="Union 2">Union 2</option>
                                            <option value="Union 3">Union 3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-block">
                    <table id="manageUsersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <span>Name</span>
                                </th>
                                <th>
                                    <span>Email</span>
                                </th>
                                <th>
                                    <span>User number</span>
                                </th>
                                <th>
                                    <span>Restaurant name</span>
                                </th>
                                <th>
                                    <span>Phone number</span>
                                </th>
                                <th>
                                    <span>Email</span>
                                </th>
                                <th>
                                    <span>Address</span>
                                </th>
                                <th>
                                    <span>Role</span>
                                </th>
                                <th>
                                    <span>Registered At</span>
                                </th>
                                <th>
                                    <span>Web url</span>
                                </th>
                                <th>
                                    <span>Request</span>
                                </th>
                                <th>
                                    <span>Status</span>
                                </th>
                                @if(auth()->user()->role !== 'admin')
                                <th class="action-cell text-center">
                                    <span>Action</span>
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)


                            <tr>
                                <td>
                                    <span>{{$user->first_name.' '.$user->last_name}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span>{{ $user->phone_number }}</span>
                                </td>
                                <td>
                                    <span>{{ $user->restaurant_name}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->restaurant_phone}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->restaurant_email}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->restaurant_address}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->restaurant_position?? Str::title(str_replace('_', ' ',$user->role))}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->created_at->timezone(auth()->user()->timezone ?? session('user_timezone') ?? config('app.timezone'))->format('d/m/Y H:i:s') }}</span>
                                </td>
                                <td>
                                    <span>{{ $user->website??''}}</span>
                                </td>
                                <td>
                                    <div class="request-select normal-select">
                                        <select class="form-select" aria-label="Request" id="requestType">
                                            <option selected disabled>Please Select</option>
                                            <option value="{{ $user->email }}" >Send a Welcome Email</option>
                                            <option value="{{ $user->email }}">Send Reset Password Link</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    @php
                                    $currentUser = auth()->user();
                                    $currentUserRole = $currentUser->role;
                                    $targetUserRole = $user->role;

                                    $canToggle = false;

                                    // Prevent self-disable
                                    if ($currentUser->id !== $user->id) {
                                        if ($currentUserRole === 'admin' && $targetUserRole === 'restaurant_owner') {
                                            $canToggle = true;
                                        } elseif ($currentUserRole === 'restaurant_owner' && $user->id !== $currentUser->id) {
                                            $canToggle = true;
                                        } elseif ($currentUserRole === 'manager' && $targetUserRole !== 'restaurant_owner') {
                                            $canToggle = true;
                                        }
                                    }
                                @endphp

                                <div class="toggle-switch d-flex align-items-center">
                                    <div class="toggle-button toggle-front d-flex align-items-center position-relative">
                                        <label for="status{{ $user->id }}" class="form-check-label font-dmsans text-primary-v1 visually-hidden">Status</label>

                                        <label class="switch {{ $canToggle ? '' : 'switch-disabled' }}">
                                            <input type="checkbox"
                                                   id="status{{ $user->id }}"
                                                   class="status-toggle"
                                                   data-id="{{ $user->id }}"
                                                   {{ $user->status == 1 ? 'checked' : '' }}
                                                   {{ $canToggle ? '' : 'disabled' }}>
                                            <span class="slider"></span>
                                            <span class="active font-dmsans fw-medium">Active</span>
                                            <span class="inactive font-dmsans fw-medium">Inactive</span>
                                        </label>
                                    </div>
                                </div>
                                </td>
                                @if(auth()->user()->role !== 'admin')
                                <td class="action-cell text-center">
                                    <div class="action-col position-relative d-inline-block">
                                        <a href="javascript:;" class="p-1" data-bs-toggle="popover" data-bs-placement="top">
                                            <svg class="action-icon cursor-pointer" width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                                                <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                                                <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                                            </svg>
                                        </a>
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

<!-- Table Action Cell -->
<div class="popover-content" data-name="table-action-btn">
    <div class="action-menu">
        <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 edit-action" data-bs-toggle="modal" data-bs-target="#editUser" data-user-id="{{ $user->id }}" title="Edit">Edit</a>
            </li>
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-action" data-bs-toggle="modal" data-bs-target="#deleteUser" title="Delete">Delete</a>
            </li>
        </ul>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:#dbeafe;">
                        <i class="fas fa-user-plus" style="color:#2563eb;font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="addUserLabel">Add New User</h5>
                        <p class="text-muted small mb-0">Fill in the details to create a new team member</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="message-container-login"></div>
            <form action="{{ route('create-employee') }}" method="POST" id="addUserForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="d-flex justify-content-center mb-3">
                    <div class="position-relative" style="width:90px;height:90px;">
                        <div id="addAvatarIcon" class="rounded-circle d-flex align-items-center justify-content-center" style="width:90px;height:90px;background:#ede9fe;border:3px solid #c4b5fd;">
                            <i class="fas fa-user-circle" style="font-size:2.8rem;color:#7c3aed;"></i>
                        </div>
                        <img id="addAvatarPreview" src="" alt="" class="rounded-circle object-fit-cover d-none" style="width:90px;height:90px;border:3px solid #c4b5fd;position:absolute;top:0;left:0;">
                        <label for="profilePicture" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:28px;height:28px;background:#184C55;border:2px solid #fff;cursor:pointer;" title="Upload photo">
                            <i class="fas fa-camera text-white" style="font-size:0.65rem;"></i>
                        </label>
                        <input class="d-none" id="profilePicture" type="file" accept="image/*" name="profile_picture">
                    </div>
                </div>
                <div class="modal-form">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">First name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter First Name" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Last name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter Last Name" name="last_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                    <input type="email" class="form-control rounded-3" id="userEmail" placeholder="Enter Email" name="email">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control rounded-3" id="userPhone" placeholder="Enter Phone" name="phone_number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">User Role <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role" name="position">
                                        <option selected disabled>Select user role</option>
                                        <option value="Manager" >Manager</option>
                                        <option value="Chef">Chef</option>
                                        <option value="Waiter">Waiter</option>
                                        <option value="Cashier">Cashier</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Permission <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role" name="permissions[]"  multiple="multiple" id="Permissions">

                                        @foreach ($permissions as $key => $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">

                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="active" name="is_active" value="1" class="input-radio">
                                            <label for="active" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio p-0 m-0">
                                            <input type="radio" id="inactive" name="is_inactive" value="0" class="input-radio">
                                            <label for="inactive" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                    <i class="fas fa-user-plus me-1"></i> Create User
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:#fef3c7;">
                        <i class="fas fa-user-edit" style="color:#d97706;font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="editUserLabel">Edit User</h5>
                        <p class="text-muted small mb-0">Update user details and permissions</p>
                    </div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="edit-message-container"></div>
            <form id="editUserForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="position-relative" style="width:90px;height:90px;">
                            <div id="editAvatarIcon" class="rounded-circle d-flex align-items-center justify-content-center" style="width:90px;height:90px;background:#ede9fe;border:3px solid #c4b5fd;">
                                <i class="fas fa-user-circle" style="font-size:2.8rem;color:#7c3aed;"></i>
                            </div>
                            <img id="editAvatarPreview" src="" alt="" class="rounded-circle object-fit-cover d-none" style="width:90px;height:90px;border:3px solid #c4b5fd;position:absolute;top:0;left:0;">
                            <label for="edit_profile_picture" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:28px;height:28px;background:#184C55;border:2px solid #fff;cursor:pointer;" title="Upload photo">
                                <i class="fas fa-camera text-white" style="font-size:0.65rem;"></i>
                            </label>
                            <input class="d-none" id="edit_profile_picture" type="file" accept="image/*" name="profile_picture">
                        </div>
                    </div>
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_first_name" class="form-label">First name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="edit_first_name" placeholder="Enter First Name" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_last_name" class="form-label">Last name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="edit_last_name" placeholder="Enter Last Name" name="last_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_email" class="form-label">Email Address <span class="asterik">*</span></label>
                                    <input type="email" class="form-control rounded-3" id="edit_email" placeholder="Enter Email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_phone_number" class="form-label">Phone</label>
                                    <input type="text" class="form-control rounded-3" id="edit_phone_number" placeholder="Enter Phone" name="phone_number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_position" class="form-label">User Role <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role" name="position" id="edit_position">
                                        <option selected disabled>Select user role</option>
                                        <option value="Restaurant_owner">Restaurant Owner</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Chef">Chef</option>
                                        <option value="Waiter">Waiter</option>
                                        <option value="Cashier">Cashier</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_permissions" class="form-label">Permission <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role" name="permissions[]" multiple="multiple" id="edit_permissions">
                                        @foreach ($permissions as $key => $permission)
                                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="edit_active" name="is_active" value="1" class="input-radio">
                                            <label for="edit_active" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio p-0 m-0">
                                            <input type="radio" id="edit_inactive" name="is_active" value="0" class="input-radio">
                                            <label for="edit_inactive" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning rounded-3 px-4 fw-semibold text-white" id="updateUserBtn">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-body px-4 pt-4 pb-3 text-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:60px;height:60px;background:#fee2e2;">
                    <i class="fas fa-user-times" style="color:#dc2626;font-size:1.4rem;"></i>
                </div>
                <h5 class="fw-bold mb-1">Delete User</h5>
                <p class="text-muted small mb-3">Are you sure you want to delete this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 justify-content-center">
                <button type="button" class="btn btn-light rounded-3 px-4 border" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger rounded-3 px-4 fw-semibold">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Welcome email User Modal -->
<div class="modal fade" id="welcomeEmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteUserLabel">Send Welcome Email</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <form action="" method="POST" id="welcomeEmailForm">
                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to sent welcome wish to ?</p>
                            <input type="hidden" name="email" value="" id="welcomeEmailId">
                            <div class="footer-btns">
                                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Yes</button>
                                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start pt-0">
            </div>
        </div>
    </div>
</div>
<!-- Reset password email User Modal -->
<div class="modal fade" id="resetEmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteUserLabel">Send reset password link</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <form action="" method="POST" id="resetPasswordForm">

                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to send link to ?</p>
                            <input type="hidden" name="email" value="" id="resetForEmail">
                            <div class="footer-btns">
                                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Yes</button>
                                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start pt-0">
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('script')
<script>
   $(document).ready(function() {


            //Bootstrap Duallistbox
            $('#Permissions').bootstrapDualListbox({
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false
            });
            // Initialize Dual Listbox for edit permissions
            $('#edit_permissions').bootstrapDualListbox({
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false
            });
// To access the non-selected list container (if needed)
var nonSelectedList = $('[id="bootstrap-duallistbox-nonselected-list_Permissions[]"]');

// To access the selected list container (if needed)
var selectedList = $('[id="bootstrap-duallistbox-selected-list_Permissions[]"]');
    // Modal selection logic
    $("#requestType").change(function() {
        var selectedValue = $(this).val();
        var selectedText = $(this).find("option:selected").text();

        $(".modal").modal('hide');

        if (selectedText === "Send a Welcome Email") {
            $("#welcomeEmailId").val(selectedValue);
            $("#welcomeEmail").modal('show');
        } else if (selectedText === "Send Reset Password Link") {
            $("#resetForEmail").val(selectedValue);
            $("#resetEmail").modal('show');
        }
    });

    // Form submission with validation
    $("#resetPasswordForm").validate({
        submitHandler: function(form) {
            var form_data = new FormData(form); // Capture all form fields

            // Set up CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Make AJAX request
            $.ajax({
                url: "{{ route('forgot-password-post') }}",
                type: "POST",
                dataType: "json",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function() {
                    $('#send_btn').html("<i class='fa fa-spin fa-spinner'></i> Submit");
                    $('#loader').show();
                    $('#send_btn').prop('disabled', true);
                    $('.alert-dismissible').hide();
                },
                success: function(result) {
                    console.log(result.status);

                    if (result.status == '201') {
                        $('#message-container-login').html(
                            '<div class="alert alert-success alert-dismissible">Logged In! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                        );

                        setTimeout(function() {
                            window.location.href = result.redirect || '/manage-users';
                        }, 1500);
                    } else {
                        $('#message-container-login').html(
                            '<div class="alert alert-danger alert-dismissible">' +
                            (result.msg || 'An error occurred. Please try again.') +
                            ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                        );

                        $('#send_btn').html("Submit"); // Fixed to use same button ID
                        $('#loader').hide();
                        $('#send_btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    $('#message-container-login').html(
                        '<div class="alert alert-danger alert-dismissible">Request failed. Please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                    );
                    $('#send_btn').html("Submit");
                    $('#loader').hide();
                    $('#send_btn').prop('disabled', false);
                }
            });
        }
    });

    $("#welcomeEmailForm").validate({
        submitHandler: function(form) {
            var form_data = new FormData(form); // Capture all form fields

            // Set up CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Make AJAX request
            $.ajax({
                url: "{{ route('welcome-post') }}",
                type: "POST",
                dataType: "json",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function() {
                    $('#send_btn').html("<i class='fa fa-spin fa-spinner'></i> Submit");
                    $('#loader').show();
                    $('#send_btn').prop('disabled', true);
                    $('.alert-dismissible').hide();
                },
                success: function(result) {
                    console.log(result.status);

                    if (result.status == '201') {
                        $('#message-container-login').html(
                            '<div class="alert alert-success alert-dismissible">Logged In! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                        );

                        setTimeout(function() {
                            window.location.href = result.redirect || '/manage-users';
                        }, 1500);
                    } else {
                        $('#message-container-login').html(
                            '<div class="alert alert-danger alert-dismissible">' +
                            (result.msg || 'An error occurred. Please try again.') +
                            ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                        );

                        $('#send_btn').html("Submit"); // Fixed to use same button ID
                        $('#loader').hide();
                        $('#send_btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    $('#message-container-login').html(
                        '<div class="alert alert-danger alert-dismissible">Request failed. Please try again. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'
                    );
                    $('#send_btn').html("Submit");
                    $('#loader').hide();
                    $('#send_btn').prop('disabled', false);
                }
            });
        }
    });
// Profile picture preview — Add modal
$('#profilePicture').on('change', function() {
    var file = this.files[0];
    if (!file) return;
    var url = URL.createObjectURL(file);
    $('#addAvatarPreview').attr('src', url).removeClass('d-none');
    $('#addAvatarIcon').addClass('d-none');
});
// Profile picture preview — Edit modal
$('#edit_profile_picture').on('change', function() {
    var file = this.files[0];
    if (!file) return;
    var url = URL.createObjectURL(file);
    $('#editAvatarPreview').attr('src', url).removeClass('d-none');
    $('#editAvatarIcon').addClass('d-none');
});
    // Form validation

$('#addUserForm').validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        email: {
            required: true,
            email: true,
        },
        position:{
            required: true,
        },
        phone_number: {
            required: true,
            minlength: 14,
        },

        profile_picture: {
            extension: "jpeg,jpg,png",
            maxsize: 5242880,
        },
       'permissions[]': {
            required: true,
        },
        is_active: {
            required: true,
        },

    },
    messages: {
        first_name: {
            required: "Please enter your first name",
        },
        last_name: {
            required: "Please enter your last name",
        },
        email: {
            required: "Please enter an email address",
            email: "Please enter a valid email address",
        },
        phone_number: {
            required: "Please enter a phone number",
            minlength: "Please enter a valid phone number",
        },

        profile_picture: {
            extension: "Please upload jpg, jpeg, or png files only",
            maxsize: "File size must be less than 5 MB",
        },
        position: {
            required: "Please select a role",
        },
        "permissions[]": {
            required: "Please enter permission",
        },
is_active   : {
            required: "Please select status",
        },
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function(element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
        $(element).removeClass('is-invalid');
    },
        // Add submit handler to prevent default form submission and handle via AJAX
        submitHandler: function(form, e) {
        e.preventDefault();

        var form_data = new FormData();


                $('#addUserForm input').each(function(i, e) {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });

                $('#addUserForm select').each(function() {
    var $select = $(this);
    var name = $select.attr('name');

    if ($select.attr('multiple')) {
        // For multiple select (like permissions)
        var values = $select.val() || []; // Get array of selected values
        console.log(values);
        values.forEach(function(value) {
            form_data.append(name, value); // Append each value separately
        });
    } else {
        // For single select
        form_data.append(name, $select.val());
    }
});
                // Loop through textarea elements
                $('#addUserForm textarea').each(function() {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });
                $('#addUserForm input[type="file"]').each(function() {
    var getID = $(this).attr('id');
    var name = $(this).attr('name');
    form_data.append('is_active', $('input[name="is_active"]:checked').val());
    var file = $("#" + getID)[0].files[0]; // Get the first selected file

    if (file) {
        form_data.append(name, file); // Append the file to form_data
    }
});

        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('create-employee') }}", // Use form action or fallback
            type: "POST",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function() {
                $('#send_btn2').html("<i class='fa fa-spin fa-spinner'></i> Submit");
                $('#loader').show();
                $('#send_btn2').prop('disabled', true);
                $('.alert-dismissible').hide(); // Hide any previous alerts
            },
            success: function(result) {
                console.log(result);

                if (result.status == '200') {
                    $('#message-container').html('<div class="alert alert-success alert-dismissible">Thank you for join us! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');


                    // Redirect after a short delay
                    setTimeout(function() {
                        window.location.href = result.redirect || '/manage-users';
                    }, 1500);
                } else {
                    // Show error message
                    $('#message-container').html('<div class="alert alert-danger alert-dismissible">' + (result.message || 'An error occurred. Please try again.') + ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
                    // Reset button
                    $('#send_btn2').html("Register");
                    $('#loader').hide();
                    $('#send_btn2').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);

                // Handle validation errors from Laravel
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMessages = '';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        errorMessages += errors.join('<br>') + '<br>';
                    });

                    $('.alert-dismissible').removeClass('alert-success').addClass('alert-danger');
                    $('.alert-dismissible').html(errorMessages);
                } else {
                    // Generic error message
                    $('.alert-dismissible').removeClass('alert-success').addClass('alert-danger');
                    $('.alert-dismissible').html('An error occurred: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }

                $('.alert-dismissible').show();
                $('#send_btn2').html("Register");
                $('#loader').hide();
                $('#send_btn2').prop('disabled', false);
            }
        });

        return false; // Prevent default form submission
    }


});
function resetForm() {
            document.getElementById("addUserForm").reset();
            $('#Permissions').bootstrapDualListbox('destroy');
        }
        $('#userPhone').mask('(000) 000-0000');
});

// Toggle Status
$(document).on('change', '.status-toggle', function() {
        const userId = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;
// console.log(userId);
        console.log(isActive);
        $.ajax({
            url: `user/${userId}/status`,
            method: 'PATCH',
            data: {
                id: userId,
                status: isActive,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 200) {
                    toastr.success('User status updated successfully');
                    table.ajax.reload();
                } else {
                    toastr.error('Failed to update user status');
                }
            },
            error: function() {
                toastr.error('An error occurred while updating user status');
            }
        });
    });



    // Handle edit button click
    $(document).on('click', '.edit-action', function() {
        const userId = $(this).data('user-id');

        // Clear previous form data
        $('#editUserForm')[0].reset();
        $('#edit_permissions').val([]).trigger('change');
        $('#edit_permissions').bootstrapDualListbox('refresh');

        // Clear all select options
        $('#edit_position').empty().append('<option selected disabled>Select user role</option>');

        // Add role options
        const roles = [
            {value: 'admin', label: 'Admin'},
            {value: 'restaurant_owner', label: 'Restaurant Owner'},
            {value: 'manager', label: 'Manager'},
            {value: 'chef', label: 'Chef'},
            {value: 'waiter', label: 'Waiter'},
            {value: 'cashier', label: 'Cashier'},
            {value: 'other', label: 'Other'}
        ];

        roles.forEach(role => {
            $('#edit_position').append(new Option(role.label, role.value));
        });

        $.ajax({
            url: `/users/${userId}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.status === 200) {
                    const user = response.user;

                    // Set basic user info
                    $('#edit_user_id').val(user.id);
                    $('#edit_first_name').val(user.first_name);
                    $('#edit_last_name').val(user.last_name);
                    $('#edit_email').val(user.email);
                    $('#edit_phone_number').val(user.phone_number);

                    // Set role - convert to lowercase and replace spaces with underscore
                    const roleValue = user.role.toLowerCase().replace(/ /g, '_');
                    $('#edit_position').val(roleValue);

                    // Set permissions
                    if (user.permissions && Array.isArray(user.permissions)) {
                        $('#edit_permissions').val(user.permissions);
                    }
                    $('#edit_permissions').bootstrapDualListbox('refresh');

                    // Set active/inactive radio
                    if (user.status == 1) {
                        $('#edit_active').prop('checked', true);
                        $('#edit_inactive').prop('checked', false);
                    } else {
                        $('#edit_active').prop('checked', false);
                        $('#edit_inactive').prop('checked', true);
                    }

                    $('#editUser').modal('show');
                }
            },
            error: function(xhr) {
                toastr.error('Error loading user data');
            }
        });
    });

    // Single update button click handler
    $(document).on('click', '#updateUserBtn', function() {
        // Ensure dual listbox values are synced
        $('#edit_permissions').val($('#edit_permissions').val());

        // Ensure is_active is set from the checked radio
        var isActive = $('input[name="is_active"]:checked', '#editUserForm').val();
        if (typeof isActive !== 'undefined') {
            // Remove any existing hidden is_active fields to avoid duplicates
            $('#editUserForm input[type="hidden"][name="is_active"]').remove();
        } else {
            // If none checked, add a hidden field with empty value
            $('#editUserForm').append('<input type="hidden" name="is_active" value="">');
        }

        var formData = new FormData($('#editUserForm')[0]);
        const userId = $('#edit_user_id').val();

        $.ajax({
            url: `/users/${userId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    $('#editUser').modal('hide');
                    toastr.success('User updated successfully');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                    toastr.error(errorMessage);
                }
            }
        });
    });

    // Initialize DataTable
    /*  */
    // Handle filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
        $('.dropdown-menu').removeClass('show');
    });

    // Handle reset filter
    $('#resetFilter').click(function() {
        $('#filterForm')[0].reset();
        table.ajax.reload();
        $('.dropdown-menu').removeClass('show');
    });

    // Handle custom search
    $('.custom-search').on('keyup', function() {
        table.ajax.reload();
    });

    // Handle role filter change
    $('#roleFilter').on('change', function() {
        table.ajax.reload();
    });

    // Handle status filter change
    $('#statusFilter').on('change', function() {
        table.ajax.reload();
    });

    // Handle restaurant filter change
    $('#restaurantFilter').on('change', function() {
        table.ajax.reload();
    });

    // Toggle Status
    $(document).on('change', '.status-toggle', function() {
        const userId = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `user/${userId}/status`,
            method: 'PATCH',
            data: {
                id: userId,
                status: isActive,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 200) {
                    toastr.success('User status updated successfully');
                    table.ajax.reload();
                } else {
                    toastr.error('Failed to update user status');
                }
            },
            error: function() {
                toastr.error('An error occurred while updating user status');
            }
        });
    });
</script>
  @endsection
