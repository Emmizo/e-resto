@extends('layouts.app')

<!-- Main Content -->
<main class="content-wrapper">
    <div class="main-content manage-users">

        <!-- Breadcrumb -->
        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Home">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Users">Manage Users</a>
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
                            <input type="search" class="custom-search" placeholder="Search Article" aria-controls="manageUsersTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0">
                        <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 filter-btn" title="Filter">Filter</a>
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#addUser" title="Add User">Add User</a>
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#importUser" title="Import User">Import User</a>
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
                                            <option selected disabled>Select Document Type</option>
                                            <option value="Document 1">Document 1</option>
                                            <option value="Document 2">Document 2</option>
                                            <option value="Document 3">Document 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="form-group m-0 mb-3 mb-xl-0 normal-select">
                                        <label for="chapter" class="form-label visually-hidden">Chapter</label>
                                        <select class="form-select" id="chapter">
                                            <option selected disabled>Select Chapter</option>
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
                                            <option selected disabled>Select State</option>
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
                                            <option selected disabled>Select Union</option>
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
                                <th class="action-cell text-center">
                                    <span>Action</span>
                                </th>
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
                                    <span>{{ $user->number_phone }}</span>
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
                                    <span>{{ $user->restaurant_position}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->created_at}}</span>
                                </td>
                                <td>
                                    <span>{{ $user->website??''}}</span>
                                </td>
                                <td>
                                    <div class="request-select normal-select">
                                        <select class="form-select" aria-label="Request">
                                            <option selected disabled>Please Select</option>
                                            <option value="welcome">Send a Welcome Email</option>
                                            <option value="reset_password">Send Reset Password Link</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="toggle-switch d-flex align-items-center">
                                        <div class="toggle-button toggle-front d-flex align-items-center position-relative">
                                            <label for="status1" class="form-check-label font-dmsans text-primary-v1 visually-hidden">Status</label>
                                            <label class="switch">
                                                <input type="checkbox" id="status1" checked>
                                                <span class="slider"></span>
                                                <span class="active font-dmsans fw-medium">Active</span>
                                                <span class="inactive font-dmsans fw-medium">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
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

<!-- Table Action Cell -->
<div class="popover-content" data-name="table-action-btn">
    <div class="action-menu">
        <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 edit-action" data-bs-toggle="modal" data-bs-target="#editUser" title="Edit">Edit</a>
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
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="addUserLabel">Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-profile-add pb-3 mb-4">
                    <div class="user-profile-icon d-flex align-items-center justify-content-center mx-auto border border-grey-v1 position-relative rounded-circle">
                        <div class="user-profile-circle w-100 h-100 position-absolute start-0 top-0 overflow-hidden rounded-circle">
                            <img class="user-profile-pic w-100 h-100 object-fit-cover rounded-circle d-none" src="assets/images/user.png" alt="Profile Image">
                        </div>
                        <div class="user-profile-add">
                            <svg class="upload-button position-relative cursor-pointer" width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.3238 28.3542C2.5871 28.3542 1.96354 28.099 1.45312 27.5885C0.942708 27.0781 0.6875 26.4546 0.6875 25.7179V9.11547C0.6875 8.37877 0.942708 7.75521 1.45312 7.24479C1.96354 6.73437 2.5871 6.47917 3.3238 6.47917H7.77719L9.68432 4.41234C9.92543 4.15081 10.216 3.94385 10.556 3.79146C10.8958 3.63882 11.2522 3.5625 11.625 3.5625H17.5426C17.8524 3.5625 18.1122 3.66738 18.3217 3.87713C18.5314 4.08689 18.6363 4.34672 18.6363 4.65661C18.6363 4.96675 18.5314 5.22646 18.3217 5.43573C18.1122 5.64524 17.8524 5.75 17.5426 5.75H11.4289L8.75318 8.66667H3.3238C3.1928 8.66667 3.08524 8.70871 3.00115 8.79281C2.91705 8.87691 2.875 8.98446 2.875 9.11547V25.7179C2.875 25.8489 2.91705 25.9564 3.00115 26.0405C3.08524 26.1246 3.1928 26.1667 3.3238 26.1667H25.7595C25.8905 26.1667 25.9981 26.1246 26.0822 26.0405C26.1663 25.9564 26.2083 25.8489 26.2083 25.7179V14.4158C26.2083 14.1059 26.3132 13.8462 26.523 13.6367C26.7327 13.4269 26.9926 13.322 27.3024 13.322C27.6126 13.322 27.8723 13.4269 28.0816 13.6367C28.2911 13.8462 28.3958 14.1059 28.3958 14.4158V25.7179C28.3958 26.4546 28.1406 27.0781 27.6302 27.5885C27.1198 28.099 26.4962 28.3542 25.7595 28.3542H3.3238ZM26.2083 5.75H24.3854C24.0755 5.75 23.8157 5.64512 23.6059 5.43536C23.3964 5.22561 23.2917 4.96578 23.2917 4.65588C23.2917 4.34574 23.3964 4.08604 23.6059 3.87677C23.8157 3.66726 24.0755 3.5625 24.3854 3.5625H26.2083V1.73958C26.2083 1.42969 26.3132 1.16998 26.523 0.960468C26.7327 0.750711 26.9926 0.645832 27.3024 0.645832C27.6126 0.645832 27.8723 0.750711 28.0816 0.960468C28.2911 1.16998 28.3958 1.42969 28.3958 1.73958V3.5625H30.2187C30.5286 3.5625 30.7883 3.66738 30.9979 3.87713C31.2076 4.08689 31.3125 4.34672 31.3125 4.65661C31.3125 4.96675 31.2076 5.22646 30.9979 5.43573C30.7883 5.64524 30.5286 5.75 30.2187 5.75H28.3958V7.57292C28.3958 7.88281 28.291 8.14264 28.0812 8.35239C27.8714 8.56191 27.6116 8.66667 27.3017 8.66667C26.9916 8.66667 26.7319 8.56191 26.5226 8.35239C26.3131 8.14264 26.2083 7.88281 26.2083 7.57292V5.75ZM14.5417 23.4181C16.2151 23.4181 17.6337 22.8362 18.7974 21.6724C19.9612 20.5087 20.5431 19.0901 20.5431 17.4167C20.5431 15.7432 19.9612 14.3246 18.7974 13.1609C17.6337 11.9971 16.2151 11.4153 14.5417 11.4153C12.8682 11.4153 11.4496 11.9971 10.2859 13.1609C9.12214 14.3246 8.54026 15.7432 8.54026 17.4167C8.54026 19.0901 9.12214 20.5087 10.2859 21.6724C11.4496 22.8362 12.8682 23.4181 14.5417 23.4181ZM14.5417 21.2309C13.4647 21.2309 12.5598 20.8644 11.827 20.1314C11.0939 19.3985 10.7274 18.4936 10.7274 17.4167C10.7274 16.3397 11.0939 15.4348 11.827 14.702C12.5598 13.9689 13.4647 13.6024 14.5417 13.6024C15.6186 13.6024 16.5235 13.9689 17.2564 14.702C17.9894 15.4348 18.3559 16.3397 18.3559 17.4167C18.3559 18.4936 17.9894 19.3985 17.2564 20.1314C16.5235 20.8644 15.6186 21.2309 14.5417 21.2309Z" fill="#06152B"/>
                            </svg>
                            <input class="file-upload" type="file" accept="image/*">
                        </div>
                        <div class="user-profile-delete position-absolute rounded-circle p-1 d-flex align-items-center justify-content-center cursor-pointer d-none">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#06152B">
                                <path d="M640-520v-80h240v80H640Zm-280 40q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="modal-form">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                    <input type="email" class="form-control rounded-3" id="userEmail" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">User Role <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role">
                                        <option selected disabled>Select user role</option>
                                        <option value="User">User</option>
                                        <option value="Admin">Admin</option>
                                        <option value="SuperAdmin">SuperAdmin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control rounded-3" id="userPhone" placeholder="Enter Phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Chapter <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="Chapter">
                                        <option selected disabled>Select chapter</option>
                                        <option value="Chapter 1">Chapter 1</option>
                                        <option value="Chapter 2">Chapter 2</option>
                                        <option value="Chapter 3">Chapter 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Company Name</label>
                                    <select class="form-select" aria-label="Company Name">
                                        <option selected disabled>Select company</option>
                                        <option value="Company 1">Company 1</option>
                                        <option value="Company 2">Company 2</option>
                                        <option value="Company 3">Company 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userRegistrationNumber" class="form-label">Registration</label>
                                    <input type="text" class="form-control rounded-3" id="userRegistrationNumber" placeholder="Enter Registration number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Company Name</label>
                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="active" name="companyStatus" class="input-radio">
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
                                            <input type="radio" id="inactive" name="companyStatus" class="input-radio">
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
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start">
                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Submit</button>
                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Import User Modal -->
<div class="modal fade" id="importUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="importUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="importUserLabel">Import User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-0">
                                    <label for="fileUpload2" class="form-label">Upload Excel</label>
                                    <div class="custom-file-upload">
                                        <input type="file" id="fileUpload2" class="visually-hidden" accept=".xls, .xlsx">
                                        <label for="fileUpload2" class="btn btn-small border border-grey-v1 btn-white d-inline-flex align-items-center me-2">
                                            <svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.30775 15.5C1.80258 15.5 1.375 15.325 1.025 14.975C0.675 14.625 0.5 14.1974 0.5 13.6922V11.7307C0.5 11.5179 0.571833 11.3397 0.7155 11.1962C0.859 11.0525 1.03717 10.9807 1.25 10.9807C1.46283 10.9807 1.641 11.0525 1.7845 11.1962C1.92817 11.3397 2 11.5179 2 11.7307V13.6922C2 13.7692 2.03208 13.8397 2.09625 13.9037C2.16025 13.9679 2.23075 14 2.30775 14H13.6923C13.7692 14 13.8398 13.9679 13.9038 13.9037C13.9679 13.8397 14 13.7692 14 13.6922V11.7307C14 11.5179 14.0718 11.3397 14.2155 11.1962C14.359 11.0525 14.5372 10.9807 14.75 10.9807C14.9628 10.9807 15.141 11.0525 15.2845 11.1962C15.4282 11.3397 15.5 11.5179 15.5 11.7307V13.6922C15.5 14.1974 15.325 14.625 14.975 14.975C14.625 15.325 14.1974 15.5 13.6923 15.5H2.30775ZM7.25 3.38845L5.327 5.31145C5.17817 5.46012 5.00158 5.53354 4.79725 5.5317C4.59275 5.5297 4.41292 5.45112 4.25775 5.29595C4.11292 5.14095 4.03792 4.96537 4.03275 4.7692C4.02758 4.57304 4.10258 4.39737 4.25775 4.2422L7.36725 1.1327C7.46092 1.03904 7.55967 0.973036 7.6635 0.934703C7.76733 0.896203 7.8795 0.876953 8 0.876953C8.1205 0.876953 8.23267 0.896203 8.3365 0.934703C8.44033 0.973036 8.53908 1.03904 8.63275 1.1327L11.7423 4.2422C11.8909 4.39087 11.9643 4.56495 11.9625 4.76445C11.9605 4.96379 11.8871 5.14095 11.7423 5.29595C11.5871 5.45112 11.4089 5.53129 11.2078 5.53645C11.0064 5.54162 10.8282 5.46662 10.673 5.31145L8.75 3.38845V11.0385C8.75 11.2513 8.67817 11.4295 8.5345 11.573C8.391 11.7166 8.21283 11.7885 8 11.7885C7.78717 11.7885 7.609 11.7166 7.4655 11.573C7.32183 11.4295 7.25 11.2513 7.25 11.0385V3.38845Z" fill="#007AFF"/>
                                            </svg>
                                            <span class="ps-1">Choose File</span>
                                        </label>
                                        <span class="selected-file">No file Chosen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-between">
                <div class="footer-btns order-2 order-sm-1">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Submit</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
                </div>
                <div class="sample-data order-1 order-sm-2 m-0 mb-2 mb-sm-0">
                    <a href="https://www.necadistrict10.com/sample-excel-files/Sample-Users.xlsx" class="font-dmsans fw-medium xsmall text-secondary d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" class="me-2" >
                            <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" fill="#007AFF"/>
                        </svg>
                        <span>Download Sample File</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="editUserLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-profile-add position-relative pb-3 mb-4">
                    <div class="user-profile-icon d-flex align-items-center justify-content-center mx-auto border border-grey-v1 position-relative rounded-circle">
                        <div class="user-profile-circle w-100 h-100 position-absolute start-0 top-0 overflow-hidden rounded-circle">
                            <img class="user-profile-pic w-100 h-100 object-fit-cover rounded-circle" src="assets/images/user.png" alt="Profile Image">
                        </div>
                        <div class="user-profile-add">
                            <svg class="upload-button position-relative cursor-pointer d-none" width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.3238 28.3542C2.5871 28.3542 1.96354 28.099 1.45312 27.5885C0.942708 27.0781 0.6875 26.4546 0.6875 25.7179V9.11547C0.6875 8.37877 0.942708 7.75521 1.45312 7.24479C1.96354 6.73437 2.5871 6.47917 3.3238 6.47917H7.77719L9.68432 4.41234C9.92543 4.15081 10.216 3.94385 10.556 3.79146C10.8958 3.63882 11.2522 3.5625 11.625 3.5625H17.5426C17.8524 3.5625 18.1122 3.66738 18.3217 3.87713C18.5314 4.08689 18.6363 4.34672 18.6363 4.65661C18.6363 4.96675 18.5314 5.22646 18.3217 5.43573C18.1122 5.64524 17.8524 5.75 17.5426 5.75H11.4289L8.75318 8.66667H3.3238C3.1928 8.66667 3.08524 8.70871 3.00115 8.79281C2.91705 8.87691 2.875 8.98446 2.875 9.11547V25.7179C2.875 25.8489 2.91705 25.9564 3.00115 26.0405C3.08524 26.1246 3.1928 26.1667 3.3238 26.1667H25.7595C25.8905 26.1667 25.9981 26.1246 26.0822 26.0405C26.1663 25.9564 26.2083 25.8489 26.2083 25.7179V14.4158C26.2083 14.1059 26.3132 13.8462 26.523 13.6367C26.7327 13.4269 26.9926 13.322 27.3024 13.322C27.6126 13.322 27.8723 13.4269 28.0816 13.6367C28.2911 13.8462 28.3958 14.1059 28.3958 14.4158V25.7179C28.3958 26.4546 28.1406 27.0781 27.6302 27.5885C27.1198 28.099 26.4962 28.3542 25.7595 28.3542H3.3238ZM26.2083 5.75H24.3854C24.0755 5.75 23.8157 5.64512 23.6059 5.43536C23.3964 5.22561 23.2917 4.96578 23.2917 4.65588C23.2917 4.34574 23.3964 4.08604 23.6059 3.87677C23.8157 3.66726 24.0755 3.5625 24.3854 3.5625H26.2083V1.73958C26.2083 1.42969 26.3132 1.16998 26.523 0.960468C26.7327 0.750711 26.9926 0.645832 27.3024 0.645832C27.6126 0.645832 27.8723 0.750711 28.0816 0.960468C28.2911 1.16998 28.3958 1.42969 28.3958 1.73958V3.5625H30.2187C30.5286 3.5625 30.7883 3.66738 30.9979 3.87713C31.2076 4.08689 31.3125 4.34672 31.3125 4.65661C31.3125 4.96675 31.2076 5.22646 30.9979 5.43573C30.7883 5.64524 30.5286 5.75 30.2187 5.75H28.3958V7.57292C28.3958 7.88281 28.291 8.14264 28.0812 8.35239C27.8714 8.56191 27.6116 8.66667 27.3017 8.66667C26.9916 8.66667 26.7319 8.56191 26.5226 8.35239C26.3131 8.14264 26.2083 7.88281 26.2083 7.57292V5.75ZM14.5417 23.4181C16.2151 23.4181 17.6337 22.8362 18.7974 21.6724C19.9612 20.5087 20.5431 19.0901 20.5431 17.4167C20.5431 15.7432 19.9612 14.3246 18.7974 13.1609C17.6337 11.9971 16.2151 11.4153 14.5417 11.4153C12.8682 11.4153 11.4496 11.9971 10.2859 13.1609C9.12214 14.3246 8.54026 15.7432 8.54026 17.4167C8.54026 19.0901 9.12214 20.5087 10.2859 21.6724C11.4496 22.8362 12.8682 23.4181 14.5417 23.4181ZM14.5417 21.2309C13.4647 21.2309 12.5598 20.8644 11.827 20.1314C11.0939 19.3985 10.7274 18.4936 10.7274 17.4167C10.7274 16.3397 11.0939 15.4348 11.827 14.702C12.5598 13.9689 13.4647 13.6024 14.5417 13.6024C15.6186 13.6024 16.5235 13.9689 17.2564 14.702C17.9894 15.4348 18.3559 16.3397 18.3559 17.4167C18.3559 18.4936 17.9894 19.3985 17.2564 20.1314C16.5235 20.8644 15.6186 21.2309 14.5417 21.2309Z" fill="#06152B"/>
                            </svg>
                            <input class="file-upload" type="file" accept="image/*">
                        </div>
                        <div class="user-profile-delete position-absolute rounded-circle p-1 d-flex align-items-center justify-content-center cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#06152B">
                                <path d="M640-520v-80h240v80H640Zm-280 40q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="modal-form">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter Name" value="Alex Hamilton">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                    <input type="email" class="form-control rounded-3" id="userEmail" placeholder="Enter Email" value="alexhamil123@gmail.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">User Role <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="User Role">
                                        <option disabled>Select user role</option>
                                        <option value="User" selected>User</option>
                                        <option value="Admin">Admin</option>
                                        <option value="SuperAdmin">SuperAdmin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control rounded-3" id="userPhone" placeholder="Enter Phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Chapter <span class="asterik">*</span></label>
                                    <select class="form-select" aria-label="Chapter">
                                        <option selected disabled>Select chapter</option>
                                        <option value="Chapter 1" selected>Chapter 1</option>
                                        <option value="Chapter 2">Chapter 2</option>
                                        <option value="Chapter 3">Chapter 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Company Name</label>
                                    <select class="form-select" aria-label="Company Name">
                                        <option selected disabled>Select company</option>
                                        <option value="Company 1" selected>Company 1</option>
                                        <option value="Company 2">Company 2</option>
                                        <option value="Company 3">Company 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userRegistrationNumber" class="form-label">Registration</label>
                                    <input type="text" class="form-control rounded-3" id="userRegistrationNumber" placeholder="Enter Registration number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Company Name</label>
                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="active1" name="companyStatus" class="input-radio" checked>
                                            <label for="active1" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio p-0 m-0">
                                            <input type="radio" id="inactive1" name="companyStatus" class="input-radio">
                                            <label for="inactive1" class="form-radio-label">
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
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start">
                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Submit</button>
                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteUserLabel">Delete User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this User?</p>
                            <div class="footer-btns">
                                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Yes</button>
                                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">No</button>
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
{{-- @section('script')
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrapValidator.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/slick.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endsection --}}
