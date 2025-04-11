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

        <div class="card">
            <div class="card-header border-0">
                <h1 class="fs-5 font-dmsans fw-bold text-primary-v1">Update your profile</h1>
            </div>
            <div id="message-container-login"></div>
            <form action="{{route('manage-update-profile')}}" method="POST" id="addUserForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$info->id}}">
                <div class="card-body">
                    <div class="user-profile-add position-relative pb-3 mb-4">
                        <div class="user-profile-icon d-flex align-items-center justify-content-center mx-auto border border-grey-v1 position-relative rounded-circle">
                            <div class="user-profile-circle w-100 h-100 position-absolute start-0 top-0 overflow-hidden rounded-circle">
                                <img class="user-profile-pic w-100 h-100 object-fit-cover rounded-circle" src="{{asset($info->profile_picture ?? 'assets/images/user.png')}}" alt="Profile Image">
                            </div>
                            <div class="user-profile-add">
                                <svg class="upload-button position-relative cursor-pointer d-none" width="32" height="29" viewBox="0 0 32 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.3238 28.3542C2.5871 28.3542 1.96354 28.099 1.45312 27.5885C0.942708 27.0781 0.6875 26.4546 0.6875 25.7179V9.11547C0.6875 8.37877 0.942708 7.75521 1.45312 7.24479C1.96354 6.73437 2.5871 6.47917 3.3238 6.47917H7.77719L9.68432 4.41234C9.92543 4.15081 10.216 3.94385 10.556 3.79146C10.8958 3.63882 11.2522 3.5625 11.625 3.5625H17.5426C17.8524 3.5625 18.1122 3.66738 18.3217 3.87713C18.5314 4.08689 18.6363 4.34672 18.6363 4.65661C18.6363 4.96675 18.5314 5.22646 18.3217 5.43573C18.1122 5.64524 17.8524 5.75 17.5426 5.75H11.4289L8.75318 8.66667H3.3238C3.1928 8.66667 3.08524 8.70871 3.00115 8.79281C2.91705 8.87691 2.875 8.98446 2.875 9.11547V25.7179C2.875 25.8489 2.91705 25.9564 3.00115 26.0405C3.08524 26.1246 3.1928 26.1667 3.3238 26.1667H25.7595C25.8905 26.1667 25.9981 26.1246 26.0822 26.0405C26.1663 25.9564 26.2083 25.8489 26.2083 25.7179V14.4158C26.2083 14.1059 26.3132 13.8462 26.523 13.6367C26.7327 13.4269 26.9926 13.322 27.3024 13.322C27.6126 13.322 27.8723 13.4269 28.0816 13.6367C28.2911 13.8462 28.3958 14.1059 28.3958 14.4158V25.7179C28.3958 26.4546 28.1406 27.0781 27.6302 27.5885C27.1198 28.099 26.4962 28.3542 25.7595 28.3542H3.3238ZM26.2083 5.75H24.3854C24.0755 5.75 23.8157 5.64512 23.6059 5.43536C23.3964 5.22561 23.2917 4.96578 23.2917 4.65588C23.2917 4.34574 23.3964 4.08604 23.6059 3.87677C23.8157 3.66726 24.0755 3.5625 24.3854 3.5625H26.2083V1.73958C26.2083 1.42969 26.3132 1.16998 26.523 0.960468C26.7327 0.750711 26.9926 0.645832 27.3024 0.645832C27.6126 0.645832 27.8723 0.750711 28.0816 0.960468C28.2911 1.16998 28.3958 1.42969 28.3958 1.73958V3.5625H30.2187C30.5286 3.5625 30.7883 3.66738 30.9979 3.87713C31.2076 4.08689 31.3125 4.34672 31.3125 4.65661C31.3125 4.96675 31.2076 5.22646 30.9979 5.43573C30.7883 5.64524 30.5286 5.75 30.2187 5.75H28.3958V7.57292C28.3958 7.88281 28.291 8.14264 28.0812 8.35239C27.8714 8.56191 27.6116 8.66667 27.3017 8.66667C26.9916 8.66667 26.7319 8.56191 26.5226 8.35239C26.3131 8.14264 26.2083 7.88281 26.2083 7.57292V5.75ZM14.5417 23.4181C16.2151 23.4181 17.6337 22.8362 18.7974 21.6724C19.9612 20.5087 20.5431 19.0901 20.5431 17.4167C20.5431 15.7432 19.9612 14.3246 18.7974 13.1609C17.6337 11.9971 16.2151 11.4153 14.5417 11.4153C12.8682 11.4153 11.4496 11.9971 10.2859 13.1609C9.12214 14.3246 8.54026 15.7432 8.54026 17.4167C8.54026 19.0901 9.12214 20.5087 10.2859 21.6724C11.4496 22.8362 12.8682 23.4181 14.5417 23.4181ZM14.5417 21.2309C13.4647 21.2309 12.5598 20.8644 11.827 20.1314C11.0939 19.3985 10.7274 18.4936 10.7274 17.4167C10.7274 16.3397 11.0939 15.4348 11.827 14.702C12.5598 13.9689 13.4647 13.6024 14.5417 13.6024C15.6186 13.6024 16.5235 13.9689 17.2564 14.702C17.9894 15.4348 18.3559 16.3397 18.3559 17.4167C18.3559 18.4936 17.9894 19.3985 17.2564 20.1314C16.5235 20.8644 15.6186 21.2309 14.5417 21.2309Z" fill="#06152B"/>
                                </svg>
                                <input class="file-upload" type="file" accept="image/*" name="profile_picture" id="file-upload" onchange="previewImage(event)" style="width: 100%; height: 100%; opacity: 0; position: absolute; top: 0; left: 0; cursor: pointer;">
                            </div>
                            <div class="user-profile-delete position-absolute rounded-circle p-1 d-flex align-items-center justify-content-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#06152B">
                                    <path d="M640-520v-80h240v80H640Zm-280 40q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">First name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter First Name" name="first_name" value="{{$info->first_name}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Last name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter Last Name" name="last_name" value="{{$info->last_name}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                    <input type="email" class="form-control rounded-3" id="userEmail" placeholder="Enter Email" name="email" value="{{$info->email}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control rounded-3" id="userPhone" placeholder="Enter Phone" name="phone_number" value="{{$info->phone_number}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userPhone" class="form-label">Role</label>
                                    <input type="text" class="form-control rounded-3" id="userPhone" placeholder="Enter role" name="role" value="{{$info->role}}" readonly>
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
                <div class="card-footer border-0">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Submit</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</main>

@section('scripts')
<script>
   $(document).ready(function() {
    $('#userPhone').mask('(000) 000-0000');
   });
   </script>
   @endsection
