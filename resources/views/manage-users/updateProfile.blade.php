@extends('layouts.app')
@section('content')
<main class="content-wrapper">
    <div class="main-content">

        <!-- Breadcrumb -->
        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('manage-users') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Manage Users</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <span class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Edit Profile</span>
                </li>
            </ul>
        </div>

        <form action="{{ route('manage-update-profile') }}" method="POST" id="updateProfileForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $info->id }}">

            <div class="row g-4">

                {{-- Left: Avatar card --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                        <div class="position-relative d-inline-block mx-auto mb-3" style="width:110px;height:110px;">
                            {{-- Avatar: photo if exists, else icon --}}
                            @if(!empty($info->profile_picture))
                                <img id="avatarPreview"
                                     src="{{ asset($info->profile_picture) }}"
                                     alt="Profile"
                                     class="rounded-circle object-fit-cover border border-3"
                                     style="width:110px;height:110px;border-color:#e2e8f0!important;">
                                <div id="avatarIconFallback" class="rounded-circle d-none align-items-center justify-content-center border border-3"
                                     style="width:110px;height:110px;background:#ede9fe;border-color:#c4b5fd!important;">
                                    <i class="fas fa-user-circle" style="font-size:3.5rem;color:#7c3aed;"></i>
                                </div>
                            @else
                                <img id="avatarPreview"
                                     src=""
                                     alt="Profile"
                                     class="rounded-circle object-fit-cover border border-3 d-none"
                                     style="width:110px;height:110px;border-color:#e2e8f0!important;">
                                <div id="avatarIconFallback" class="rounded-circle d-flex align-items-center justify-content-center border border-3"
                                     style="width:110px;height:110px;background:#ede9fe;border-color:#c4b5fd!important;">
                                    <i class="fas fa-user-circle" style="font-size:3.5rem;color:#7c3aed;"></i>
                                </div>
                            @endif

                            {{-- Upload overlay --}}
                            <label for="file-upload"
                                   class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-sm"
                                   style="width:32px;height:32px;background:#184C55;border:2px solid #fff;cursor:pointer;"
                                   title="Change photo">
                                <i class="fas fa-camera text-white" style="font-size:0.7rem;"></i>
                            </label>
                            <input type="file" id="file-upload" name="profile_picture" accept="image/*" class="d-none">
                        </div>

                        <h5 class="fw-bold text-dark mb-0">{{ $info->first_name }} {{ $info->last_name }}</h5>
                        <p class="text-muted small mb-3">{{ Str::title(str_replace('_', ' ', $info->role)) }}</p>

                        <div class="d-flex flex-column gap-2 text-start small text-muted">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-envelope" style="width:16px;color:#94a3b8;"></i>
                                <span class="text-truncate">{{ $info->email }}</span>
                            </div>
                            @if($info->phone_number)
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-phone" style="width:16px;color:#94a3b8;"></i>
                                <span>{{ $info->phone_number }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Form card --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h5 class="fw-bold text-dark mb-0">Edit Profile</h5>
                            <p class="text-muted small mb-0">Update your personal information</p>
                        </div>
                        <div class="card-body px-4 pb-0 pt-3">
                            <div id="message-container-login"></div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" name="first_name"
                                           placeholder="First name" value="{{ $info->first_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" name="last_name"
                                           placeholder="Last name" value="{{ $info->last_name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control rounded-3" name="email"
                                           placeholder="Email" value="{{ $info->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Phone Number</label>
                                    <input type="text" class="form-control rounded-3" name="phone_number"
                                           placeholder="Phone" value="{{ $info->phone_number }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small">Role</label>
                                    <input type="text" class="form-control rounded-3 bg-light" name="role"
                                           value="{{ Str::title(str_replace('_', ' ', $info->role)) }}" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small">Status</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_active" id="statusActive" value="1"
                                                   {{ $info->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="statusActive">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_active" id="statusInactive" value="0"
                                                   {{ $info->status == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="statusInactive">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 py-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('manage-users') }}" class="btn btn-light rounded-3 px-4 border">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</main>

@section('script')
<script>
document.getElementById('file-upload').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('avatarPreview');
        var fallback = document.getElementById('avatarIconFallback');
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        fallback.classList.add('d-none');
        fallback.classList.remove('d-flex');
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
